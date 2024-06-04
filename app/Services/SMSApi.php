<?php

namespace App\Services;

use Exception;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * SMS Client
 *
 * @link https://developer.transmitsms.com/
 */
class SMSApi
{
    private static $apiUrl;
    private static $apiKey;
    private $token = null;

    function __construct()
    {
        $this::$apiUrl = 'https://api.transmitsms.com';
        $this::$apiKey = config('app.sms_service.key');
    }

    /**
     * @link https://support.transmitsms.com/support/solutions/articles/44001940835-sms-message-length-and-character-count
     */
    public function send_sms($data = []){
        return $this->call('send-sms.json', [
            'to'=> $data['to'] ?? '',
            'list_id'=> $data['list_id'] ?? '',
            'message'=> $data['message'] ?? '',
            'send_at'=> $data['send_at'] ?? '',
            'validity'=> $data['validity'] ?? '',
            'replies_to_email'=> $data['replies_to_email'] ?? '',
            'tracked_link_url'=> $data['tracked_link_url'] ?? '',
            'countrycode'=> $data['countrycode'] ?? 'gb',
            'link_hits_callback'=> $data['link_hits_callback'] ?? '',
            'dlr_callback'=> $data['dlr_callback'] ?? '',
            'reply_callback'=> $data['reply_callback'] ?? '',
            'from'=> $data['from'] ?? '',
        ], 'POST');
    }

    public function get_balance()
    {
        return $this->call('get-balance.json');
    }

    private function url($action)
    {
        return rtrim($this::$apiUrl) . '/' . ltrim($action, '/');
    }

    private static function logError($subject = '', $body = ''){
        // send email to admin for every error
    }

    private function withAuth($array = [])
    {
        $bearer = base64_encode($this::$apiKey .':'. $this->token());
        $array[] = "Authorization: Bearer {$bearer}";
        return $array;
    }

    public function call($action, $data = [], $method = 'GET', $shouldCache = false)
    {
        $curl = curl_init($this->url($action));
        try {
            $result = $this->_call($curl, $data, $method, false, $shouldCache);
        } catch (Exception $e) { // refresh token and try again
            Log::info('Refresh token', ['message' => $e->getMessage()]);
            $this->token = '';
            $result = $this->_call($curl, $data, $method, true, $shouldCache);
        }
        $data = json_decode($result, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['error' => $result];
        }
        return $data;
    }

    protected function token()
    {
        if (empty($this->token) || !is_string($this->token) || strlen($this->token) < 1) {
            $keys = ['transmitsms_secret'];
            $setting = Setting::whereIn('key', $keys)->pluck('value', 'key');
            if ( !empty($setting['transmitsms_secret']) ) {
                Cache::put('transmitsms_secret', $setting['transmitsms_secret']);
                $this->token = $setting['transmitsms_secret'];
            } else {
                $transmitsms_secret = config('app.sms_service.secret');
                if(!empty($transmitsms_secret)) {
                    Cache::put('transmitsms_secret', $transmitsms_secret);
                    $this->token = $transmitsms_secret;
                } else {
                    $this::logError('Transmit SMS API token missing', [
                        'message'=> 'Please add token to the database from admin panel or .env file.'
                    ]);
                }
            }
        }
        return $this->token;
    }


    protected function execCurl($curl, $data = [], $method = 'GET')
    {
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $info = curl_getinfo($curl);
        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        if ($method == 'GET') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            if (!empty($data)) {
                $query = http_build_query($data);
                curl_setopt($curl, CURLOPT_URL, $info['url'] . '?' . $query);
            }
        }
        $result = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (!in_array($httpcode, [200, 201])) {
            $this::logError('API CALL ERROR: ' . $info['url'], $result);
            return null;
        }
        return $result;
    }

    protected function _call($curl, $data = [], $method = 'GET', $debug = false, $shouldCache = false)
    {
        if (!$this::$apiUrl) {
            throw new Exception('No API base sepecified');
        }

        $info = curl_getinfo($curl);
        $query = http_build_query($data);
        $full_url = $info['url'] . '?' . $query;
        if ($shouldCache) {
            $cacheKey = 'api_cache_' . md5($full_url);

            if (Cache::has($cacheKey)) {
                Log::info('SERVING CAHCE ', [$full_url]);
                return Cache::get($cacheKey);
            }
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->withAuth([
                "Content-Type: application/json",
                "Content-Lenght: " . strlen(json_encode($data))
            ]));
        }

        if ($method == 'GET') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->withAuth());
            if ($data) {
                $query = http_build_query($data);
                curl_setopt($curl, CURLOPT_URL, $info['url'] . '?' . $query);
            }
        }

        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        // Log::info('API=> ' . $full_url, ['token'=> $this->token, $result]);
        if ($info['http_code'] == 401) {
            if ($debug) {
                $this::logError('Transmit API connection failed', [
                    'result' => $result,
                    'info' => $info,
                ]);
                Log::error('Api connection error', [
                    'result' => $result,
                    'info' => $info,
                ]);
            }
            throw new Exception('No authorization 401');
        }
        if (!$result) {
            $this::logError('Connection to Transmit API failed', [
                'result' => $result,
                'info' => $info,
            ]);
            Log::error('Connection to Transmit API failed', [
                'result' => $result,
                'info' => $info,
            ]);
        }

        if ($shouldCache) {
            // Cache for minutes
            Cache::put($cacheKey, $result, now()->addMinutes(15));
        }
        return $result;
    }

}