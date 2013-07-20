<?php
class TasksController extends BaseController {

	public function index() {

		$pbase = new PermissionManager(Auth::user());

		if (!$server = $this->currentServer()) {
			return App::abort(404);
		}

		if (!$pbase->can('console.tasks.manage')->on($server)) {
			return App::abort(403);
		}

		$tasks = DB::table('server_tasks')->
			where('server', '=', $server)->
			get();

		return View::make('standard')->
			with('title', 'Dashboard')->
			with('footer_scripts', array('js/ajax.tasks.js'))->
			nest('content', 'user.tasks', array(
				'server' => $server,
				'perm' => $pbase,
				'tasks' => $tasks
			));
	}
}