<?php
class BukGetBridge {

	const version = 3;
	const baseurl = 'http://api.bukget.org/';

	public static function fullRefresh() {
		$request = new Curl();
		$request->setOption('CURLOPT_CONNECTTIMEOUT', 15);
		$request->setOption('CURLOPT_RETURNTRANSFER', true);
		if (!$request->request(self::baseurl . self::version . '/plugins/bukkit', 'GET')) {
			return false;
		}

		$items = json_decode($request->data, true);

		$inserts = array();
		foreach ($items as $item) {
			$inserts[] = array(
				'slug' => $item['slug']
			);
		}
		DB::table('plugins')->insert($inserts);
		return true;
	}

	public static function updatePlugins() {

		$plugins = DB::table('plugins')->
			select('slug')->
			whereRaw('`name` IS NULL')->
			get();

		if (!count($plugins)) {
			return true;
		}

		$request = new Curl();
		$request->setOption('CURLOPT_CONNECTTIMEOUT', 15);
		$request->setOption('CURLOPT_RETURNTRANSFER', true);

		foreach ($plugins as $plugin) {
			if (!$request->request(self::baseurl . self::version . '/plugins/bukkit/' . $plugin->slug . '/latest', 'GET')) {
				return false;
			}

			$response = json_decode($request->data, true);

			DB::table('plugins')->
				where('slug', '=', $plugin->slug)->
				update(array(
					'name' => $response['plugin_name'],
					'stage' => $response['stage'],
					'page_url' => $response['dbo_page'],
					'download_url' => $response['versions'][0]['download'],
					'description' => $response['description'],
					'cb_version' => isset($response['versions'][0]['game_versions'][0]) ? $response['versions'][0]['game_versions'][0] : '',
					'lastrelease' => date('Y-m-d H:i:s', $response['versions'][0]['date']),
					'popularity' => $response['popularity']['monthly'],
					'version' => $response['versions'][0]['version'][0]
				));
		}

	}
	public static function getUpdates() {

		$request = new Curl();
		$request->setOption('CURLOPT_CONNECTTIMEOUT', 5);
		$request->setOption('CURLOPT_RETURNTRANSFER', true);

		if (!$request->request(self::baseurl . self::version, 'GET')) {
			return false;
		}

		$out = json_decode($request->data, true);

	
		foreach ($out[0]['changes'] as $plugin) {

			if (!$request->request(self::baseurl . self::version . '/plugins/bukkit/' . $plugin['plugin'] . '/latest', 'GET')) {
				return false;
			}

			$response = json_decode($request->data, true);

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
				where('slug', '=', $plugin['plugin'])->
				update(array(
					'name' => $response['plugin_name'],
					'stage' => $response['stage'],
					'page_url' => $response['dbo_page'],
					'download_url' => $response['versions'][0]['download'],
					'description' => $response['description'],
					'cb_version' => isset($response['versions'][0]['game_versions'][0]) ? $response['versions'][0]['game_versions'][0] : '',
					'lastrelease' => date('Y-m-d H:i:s', $response['versions'][0]['date']),
					'popularity' => $response['popularity']['monthly'],
					'version' => $response['versions'][0]['version'][0]
				));
		}

	}
}