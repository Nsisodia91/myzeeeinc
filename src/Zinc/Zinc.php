<?php

namespace Zinc;

//Dotenv
use Dotenv\Dotenv;

//For handling Exceptions
use Exception;

//Guzzle classes for HTTP request and response
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

//Base class with defined variables
use Zinc\Base;
use Zinc\Filelogs;

/**
 * Class that interprets with zinc api and retrieve its output
 */
class Zinc extends Base
{

    /**
     * Environment variable for whether its sandbox/production
     * @var string
     */
    protected $environment;

    /**
     * API client id required for authorization
     * @var string
     */
    protected $clientId;

    /**
     * API client secret required for authorization
     * @var string
     */
    protected $clientSecret;

    /**
     * GuzzleHttp\Client object
     * @var object
     */
    private $guzzle_client;

    /**
     * Dotenv\Dotenv object
     * @var object
     */
    private $dotenv;

    /**
     * API URL
     * @var string
     */
    private $url;

    /**
     * API HTTP method been used for call
     * @var string
     */
    private $method;

    /**
     * Params thats been identified on fly while creating guzzle request
     * @var array
     */
    private $client_method;

    /**
     * Zinc\Filelogs object
     * @var object
     */
    private $log;

    /**
     * Default object definition
     */
    public function __construct()
    {
        //Defining guzzle_client object
        $this->guzzle_client = new GuzzleHttpClient();

        //Defining env object and loading within page
        $this->dotenv        = new Dotenv(__DIR__);
        $this->dotenv->load();

        //setting environment on fly and token on fly
        $this->setEnvironmentOnFly()->setTokenOnFly();

        //defining log object
        $this->log = new Filelogs(dirname(dirname(__DIR__)));
    }

    /**
     * Setting clientId and clientSecret manually
     * @param string $clientId
     * @param string $clientSecret
     */
    public function setToken($clientId, $clientSecret)
    {
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * Setting environment manually
     * @param object
     */
    public function setEnvironment($environment)
    {
        if (!empty($environment)) {
            $this->environment = getenv($environment);
        }
        return $this;
    }

    /**
     * Calling zinc API with its url request and all post/put/get params
     * @param  string $apirequest
     * @param  array  $post
     * @return array
     */
    protected function callZincAPI($apirequest, $post = [])
    {
        try {
            $this->getHttpMethod($apirequest)->getMethodBasedURL($apirequest);

            $this->getHttpClientMethod($this->method);

            if ($this->method == 'GET') {
                $this->updateGetRequest($post);
            }

            $logs_arr = [
                'http_method'        => $this->method,
                'http_url'           => $this->url,
                $this->client_method => $post,
                'headers'            => $this->headers,
            ];
            $this->writeLog("<hr/><b>Zinc Request</b> <br/><br/> <b>Called Method</b> : {$apirequest}<br/> <b>Called At</b> : " . date('Y-m-d H:i:s') . "<br/> <b>Called Params</b> : " . json_encode($logs_arr) . '<br/>');

            $response = $this->guzzle_client->request($this->method, $this->url, ['auth' => [$this->clientId, ''], $this->client_method => $post, 'headers' => $this->headers]);

            $response_body = $response->getBody()->getContents();

            $this->writeLog("<br/> <b>Zinc Response</b> <br> {$response_body} <br/><hr/>");

            $data = json_decode($response_body, true);

            if (!empty($data['_type']) && $data['_type'] == 'error') {
                if (!empty($data['error']) && isset($data['error']['message'])) {
                    throw new Exception($data['error']['message'], 0);
                } elseif (!empty($data['message']) || !empty($data['code'])) {
                    throw new Exception($data['code'] . ' ' . $data['message'], 0);
                } else {
                    throw new Exception('Something went wrong!', 0);
                }
            }
            return $data;
        } catch (RequestException $e) {
            $response = $this->statusCodeHandling($e);
            return $response;
        }
    }

    /**
     * Default function to set environment based on env variables
     */
    private function setEnvironmentOnFly()
    {
        if (strtolower(getenv('APP_ENV')) == 'production') {
            $this->setEnvironment('PRODUCTION_URL');
        } else {
            $this->setEnvironment('SANDBOX_URL');
        }

        return $this;
    }

    /**
     * Default function to set clientId and clientSecret based on env variables
     */
    private function setTokenOnFly()
    {
        $this->setToken(getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));
        return $this;
    }

    /**
     * Handling any Exception occured while calling GuzzleHTTP call for any API
     * @param  object $e
     * @return array
     */
    protected function statusCodeHandling($e)
    {
        $body = $e->getResponse()->getBody(true)->getContents();

        $body_contents = json_decode($body, true);

        $this->writeLog("<br/> <b>Zinc Response with Error</b> <br> {$body} <br/><hr/>");

        $error = (!empty($body_contents) && !empty($body_contents[$this->error_description])) ? $body_contents['error_description'] : $e->getResponse()->getReasonPhrase();

        return [
            "statuscode" => $e->getResponse()->getStatusCode(),
            "error"      => $error,
        ];
    }

    /**
     * To set URL variable to be accessed within class
     * @param  string $api_method
     * @return object
     */
    private function getMethodBasedURL($api_method)
    {
        $this->url = $this->environment . self::$api_methods[$api_method];
        return $this;
    }

    /**
     * Setting api endpoints for HTTP call whether call is POST/PUT/GET
     * @param string $api_endpoint
     * @return object
     */
    private function getHttpMethod($api_endpoint)
    {
        switch ($api_endpoint) {
            case in_array($api_endpoint, self::$api_endpoints['POST']):
                $this->method = 'POST';
                break;
            case in_array($api_endpoint, self::$api_endpoints['GET']):
                $this->method = 'GET';
                break;
            case in_array($api_endpoint, self::$api_endpoints['PUT']):
                $this->method = 'PUT';
                break;
            default:
                $this->method = 'GET';
                break;
        }
        return $this;
    }

    /**
     * To set method of calling params within GUZZLEHTTP whether its POST/PUT/GET
     * @param  string $api_type
     * @return object
     */
    private function getHttpClientMethod($api_type)
    {
        switch ($api_type) {
            case in_array($api_type, self::$api_method_type['body']):
                $this->client_method = 'body';
                break;
            case in_array($api_type, self::$api_method_type['query']):
                $this->client_method = 'query';
                break;
            default:
                $this->client_method = 'query';
                break;
        }
        return $this;
    }

    /**
     * To modify get URL
     * @param  array  $params
     * @return object
     */
    protected function updateGetRequest(array $params)
    {
        $this->url = strtr($this->url, $params);
        return $this;
    }

    /**
     * To write up logs for request/response call placed on GUZZLEHTTP
     * @param  string $content
     * @param  string $filename
     * @return object
     */
    public function writeLog($content, $filename = '')
    {
        if (getenv('APP_LOG') == 'false') {
            return $this;
        }

        //file name to be created for log file
        $file_name = (empty($filename)) ? "log_" . date("Y_m_d_H") . ".html" : $filename;

        //method to file log with html data
        $this->log->setFile($file_name)->setContent($content)->setFilePath('logs/zinc', 0777)->writeLog();

        return $this;
    }

    /**
     * To validate that given string is valid JSON or not
     * @param  string  $string
     * @return boolean
     */
    protected function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * To check that error code exist for stopping cron
     * @param  string $string
     * @return boolean
     */
    public function checkToStopCron($string)
    {
        return in_array($string, $this->cron_stop);
    }
}
