<?php

namespace App\Http\Controllers;

use App\Services\Log;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;

class WebSocketController extends Controller implements MessageComponentInterface
{

    public function onOpen(ConnectionInterface $conn)
    {
        Log::info('New connection.');
        $cookies = $conn->httpRequest->getHeader('Cookie');
        Log::info(json_encode($cookies));
    }

    public function onClose(ConnectionInterface $conn)
    {
        Log::info('Connection closed.');
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        Log::info('An error occurred: ' . $e->getMessage());
    }

    public function onMessage(ConnectionInterface $conn, $msg)
    {
        Log::info('Message received.');
    }

}
