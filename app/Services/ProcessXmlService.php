<?php

namespace App\Services;

use App\Models\JobStatus;
use App\Models\Person;
use App\Models\PersonAka;
use XMLReader;

class ProcessXmlService extends AbstractService
{
    private const TAG = 'sdnEntry';

    private $job_status;

    public function __construct(
        private XMLReader $xml
    ) {
        $this->job_status = JobStatus::updateOrCreate(
            ['status' => JobStatus::OK],
            ['status' => JobStatus::UPDATING]
        );
    }

    /**
     * Process
     *
     * @param  string  $url
     * @return array
     */
    public function process(string $url): void
    {
        $this->xml->open($url);

        $this->readString();
    }

    /**
     * Parse XML to JSON
     *
     * @param  string  $xmlString
     * @return array
     */
    private function parseToJson(string $xmlString)
    {
        $xml = simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NOCDATA);

        $json = json_encode($xml);

        return json_decode($json, true);
    }

    /**
     * Read string
     *
     * @return void
     */
    public function readString(): void
    {
        $depth = 1;

        while ($this->xml->read() && $depth !== 0) {

            if ($this->xml->nodeType === XMLReader::ELEMENT && $this->xml->name === self::TAG) {

                $json = $this->parseToJson($this->xml->readOuterXml());
                $hash = md5(serialize($json));

                $person = Person::select('id', 'uid', 'hash')
                    ->where('uid', $json['uid'])
                    ->first();

                if (!$person) {
                    $person = Person::create([
                        'uid' => $json['uid'],
                        'first_name' => $json['firstName'] ?? '',
                        'last_name' => $json['lastName'] ?? '',
                        'category' => $json['category'] ?? '',
                        'hash' => $hash,
                    ]);

                    $this->updateAkaList($json);

                } else if ($person && $hash != $person->hash) {
                    $person->update([
                        'first_name' => $json['firstName'] ?? '',
                        'last_name' => $json['lastName'] ?? '',
                        'category' => $json['category'] ?? '',
                        'hash' => $hash,
                    ]);

                    $this->updateAkaList($json);
                }
            }
        }
    }

    /**
     * Update AkaList
     *
     * @param  array  $json
     * @return void
     */
    private function updateAkaList(array $json): void
    {
        if (!empty($json['akaList'])) {
            $list = isset($json['akaList']['aka'][0]) ? $json['akaList']['aka'] : $json['akaList'];

            foreach ($list as $aka) {
                PersonAka::updateOrCreate([
                    'uid' => $aka['uid'],
                    'parent_uid' => $json['uid'],
                ], [
                    'first_name' => $aka['firstName'] ?? '',
                    'last_name' => $aka['lastName'] ?? '',
                    'category' => $aka['category'] ?? '',
                ]);
            }
        }
    }

    public function __destruct()
    {
        $this->job_status->update([
            'status' => JobStatus::OK,
        ]);
    }

}
