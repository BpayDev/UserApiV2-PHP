<?php

require_once __DIR__ . "/../lib/client.php";

$client = new UserApi();

echo $client->transfer([
    'payer_account' => '11656558',
    'recipient_account' => '11600653',
    'amount' => 10,
    'description' => 'P2P transfer',
    'txnid' => date('YmdHis')
]);