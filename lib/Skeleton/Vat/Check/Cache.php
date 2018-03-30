<?php
/**
 * Cache class
 *
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Vat\Check;

use \Skeleton\Database\Database;

class Cache {
	use \Skeleton\Object\Model;
	use \Skeleton\Object\Get;
	use \Skeleton\Object\Delete;
	use \Skeleton\Object\Save;

	/**
	 * Class configuration
	 *
	 * @var array $class_configuration
	 */
	private static $class_configuration = [
		'database_table' => 'vat_check_cache',
	];

	/**
	 * Returns a Vat_Check_Cache by supplying a VAT number
	 *
	 * @access public
	 * @param string $vat_number
	 * @return Cache $object
	 */
	public static function get_by_vat_number_country($vat_number, \Country $country) {
		$db = Database::get();
		$id = $db->get_one('SELECT id FROM vat_check_cache WHERE vat_number = ? AND country_id = ?', [ $vat_number, $country->id] );

		if ($id === null) {
			throw new \Exception('No cache available for VAT number ' . $vat_number);
		}

		return self::get_by_id($id);
	}

	/**
	 * Get all Vat_Checks_Cache that are expired
	 *
	 * @access public
	 * @return array  $objects
	 */
	public static function get_overdue() {
		$db = Database::get();
		$since = strtotime(Config::$caching_window);
		$ids = $db->get_column('SELECT id FROM vat_check_cache WHERE created < ?', [ date('Y-m-d H:i:s', $since) ]);

		$objects = [];
		foreach ($ids as $id) {
			$objects[] = self::get_by_id($id);
		}

		return $objects;
	}
}
