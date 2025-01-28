<?php

/**
 * This file contains the PHPMailerAfterSendingTest class.
 */

namespace Lunr\Ticks\PHPMailer\Tests;

/**
 * This class contains tests for the PHPMailer class.
 *
 * @covers PHPMailer\Logger\PHPMailer
 */
class PHPMailerAfterSendingTest extends PHPMailerTest
{

    /**
     * Test that the afterSending works correctly with empty extra.
     *
     * @covers PHPMailer\Logger\PHPMailer::afterSending
     */
    public function testAfterSendingWorksCorrectlyWithEmptyExtra(): void
    {
        $this->mock_function('microtime', fn() => 1724932394.128985);

        $this->set_reflection_property_value('start_time', 1724932393.008985);
        $this->set_reflection_property_value('Mailer', 'smtp');
        $this->set_reflection_property_value('MIMEHeader', 'full mime header');
        $this->set_reflection_property_value('MIMEBody', 'full mime body');

        $options = [
            'subject' => 'subject',
            'body'    => 'body',
            'from'    => 'from@mail.com',
        ];

        $this->logger->expects($this->once())
                     ->method('log_mail')
                     ->with('smtp', 'example@mail.com', TRUE, 1.119999885559082, 'full mime header', 'full mime body', $options);

        $method = $this->get_reflection_method('afterSending');
        $method->invoke($this->class, TRUE, [ 'example@mail.com', 'John Doe' ], [], [], 'subject', 'body', 'from@mail.com', []);

        $this->unmock_function('microtime');

        uopz_unset_return('microtime');
    }

    /**
     * Test that the afterSending works correctly.
     *
     * @covers PHPMailer\Logger\PHPMailer::afterSending
     */
    public function testAfterSendingWorksCorrectly(): void
    {
        $this->mock_function('microtime', fn() => 1724932394.128985);

        $this->set_reflection_property_value('start_time', 1724932393.008985);
        $this->set_reflection_property_value('Mailer', 'smtp');
        $this->set_reflection_property_value('MIMEHeader', 'full mime header');
        $this->set_reflection_property_value('MIMEBody', 'full mime body');

        $options = [
            'subject'             => 'subject',
            'body'                => 'body',
            'from'                => 'from@mail.com',
            'smtp_transaction_id' => FALSE,
        ];

        $this->logger->expects($this->once())
                     ->method('log_mail')
                     ->with('smtp', 'example@mail.com', TRUE, 1.119999885559082, 'full mime header', 'full mime body', $options);

        $extra = [ 'smtp_transaction_id' => FALSE ];

        $method = $this->get_reflection_method('afterSending');
        $method->invoke($this->class, TRUE, [ 'example@mail.com', 'John Doe' ], [], [], 'subject', 'body', 'from@mail.com', $extra);

        $this->unmock_function('microtime');

        uopz_unset_return('microtime');
    }

}

?>
