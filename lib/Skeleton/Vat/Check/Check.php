<?php
/**
 * Check class
 *
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Vat\Check;

class Check {

	/**
	 * Validate a VAT number against the VIES or cached data
	 *
	 * For VIES, please see http://ec.europa.eu/taxation_customs/vies/
	 *
	 * @access public
	 * @param string $vat_number
	 * @param Country $country
	 * @param boolean $ignore_cache
	 * @return boolean $valid
	 */
	public static function validate($vat_number, Country $country, &$reason = '', $ignore_cache = false) {
		// 1. Check syntax
		if (!self::validate_syntax($vat_number, $country)) {
			$reason = 'invalid_syntax';
			return false;
		}

		$vat_config = Config::$vat_config;

		// 2. Check if the vat is a european vat-number
		if (!isset($vat_config[ $country->get_iso2() ])) {
			$reason = 'not_european';
			return true;
		}

		//3. Check if vat-number is in cache
		if ($ignore_cache === false) {
			try {
				$vat_cache = Cache::get_by_vat_number_country($vat_number, $country);
				if ($vat_cache->valid == 1) {
					$reason = 'valid_from_cache';
					return true;
				} else {
					$reason = 'invalid_from_cache';
					return false;
				}
			} catch (\Exception $e) {}
		}

		//4. Check online (VIES-server)
		$result = self::validate_online($vat_number, $country);
		if ($result['save'] === true) {
			try {
				$vat_cache = Cache::get_by_vat_number_country($vat_number, $country);
				$vat_cache->delete();
			} catch (\Exception $e) {}

			$vat_cache = new Cache();
			$vat_cache->vat_number = $vat_number;
			$vat_cache->country_id = $country->id;
			$vat_cache->valid = $result['result'];
			$vat_cache->save();
		} elseif ($result['reachable'] === false) {
			$reason = 'vies_service_not_reachable';
		} else {
			$reason = 'invalid_number';
		}

		return $result['result'];
	}

	/**
	 * Check the syntax of a VAT number
	 *
	 * @access public
	 * @param string $vat_number
	 * @param Country $country
	 * @return boolean $valid
	 */
	public static function validate_syntax($vat_number, Country $country) {
		$vat_config = Config::$vat_config;
		if (isset($vat_config[ $country->get_iso2() ])) {
			$regexp = $vat_config[ $country->get_iso2() ]['regexp'];

			if (!preg_match($regexp, $vat_number)) {
				return false;
			} else {
				return true;
			}
		}

		if (!preg_match('/^[a-zA-Z0-9.]{2,20}$/', $vat_number)) {
			return false;
		}

		if (strlen($vat_number) < 6 OR strlen($vat_number) > 20) {
			return false;
		}

		return true;
	}

	/**
	 * Try to check the VAT number online
	 *
	 * @access public
	 * @param string $vat_number
	 * @param Country $country
	 * @return array $info
	 */
	public static function validate_online($vat_number, \Country $country) {
		$continue = true;
		$retry = 3;

		while ($continue == true) {
			try {
				$result = self::validate_online_call($vat_number, $country);
				$continue = false;
				$save = true;
				$reachable = true;
			} catch (\Exception $e) {
				$retry--;

				if ($retry === 0) {
					$continue = false;
					$result = false;
					$save = false;
					$reachable = false;
				}
			}
		}

		return [ 'result' => $result, 'save' => $save, 'reachable' => $reachable ];
	}

	/**
	 * Try to check the VAT number online against the VIES database
	 * This method should ALWAYS be used in a try {} catch {}, because
	 * an Exception can be thrown at any time when the webservice is
	 * not available.
	 *
	 * @access public
	 * @param string $vat_number
	 * @param Country $country
	 * @throws \Exception $e
	 * @return boolean $valid
	 */
	public static function validate_online_call($vat_number, Country $country) {
		$vat_config = Config::$vat_config;
		if (!isset($vat_config[ $country->get_iso2() ])) {
			throw new \Exception('Cannot perform online call for non-EU country');
		}

	echo 'online call' . "\n";
		// The @ is to suppress the warnings triggered by the SOAP client when the URL is not reachable
		// An exception is also thrown, which is catched higher in the stack
		$client = @new \SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl", [
			'cache_wsdl' => WSDL_CACHE_DISK
		]);
		$params = [ 'countryCode' => $vat_config[ $country->get_iso2() ]['country_code'], 'vatNumber' => $vat_number ];
		$result = $client->checkVat($params);
		var_dump($result);
		echo 'aze';

		if ($result->valid == 1) {
			return true;
		} else {
			return false;
		}
	}

}
