<?php
class AdminPanelController extends BaseController {

	public function index($error = array()) {

		return View::make('standard')->
			with('title', 'Panel Options')->
			with('footer_scripts', array('js/bootstrap.min.js', 'js/ajax.options.js')) ->
			nest('content', 'admin.options', array(
					'error' => $error
				)
			);
	}

	public function getPage($page) {
		switch ($page) {
			case 'sms':

				$monthstart = time() - 86400 * 30;
				$days = array();
				for ($i = 0; $monthstart + $i * 86400 < time(); $i++) {
					$days[] = $monthstart + $i * 86400;
				}

				$historic = DB::table('smss')->
					whereRaw('UNIX_TIMESTAMP(`created_at`) > ' . $monthstart)->
					select(array(
						DB::raw('UNIX_TIMESTAMP(`created_at`) as created_at'),
						DB::raw('COUNT(*) as count')
					))->
					groupBy(DB::raw('DAY(`created_at`)'))->
					orderBy('created_at', 'asc')->
					get();

				$sums = array();
				$sumints = array(0, 6, 29);
				$sum = 0;
				$chart_data = array();
				$hoffset = 0;

				for ($i = 1; $i < count($days); $i++) {
					
					if (isset($historic[$i - $hoffset]) && $historic[$i - $hoffset]->created_at <= $days[$i] && $historic[$i - $hoffset]->created_at > $days[$i - 1]) {

						$chart_data[$i] = array(
							'created_at' => date('M j', $days[$i]),
							'count' => $historic[$i - $hoffset]->count
						);
						$sum += $historic[$i - $hoffset]->count;

					} else {
						$hoffset++;
						$chart_data[$i] = array(
							'created_at' => date('M j', $days[$i]),
							'count' => 0
						);
					}

					if (in_array($i, $sumints)) {
						$sums[$i] = $sum;
					}

				}

				return View::make('admin.options_sms')->
					with(array(
						'sms_sums' => $sums,
						'chart_data' => $chart_data
					));

			break;
			case 'general':

				return View::make('admin.options_general');

			break;
			case 'advanced':

				return View::make('admin.options_advanced');

			break;
			default:
				return App::abort(404);
			break;
		}
	}

	protected function saveConfig($name, $value) {
		$parts = explode('.', $name);

		if (Config::get($name) === $value) {
			return true;
		}

		if (!$reading = @fopen(app_path() . '/config/' . $parts[0] . '.php', 'r'))
			return false;

		$writing = fopen(storage_path() . '/temp/' . $parts[0] . '.php.tmp', 'w');

		$replaced = false;

		while (!feof($reading)) {
			$line = fgets($reading);
			if (strstr($line, '\'' . $parts[1] . '\' =>')) {
				$line = str_replace(Config::get($name), $value, $line);
				$replaced = true;
			}
			fputs($writing, $line);
		}
		fclose($reading);
		fclose($writing);

		if ($replaced)  {
			rename(storage_path() . '/temp/' . $parts[0] . '.php.tmp', app_path() . '/config/' . $parts[0] . '.php');
			return true;
		} else {
			unlink(storage_path() . '/temp/' . $parts[0] . '.php.tmp');
			return false;
		}
	}

	public function save() {
		$input = Input::all();

		$allowed = array(
			'flamion_',
			'mail_',
			'app_url',
			'app_timezone',
			'app_debug'
		);

		foreach ($input as $key => $value) {
			$good = false;
			foreach ($allowed as $al) {
				if (strpos($key, $al) === 0) {
					$good = true;
					break;
				}
			}
			if (!$good) {
				continue;
			}

			$this->saveConfig(preg_replace('/_/', '.', $key, 1), $value);
		}

		return Redirect::to('/admin/options');
	}

	public function buildless() {

		$formatter = new lessc_formatter_classic;
		$formatter->indentChar = '';
		$formatter->break = '';

		$less = new lessc;
		$less->setVariables(array(
			'primary-light' => Config::get('flamion.color'),
			'monoFontFamily' => Config::get('flamion.console_font'),
			'sansFontFamily' => Config::get('flamion.primary_font')
		));

		$less->setFormatter($formatter);
		try {
			$less->compileFile(storage_path() . '/less/dashboard.less', public_path() . '/css/dashboard.temp.css');
			$less->compileFile(storage_path() . '/less/login.less', public_path() . '/css/login.temp.css');	

			unlink(public_path() . '/css/dashboard.css');
			unlink(public_path() . '/css/login.css');

			rename(public_path() . '/css/dashboard.temp.css', public_path() . '/css/dashboard.css');
			rename(public_path() . '/css/login.temp.css', public_path() . '/css/login.css');

		} catch (Exception $e) {
			return '<b>Compiled failed,</b> backup was restored. Message: ' . $e->getMessage();
		}

		return 'success';

	}

	public function testmail() {
		if (Mail::send('emails.standard', array(
				'content' => 'This is a test email, and it works!', 
				'subject' => 'Test Email'
			), function($message) {
				$message->to(Auth::user()->email)->subject('Test Email');
		})) {
			return 'success';
		} else {
			return 'fail';
		}
	}
}
?>