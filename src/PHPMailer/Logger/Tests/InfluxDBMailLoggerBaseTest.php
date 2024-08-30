<?php

/**
 * This file contains the InfluxDBMailLoggerBaseTest class.
 */

namespace PHPMailer\Logger\Tests;

use Stellr\Ticks\ProfilingLevel;

/**
 * This class contains the base tests for the InfluxDBMailLogger.
 *
 * @covers PHPMailer\Logger\InfluxDBMailLogger
 */
class InfluxDBMailLoggerBaseTest extends InfluxDBMailLoggerTest
{

    /**
     * Test that the influxdb client is passed correctly.
     *
     * @covers PHPMailer\Logger\InfluxDBMailLogger::__construct
     */
    public function testInfluxDBClientIsPassedCorrectly(): void
    {
        $this->assertPropertySame('influx_client', $this->client);
    }

    /**
     * Test that data is set correctly in constructor.
     *
     * @covers PHPMailer\Logger\InfluxDBMailLogger::__construct
     */
    public function testDataSetCorrectly(): void
    {
        $this->assertPropertySame('database', 'database');
        $this->assertPropertySame('measurement', 'measurement');
        $this->assertPropertySame('tags', [ 'tags' => 'tag' ]);
        $this->assertPropertySame('fields', []);
        $this->assertPropertySame('policy', '15d');
        $this->assertPropertySame('level', 0);
    }

    /**
     * Test that data is set correctly in constructor.
     *
     * @covers PHPMailer\Logger\InfluxDBMailLogger::__destruct
     */
    public function testDataUnsetCorrectly(): void
    {
        $this->class->__destruct();

        $this->assertPropertyUnset('tags', [ 'tags' => 'tag' ]);
        $this->assertPropertyUnset('fields', []);
        $this->assertPropertyUnset('level', 0);
    }

    /**
     * Test that the profiling level can be set
     *
     * @covers PHPMailer\Logger\InfluxDBMailLogger::set_profiling_level
     */
    public function testProfilingLevelSetsCorrectly(): void
    {
        $this->class->set_profiling_level(ProfilingLevel::DEBUG);
        $this->assertPropertySame('level', 1);
    }

}

?>
