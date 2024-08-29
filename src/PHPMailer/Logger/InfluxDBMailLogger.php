<?php

/**
 * This file contains the InfluxDBMailLogger class.
 */

namespace PHPMailer\Logger;

use InfluxDB\Exception as InfluxDBException;
use Stellr\Ticks\InfluxDBClient;
use Stellr\Ticks\ProfilingLevel;

/**
 * InfluxDBMailLogger class
 *
 * @phpstan-import-type Options from MailLoggerInterface
 */
class InfluxDBMailLogger implements MailLoggerInterface
{

    /**
     * InfluxDB Client
     * @var InfluxDBClient
     */
    private readonly InfluxDBClient $influx_client;

    /**
     * Profiling level
     * @var int
     */
    private int $level;

    /**
     * Measurement name to push records to
     * @var string
     */
    private readonly string $measurement;

    /**
     * Database name to push records to
     * @var string
     */
    private readonly string $database;

    /**
     * Tags to add to all measurements
     * @var array<string,string>
     */
    private array $tags;

    /**
     * Fields to add to all measurements
     * @var array<string, mixed>
     */
    private array $fields;

    /**
     * Retention policy to apply
     * @var string|null
     */
    private readonly ?string $policy;

    /**
     * Constructor
     *
     * @param InfluxDBClient       $client           Client to log with
     * @param string               $database         Database name to log to
     * @param string               $measurement      Measurement to log to
     * @param array<string,string> $tags             Default tags
     * @param array<string, mixed> $fields           Default fields
     * @param string|null          $retention_policy Default retention policy
     */
    public function __construct(
        InfluxDBClient $client,
        string $database,
        string $measurement,
        array $tags = [],
        array $fields = [],
        ?string $retention_policy = NULL
    )
    {
        $this->influx_client = $client;
        $this->database      = $database;
        $this->measurement   = $measurement;
        $this->tags          = $tags;
        $this->fields        = $fields;
        $this->policy        = $retention_policy;
        $this->level         = ProfilingLevel::INFO;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        unset($this->tags);
        unset($this->fields);
        unset($this->level);
    }

    /**
     * Set profiling level.
     *
     * @param int $level Profiling level
     *
     * @return void
     */
    public function set_profiling_level(int $level): void
    {
        $this->level = $level;
    }

    /**
     * Log the mail info
     *
     * @param string  $type       Type of mail
     * @param string  $to         The address the mail was sent to
     * @param bool    $status     The status of the mail
     * @param float   $duration   The duration of the mail request
     * @param string  $req_header The MIME mail header
     * @param string  $req_body   The MIME mail body
     * @param Options $options    The mail options
     *
     * @return void
     */
    public function log_mail(string $type, string $to, bool $status, float $duration, string $req_header, string $req_body, array $options): void
    {
        $tags = [
            'type'   => 'MAIL-' . $type,
            'status' => $status,
        ];

        $fields = [
            'url'      => $to,
            'duration' => $duration,
        ];

        if ($this->level !== ProfilingLevel::INFO)
        {
            $fields['request_headers'] = $this->prepare_log_data($req_header);
            $fields['data']            = $this->prepare_log_data($req_body);
            $fields['options']         = json_encode($options);
        }

        $this->record_point($fields, $tags);
    }

    /**
     * Prepare data according to loglevel.
     *
     * @param string $data Data to prepare for logging.
     *
     * @return string Prepare data to log.
     */
    private function prepare_log_data(string $data): string
    {
        if ($this->level === ProfilingLevel::DEBUG && strlen($data) > 512)
        {
            return substr($data, 0, 512) . '...';
        }

        return $data;
    }

    /**
     * Finalize measurement point data and send to InfluxDB.
     *
     * @param array<string,mixed>       $fields Field data
     * @param array<string,string|bool> $tags   Tag data
     *
     * @return void
     */
    private function record_point(array $fields, array $tags): void
    {
        $point = $this->influx_client->takeMeasurement();

        $point->recordTimestamp();
        $point->addTags(array_merge($this->tags, $tags));
        $point->addFields(array_merge($this->fields, $fields));
        $point->setMeasurement($this->measurement);

        try
        {
            $this->influx_client->selectDB($this->database)->writePoints(points: [ $point ], retentionPolicy: $this->policy);
        }
        catch (InfluxDBException $e)
        {
            // Ignore not being able to log to InfluxDB
        }
    }

}

?>
