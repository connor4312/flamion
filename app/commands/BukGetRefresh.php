<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BukGetRefresh extends Command {

 
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'flamion:bukgetrefresh';
	 
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Updates -all- plugins in the plugin database. Takes a while!';
	 
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
		DB::table('plugins')->delete();

		if (!BukGetBridge::fullRefresh()) {
			$this->error("Couldn't connect to BukGet!");
			return false;
		} else {
			$this->info("Success! Starting to join plugins...");
		}

		$plugins = DB::table('plugins')->
			select('slug')->
			whereRaw('`name` IS NULL')->
			get();

		if (!$c = count($plugins)) {
			return true;
		}

		$request = new Curl();
		$request->setOption('CURLOPT_CONNECTTIMEOUT', 15);
		$request->setOption('CURLOPT_RETURNTRANSFER', true);

		$num = 0;
		foreach ($plugins as $plugin) {
			if (!$request->request(BukGetBridge::baseurl . BukGetBridge::version . '/plugins/bukkit/' . $plugin->slug . '/latest', 'GET')) {
				continue;
			}

			$response = json_decode($request->data, true);

			$num++;
			if ($num/100 == floor($num/100)) {
				$this->comment($num . ' plugins done of ' . $c);
			}

			if (!isset(
					$response['plugin_name'],
					$response['stage'],
					$response['dbo_page'],
					$response['versions'][0]['download'],
					$response['description'],
					$response['versions'][0]['date'],
					$response['popularity']['monthly'],
					$response['versions'][0]['version'][0]
			))
				continue;

			DB::table('plugins')->
				where('slug', '=', $plugin->slug)->
				update(array(
					'name' => $response['plugin_name'],
					'stage' => $response['stage'],
					'page_url' => $response['dbo_page'],
					'download_url' => $response['versions'][0]['download'],
					'description' => $response['description'],
					'cb_version' => isset($response['versions'][0]['game_versions'][0]) ? $response['versions'][0]['game_versions'][0] : '',
					'lastrelease' => date('Y-m-d H:i:s', strtotime($response['versions'][0]['date'])),
					'popularity' => $response['popularity']['monthly'],
					'version' => $response['versions'][0]['version'][0]
				));

		}
		$this->info("Complete!");

	}

}