<?php
require '../vendor/autoload.php';

$options = array(
    'cluster' => 'us2',
    'useTLS' => true
);

$pusher = new Pusher\Pusher(
    '6dbee54e89268399db8d',
    '6d963ea463150847b86e',
    '1860759',
    $options
);

$data = json_decode(file_get_contents('php://input'), true);
$elementId = $data['elementId'];
$value = $data['value'];

$pusher->trigger('chat-channel', 'update-data', array('elementId' => $elementId, 'value' => $value));