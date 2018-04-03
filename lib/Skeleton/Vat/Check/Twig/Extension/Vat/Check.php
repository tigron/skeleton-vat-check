<?php
/**
 * Additional functions and filters for Twig
 *
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Vat\Check\Twig\Extension\Vat;

class Check extends \Twig_Extension {

	/**
     * Returns a list of functions
     *
     * @return array
     */
	public function getFunctions() {
		return array(
			new \Twig_SimpleFunction('example_vat', array($this, 'get_example_vat'), array('is_safe' => array('html')))
		);
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
