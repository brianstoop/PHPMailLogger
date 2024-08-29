<?php

/**
 * This file contains the PHPMailerTest class.
 */

namespace PHPMailer\Logger\Tests;

use Lunr\Halo\LunrBaseTest;
use PHPMailer\Logger\MailLoggerInterface;
use PHPMailer\Logger\PHPMailer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the PHPMailerTest class.
 *
 * @covers PHPMailer\Logger\PHPMailer
 */
abstract class PHPMailerTest extends LunrBaseTest
{

    /**
     * Instance of the MailLoggerInterface.
     * @var MailLoggerInterface&MockObject&Stub
     */
    protected MailLoggerInterface&MockObject&Stub $mail_logger;

    /**
     * Instance of the tested class.
     * @var PHPMailer
     */
    protected PHPMailer $class;

    /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {
        $this->mail_logger = $this->getMockBuilder(MailLoggerInterface::class)->getMock();

        $this->class = new PHPMailer($this->mail_logger);

        $this->baseSetUp($this->class);
    }

    /**
     * TestCase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->class);
        unset($this->mail_logger);

        parent::tearDown();
    }

}

?>
