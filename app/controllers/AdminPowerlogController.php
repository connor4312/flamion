<?php
class AdminPowerlogController extends BaseController {
	public function index() {
		$parts = func_get_args();
		$path = implode('.', $parts);
		$err = null;

		$query = DB::table('loggers')->
			where('action', 'LIKE', $path . '%')->
			orderBy('loggers.created_at', 'desc')->
			join('users', 'users.id', '=', 'loggers.user_id')->
			select('loggers.ip', 'loggers.action', 'loggers.message', 'loggers.created_at', 'users.email', 'users.id', 'loggers.level');

		if ($input = Input::get('query')) {
			switch (Input::get('type')) {
				case 'time':
					$count = DB::table('loggers')->
						where('action', 'LIKE', $path . '%')->
						where(DB::raw('UNIX_TIMESTAMP(created_at)'), '<=', strtotime($input))->
						orderBy('created_at', 'desc')->
						select(DB::raw('count(*) as count'))->
						first();

					$_GET['page'] = floor($count->count / 40);

				break;
				case 'user':
					$query->
						where(function($qu) use ($input) {
							if (is_numeric($input))
								$qu->where('users.id', '=', $input);
							else
								$qu->where('users.email', '=', $input);
						});
				break;
				case 'action':

					$input = str_replace(' ', '', str_replace('>', '.', $input));

					if (!preg_match('/^[a-zA-Z0-9.]*$/', $input)) {
						$err = 'Invalid action formation. Please use dot format, for example "user.admin.edit" or arrow format "user &lt; admin &lt; edit';
					} else {
						return Redirect::to('/admin/log/view/' . str_replace('.', '/', $input));
					}
				break;
				case 'ip':
					$logs = $query->where('ip', '=', $input)->paginate(40);
				break;
			}
			

		}

		$logs = $query->paginate(40);

		$descriptions = DB::table('loggers_info')->
			select('action', 'info', 'depth')->
			where('depth', '<=', count($path))->
			where('action', 'LIKE', implode('.', array_slice($parts, -1)) . '%')->
			orWhere('action', '=', $path)->
			orderBy('action', 'ASC')->
			get();

		$monthstart = time() - 86400 * 30;
		$days = array();
		for ($i = 0; $monthstart + $i * 86400 < time(); $i++) {
			$days[] = $monthstart + $i * 86400;
		}

		$historic = DB::table('loggers')->
			where('action', 'LIKE', $path . '%')->
			whereRaw('UNIX_TIMESTAMP(`created_at`) > ' . $monthstart)->
			select(array(
				DB::raw('UNIX_TIMESTAMP(`created_at`) as created_at'),
				DB::raw('COUNT(*) as count')
			))->
			groupBy(DB::raw('DAY(`created_at`)'))->
			orderBy('created_at', 'asc')->
			get();

		$chart_data = array();
		$hoffset = 0;

		for ($i = 1; $i < count($days); $i++) {
			
			if (isset($historic[$i - $hoffset]) && $historic[$i - $hoffset]->created_at <= $days[$i] && $historic[$i - $hoffset]->created_at > $days[$i - 1]) {

				$chart_data[$i] = array(
					'created_at' => date('M j', $days[$i]),
					'count' => $historic[$i - $hoffset]->count
				);

			} else {
				$hoffset++;
				$chart_data[$i] = array(
					'created_at' => date('M j', $days[$i]),
					'count' => 0
				);
			}
		}

		return View::make('standard')->
			with('title', 'PowerLogger')->
			with('footer_scripts', array('js/ajax.logger.js')) ->
			nest('content', 'admin.log', array(
				'logs' => $logs,
				'descriptions' => $descriptions,
				'path' => $path,
				'parts' => $parts,
				'err' => $err,
				'chart_data' => $chart_data
			)
		);
		
	}
}
?>