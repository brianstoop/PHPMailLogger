<?php

/**
 * This file contains the PHPMailerTest class.
 */

namespace Lunr\Ticks\PHPMailer\Tests;

use Lunr\Halo\LunrBaseTest;
use Lunr\Ticks\EventLogging\EventInterface;
use Lunr\Ticks\PHPMailer\PHPMailer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the PHPMailerTest class.
 *
 * @covers Lunr\Ticks\PHPMailer\PHPMailer
 */
abstract class PHPMailerTest extends LunrBaseTest
{

    /**
     * Instance of the EventInterface.
     * @var EventInterface&MockObject&Stub
     */
    protected EventInterface&MockObject&Stub $logger;

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
        $this->logger = $this->getMockBuilder(EventInterface::class)->getMock();

        $this->class = new PHPMailer($this->logger, 1);

        $this->baseSetUp($this->class);
    }

    /**
     * TestCase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->class);
        unset($this->logger);

        parent::tearDown();
    }

}

?>
