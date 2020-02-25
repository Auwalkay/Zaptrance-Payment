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
		


		$this->hashed= Generate::my_hash($this->merchantID,$this->apikey,$Serviceid,request()->amount,request()->transaction_id);
		$data=[
			'merchant_id'=>$this->merchantID,
			'amt'=> request()->amount,
			'tid' =>request()->transaction_id,
			'services_token'=>request()->service_token,
			'identifier' =>request()->email,
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