<?php

/**
 * This file contains the PHPMailer class.
 */

namespace Lunr\Ticks\PHPMailer;

use Lunr\Ticks\EventLogging\EventInterface;
use PHPMailer\PHPMailer\PHPMailer as BaseMailer;

/**
 * PHPMailer class
 */
class PHPMailer extends BaseMailer
{

    /**
     * Shared instance of the mail logger
     * @var EventInterface
     */
    protected readonly EventInterface $logger;

    /**
     * Profiling level
     * @var int
     */
    protected int $level;

    /**
     * Start time of the mail sending
     * @var float
     */
    protected float $startTime;

    /**
     * Constructor.
     *
     * @param EventInterface $logger     PHP mail logger
     * @param int            $level      Profiling level
     * @param bool|null      $exceptions Should we throw external exceptions?
     */
    public function __construct(EventInterface $logger, int $level, ?bool $exceptions = NULL)
    {
        $this->logger = $logger;
        $this->level  = $level;

        parent::__construct($exceptions);

        /**
         * The type of action_function is defined as string but it should be callable, so we ignore the phpstan warning
         * @phpstan-ignore assign.propertyType
         */
        $this->action_function = [ $this, 'afterSending' ];
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset($this->startTime);
        unset($this->level);

        parent::__destruct();
    }

    /**
     * Create a message and send it.
     * Uses the sending method specified by $Mailer.
     *
     * @return bool false on error - See the ErrorInfo property for details of the error
     */
    public function send(): bool
    {
        $this->startTime = microtime(TRUE);

        return parent::send();
    }

    /**
     * Callback after each mail send
     *
     * @param bool                                          $isSent  Result of the send action
     * @param array{0: string,1?: string}                   $to      Email addresses of the recipients
     * @param array<array{0: string,1?: string}>            $cc      Cc email addresses
     * @param array<array{0: string,1?: string}>            $bcc     Bcc email addresses
     * @param string                                        $subject The subject
     * @param string                                        $body    The email body
     * @param string                                        $from    Email address of sender
     * @param array{smtp_transaction_id?: bool|string|null} $extra   Extra information of possible use
     *
     * @return void
     */
    protected function afterSending(bool $isSent, array $to, array $cc, array $bcc, string $subject, string $body, string $from, array $extra): void
    {
        $tags = [
            'type'   => $this->Mailer,
            'status' => $isSent,
        ];

        $fields = [
            'url'      => $this->Host,
            'duration' => microtime(TRUE) - $this->startTime,
        ];

        $options = [
            'subject' => $subject,
            'body'    => $body,
            'from'    => $from,
        ];

        $options += $extra;

        // If the profiling level is not INFO we want to log extra info
        if ($this->level !== 0)
        {
            $fields['request_headers'] = $this->prepareLogData($this->MIMEHeader);
            $fields['data']            = $this->prepareLogData($this->MIMEBody);
            $fields['options']         = $this->prepareLogData(json_encode($options));
        }

        $this->logger->add_tags($tags);
        $this->logger->add_fields($fields);
        $this->logger->record();
    }

    /**
     * Prepare data according to loglevel.
     *
     * @param string $data Data to prepare for logging.
     *
     * @return string Prepare data to log.
     */
    private function prepareLogData(string $data): string
    {
        // If the profiling level is DEBUG we want to log part of the info
        if ($this->level === 1 && strlen($data) > 512)
        {
            return substr($data, 0, 512) . '...';
        }

        return $data;
    }

}

?>
