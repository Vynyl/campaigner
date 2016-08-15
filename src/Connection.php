<?php

namespace Vynyl\Campaigner;

use \GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Response;
use Vynyl\Campaigner\Responses\CampaignerResponse;

class Connection
{
    /**
     * Instance of Guzzle HTTP client.
     * @var \GuzzleHttp\Client
     */
    private $client;
    /**

     * API Key.
     * @var string
     */
    private $apiKey;

    /**
     * Base URL for API.
     * @var string
     */
    private $baseUrl;

    /**
     * Headers to be sent with request;
     * @var array
     */
    private $headers = [];

    public function __construct($baseUrl, $apiKey)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
        ]);

        $this->setApiKey($this->apiKey);
    }

    /**
     * Sets header to value, replacing existing values.
     * @param $header
     */
    public function setHeader($header, $value)
    {
        $this->headers[$header] = $value;
    }

    /**
     * Makes a GET request.
     * @param $resourceUri string
     * @return CampaignerResponse
     */
    public function get($resourceUri, CampaignerResponse $campaignerResponse)
    {
        $options = [
            'headers' => $this->headers,
        ];
        return $this->request('GET', $resourceUri, $options, $campaignerResponse);
    }

    /**
     * Makes a POST request.
     * @param $resourceUri string
     * @param $payload string
     */
    public function post($resourceUri, $payload, CampaignerResponse $campaignerResponse)
    {
        $this->setHeader("Content-Type", "application/json");
        $options = [
            'headers' => $this->headers,
            'body' => json_encode($payload, JSON_PRETTY_PRINT),
        ];
        return $this->request('POST', $resourceUri, $options, $campaignerResponse);
    }

    /**
     * Makes a PUT request.
     * @param $resourceUri string
     * @param $payload string
     */
    public function put($resourceUri, $payload, CampaignerResponse $campaignerResponse)
    {
        $this->setHeader("Content-Type", "application/json");
        $options = [
            'headers' => $this->headers,
            'body' => json_encode($payload, JSON_PRETTY_PRINT),
        ];
        return $this->request('PUT', $resourceUri, $options, $campaignerResponse);
    }

    public function request($method, $resourceUri, $options, CampaignerResponse $campaignerResponse)
    {
        $url = $this->getFullURL($resourceUri);

        try {
            $response = $this->client->request($method, $url, $options);
        } catch (RequestException $e) {
            // guzzle throws a RequestException on 400 responses, which we need to be able to handle gracefully without blowing up
            if ($e->getResponse()->getStatusCode() == '400') {
                return $this->buildResponse($e->getResponse());
            }
        } catch (ServerException $e) {
            throw new CampaignerException("An error occurred while accessing the Campaigner API");
        }
        $campaignerResponse = $this->buildResponse($response, $campaignerResponse);
        return $campaignerResponse;
    }

    /**
     * @param Response $response
     */
    public function buildResponse(Response $response, CampaignerResponse $campaignerResponse)
    {
        // the Guzzle implementation has changed to PSR-7, so casting to string is now required to bypass streaming interface
        $body = (string) $response->getBody();

        return $campaignerResponse
            ->setBody(json_decode($body, true))
            ->setStatusCode($response->getStatusCode());
    }

    public function getFullUrl($resourceUri)
    {
        return $this->baseUrl . $resourceUri;
    }

    public function setApiKey($apiKey)
    {
        $this->setHeader('ApiKey', $apiKey);
    }
}
