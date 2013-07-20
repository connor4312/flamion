<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BukGetUpdate extends Command {

 
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'flamion:bukgetupdate';
	 
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Updates BukGet plugins';
	 
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	 }
	 
	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire() {

		$this->info("Success! Starting to update plugins...");

		BukGetBridge::getUpdates();
		
		$this->info("Complete!");

	}

}