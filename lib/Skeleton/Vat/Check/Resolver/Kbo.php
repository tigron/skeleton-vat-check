<?php
/**
 * Validation_Kbo class
 * For KBO, please see https://kruispuntdatabank.be/documentatie/
 * 
 * @author Roan Buysse <roan@tigron.be>
 */

namespace Skeleton\Vat\Check\Resolver;

use Exception;
use Skeleton\Vat\Check\Answer;
use GuzzleHttp\Client;

class Kbo {
    /**
	 * Check the syntax of a VAT number against KBO
	 *
	 * @access public
	 * @param string $vat_number
	 * @param \Country $country
	 * @return boolean $valid
	 */
	public static function resolve($vat_number, \Country $country) {
		$kbo_authentication = \Skeleton\Vat\Check\Config::$kbo_authentication;
		$vat_config = \Skeleton\Vat\Check\Config::$vat_config;
		
		if ($vat_config[ $country->get_iso2() ]['country_code'] != 'BE') {
			return new Answer\Indefinite\Negative;
		}

		if($kbo_authentication['user'] == "" ||  $kbo_authentication['key'] == ""){
			throw new \Exception('Kbo authentication not set correctly');
		}
	

        $continue = true;
		$retry = 3;
		while ($continue == true) {
			try {
				$result = self::validate_call($vat_number, $kbo_authentication);
				if ($result) {
					Cache::store($vat_number, $country, $result);
					return new Answer\Definite\Positive;
				} else {
					Cache::store($vat_number, $country, $result);
					return new Answer\Definite\Negative;
				}
			} catch (\Exception $e) {
				$retry--;
				if ($retry === 0) {
					throw new Answer\Exception($e);
				}
			}
		}
	}

    
    /**
	 * Try to check the enterprise number online against the KBO database.
	 * This call can only be used for BE numbers.
	 *
	 * @access public
	 * @param string $vat_number
	 * @param array $kbo_authentication
	 * @throws \Exception $e
	 * @return boolean $valid
	 */
	public static function validate_call($vat_number, $kbo_authentication) {
		$client = new Client();
		try{
			// We only need to check if it exists
			$client->get("https://api.kbodata.app/v2/enterprise/" . trim($vat_number), [
				'auth' => [$kbo_authentication['user'], $kbo_authentication['key']]
			]);
		} catch (\GuzzleHttp\Exception\ClientException $e) {
			// 404 Not found is the expected response 
			if($e->getResponse()->getStatusCode() == '404') {
				return false;
			} else {
				throw $e;
			}
		}
		return true;
	}
}
