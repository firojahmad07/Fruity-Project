<?php

namespace Spygar\FruityBundle\Services;

use Spygar\FruityBundle\Helper\EndPointsHelper;

class OauthService
{
	/** @var string */
	private $host;

	/** 
	 * @param string $host
	 */
	public function __construct(string $host)
	{
		$this->host = $host;
	}

	public function request()
	{
		$curl = curl_init();
		$apiEndPoint = $this->host . EndPointsHelper::FRUITS_FETCH_ALL;
		curl_setopt($curl, CURLOPT_URL, $apiEndPoint);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$response = curl_exec($curl);
		$status   = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $responseData['data']   = json_decode($response, true);
        $responseData['status'] = $status;
        $responseData['message'] = curl_error($curl);
		curl_close($curl);

		return $responseData;
	}
}