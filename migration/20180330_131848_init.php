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

		$db->query("
			INSERT INTO `transaction` (`classname`, `created`, `scheduled_at`, `data`, `retry_attempt`, `recurring`, `completed`, `failed`, `locked`, `frozen`, `parallel`)
			VALUES ('Cleanup_Vat_Check_Cache', now(), now(), '', '0', '1', '0', '0', '0', '0', '0');
		", []);
	}

	/**
	 * Migrate down
	 *
	 * @access public
	 */
	public function down() {

	}
}
