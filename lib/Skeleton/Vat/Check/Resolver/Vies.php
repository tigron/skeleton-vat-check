<?php
/**
 * Validation_Vies class
 * For VIES, please see http://ec.europa.eu/taxation_customs/vies/
 * 
 * @author David Vandemaele <david@tigron.be>
 * @author Roan Buysse <roan@tigron.be>
 */

namespace Skeleton\Vat\Check\Resolver;
use Skeleton\Vat\Check\Answer;

class Vies {
    /**
	 * Resolve the VAT number against Vies
	 *
	 * @access public
	 * @param string $vat_number
	 * @param \Country $country
	 * @return boolean $valid
	 */
	public static function resolve($vat_number, \Country $country) {
        $continue = true;
		$retry = 3;

		while ($continue == true) {
			try {
				$result = self::validate_call($vat_number, $country);
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
					throw new Answer\Exception();
				}
			}
		}
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
	public static function validate_call($vat_number, \Country $country) {
		$vat_config = \Skeleton\Vat\Check\Config::$vat_config;
		if (!isset($vat_config[ $country->get_iso2() ])) {
			throw new \Exception('Cannot perform online call for non-EU country');
		}

		// The @ is to suppress the warnings triggered by the SOAP client when the URL is not reachable
		// An exception is also thrown, which is catched higher in the stack
		$client = @new \SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl", [
			'cache_wsdl' => WSDL_CACHE_DISK
		]);
		$params = [ 'countryCode' => $vat_config[ $country->get_iso2() ]['country_code'], 'vatNumber' => $vat_number ];
		$result = $client->checkVat($params);

		if ($result->valid == 1) {
			return true;
		} 
		return false;
	}
}
