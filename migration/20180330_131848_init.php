<?php
/**
 * Database migration class
 *
 * @author Gerry Demaret <gerry@tigron.be>
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author David Vandemaele <david@tigron.be>
 * @author Lionel Laffineur <lionel@tigron.be>
 */
namespace Skeleton\Vat\Check;


use \Skeleton\Database\Database;

class Migration_20180330_131848_Init extends \Skeleton\Database\Migration {

	/**
	 * Migrate up
	 *
	 * @access public
	 */
	public function up() {
		$db = Database::get();
		$db->query("
			CREATE TABLE `vat_check_cache` (
  				`id` int(11) NOT NULL AUTO_INCREMENT,
  				`vat_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  				`country_id` int(11) NOT NULL,
  				`valid` int(11) NOT NULL DEFAULT '0',
  				`created` datetime NOT NULL,
  				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		", []);

		$original_auto_discard_value = \Skeleton\Database\Config::$auto_discard;
		\Skeleton\Database\Config::$auto_discard = true;

		$transaction = new \Transaction_Cleanup_Vat_Check_Cache();
		$transaction->classname = 'Cleanup_Vat_Check_Cache';
		$transaction->data = null;
		$transaction->retry_attempt = 0;
		$transaction->recurring = 1;
		$transaction->completed = 0;
		$transaction->failed = 0;
		$transaction->locked = 0;
		$transaction->frozen = 0;
		$transaction->parallel = 0;
		$transaction->scheduled_at = date('Y-m-d H:i:s');
		$transaction->created = date('Y-m-d H:i:s');
		$transaction->save();

		\Skeleton\Database\Config::$auto_discard = $original_auto_discard_value;
	}

	/**
	 * Migrate down
	 *
	 * @access public
	 */
	public function down() {

	}
}
