<?php

class ConsoleController extends BaseController {

	public function index() {


		if (!$server = $this->currentServer()) {
			return App::abort(404);
		}

		$pbase = new PermissionManager(Auth::user());
		$pbase->on($server);

		$tasks = DB::table('server_tasks')->
			where('server', '=', $server)->
			select('command', 'increment')->
			get();

		return View::make('standard')->
			with('title', 'Dashboard')->
			with('footer_scripts', array('js/ajax.console.js'))->
			nest('content', 'user.console', array(
				'server' => $server,
				'perm' => $pbase,
				'tasks' => $tasks
			));
	}

	public function delete() {

		if (!$server = $this->currentServer() || !Request::ajax()) {
			return App::abort(404);
		}

		$pbase = new PermissionManager(Auth::user());
		$pbase->on($server);

		if ($pbase->can('console.clear')->on($server) != 'yes') {
			return App::abort(403);
		}
		
	}
}