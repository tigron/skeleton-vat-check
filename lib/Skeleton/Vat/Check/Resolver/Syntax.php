<?php
/**
 * Validation_Syntax class
 *
 * @author Roan Buysse <roan@tigron.be>
 */

namespace Skeleton\Vat\Check\Resolver;
use Skeleton\Vat\Check\Answer;

class Syntax {
    /**
	 * Check the syntax of a VAT number
	 *
	 * @access public
	 * @param string $vat_number
	 * @param Country $country
	 * @return boolean $valid
	 */
	public function resolve($vat_number, \Country $country) {
		$vat_config = \Skeleton\Vat\Check\Config::$vat_config;
		if (isset($vat_config[ $country->get_iso2() ])) {
			$regexp = $vat_config[ $country->get_iso2() ]['regexp'];

			if (!preg_match($regexp, $vat_number)) {
				return new Answer\Definite\Negative();
			} 
		}

		if (!preg_match('/^[a-zA-Z0-9.]{2,20}$/', $vat_number)) {
			return new Answer\Definite\Negative;
		}

		if (strlen($vat_number) < 6 OR strlen($vat_number) > 20) {
			return new Answer\Definite\Negative;
		}

		return new Answer\Indefinite\Positive;
	}
}