<?php
class AdminUserController extends BaseController {

	public function index() {
		
		$users = DB::table('users')->paginate(30);

		return View::make('standard')->
			with('title', 'User Management')->
			with('footer_scripts', array('js/ajax.users.js')) ->
			nest('content', 'admin.user', array('users' => $users, 'message' => ''));
	}
	public function makeUser() {

		$out = array();
		$input = Input::all();
		$validator = Validator::make($input, array(
			'email' => 'required|email|unique:users',
			'password'  => 'required|between:5,50'
		));
		if ($validator->fails()) {
			$messages = $validator->messages();
			$out['result'] = '';
			foreach ($messages->all() as $message) {
				$out['result'] .= $message . ' ';
			}
		} else {
			$user = new User();
			$user->email = Input::get('email');
			$user->global_role = Input::get('global_role');
			$user->password = Hash::make(Input::get('password'));
			$user->save();

			$out['result'] = 'success';

			Logger::add(array(
				'user_id' => Auth::user()->id,
				'action' => 'user.admin.register',
				'message' => 'Created user ' . $user->id
			));

			Logger::add(array(
				'user_id' => $user->id,
				'action' => 'user.register',
				'message' => $parts[0] . 'Created by admin ' . Auth::user()->id
			));

		}
		
		if (Request::ajax()) {
			return json_encode($out);
		} else {
			$users = DB::table('users')->paginate(30);

			return View::make('standard')->
				with('title', 'User Management')->
				with('footer_scripts', array('js/ajax.users.js')) ->
				nest('content', 'admin.user', array(
					'users' => $users, 
					'message' => ($out['result'] == 'success' ? '<div class="alert alert-success">Account successfully created!</div>' : '<div class="alert alert-error">' . $out['result'] . '</div>')
				));

		}

	}

	public function getUser($id) {
		$data = array();

		$data['servers'] = DB::table('users')->
			join('permissions', 'users.id', '=', 'permissions.user_id')->
			join('servers', 'servers.id', '=', 'permissions.server_id')->
			where('users.id', '=', $id)->
			select('servers.name', 'servers.id', 'permissions.permission')->
			get();


		$data['logs'] = DB::table('loggers')->
			where('user_id', '=', $id)->
			select('ip', 'action', 'message', 'created_at')->
			take(10)->
			get();

		$data['userdata'] = DB::table('users')->
			where('id', '=', $id)->
			select('*')->
			first();

		if (Request::ajax()) {

			$data['wrap'] = false;
			return View::make('admin.user_get', $data);

		} else {

			$data['wrap'] = true;
			return View::make('standard')->
				with('title', 'View User')->
				with('footer_scripts', array('js/ajax.users.js')) ->
				nest('content', 'admin.user_get', $data);

		}
	}

	public function updateUser() {
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

		Logger::add(array(
			'user_id' => Auth::user()->id,
			'action' => 'user.admin.edit',
			'message' => 'Edited ' . $parts[0] . ' of user ' . $parts[1]
		));
		Logger::add(array(
			'user_id' => $parts[1],
			'action' => 'user.edit',
			'message' => $parts[0] . ' edited by admin ' . Auth::user()->id
		));


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

	}
}
?>