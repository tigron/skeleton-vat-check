<?php
/**
 * Validation_Cache class
 *
 * @author Roan Buysse <roan@tigron.be>
 */

namespace Skeleton\Vat\Check\Resolver;
use \Skeleton\Database\Database;
use Skeleton\Vat\Check\Answer;

class Cache{
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
	 * Reolve against the API cache
	 *
	 * @param string $vat_number
	 * @param \Country $country
	 * @return void
	*/
    public static function resolve($vat_number, $country) {
		$vat_cache = self::get_by_vat_number_country($vat_number, $country);
		if ($vat_cache->valid == 1) {
			return new Answer\Definite\Positive();
		} else {
			return new Answer\Definite\Negative();
		}
    }

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
			throw new Answer\Exception('No cache available for VAT number ' . $vat_number);
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
		$since = strtotime(\Skeleton\Vat\Check\Config::$caching_window);
		$ids = $db->get_column('SELECT id FROM vat_check_cache WHERE created < ?', [ date('Y-m-d H:i:s', $since) ]);

		$objects = [];
		foreach ($ids as $id) {
			$objects[] = self::get_by_id($id);
		}

		return $objects;
	}

	/**
	 * Store a result in the cache
	 *
	 * @param string $vat_number
	 * @param \Country $country
	 * @param Bool $result
	 * @return void
	 */
	public static function store($vat_number, \Country $country, Bool $result) {
		try {
			$vat_cache = self::get_by_vat_number_country($vat_number, $country);
			$vat_cache->delete();
		} catch (\Exception $e) {}
		$vat_cache = new Cache();
		$vat_cache->vat_number = $vat_number;
		$vat_cache->country_id = $country->id;
		$vat_cache->valid = $result;
		$vat_cache->save();
	}
}