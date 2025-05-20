# skeleton-vat-check

## Description

Check Vat number with separate resolvers (these are the default steps):
1. Is european (always performed not configurable)
2. Syntax via a predefined regex array (list available in config)
3. Caching table (to check if there were recent results for KBO and VIES + avoid taxing the API when not needed)
4. VIES service check

## Installation

Installation via composer:

    composer require tigron/skeleton-vat-check

## Howto setup

Run the initial migration or executed the following queries

	CREATE TABLE `vat_check_cache` (
  		`id` int(11) NOT NULL AUTO_INCREMENT,
  		`vat_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  		`country_id` int(11) NOT NULL,
  		`valid` int(11) NOT NULL DEFAULT '0',
  		`created` datetime NOT NULL,
  		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

	$transaction = new \Transaction_Cleanup_Vat_Check_Cache();
	$transaction->classname = 'Cleanup_Vat_Check_Cache';
	$transaction->data = null;
	$transaction->retry_attempt = 0;
	$transaction->recurring = 1;
	$transaction->completed = 0;
	$transaction->failed = 0;
	$transaction->locked = 0;
	$transaction->parallel = 0;
	$transaction->scheduled_at = date('Y-m-d H:i:s');
	$transaction->created = date('Y-m-d H:i:s');
	$transaction->save();

## How to use

	/**
	 * the VAT number to check
	 */
	$vat_number = '0886776275';

	/**
	 * The Country object.
	 * Your object should implement the \Skeleton\Vat\Check\Country Interface
	 */
	$country = $your_country_object;

	/**
	 * The resolver used to give you the result, passed by reference and the
	 * resolver_used can be retrieved after calling the validate method
	 */
	$resolver_used = '';

	/**
	 * Perform the call
	 */
    \Skeleton\Vat\Check\Check::validate($vat_number, $country, $resolver_used)

## Optional config

	By default the validator will do these steps:
	1. Is european (always performed not configurable)
	2. Syntax via a predefined regex array (list available in config)
	3. Caching table (to check if there were recent results for KBO and VIES + avoid taxing the API when not needed)
	4. VIES service checkCheck Syntax, check recent cache (for API results) and Check against Vies.

	It is now possible to change the order of these steps and it is possible to do an aditional step against the KBO (requires authentication).

	/**
	* Example config
	*/
	\Skeleton\Vat\Check\Config::set_resolvers([
		new \Skeleton\Vat\Check\Resolver\Syntax(),
		new \Skeleton\Vat\Check\Resolver\Cache(),
		new \Skeleton\Vat\Check\Resolver\Kbo(), // Not used by default
		new \Skeleton\Vat\Check\Resolver\Vies()
	]);

	/**
	* Example auhtentication
	*/
	\Skeleton\Vat\Check\Config::$kbo_authentication = [
		'user' => 'test@tigron.be',
		key' => 'test_key'
	];


