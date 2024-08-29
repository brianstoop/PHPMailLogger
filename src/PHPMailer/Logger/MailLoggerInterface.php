<?php

/**
 * This file contains the MailLoggerInterface interface.
 */

namespace PHPMailer\Logger;

 /**
  * MailLoggerInterface interface
  *
  * @phpstan-type Options array{
  *     subject: string,
  *     body: string,
  *     from: string,
  *     smtp_transaction_id?: bool|string|null,
  * }
  */
interface MailLoggerInterface
{

    /**
     * Set profiling level.
     *
     * @param int $level Profiling level
     *
     * @return void
     */
    public function set_profiling_level(int $level): void;

    /**
     * Log the mail info
     *
     * @param string  $type       Type of mail
     * @param string  $to         The address the mail was sent to
     * @param bool    $status     The status of the mail
     * @param float   $duration   The duration of the mail request
     * @param string  $req_header The MIME mail header
     * @param string  $req_body   The MIME mail body
     * @param Options $options    The mail options
     *
     * @return void
     */
    public function log_mail(string $type, string $to, bool $status, float $duration, string $req_header, string $req_body, array $options): void;

}
?>
