<?php

class PermissionManager {

	private $permission;
	private $user;
	private $server;

	public function PermissionManager($user) {
		$this->user = $user;
	}

	public function __toString() {

		if (!is_object($this->user)) {
			return 'no';
		}


		if (Auth::user()->id == $this->user->id) {

			$permissions = Session::get('permissions', function() {
				$perms = DB::table('permissions')->
					where('user_id', '=', Auth::user()->id)->
					select('server_id', 'permission')->
					get();
				$out = array();
				foreach ($perms as $p) {
					$out[$p->server_id][] = $p->permission;
				}
				return $out;
			});

			if (!$this->permission) {
				return isset($permissions[$this->server]) ? $permissions[$this->server] : array();
			} else {

				$p = explode('.', $this->permission);
				$thread = '';
				$allowed = array('*', $this->permission);

				foreach ($p as $part) {
					$thread .= $part . '.';
					$allowed[] = $thread . '*';
				}
				if (!isset($permissions[$this->server])) {
					return 'no';
				}

				foreach ($permissions[$this->server] as $perm) {
					if (in_array($perm, $allowed)) {
						return 'yes';
					}
				}

				return 'no';
			}

		} else {
			if (!$this->permission) {

				return DB::table('permissions')->
					where('user_id', '=', $this->id)->
					where('server_id', '=', $this->server)->
					get();

			} else {

				$q = DB::table('permissions')->
					select(DB::raw('COUNT(*) as count'))->
					where('user_id', '=', $this->id)->
					where('server_id', '=', $this->server)->
					where(function ($query) {
						
						$query->where('permission', '=', $this->permission)->
							orWhere('permission', '=', '*');

						$p = explode('.', $this->permission);
						$thread = '';

						foreach ($p as $part) {
							$thread .= $part . '.';
							$query->orWhere('permission', '=', $thread . '*');
						}

					})->first();


				return $q->count ? 'yes' : 'no';
			}
		}


	}

	public function can($permission) {
		$this->permission = $permission;
		return $this;
	}
	public function on($server) {

		if (is_object($server)) {
			$server = $server->id;
		}
		if (is_array($server)) {
			$server = $server['id'];
		}

		$this->server = $server;
	}
}