<?php

/**
 * This file contains the InfluxDBMailLoggerLogMailTest class.
 */

namespace PHPMailer\Logger\Tests;

/**
 * This class contains the base tests for the InfluxDBMailLogger.
 *
 * @covers PHPMailer\Logger\InfluxDBMailLogger
 */
class InfluxDBMailLoggerLogMailTest extends InfluxDBMailLoggerTest
{

    /**
     * Test that log_mail writes correctly on INFO level.
     *
     * @covers PHPMailer\Logger\InfluxDBMailLogger::log_mail
     */
    public function testLogMailWritesCrorrectlyForInfoLevel(): void
    {
        $this->set_reflection_property_value('level', 0);

        $options = [
            'subject' => 'subject',
            'body'    => 'body',
            'from'    => 'from@mail.com',
        ];

        $this->client->expects($this->once())
                     ->method('takeMeasurement')
                     ->willReturn($this->point);

        $this->point->expects($this->once())
                    ->method('addTags')
                    ->with([
                        'type'   => 'MAIL-smtp',
                        'status' => TRUE,
                        'tags'   => 'tag',
                    ]);

        $this->point->expects($this->once())
                    ->method('addFields')
                    ->with([
                        'url'      => 'example@mail.com',
                        'duration' => 1.119999885559082,
                    ]);

        $this->client->expects($this->once())
                     ->method('selectDB')
                     ->willReturn($this->database);

        $this->class->log_mail('smtp', 'example@mail.com', TRUE, 1.119999885559082, 'full mime header', 'full mime body', $options);
    }

    /**
     * Test that log_mail writes correctly on DEBUG level.
     *
     * @covers PHPMailer\Logger\InfluxDBMailLogger::log_mail
     */
    public function testLogMailWritesCrorrectlyForDEBUGLevel(): void
    {
        $this->set_reflection_property_value('level', 1);

        $options = [
            'subject' => 'subject',
            'body'    => 'body',
            'from'    => 'from@mail.com',
        ];

        $this->client->expects($this->once())
                     ->method('takeMeasurement')
                     ->willReturn($this->point);

        $this->point->expects($this->once())
                    ->method('addTags')
                    ->with([
                        'type'   => 'MAIL-smtp',
                        'status' => TRUE,
                        'tags'   => 'tag',
                    ]);

        $this->point->expects($this->once())
                    ->method('addFields')
                    ->with([
                        'url'             => 'example@mail.com',
                        'duration'        => 1.119999885559082,
                        'data'            => 'full mime body',
                        'request_headers' => 'full mime header',
                        'options'         => json_encode($options),
                    ]);

        $this->client->expects($this->once())
                     ->method('selectDB')
                     ->willReturn($this->database);

        $this->class->log_mail('smtp', 'example@mail.com', TRUE, 1.119999885559082, 'full mime header', 'full mime body', $options);
    }

}

?>
