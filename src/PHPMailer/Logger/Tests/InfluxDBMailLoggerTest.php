<?php

/**
 * This file contains the InfluxDBMailLoggerTest class.
 */

namespace PHPMailer\Logger\Tests;

use InfluxDB\Database;
use Lunr\Halo\LunrBaseTest;
use PHPMailer\Logger\InfluxDBMailLogger;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use Stellr\Ticks\InfluxDBClient;
use Stellr\Ticks\MeasurementPoint;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the InfluxDBMailLoggerTest class.
 *
 * @covers PHPMailer\Logger\InfluxDBMailLogger
 */
abstract class InfluxDBMailLoggerTest extends LunrBaseTest
{

    /**
     * Mock Instance of the InfluxDBClient.
     *
     * @var InfluxDBClient&MockObject&Stub
     */
    protected InfluxDBClient&MockObject&Stub $client;

    /**
     * Mock Instance of the MeasurementPoint.
     *
     * @var MeasurementPoint&MockObject&Stub
     */
    protected MeasurementPoint&MockObject&Stub $point;

    /**
     * Mock Instance of the Database.
     *
     * @var Database&MockObject&Stub
     */
    protected Database&MockObject&Stub $database;

    /**
     * Instance of the tested class.
     * @var InfluxDBMailLogger
     */
    protected InfluxDBMailLogger $class;

    /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {
        $this->client = $this->getMockBuilder(InfluxDBClient::class)
                             ->disableOriginalConstructor()
                             ->getMock();

        $this->point = $this->getMockBuilder(MeasurementPoint::class)
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->database = $this->getMockBuilder(Database::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->class = new InfluxDBMailLogger($this->client, 'database', 'measurement', [ 'tags' => 'tag' ], [], '15d');

        $this->baseSetUp($this->class);
    }

    /**
     * TestCase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->class);
        unset($this->client);
        unset($this->point);
        unset($this->database);

        parent::tearDown();
    }

}

?>
