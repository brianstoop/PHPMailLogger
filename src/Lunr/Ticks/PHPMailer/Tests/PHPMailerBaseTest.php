<?php

/**
 * This file contains the PHPMailerBaseTest class.
 */

namespace Lunr\Ticks\PHPMailer\Tests;

/**
 * This class contains tests for the PHPMailer class.
 *
 * @covers PHPMailer\Logger\PHPMailer
 */
class PHPMailerBaseTest extends PHPMailerTest
{

    /**
     * Test that the logger is set correctly.
     *
     * @covers PHPMailer\Logger\PHPMailer::__construct
     */
    public function testLoggerIsSetCorrectly(): void
    {
        $this->assertPropertySame('logger', $this->logger);
    }

    /**
     * Test that the action_function is set correctly.
     *
     * @covers PHPMailer\Logger\PHPMailer::__construct
     */
    public function testRequestIsSetCorrectly(): void
    {
        $this->assertPropertySame('action_function', [ $this->class, 'after_sending' ]);
    }

    /**
     * Test that the start_time is unset correctly.
     *
     * @covers PHPMailer\Logger\PHPMailer::__destruct
     */
    public function testStartTimeIsUnsetCorrectly(): void
    {
        $this->set_reflection_property_value('start_time', microtime(TRUE));

        $this->class->__destruct();

        $this->assertPropertyUnset('start_time');
    }

    /**
     * Test that the send() sets start_time correctly.
     *
     * @covers PHPMailer\Logger\PHPMailer::send
     */
    public function testSendSetsStartTimeCorrectly(): void
    {
        $this->mock_function('microtime', fn() => 1724932394.128985);

        $this->class->send();

        $this->assertPropertySame('start_time', 1724932394.128985);

        uopz_unset_return('microtime');
    }

}

?>
