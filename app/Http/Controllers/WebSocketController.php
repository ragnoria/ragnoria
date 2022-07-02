<?php

namespace App\Http\Controllers;

use App\Services\Log;
use App\Services\WsConnectionService;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use Ratchet\WebSocket\WsConnection;

class WebSocketController implements MessageComponentInterface
{
    public function onOpen(ConnectionInterface|WsConnection $conn)
    {
        Log::info('New connection.');
        if (!WsConnectionService::auth($conn)) {
            $conn->close();
        }
    }

    public function onClose(ConnectionInterface|WsConnection $conn)
    {
        Log::info('Connection closed.');
    }

    public function onError(ConnectionInterface|WsConnection $conn, \Exception $e)
    {
        Log::info('An error occurred: ' . $e->getMessage());
    }

    public function onMessage(ConnectionInterface|WsConnection $conn, $msg)
    {
        Log::info('Message received.');
    }

}
