<?php

/**
 * This file contains the PHPMailer class.
 */

namespace PHPMailer\Logger;

use PHPMailer\PHPMailer\PHPMailer as BaseMailer;

/**
 * PHPMailer class
 */
class PHPMailer extends BaseMailer
{

    /**
     * Shared instance of the mail logger
     * @var MailLoggerInterface
     */
    protected readonly MailLoggerInterface $mail_logger;

    /**
     * Start time of the mail sending
     * @var float
     */
    protected float $start_time;

    /**
     * Constructor.
     *
     * @param MailLoggerInterface $mail_logger PHP mail logger
     * @param bool|null           $exceptions  Should we throw external exceptions?
     */
    public function __construct(MailLoggerInterface $mail_logger, ?bool $exceptions = NULL)
    {
        $this->mail_logger = $mail_logger;

        parent::__construct($exceptions);

        /**
         * The type of action_function is defined as string but it should be callable, so we ignore the phpstan warning
         * @phpstan-ignore assign.propertyType
         */
        $this->action_function = [ $this, 'after_sending' ];
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset($this->start_time);

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
        $this->start_time = microtime(TRUE);

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
    protected function after_sending(bool $isSent, array $to, array $cc, array $bcc, string $subject, string $body, string $from, array $extra): void
    {
        $duration = microtime(TRUE) - $this->start_time;

        $options = [
            'subject' => $subject,
            'body'    => $body,
            'from'    => $from,
        ];

        $options += $extra;

        $this->mail_logger->log_mail($this->Mailer, $to[0], $isSent, $duration, $this->MIMEHeader, $this->MIMEBody, $options);
    }

}

?>
