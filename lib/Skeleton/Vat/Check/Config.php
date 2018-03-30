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

	/**
	 * Known VAT regexes
	 *
	 * @access public
	 * @var array $regexp_vat
	 */
	public static $regexp_vat = [
		'AT' => '/^U[0-9]{8}$/',
		'BE' => '/^[0-9]{10}$/',
		'BG' => '/^[0-9]{9,10}$/',
		'CH' => '/^CHE\-[0-9]{9}$/',
		'CY' => '/^[0-9a-zA-Z]{9}$/',
		'CZ' => '/^[0-9]{8,10}$/',
		'DE' => '/^[0-9]{9}$/',
		'DK' => '/^[0-9]{8}$/',
		'EE' => '/^[0-9]{9}$/',
		'EL' => '/^[0-9]{9}$/',
		'ES' => '/^[0-9a-zA-Z]{9}$/',
		'FI' => '/^[0-9]{8}$/',
		'FR' => '/^[a-zA-Z0-9]{2}[0-9]{9}$/',
		'HU' => '/^[0-9]{8}$/',
		'IE' => '/^[0-9a-zA-Z]{9}$/',
		'IT' => '/^[0-9]{11}$/',
		'LT' => '/^[0-9]{9,12}$/',
		'LU' => '/^[0-9]{8}$/',
		'LV' => '/^[0-9]{11}$/',
		'MT' => '/^[0-9]{8}$/',
		'NL' => '/^[a-zA-Z0-9]{12}$/',
		'PL' => '/^[0-9]{10}$/',
		'PT' => '/^[0-9]{9}$/',
		'RO' => '/^[0-9]{2,10}$/',
		'SE' => '/^[0-9]{12}$/',
		'SI' => '/^[0-9]{8}$/',
		'SK' => '/^[0-9]{10}$/',
	];

}
