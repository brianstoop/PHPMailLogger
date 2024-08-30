<?php

/**
 * This file contains the InfluxDBMailLoggerRecordPointTest class.
 */

namespace PHPMailer\Logger\Tests;

use InfluxDB\Exception as InfluxDBException;

/**
 * This class contains the base tests for the InfluxDBMailLogger.
 *
 * @covers PHPMailer\Logger\InfluxDBMailLogger
 */
class InfluxDBMailLoggerRecordPointTest extends InfluxDBMailLoggerTest
{

    /**
     * Test that the point is recorded.
     *
     * @covers PHPMailer\Logger\InfluxDBMailLogger::record_point
     */
    public function testDataIsRecorded(): void
    {
        $this->client->expects($this->once())
                     ->method('takeMeasurement')
                     ->willReturn($this->point);

        $this->point->expects($this->once())
                    ->method('recordTimestamp');

        $this->point->expects($this->once())
                    ->method('addTags')
                    ->with([ 'tags' => 'tag' ]);

        $this->point->expects($this->once())
                    ->method('addFields')
                    ->with([]);

        $this->point->expects($this->once())
                    ->method('setMeasurement')
                    ->with('measurement');

        $this->client->expects($this->once())
                     ->method('selectDB')
                     ->with('database')
                     ->willReturn($this->database);

        $this->database->expects($this->once())
                       ->method('writePoints')
                       ->with([ $this->point ], NULL, '15d')
                       ->willReturnSelf();

        $method = $this->get_reflection_method('record_point');
        $method->invokeArgs($this->class, [[], []]);
    }

    /**
     * Test that the point is recorded and does not rethrow errors.
     *
     * @covers PHPMailer\Logger\InfluxDBMailLogger::record_point
     */
    public function testDataIsRecordedDoesNotRethrow(): void
    {
        $this->client->expects($this->once())
                     ->method('takeMeasurement')
                     ->willReturn($this->point);

        $this->client->expects($this->once())
                     ->method('selectDB')
                     ->willThrowException(new InfluxDBException());

        $method = $this->get_reflection_method('record_point');
        $method->invokeArgs($this->class, [[], []]);
    }

}

?>
