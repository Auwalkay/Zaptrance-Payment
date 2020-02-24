<?php


/**
 * 
 */

namespace Zaptrance\Payment;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Zaptrance\Payment\Exceptions\IsNullException;
use Zaptrance\Payment\Exceptions\PaymentVerificationFailedException;
use Zaptrance\Payment\Generate;

class Zaptrance
{
	protected $apikey;

	protected $merchantID;

	protected $requestUrl;

	protected $hashed;

	protected $client;

	protected $url;

	protected $response;

	function __construct()
	{
		$this->setApiKey();
		$this->setRequestUrl();
		$this->setMerchantId();
		$this->initializePayment();
	}	

	public function setApiKey()
	{
		$this->apikey= config('zaptrance.apiKey');
	}

	public function setMerchantId()
	{
		$this->merchantID= config('zaptrance.merchantId');
	}

	public function setRequestUrl()
	{
		$this->requestUrl= config('zaptrance.requestUrl');
	}
	public function initializePayment()
	{
		$authBearer = ' Bearer '. $this->apikey;

		$this->client = new Client(
			[

				'headers' => [
					'Authorization' => $authBearer,
					'Content-Type'  => 'application/json',
				]
			]
		);	
	}
	public function makePayment($Serviceid)
	{
		


		$this->hashed= Generate::my_hash($this->merchantID,$this->apikey,$Serviceid,'1000','2');
		$data=[
			'merchant_id'=>"8347413",
			'amt'=> "1000",
			'tid' =>"2",
			'services_token'=>'kvPhvaZ3bfa9huiyZETZ4o0rd8EkldOnCR1HWK60',
			'email' =>'email@h.com',
			'hashed'=>$this->hashed
		];
		$this->response=$this->client->post(
			$this->requestUrl,
			["body" => json_encode($data)]
		);

		$this->url=$this->getResponse()['data']['url'];

		return $this;
		
	}

	public function getResponse()
	{
		return json_decode($this->response->getBody(),true);
	}
	public function redirectNow()
	{
		 $kh=curl_init();
		 curl_setopt($kh, CURLOPT_URL, $this->url);
		 curl_setopt($kh, CURLOPT_POST, true);


		 curl_setopt($kh, CURLOPT_POSTFIELDS, $this->getResponse()['data']['token']);
		 curl_setopt($kh, CURLOPT_RETURNTRANSFER, true);

		 $rt=curl_exec($kh);
		 curl_close($kh);
		 return $rt;
		
	}

	private function setHttpResponse($data)
	{
		
	}

}