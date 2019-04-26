<?php 

/**
 * Class UserApi
 */
class UserApi{

  private $merchantid;
  private $signature;
  private $login;
  private $password;
  private $apiURL;
  private $key;

  function __construct(){
      $json = json_decode(file_get_contents(__DIR__ .'/config/config.json'),true);
      if($json){
          $this->login = $json['login'];
          $this->password = $json['password'];
          $this->apiURL = $json['apiURL'];
          $this->key = $json['key'];
          $this->merchantid = $json['merchantid'];
          $this->signature = $json['signature'];
      }else{
          echo 'invalid JSON from config.json';
      }

  }

  public function payment(array $rdata){
      $json = [
          'merchantid' => $this->merchantid,
          'amount' => $rdata['amount'],
          'order_id' => $rdata['order_id'],
          'description' => $rdata['description'],
          'lang' => $rdata['lang'],
          'method' => $rdata['method'],
          'success_url' => $rdata['success_url'],
          'fail_url' => $rdata['fail_url'],
          'callback_url' => $rdata['callback_url'],
          'valute' => $rdata['value'],
          'dtime' => date('Ymd H:i:s'),
          'getUrl' => $rdata['getURL'],
          'params' => (is_array($rdata['params'])) ? $rdata['params'] : []
      ];

      $data = base64_encode(json_encode($json));
      $key = hash('sha256', $json . $this->signature);
      return '<form method="POST" action="'.$this->apiURL.'payment">
	<input type="hidden" name="data" value="'.$data.'">
	<input type="hidden" name="key" value="'.$key.'">
	<input type="submit" name="button" value="Pay">
</form>';
  }

    /**
    * @param null $trid
    * @param null $receipt
    * @return bool|string
    */
  public function checkState($trid=null,$receipt=null){echo 'test';
    $json = [
      'auth' => [
        'login' => $this->login,
        'password' => $this->password,
      ],
      'receipt' => $trid,
      'transid' => $receipt
    ];

    $url = $this->apiURL.'checkstate';
    return $this->request($url, $json);
  }


  /**
    * @param array $rdata
    * @return bool|string
    */
  public function transfer(array $rdata){


    $time = date('Ymd His');

    $json = [
      'auth' => [
        'login' => $this->login,
        'password' => $this->password,
      ],
      'transfer' => [
        'payer_account' => $rdata['payer_account'],
        'recipient_account' => $rdata['recipient_account'],
        'amount' => $rdata['amount'],
        'description' => $rdata['description'],
        'txnid' => $rdata['txnid'],
        'params' => [
          'param1' => 'value',
          'param2' => 'value'
        ]
      ],
      'dtime' => date('Ymd H:i:s')
    ];
    $json['sign'] = $this->makeSignature($json);
    $url = $this->apiURL.'transfer';
    return $this->request($url, $json);
  }


  /**
    * @param array $rdata
    * @return bool|string
    */
  public function getAccountAlias(array $rdata){
    $json = [
      'auth' => [
          'login' => $this->login,
          'password' => $this->password,
        ],
      'serviceid' => $rdata['serviceid']
    ];
    $url = $this->apiURL.'getAccountAlias';
    return $this->request($url,$json);
  }


  /**
    * @param array $rdata
    * @return bool|string
    */
  public function getPaymentsHistory(array $rdata){
    $json = [
      'auth' => [
          'login' => $this->login,
          'password' => $this->password,
        ],
      'account' => $rdata['account'],
      'date_start' => $rdata['date_start'],
      'date_end' => $rdata['date_end']
    ];
    $json = array_merge($json,$rdata['aditional']);
    $url = $this->apiURL.'getPaymentsHistory';
    return $this->request($url,$json);
  }


  /**
    * @param array $rdata
    * @return bool|string
    */
  public function calculate(array $rdata){
    $json = [
      'auth' => [
          'login' => $this->login,
          'password' => $this->password,
        ]
    ];
    $json = array_merge($json,$rdata);
    $url = $this->apiURL.'Calculate';
    return $this->request($url,$json);
  }


  /**
    * @param array $rdata
    * @return bool|string
    */
  public function checkOrderState(array $rdata){
    $json = [
       'auth' => [
          'login' => $this->login,
          'password' => $this->password,
        ],
        'orderid' => $rdata['orderid'],
        'amount' => $rdata['amount']
    ]; 
    $url = $this->apiURL.'checkOrderState';
    return $this->request($url, $json);
  }


  /**
    * @param array $rdata
    * @return string
    */
  private function makeSignature(array $rdata){
    $str_to_sign = '';
    foreach ($rdata['transfer'] as $val) {
       if(is_array($val)){
          foreach ($val as $val2) {
            $str_to_sign .= $val2;
          }
        }else{
          $str_to_sign .= $val;
        }           
    }
    $sign = hash('sha256',$str_to_sign.$this->key);
    return $sign;
  }


  /**
    * @param string $url
    * @param array $json
    * @return bool|string
    */
  private function request($url, array $json){

    $jsonrequest = json_encode($json);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, "User-Agent=Mozilla/5.0 Firefox/1.0.7");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonrequest);
    $_PROVIDER_ANSWER = curl_exec($curl);
    return $_PROVIDER_ANSWER;
  }

}