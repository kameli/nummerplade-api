<?php

namespace Kameli\NummerpladeApi;

use Exception;

class Client
{
    const API_URL = 'http://api.nrpla.de/';

    /** @var string */
    protected $apiToken;

    /**
     * @param string $apiToken
     */
    public function __construct($apiToken)
    {
        $this->apiToken = $apiToken;
    }

    /**
     * @param string $registration
     * @param bool $advanced
     * @return object
     * @throws \Exception
     */
    public function vehicleByRegistration($registration, $advanced = false)
    {
        return $this->request($registration, ['advanced' => (bool) $advanced]);
    }

    /**
     * @param string $vin
     * @param bool $advanced
     * @return object
     * @throws \Exception
     */
    public function vehicleByVin($vin, $advanced = false)
    {
        return $this->request('vin/' . $vin, ['advanced' => (bool) $advanced]);
    }

    /**
     * @param string $vehicle_id
     * @return object
     * @throws \Exception
     */
    public function dmr($vehicle_id)
    {
        return $this->request('dmr/' . $vehicle_id);
    }

    /**
     * @param string $vehicle_id
     * @return object
     * @throws \Exception
     */
    public function debt($vehicle_id)
    {
        return $this->request('debt/' . $vehicle_id);
    }

    /**
     * @param string $vehicle_id
     * @return object
     * @throws \Exception
     */
    public function inspections($vehicle_id)
    {
        return $this->request('inspections/' . $vehicle_id);
    }

    /**
     * @param string $input
     * @return object
     * @throws \Exception
     */
    public function emissions($input)
    {
        return $this->request('emissions/' . $input);
    }

    /**
     * @param string $endpoint
     * @param array $parameters
     * @return object
     * @throws \Exception
     */
    protected function request($endpoint, $parameters = [])
    {
        $ch = curl_init();
        $query = http_build_query(array_merge($parameters, ['api_token' => $this->apiToken]));
        curl_setopt_array($ch, [
            CURLOPT_URL => static::API_URL . $endpoint . '?' . $query,
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 8
        ]);

        if (! $result = curl_exec($ch)) {
            throw new Exception(curl_error($ch));
        }

        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);

        if ($response_code === 401) {
            throw new Exception('Nummerplade API - Wrong API Token');
        }

        $response = json_decode(substr($result, $header_size));

        if ($response_code === 200) {
            return $response->data;
        } else {
            throw new Exception($response->message, $response->status_code);
        }
    }
}
