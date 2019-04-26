<?php
require_once __DIR__ . "/../lib/client.php";

$client = new UserApi();

echo $client->payment([
    'amount' => 10,
    'order_id' => 9876,
    'description' => 'pay for TV',
    'lang' => 'ru',
    'method' => 'bpay',
    'success_url' => 'https://bpay.md/success',
    'fail_url' => 'https://bpay.md/fail',
    'callback_url' => 'https://bpay.md/callback',
    'valute' => 498,
    'getUrl' => 1,
    'params' => [
        'type_of_payer' => 'main',
        'info' => 'test information',
        'invoice' => [
            [
                'id' => 156,
                'name' => 'mobile phone',
                'model' => 'iphone 8+',
                'sum' => 800
            ],
            [
                'id' => 556,
                'name' => 'TV Philips',
                'model' => 'K223AA2200',
                'sum' => 300
            ]
        ]
    ]
]);



























































$json = [
    'merchantid' => 'test_merchant',
    'amount' => 10,
    'order_id' => 9876,
    'description' => 'user api v2',
    'lang' => 'ru',
    'method' => 'bpay',
    'success_url' => 'https://bpay.md/success',
    'fail_url' => 'https://bpay.md/fail',
    'callback_url' => 'https://bpay.md/callback',
    'valute' => 498,
    'dtime' => '20190423 16:13:10',
    'getUrl' => '1',
    'params' => [
        'type_of_payer' => 'main',
        'info' => 'test information',
        'invoice' => [
            [
                'id' => 156,
                'name' => 'mobile phone',
                'model' => 'iphone 8+',
                'sum' => 800
            ],
            [
                'id' => 556,
                'name' => 'TV Philips',
                'model' => 'K223AA2200',
                'sum' => 300
            ]
        ]
    ]
];

$data = base64_encode(json_encode($json));
$signature = 'otdaiDenighi';
$key = hash('sha256', $json . $signature);