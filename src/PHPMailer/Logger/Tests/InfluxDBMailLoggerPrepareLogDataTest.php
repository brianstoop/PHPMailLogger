<?php

/**
 * This file contains the InfluxDBMailLoggerPrepareLogDataTest class.
 */

namespace PHPMailer\Logger\Tests;

/**
 * This class contains the base tests for the InfluxDBMailLogger.
 *
 * @covers PHPMailer\Logger\InfluxDBMailLogger
 */
class InfluxDBMailLoggerPrepareLogDataTest extends InfluxDBMailLoggerTest
{

    /**
     * Test that the influxdb client is passed correctly.
     *
     * @param null|string $input  Method input.
     * @param null|string $output Expected output.
     * @param int         $level  Log level.
     *
     * @dataProvider logDataOutcomeProvider
     *
     * @covers PHPMailer\Logger\InfluxDBMailLogger::prepare_log_data
     */
    public function testDataPrepares(?string $input, ?string $output, int $level): void
    {
        $this->set_reflection_property_value('level', $level);

        $method = $this->get_reflection_method('prepare_log_data');
        $data   = $method->invokeArgs($this->class, [ $input ]);

        $this->assertEquals($output, $data);
    }

    /**
     * Provide data for the influxdb client.
     *
     * @return array
     */
    public function logDataOutcomeProvider(): array
    {
        $string  = 'b7rrrEKWPBBniam2zDQjn2QaYE5dAPLgfyTy2RbTPVykQDrYeq3HKjTKPLeSgaf8dTJNiatfrbGKMUBU4VYY8PphqxBZSe6mKuz2R7FVdcc9VZmAEkNDg7mfT7EPcvg';
        $string .= 'LgTKUihAfxc76CihMFqVpnU7e3iqWJdBPLnP34JQ2zQVBmSv8kvHjAGrv5fCVnPCEvbQx5PUNBukQVNFZukLtEtb2ZYy54JqjbHi4CF9kWV9MHq2Ah5A9vjYLxTBziT';
        $string .= 'MYcTCtXxcFCVYQ6awvkN9TdupdD7ihecSHB79JbqPSAVbRbz4ZFtnbe2aPzVRmVvkLDuFefmutDfGgKCizYMGJnExv6ViCryU4JZAufWxeag22BrDJ34aBRwbnCqwEa';
        $string .= 't2K6p45zvvCVpen5Z6VkQCiLGV5kGzfhb6cgUvnvyKK5tzjE7xx95PLupW8uPaCYyrpgT9RS8GQNf72qwnA5bebjRe3hi66KXLaJU2d5Tkpe4eRutgucvKFFBk8MxkY';

        return [
            'Debug shortened string' => [
                $string . 'E984TBDFDAKJF',
                $string . 'E984...',
                1
            ],
            'Debug full string' => [
                $string . 'E984',
                $string . 'E984',
                1
            ],
            'Full_debug string'      => [
                $string . 'E984TBDFDAKJF',
                $string . 'E984TBDFDAKJF',
                2
            ],
        ];
    }

}

?>
