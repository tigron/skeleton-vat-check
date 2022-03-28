<?php
/**
 * Check class
 *
 * @author David Vandemaele <david@tigron.be>
 * @author Roan Buysse <roan@tigron.be>
 */

namespace Skeleton\Vat\Check;

use Skeleton\Core\Skeleton;

class Check {
	/**
	 * Validate a VAT number
	 *
	 *
	 * @access public
	 * @param string $vat_number
	 * @param \Country $country
	 * @return boolean $valid
	 */
	public static function validate($vat_number, \Country $country, &$reason = '') {
		// Default check
		$vat_config = \Skeleton\Vat\Check\Config::$vat_config;
		if (!isset($vat_config[ $country->get_iso2() ])) {
			$reason = 'not_european';
			return true;
		}

		// The order of the other validations is defined in the config array. 
		if (empty(Config::get_resolvers())) {
			$resolvers = Config::set_resolvers([
				new Resolver\Syntax(),
				new Resolver\Cache(),
				new Resolver\Vies()
			]);
		}
		
		$resolvers = Config::get_resolvers();

		// Loop over the configured resolvers
		foreach ($resolvers as $resolver)  {
			try {
				$result = $resolver->resolve($vat_number, $country);
			} catch (Answer\Exception $e) {
				continue;
			}

			switch (get_class($result)) {
				case 'Skeleton\Vat\Check\Answer\Definite\Negative':
					return false;
				break;
				case 'Skeleton\Vat\Check\Answer\Definite\Positive':
					return true;
				break;
				case 'Skeleton\Vat\Check\Answer\Indefinite\Negative':
					$result = false;
				break;
				case 'Skeleton\Vat\Check\Answer\Indefinite\Positive':
					$result = true;
				break;
			}
		}

		// If we have no definitive answer return the indefinite one.
		if(!empty($result)) {
			return $result;
		} else {
			return false;
		}
	}
}
