<?php
class APIPrivateController extends BaseController {

	public function handle() {

		if (!Auth::check()) {
			return App::abort(404);
		}

		$input = Input::all();
		$validator = Validator::make($input, array(
			'method' => 'required|alpha',
			'server'  => 'numeric'
		));
		if ($validator->fails()) {
			die('val fail');
			return App::abort(401);
		}

		$method = Input::get('method');

		if (!array_key_exists($method, ReactorAPI::$funcs)) {
			return App::abort(401);
		}

		$mdata = explode('|', ReactorAPI::$funcs[$method]);

		$perm = new PermissionManager(Auth::user());

		foreach ($mdata as $dat) {
			list($subject, $require) = explode(':', $dat);
			switch ($subject) {
				case'input':
					if (Input::get($require) === null) {
						return App::abort(401);
					}
					break;
				case 'permission':
					if (!$perm->can($require)->on(Input::get('server'))) {
						return App::abort(403);
					}
					break;
			}
		}

		return ReactorAPI::$method(Input::all());

	}
}