<?php
/**
 * validate-check:validate command for Skeleton Console
 *
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Vat_Check_Validate extends \Skeleton\Console\Command {

	/**
	 * Configure the Create command
	 *
	 * @access protected
	 */
	protected function configure() {
		$this->setName('vat-check:validate');
		$this->setDescription('Validate a specific VAT number and country vat code');
		$this->addArgument('vat_code', InputArgument::REQUIRED, 'Country vat code');
		$this->addArgument('vat_number', InputArgument::REQUIRED, 'VAT number');
		$this->addOption('ignore_cache');
	}

	/**
	 * Execute the Command
	 *
	 * @access protected
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int {
		try {
			$country = \Country::get_by_vat_code($input->getArgument('vat_code'));
		} catch (\Exception $e) {
			$output->writeln('<error>' . $e->getMessage() . '</error>');
			return 1;
		}

		$ignore_cache = false;
		if ($input->getOption('ignore_cache')) {
			$ignore_cache = true;
		}

		$is_valid = \Skeleton\Vat\Check\Check::validate($input->getArgument('vat_number'), $country, $reason, $ignore_cache);
		if ($is_valid) {
			$output->writeln($input->getArgument('vat_code') . ' ' . $input->getArgument('vat_number') . " is valid \t" . ' <info>ok</info>');
		} else {
			$output->writeln($input->getArgument('vat_code') . ' ' . $input->getArgument('vat_number') . " is NOT valid (" . $reason . ") \t" . ' <error>nok</error>');
		}

		return 0;
	}

}
