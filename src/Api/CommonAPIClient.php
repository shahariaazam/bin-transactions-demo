<?php

namespace ShahariaAzam\BinList\Api;

use Nyholm\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use ShahariaAzam\BinList\Exception\UtilityException;
use Exception;

/**
 * Class CommonAPIClient
 */
class CommonAPIClient
{
    /**
     * PSR-18 compatible HTTP Client
     *
     * @var ClientInterface
     */
    private $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Send GET Request to API endpoint
     *
     * @param string $path
     * @return array
     * @throws UtilityException
     */
    public function sendRequest($path)
    {
        $request = new Request('GET', $path);

        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (Exception $exception) {
            throw new UtilityException($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        }

        if ($response->getStatusCode() !== 200) {
            throw new UtilityException('Failed to retrieve data');
        }

        if (false === strpos($response->getHeaderLine('Content-Type'), 'application/json')) {
            throw new UtilityException('Failed to parse response');
        }

        $data = json_decode( (string) $response->getBody(), true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new UtilityException('Failed to parse response json');
        }

        return $data;
    }
}
