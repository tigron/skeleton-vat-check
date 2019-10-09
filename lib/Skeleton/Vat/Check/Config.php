<?php
/**
 * Config class
 * Configuration for Skeleton\Vat\Check
 *
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Vat\Check;

class Config {

	/**
	 * Caching window (strtotime compatible)
	 *
	 * @access public
	 * @var int $caching_window
	 */
	public static $caching_window = '-1 day';
	
	public static $vat_config = [
		'AT' => [
			'regexp' => '/^U[0-9]{8}$/',
			'example' => 'U12345678',
			'country_code' => 'AT'
		],
		'BE' => [
			'regexp' => '/^[0-9]{10}$/',
			'example' => '0123456789',
			'country_code' => 'BE'
		],			
		'BG' => [
			'regexp' => '/^[0-9]{9,10}$/',
			'example' => '123456789',
			'country_code' => 'BG'
		],
		'CH'  => [
			'regexp' => '/^CHE\-[0-9]{9}$/',
			'example' => 'U12345678',
			'country_code' => 'CH'
		],
		'CY'  => [
			'regexp' => '/^[0-9a-zA-Z]{9}$/',
			'example' => '12345678L',
			'country_code' => 'CY'
		],
		'CZ'  => [
			'regexp' => '/^[0-9]{8,10}$/',
			'example' => '123456789',
			'country_code' => 'CZ'
		],
		'DE'  => [
			'regexp' => '/^[0-9]{9}$/',
			'example' => '123456789',
			'country_code' => 'DE'
		],
		'DK'  => [
			'regexp' => '/^[0-9]{8}$/',
			'example' => '12345678',
			'country_code' => 'DK'
		],
		'EE'  => [
			'regexp' => '/^[0-9]{9}$/',
			'example' => '123456789',
			'country_code' => 'EE'
		],
		'GR'  => [
			'regexp' => '/^[0-9]{9}$/',
			'example' => '123456789',
			'country_code' => 'EL'
		],
		'ES'  => [
			'regexp' => '/^[0-9a-zA-Z]{9}$/',
			'example' => 'X9999999X',
			'country_code' => 'ES'
		],
		'FI'  => [
			'regexp' => '/^[0-9]{8}$/',
			'example' => '12345678',
			'country_code' => 'FI'
		],
		'FR'  => [
			'regexp' => '/^[a-zA-Z0-9]{2}[0-9]{9}$/',
			'example' => '12123456789',
			'country_code' => 'FR'
		],
		'HU'  => [
			'regexp' => '/^[0-9]{8}$/',
			'example' => '12345678',
			'country_code' => 'HU'
		],
		'IE'  => [
			'regexp' => '/^[0-9a-zA-Z]{9}$/',
			'example' => '1S23456L',
			'country_code' => 'IE'
		],
		'IT'  => [
			'regexp' => '/^[0-9]{11}$/',
			'example' => '12345678901',
			'country_code' => 'IT'
		],
		'LT'  => [
			'regexp' => '/^[0-9]{9,12}$/',
			'example' => '123456789',
			'country_code' => 'LT'
		],
		'LU'  => [
			'regexp' => '/^[0-9]{8}$/',
			'example' => '12345678',
			'country_code' => 'LU'
		],
		'LV'  => [
			'regexp' => '/^[0-9]{11}$/',
			'example' => '12345678901',
			'country_code' => 'LV'
		],
		'MT'  => [
			'regexp' => '/^[0-9]{8}$/',
			'example' => '12345678',
			'country_code' => 'MT'
		],
		'NL'  => [
			'regexp' => '/^[a-zA-Z0-9]{12}$/',
			'example' => '123412123B12',
			'country_code' => 'NL'
		],
		'PL'  => [
			'regexp' => '/^[0-9]{10}$/',
			'example' => '1234567890',
			'country_code' => 'PL'
		],
		'PT'  => [
			'regexp' => '/^[0-9]{9}$/',
			'example' => '123456789',
			'country_code' => 'PT'
		],
		'RO'  => [
			'regexp' => '/^[0-9]{2,10}$/',
			'example' => '1234567890',
			'country_code' => 'RO'
		],
		'SE'  => [
			'regexp' => '/^[0-9]{12}$/',
			'example' => '123456789012',
			'country_code' => 'SE'
		],
		'SI'  => [
			'regexp' => '/^[0-9]{8}$/',
			'example' => '12345678',
			'country_code' => 'SI'
		],
		'SK'  => [
			'regexp' => '/^[0-9]{10}$/',
			'example' => '1234567890',
			'country_code' => 'SK',
		],
		
	];
}
