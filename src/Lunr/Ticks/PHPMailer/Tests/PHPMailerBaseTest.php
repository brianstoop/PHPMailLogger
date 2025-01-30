<?php

/**
 * This file contains the PHPMailerBaseTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2025 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\PHPMailer\Tests;

/**
 * This class contains tests for the PHPMailer class.
 *
 * @covers Lunr\Ticks\PHPMailer\PHPMailer
 */
class PHPMailerBaseTest extends PHPMailerTestCase
{

    /**
     * Test that the logger is set correctly.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::__construct
     */
    public function testLoggerIsSetCorrectly(): void
    {
        $this->assertPropertySame('logger', $this->logger);
    }

    /**
     * Test that the action_function is set correctly.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::__construct
     */
    public function testRequestIsSetCorrectly(): void
    {
        $this->assertPropertySame('action_function', [ $this->class, 'afterSending' ]);
    }

    /**
     * Test that the startTime is unset correctly.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::__destruct
     */
    public function testStartTimeIsUnsetCorrectly(): void
    {
        $this->setReflectionPropertyValue('startTime', microtime(TRUE));

        $this->class->__destruct();

        $this->assertPropertyUnset('startTime');
    }

    /**
     * Test that the send() sets start_time correctly.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::send
     */
    public function testSendSetsStartTimeCorrectly(): void
    {
        $this->mockFunction('microtime', fn() => 1724932394.128985);

        $this->class->send();

        $this->assertPropertySame('startTime', 1724932394.128985);

        uopz_unset_return('microtime');
    }

}

?>
