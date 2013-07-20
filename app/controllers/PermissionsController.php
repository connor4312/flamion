<?php

class PermissionsController extends BaseController {

	public function index($user = null) {

		if (!$user) {
			$user = Auth::user();
		} elseif (!$user = User::find($user)) {
			return App::abort(404);
		}

		$perm = new PermissionManager($user);
		$perm->on($this->currentServer());

		if ($perm->can('permissions.view') != 'yes') {
			return App::abort(403);
		}

		$users = DB::table('permissions')->
			join('users', 'users.id', '=', 'permissions.user_id')->
			where('server_id', '=', $this->currentServer()->id)->
			select('users.email', 'users.id')->
			get();

		$permissions = DB::table('permissions')->
			where('user_id', '=', $user->id)->
			select('permission')->
			get();

		return View::make('standard')->
			with('content', 'PLAYER MANAGEMENT')->
			with('title', 'Permissions Management')->
			with('footerscripts', array('js/permissionsforms.js'))->
			nest('content', 'user.permissions', array(
				'server' => $this->currentServer(),
				'users' => $users,
				'theuser' => $user,
				'permissions' => $permissions
			));
	}
}