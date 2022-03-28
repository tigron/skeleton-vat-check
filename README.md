# skeleton-vat-check

## Description

Check Vat number in 4 steps:
1. syntax via a predefined regex array (list available in config)
2. is european
3. caching table (prevent denial of VIES service)
4. VIES service

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

	INSERT INTO `transaction` (`classname`, `created`, `scheduled_at`, `data`, `retry_attempt`, `recurring`, `completed`, `failed`, `locked`, `frozen`, `parallel`) 
	VALUES ('Cleanup_Vat_Check_Cache', now(), now(), '', '', '1', '0', '0', '0', '0', '0');
	
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
	 * A string for the fail reason. This will be passed by reference and the 
	 * error can be retrieved after calling the validate method
	 */
	$reason = '';
	
	/** 
	 * A flag to ignore a cache lookup
	 */
	$ignore_cache = false;
	
	/**
	 * Perform the call
	 */
    \Skeleton\Vat\Check\Check::validate($vat_number, $country, $reason, $ignore_cache) 

## Optional config
	
	By default the validator will do these steps: Check Syntax, check recent cache (for API results) and Check against Vies. 
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
	\Skeleton\Vat\Check\Config::$kbo_authentication = ['user' => 'test@tigron.be',
														key' => 'test_key'
													];


