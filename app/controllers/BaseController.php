<?php

class BaseController extends Controller {

	protected $pagespread = true;

	public function __construct() {

		if (!$pagespread || Request::ajax()) {
			return false;
		}

		function generate_list($array) {
			$out = '<ul>';

			foreach ($array as $name => $url) {
				if (is_array($url)) {
					$out .= '<li class="navheader">' . strtoupper($name) . '</li>';
					foreach ($url as $title => $link) {
						$out .= genLink($link, $title);
					}
				} else {
					$out .= genLink($url, $name);
				}				
			}
			return $out . '</ul>';
		}

		function genLink($url, $name) {
			if (strpos((string) $url, '{id}') !== false) {
				$url = str_replace('{id}', Session::get('current-server')['id'], $url);
			}
			return '<li><a href="' . URL::to($url) . '"><span class="icon icon-' . strtok($name, ' ') . '"></span> ' . strstr($name, ' ') . '</a></li>';
		}

		$links = array(
			'My Server' => array(
				'dashboard Dashboard' => '/#',
				'console Console' => '/server/console',
				'users Players' => '/server/players',
				'user Permissions' => '/server/permissions',
			),
			'Files' => array(
				'power-cord Plugins' => '/server/plugins',
				'upload FTP Access' => '/server/upload',
			),
			'Resource Manager' => array(
				'stats Usages History' => '/server/resource/graphs' ,
				'storage Disk Usage Breakdown' => '/server/resource/disk'
			),
			'table Database Access' => '/server/database'
		);

		if (@Auth::user()->global_role == 'superuser') {
			$links += array(
				'Administration' => array(
					'podcast Manage Daemons &amp; Nodes' => 'admin/nodes',
					'users Manage Users' => 'admin/users',
					'list View PowerLogs' => 'admin/log',
					'equalizer Panel Options' => 'admin/options'
				)
			);
		}

		function generateListing() {
			$first = false;

			if (!Auth::check()) {
				return false;
			}

			$out = DB::table('servers')
				->join('permissions', 'permissions.server_id', '=', 'servers.id')
				->join('users', 'users.id', '=', 'permissions.user_id')
				->where('users.id', '=', Auth::user()->id)
				->select('name', 'server_id as id')
				->get();	

			if (Input::get('serverbar')) {
				$server = (array) Server::find(Input::get('serverbar', 0));
			} else {
				$server = (array) $out[0];
			}

			Session::put('current-server', $server);
			Session::put('navbar', (array) $out);

			return (array) $out;
		}

		if (Input::get('serverbar')) {

		}

		$value = Session::get('navbar', function() {
			return generateListing();
		});


		View::share('navigationSidebar', generate_list($links));
	}

	protected function currentServer() {
		return Server::find(Session::get('current-server')['id']);
	}

	protected function setupLayout() {
		if (!is_null($this->layout)) {
			$this->layout = View::make($this->layout);
		}
	}
}