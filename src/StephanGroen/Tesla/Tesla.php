<?php

namespace StephanGroen\Tesla;

class Tesla
{
    protected $apiBaseUrl = "https://owner-api.teslamotors.com/api/1";
    protected $tokenUrl = 'https://owner-api.teslamotors.com/oauth/token';
    protected $accessToken;
    protected $vehicleId = null;

    public function __construct(string $accessToken = null)
    {
        $this->accessToken = $accessToken;
    }

    public function setAccessToken(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function allData() : array
    {
        return $this->sendRequest('/vehicle_data')['response'];
    }

    public function vehicles()
    {
        return $this->sendRequest('/vehicles');
    }

    public function vehicle()
    {
        return $this->sendRequest('')['response'];
    }

    public function setVehicleId(int $vehicleId)
    {
        $this->vehicleId = $vehicleId;
    }

    public function setClientId(string $clientId)
    {
        putenv('TESLA_CLIENT_ID=' . $clientId);
    }

    public function setClientSecret(string $clientSecret)
    {
        putenv('TESLA_CLIENT_SECRET=' . $clientSecret);
    }

    public function mobileEnabled() : bool
    {
        return $this->sendRequest('/mobile_enabled')['response'];
    }

    public function chargeState() : array
    {
        return $this->sendRequest('/data_request/charge_state')['response'];
    }

    public function climateState() : array
    {
        return $this->sendRequest('/data_request/climate_state')['response'];
    }

    public function driveState() : array
    {
        return $this->sendRequest('/data_request/drive_state')['response'];
    }

    public function guiSettings() : array
    {
        return $this->sendRequest('/data_request/gui_settings')['response'];
    }

    public function vehicleState() : array
    {
        return $this->sendRequest('/data_request/vehicle_state')['response'];
    }

    public function vehicleConfig() : array
    {
        return $this->sendRequest('/data_request/vehicle_config')['response'];
    }

    public function wakeUp() : array
    {
        return $this->sendRequest('/wake_up', [], 'POST')['response'];
    }

    public function setValetMode(bool $active = false, int $pin = 0000) : array
    {
        $params = [
            'on' => $active,
            'pin' => $pin
        ];

        return $this->sendRequest('/command/set_valet_mode', $params, 'POST')['response'];
    }

    public function resetValetPin() : array
    {
        return $this->sendRequest('/command/reset_valet_pin', [], 'POST')['response'];
    }

    public function openChargePort() : array
    {
        return $this->sendRequest('/command/charge_port_door_open', [], 'POST')['response'];
    }

    public function setChargeLimitToStandard() : array
    {
        return $this->sendRequest('/command/charge_standard', [], 'POST')['response'];
    }

    public function setChargeLimitToMaxRange() : array
    {
        return $this->sendRequest('/command/charge_max_range', [], 'POST')['response'];
    }

    public function setChargeLimit(int $percent = 90) : array
    {
        $params = [
            'percent' => "$percent"
        ];
        return $this->sendRequest('/command/set_charge_limit', $params, 'POST')['response'];
    }

    public function startCharging() : array
    {
        return $this->sendRequest('/command/charge_start', [], 'POST')['response'];
    }

    public function stopCharging() : array
    {
        return $this->sendRequest('/command/charge_stop', [], 'POST')['response'];
    }

    public function flashLights() : array
    {
        return $this->sendRequest('/command/flash_lights', [], 'POST')['response'];
    }

    public function honkHorn() : array
    {
        return $this->sendRequest('/command/honk_horn', [], 'POST')['response'];
    }

    public function unlockDoors() : array
    {
        return $this->sendRequest('/command/door_unlock', [], 'POST')['response'];
    }

    public function lockDoors() : array
    {
        return $this->sendRequest('/command/door_lock', [], 'POST')['response'];
    }

    public function setTemperature(float $driverDegreesCelcius = 20.0, float $passengerDegreesCelcius = 20.0) : array
    {
        return $this->sendRequest('/command/set_temps?driver_temp=' . $driverDegreesCelcius . '&passenger_temp=' . $passengerDegreesCelcius, [], 'POST')['response'];
    }

    public function startHvac() : array
    {
        return $this->sendRequest('/command/auto_conditioning_start', [], 'POST')['response'];
    }

    public function stopHvac() : array
    {
        return $this->sendRequest('/command/auto_conditioning_stop', [], 'POST')['response'];
    }

    public function movePanoramicRoof(string $state = 'vent', int $percent = 50) : array
    {
        return $this->sendRequest('/command/sun_roof_control?state=' . $state . '&percent=' . $percent, [], 'POST')['response'];
    }

    public function remoteStart(string $password = '') : array
    {
        return $this->sendRequest('/command/remote_start_drive?password=' . $password, [], 'POST')['response'];
    }

    public function openTrunk() : array
    {
        return $this->sendRequest('/command/trunk_open?which_trunk=rear', [], 'POST')['response'];
    }

    public function setNavigation(string $location) : array
    {
        $params = [
            'type' => 'share_ext_content_raw',
            'value' => [
                'android.intent.extra.TEXT' => $location
            ],
            'locale' => 'en-US',
            'timestamp_ms' => time(),
        ];
        return $this->sendRequest('/command/navigation_request', $params, 'POST')['response'];
    }

    public function getAccessToken(string $username, string $password)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->tokenUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'User-Agent: Mozilla/5.0 (Linux; Android 9.0.0; VS985 4G Build/LRX21Y; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/58.0.3029.83 Mobile Safari/537.36',
            'X-Tesla-User-Agent: TeslaApp/3.4.4-350/fad4a582e/android/9.0.0',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'grant_type' => 'password',
            'client_id' => getenv('TESLA_CLIENT_ID'),
            'client_secret' => getenv('TESLA_CLIENT_SECRET'),
            'email' => $username,
            'password' => $password,
        ]));

        $apiResult = curl_exec($ch);
        $apiResultJson = json_decode($apiResult, true);

        curl_close($ch);

        $this->accessToken = $apiResultJson['access_token'];

        return $apiResultJson;
    }

    public function refreshAccessToken(string $refreshToken)
    {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->tokenUrl);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 30);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'User-Agent: Mozilla/5.0 (Linux; Android 9.0.0; VS985 4G Build/LRX21Y; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/58.0.3029.83 Mobile Safari/537.36',
        'X-Tesla-User-Agent: TeslaApp/3.4.4-350/fad4a582e/android/9.0.0',
      ]);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'grant_type' => 'refresh_token',
        'client_id' => getenv('TESLA_CLIENT_ID'),
        'client_secret' => getenv('TESLA_CLIENT_SECRET'),
        'refresh_token' => $refreshToken,
      ]));

      $apiResult = curl_exec($ch);
      $apiResultJson = json_decode($apiResult, true);

      curl_close($ch);

      $this->accessToken = $apiResultJson['access_token'];

      return $apiResultJson;
    }

    protected function sendRequest(string $endpoint, array $params = [], string $method = 'GET')
    {
        $ch = curl_init();

        if ($endpoint !== '/vehicles' && ! is_null($this->vehicleId)) {
            $endpoint = '/vehicles/' . $this->vehicleId . $endpoint;
        }

        curl_setopt($ch, CURLOPT_URL, $this->apiBaseUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'User-Agent: Mozilla/5.0 (Linux; Android 9.0.0; VS985 4G Build/LRX21Y; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/58.0.3029.83 Mobile Safari/537.36',
            'X-Tesla-User-Agent: TeslaApp/3.4.4-350/fad4a582e/android/9.0.0',
            'Authorization: Bearer ' . $this->accessToken,
        ]);

        if ($method == 'POST' || $method == 'PUT' || $method == 'DELETE') {
            if ($method == 'POST') {
                curl_setopt($ch, CURLOPT_POST, true);
            }
            if (in_array($method, ['PUT', 'DELETE'])) {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }

        $apiResult = curl_exec($ch);
        $headerInfo = curl_getinfo($ch);
        $apiResultJson = json_decode($apiResult, true);
        curl_close($ch);

        $result = [];
        if ($apiResult === false) {
            $result['errorcode'] = 0;
            $result['errormessage'] = curl_error($ch);

            throw new TeslaException($result['errormessage'], $result['errorcode']);
        }

        if (! in_array($headerInfo['http_code'], ['200', '201', '204'])) {
            $result['errorcode'] = $headerInfo['http_code'];
            if (isset($apiresult)) {
                $result['errormessage'] = $apiresult;
            }

            throw new TeslaException($result['errormessage'] ?? 'Error occured', $result['errorcode']);
        }

        return $apiResultJson ?? $apiResult;
    }
}
