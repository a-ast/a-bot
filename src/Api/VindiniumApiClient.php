<?php

namespace App\Api;

use App\Exceptions\GameException;
use Http\Client\Common\Exception\ClientErrorException;
use Http\Client\Common\HttpMethodsClient;
use Http\Client\Exception;
use Http\Client\Exception\HttpException;
use Http\Client\HttpClient;

class VindiniumApiClient
{
    /**
     * @var \Http\Client\Common\HttpMethodsClient
     */
    private $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @throws GameException
     */
    public function createTraining(string $key, int $turnCount = null, $mapName = null): array
    {
        $parameters = ['key' => $key];

        if (isset($turnCount)) {
            $parameters['turns'] = $turnCount;
        }

        if (isset($mapName)) {
            $parameters['map'] = $mapName;
        }

        return $this->send('/api/training', $parameters);
    }

    /**
     * @throws GameException
     */
    public function createArena(string $key): array
    {
        $parameters = ['key' => $key];

        return $this->send('/api/arena', $parameters);
    }

    /**
     * @throws GameException
     */
    public function playMove(string $key, string $url, string $direction): array
    {
        $parameters = ['key' => $key, 'dir' => $direction];

        return $this->send($url, $parameters);
    }

    /**
     * @throws GameException
     */
    private function send(string $url, array $parameters): array
    {
        try {
            $response = $this->client->post($url, [], http_build_query($parameters));
        } catch (Exception $exception) {
            $this->processException($exception);
        }

        $contents = $response->getBody()->getContents();

        return json_decode($contents, true);
    }

    /**
     * @throws \App\Exceptions\GameException
     */
    private function processException(Exception $exception): void
    {
        $errorMessage = 'Game error';

        if ($exception instanceof \Exception) {
            $errorMessage = $exception->getMessage();
        }

        if ($exception instanceof HttpException) {
            $response = $exception->getResponse();
            $errorMessage = $response->getBody()->getContents();
        }

        throw new GameException($errorMessage);
    }
}