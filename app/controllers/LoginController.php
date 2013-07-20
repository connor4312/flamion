<?php use Carbon\Carbon;

class LoginController extends BaseController {

	public function register() {
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
			$user->password = Hash::make(Input::get('password'));
			$user->save();

			Auth::login($user);
			Logger::add(array(
				'user_id' => Auth::user()->id,
				'action' => 'user.register'
			));

			$out['result'] = 'success';
		}
		
		echo json_encode($out);
	}

	public function validate() {
		$out = array();

		if (Session::get('login.locktill', 0) > time()) {
				
			$carbon = new Carbon(date('Y-m-d H:i:s', Session::get('login.locktill')));

			$out['result'] = 'Login temporarily blocked. Try again in ' . $carbon->diffForHumans() . '.';

		} elseif (Auth::attempt(array(
			'email' => Input::get('email'),
			'password' => Input::get('password')
		))) {
			$out['result'] = 'success';
			Session::set('login.attempts', 0);

			Logger::add(array(
				'user_id' => Auth::user()->id,
				'action' => 'user.login'
			));

		} else {
			$out['result'] = Lang::get('login.invalidauth');

			$attempts = Session::get('login.attempts', 0);
			Session::set('login.attempts', $attempts + 1);

			if ($attempts >= Config::get('flamion.login_attempts')) {
				Session::put('login.locktill', time() + Config::get('flamion.login_lockout_time'));
				$out['result'] .= ' Login temporarily blocked.';
			}
			Logger::add(array(
				'message' => 'Locked out on email ' . Input::get('email'),
				'action' => 'user.lockout'
			));
		}

		echo json_encode($out);
	}
}
?>