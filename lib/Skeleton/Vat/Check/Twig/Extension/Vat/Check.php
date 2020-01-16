<?php
/**
 * Additional functions and filters for Twig
 *
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Vat\Check\Twig\Extension\Vat;

class Check extends \Twig\Extension\AbstractExtension {

	/**
     * Returns a list of functions
     *
     * @return array
     */
	public function getFunctions() {
		return [
			new \Twig\TwigFunction('example_vat', [ $this, 'get_example_vat' ], [ 'is_safe' => [ 'html' ] ])
		];
	}

	/**
	 * Function get example vat
	 *
	 * @param string $vat_code
	 * @return string $output
	 */
	public function get_example_vat($vat_code) {
		$vat_code = strtoupper($vat_code);
		if (!isset(\Skeleton\Vat\Check\Config::$example_vat[$vat_code])) {
			return 'unknown';
		}

		return \Skeleton\Vat\Check\Config::$example_vat[$vat_code];
	}

    /**
     * Name of this extension
     *
     * @return string
     */
    public function getName() {
        return 'Twig_Extension_Vat_Check';
    }
}
