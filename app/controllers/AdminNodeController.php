<?php
class AdminNodeController extends BaseController {

	public function index() {
		
		$nodes = DB::table('nodes')->paginate(30);

		return View::make('standard')->
			with('title', 'Nodes')->
			with('footer_scripts', array(
					'//maps.googleapis.com/maps/api/js?sensor=false',
					'js/nodes.maps.js'
				)) ->
			nest('content', 'admin.nodes', array('nodes' => $nodes, 'message' => ''));
	}
	public function makeNode() {

		$out = array();
		$input = Input::all();
		$validator = Validator::make($input, array(
			'name' => 'required|alpha_num',
			'hostname'  => 'required',
			'disk_space' => 'required|numeric',
			'location' => 'required',					# GEO IP
			'ssh-password' => 'required|alpha_num',		# NO SSH
			'cpu_speed' => 'numeric',					# AUTO DETECT
			'cpu_cores' => 'numeric'					# AUTO DETECT
		));
		if ($validator->fails()) {
			$messages = $validator->messages();
			$out['result'] = '';
			foreach ($messages->all() as $message) {
				$out['result'] .= $message . ' ';
			}
		} else {
			$server = new Node();
			$server->name = Input::get('name');
			$server->address = Input::get('hostname');
			$server->disk_space = round(Input::get('disk_space') * 1024);
			$server->cpu_speed = Input::get('cpu_speed', '0');
			$server->cpu_cores = Input::get('cpu_cores', '1');
			$server->ssh_password = Crypt::encrypt(Input::get('ssh-password'));

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=' . urlencode(Input::get('location')));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
			$response = json_decode(curl_exec($ch), true);
			curl_close($ch);

			if (isset($response['results'][0]['geometry'])) {
				$server->xloc = $response['results'][0]['geometry']['location']['lat'];
				$server->yloc = $response['results'][0]['geometry']['location']['lng'];
				$server->location = $response['results'][0]['formatted_address'];

				$server->save();

				$out['result'] = 'success';

				Logger::add(array(
					'user_id' => Auth::user()->id,
					'action' => 'node.created',
					'node_id' => $server->id
				));
				
			} else {
				$out['result'] = 'Invalid location.';
			}
		}
		
		if (Request::ajax()) {
			return json_encode($out);
		} else {
			$users = DB::table('users')->paginate(30);

			return View::make('standard')->
				with('title', 'Nodes')->
				with('footer_scripts', array(
					'//maps.googleapis.com/maps/api/js?sensor=false',
					'js/nodes.maps.js'
				)) ->
				nest('content', 'admin.nodes_get', array(
					'users' => $users, 
					'message' => ($out['result'] == 'success' ? '<div class="alert alert-success">Node successfully added!</div>' : '<div class="alert alert-error">' . $out['result'] . '</div>')
				));

		}

	}

	public function getNode($id) {
		$data = array();

		$data['nodedata'] = DB::table('nodes')->first();

		if (Request::ajax()) {

			$data['wrap'] = false;
			return View::make('admin.nodes_get', $data);

		} else {

			$data['wrap'] = true;
			return View::make('standard')->
				with('title', 'View User')->
				with('footer_scripts', array(
					'//maps.googleapis.com/maps/api/js?sensor=false',
					'js/nodes.maps.js'
				)) ->
				nest('content', 'admin.nodes_get', $data);

		}
	}

	/*public function updateUser() {
		if (!Request::ajax()) {
			App::abort(404);
		}

		$parts = explode('-', Input::get('id'));

		try {
			$user = User::findOrFail($parts[1]);
		} catch (Exception $e) {
			return 'No user found';
		}

		if ($parts[0] == 'password')
			$parts[1] = Hash::make($parts[1]);

		$user->$parts[0] = Input::get('value');
		$user->save();

		Logger::add(Auth::user()->id, 'user.admin.edit', 'Edited ' . $parts[0] . ' of user ' . $parts[1]);
		Logger::add($parts[1], 'user.edit', $parts[0] . ' edited by admin ' . Auth::user()->id);


		return Input::get('value');
	}

	public function remindUser($id) {

		try {
			$user = User::findOrFail($id);
		} catch (Exception $e) {
			return 'No user found';
		}

		$password = substr(sha1(microtime() . mt_rand()), 0, 10);
		$user->password = Hash::make($password);
		$user->save();

		$data = array();
		$data['email'] = $user->email;
		$data['subject'] = 'Password Reset';
		$data['content'] = View::make('emails.auth.passreset', array('password' => $user->password));

		Mail::send('emails.standard', $data, function ($message) use ($data) {

			$message->to($data['email'])->subject($data['subject']);

			$message->from(Config::get('mail.from.address'), Config::get('mail.from.name'));
		});

		//Logger::add(Auth::user()->id, 'user.admin', 'Sent to user ' . $id);
		//Logger::add($id, 'user.reminder.admin', 'Sent by admin ' . Auth::user()->id);

		return $this->index();

	}*/
}
?>