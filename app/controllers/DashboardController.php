<?php

class DashboardController extends BaseController {

	public function index() {


		if (!$server = $this->currentServer()) {
			return App::abort(404);
		}
		$pbase = new PermissionManager(Auth::user());
		$pbase->on($server);
		
		if ($pbase->can('server.view') != 'yes') {
			return App::abort(403);
		}

		return View::make('standard')->
			with('title', 'Dashboard')->
			with('footer_scripts', array('js/ajax.dashboard.js'))->
			nest('content', 'user.dashboard', array(
				'server' => $server,
				'perm' => $pbase
			));
	}

	public function save() {

		if (!$server = $this->currentServer()) {
			return App::abort(404);
		}

		$pbase = new PermissionManager(Auth::user());
		$pbase->on($server);

		if ($pbase->can('server.view') != 'yes') {
			return App::abort(403);
		}


		$name = $pbase->can('server.edit.name');
		$jar = $pbase->can('server.edit.jar');
		$slot = $pbase->can('server.edit.slot');
		$super = Auth::user()->global_role == 'superuser';

		$vrules = array();

		if ($name) {
			$vrules['name'] = 'required';
		}
		if ($jar) {
			$vrules['jar_custom'] = 'required|boolean';
			if (Input::get('jar_custom') == 'false') {
				$vrules['jar_channel'] = 'required|numeric';
			} elseif (Input::get('jar_custom') == 'true') {
				$vrules['jar_name'] = 'required'; ////////// Add regexp for alphanum and periods
			}
		}
		if ($slot) {
			$vrules['slots'] = 'required|numeric';
		}
		if ($super) {
			$vrules['port'] = 'required|numeric';
			$vrules['ip'] = 'required'; ////////// Add regexp for num and periods
		}

		$input = Input::all();

		$validator = Validator::make($input, $vrules);
		if ($validator->fails()) {
			return View::make('standard')->
				with('title', 'Dashboard')->
				with('footer_scripts', array('js/ajax.dashboard.js'))->
				nest('content', 'user.dashboard', array(
					'server' => $server,
					'perm' => $pbase,
					'error' => implode('. ', $validator->messages()->all())
				));
		}

		if ($name) {
			$server->name = Input::get('name');
		}
		if ($jar) {
			if (Input::get('jar_custom') == 'false') {

				$server->jar = Input::get('jar_channel');

			} elseif (Input::get('jar_custom') == 'true') {

				$server->jar = Input::get('jar_name');
			}
		}
		if ($slot) {
			$server->slots = Input::get('slots');
		}
		if ($super) {
			$server->port = Input::get('port');
			$server->ip = Input::get('ip');
		}
		$server->save();

		return View::make('standard')->
			with('title', 'Dashboard')->
			with('footer_scripts', array('js/ajax.dashboard.js'))->
			nest('content', 'user.dashboard', array(
				'server' => $server,
				'perm' => $pbase
			));

	}
}