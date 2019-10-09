<?php
/**
 * Country interface
 *
 * @author Christophe Gosiau <christophe.gosiau@tigron.be>
 */
namespace Skeleton\Vat\Check;

use \Skeleton\Database\Database;

interface Country {

	/**
	 * Get the ISO2 code of the country
	 *
	 * @access public
	 * @return string $iso2
	 */
	public function get_iso2();
}

