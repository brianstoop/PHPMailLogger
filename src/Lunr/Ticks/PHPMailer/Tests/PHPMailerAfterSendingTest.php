<?php

/**
 * This file contains the PHPMailerAfterSendingTest class.
 */

namespace Lunr\Ticks\PHPMailer\Tests;

/**
 * This class contains tests for the PHPMailer class.
 *
 * @covers Lunr\Ticks\PHPMailer\PHPMailer
 */
class PHPMailerAfterSendingTest extends PHPMailerTestCase
{

    /**
     * Test that the afterSending works correctly.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::afterSending
     */
    public function testAfterSendingWorksCorrectlyWithINFOLevel(): void
    {
        $this->mockFunction('microtime', fn() => 1724932394.128985);

        $this->setReflectionPropertyValue('startTime', 1724932393.008985);
        $this->setReflectionPropertyValue('Mailer', 'smtp');
        $this->setReflectionPropertyValue('MIMEHeader', 'full mime header');
        $this->setReflectionPropertyValue('MIMEBody', 'full mime body');
        $this->setReflectionPropertyValue('level', 0);

        $this->logger->expects($this->once())
                     ->method('add_tags')
                     ->with([
                         'type'   => 'smtp',
                         'status' => TRUE,
                     ]);

        $this->logger->expects($this->once())
                     ->method('add_fields')
                     ->with([
                         'url'      => 'localhost',
                         'duration' => 1.119999885559082,
                     ]);

        $this->logger->expects($this->once())
                     ->method('record');

        $extra = [ 'smtp_transaction_id' => FALSE ];

        $method = $this->getReflectionMethod('afterSending');
        $method->invoke($this->class, TRUE, [ 'example@mail.com', 'John Doe' ], [], [], 'subject', 'body', 'from@mail.com', $extra);

        $this->unmockFunction('microtime');

        uopz_unset_return('microtime');
    }

    /**
     * Test that the afterSending works correctly with empty extra.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::afterSending
     */
    public function testAfterSendingWorksCorrectlyWithDEBUGLevel(): void
    {
        $string  = 'b7rrrEKWPBBniam2zDQjn2QaYE5dAPLgfyTy2RbTPVykQDrYeq3HKjTKPLeSgaf8dTJNiatfrbGKMUBU4VYY8PphqxBZSe6mKuz2R7FVdcc9VZmAEkNDg7mfT7EPcvg';
        $string .= 'LgTKUihAfxc76CihMFqVpnU7e3iqWJdBPLnP34JQ2zQVBmSv8kvHjAGrv5fCVnPCEvbQx5PUNBukQVNFZukLtEtb2ZYy54JqjbHi4CF9kWV9MHq2Ah5A9vjYLxTBziT';
        $string .= 'MYcTCtXxcFCVYQ6awvkN9TdupdD7ihecSHB79JbqPSAVbRbz4ZFtnbe2aPzVRmVvkLDuFefmutDfGgKCizYMGJnExv6ViCryU4JZAufWxeag22BrDJ34aBRwbnCqwEa';
        $string .= 't2K6p45zvvCVpen5Z6VkQCiLGV5kGzfhb6cgUvnvyKK5tzjE7xx95PLupW8uPaCYyrpgT9RS8GQNf72qwnA5bebjRe3hi66KXLaJU2d5Tkpe4eRutgucvKFFBk8MxkY';

        $this->mockFunction('microtime', fn() => 1724932394.128985);

        $this->setReflectionPropertyValue('startTime', 1724932393.008985);
        $this->setReflectionPropertyValue('Mailer', 'smtp');
        $this->setReflectionPropertyValue('MIMEHeader', 'full mime header');
        $this->setReflectionPropertyValue('MIMEBody', $string . 'E984TBDFDAKJF');
        $this->setReflectionPropertyValue('level', 1);

        $options = [
            'subject' => 'subject',
            'body'    => 'body',
            'from'    => 'from@mail.com',
        ];

        $this->logger->expects($this->once())
                     ->method('add_tags')
                     ->with([
                         'type'   => 'smtp',
                         'status' => TRUE,
                     ]);

        $this->logger->expects($this->once())
                     ->method('add_fields')
                     ->with([
                         'url'             => 'localhost',
                         'duration'        => 1.119999885559082,
                         'request_headers' => 'full mime header',
                         'data'            => $string . 'E984...',
                         'options'         => '{"subject":"subject","body":"body","from":"from@mail.com"}',
                     ]);

        $this->logger->expects($this->once())
                     ->method('record');

        $method = $this->getReflectionMethod('afterSending');
        $method->invoke($this->class, TRUE, [ 'example@mail.com', 'John Doe' ], [], [], 'subject', 'body', 'from@mail.com', []);

        $this->unmockFunction('microtime');

        uopz_unset_return('microtime');
    }

    /**
     * Test that the afterSending works correctly with empty extra.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::afterSending
     */
    public function testAfterSendingWorksCorrectlyWithFULLDEBUGLevel(): void
    {
        $string  = 'b7rrrEKWPBBniam2zDQjn2QaYE5dAPLgfyTy2RbTPVykQDrYeq3HKjTKPLeSgaf8dTJNiatfrbGKMUBU4VYY8PphqxBZSe6mKuz2R7FVdcc9VZmAEkNDg7mfT7EPcvg';
        $string .= 'LgTKUihAfxc76CihMFqVpnU7e3iqWJdBPLnP34JQ2zQVBmSv8kvHjAGrv5fCVnPCEvbQx5PUNBukQVNFZukLtEtb2ZYy54JqjbHi4CF9kWV9MHq2Ah5A9vjYLxTBziT';
        $string .= 'MYcTCtXxcFCVYQ6awvkN9TdupdD7ihecSHB79JbqPSAVbRbz4ZFtnbe2aPzVRmVvkLDuFefmutDfGgKCizYMGJnExv6ViCryU4JZAufWxeag22BrDJ34aBRwbnCqwEa';
        $string .= 't2K6p45zvvCVpen5Z6VkQCiLGV5kGzfhb6cgUvnvyKK5tzjE7xx95PLupW8uPaCYyrpgT9RS8GQNf72qwnA5bebjRe3hi66KXLaJU2d5Tkpe4eRutgucvKFFBk8MxkY';

        $this->mockFunction('microtime', fn() => 1724932394.128985);

        $this->setReflectionPropertyValue('startTime', 1724932393.008985);
        $this->setReflectionPropertyValue('Mailer', 'smtp');
        $this->setReflectionPropertyValue('MIMEHeader', 'full mime header');
        $this->setReflectionPropertyValue('MIMEBody', $string . 'E984TBDFDAKJF');
        $this->setReflectionPropertyValue('level', 2);

        $options = [
            'subject' => 'subject',
            'body'    => 'body',
            'from'    => 'from@mail.com',
        ];

        $this->logger->expects($this->once())
                     ->method('add_tags')
                     ->with([
                         'type'   => 'smtp',
                         'status' => TRUE,
                     ]);

        $this->logger->expects($this->once())
                     ->method('add_fields')
                     ->with([
                         'url'             => 'localhost',
                         'duration'        => 1.119999885559082,
                         'request_headers' => 'full mime header',
                         'data'            => $string . 'E984TBDFDAKJF',
                         'options'         => '{"subject":"subject","body":"body","from":"from@mail.com"}',
                     ]);

        $this->logger->expects($this->once())
                     ->method('record');

        $method = $this->getReflectionMethod('afterSending');
        $method->invoke($this->class, TRUE, [ 'example@mail.com', 'John Doe' ], [], [], 'subject', 'body', 'from@mail.com', []);

        $this->unmockFunction('microtime');

        uopz_unset_return('microtime');
    }

}

?>
