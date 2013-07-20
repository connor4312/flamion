<?php namespace Flamion\Reactorapi;


class ReactorAPI {

	public static $funcs = array(
		'info' => 'input:server|permission:server.info',
		'start' => 'input:server|permission:server.start',
		'restart' => 'input:server|permission:server.restart',
		'stop' => 'input:server|permission:server.stop',
		'issuecommand' => 'input:server|input:command|permission:console.issuecommand',
		'getlog' => 'input:server|permission:console.view'
	);

	public static function info($args) {
		return json_encode(array('result' => 'running'));
	}

	public static function start($args) {
		return json_encode(array('result' => 'success'));
	}

	public static function stop($args) {
		return json_encode(array('result' => 'success'));
	}

	public static function restart($args) {
		return json_encode(array('result' => 'success'));
	}
	public static function getlog($args) {
		$foostuff = explode("\n",'2013-05-25 12:39:58 [INFO] xtranchida[/71.175.95.78:49899] logged in with entity id 596064 at ([PVP] 359.7058179379771, 64.0, 233.428587802035)
2013-05-25 12:41:38 [INFO] There are 1 out of maximum 49 players online.
2013-05-25 12:41:38 [INFO] Connected players: [Merchant]~xt
2013-05-25 12:41:42 [INFO] xtranchida issued server command: /warp clan
2013-05-25 12:42:03 [INFO] CONSOLE: Enabled level saving..
2013-05-25 12:42:03 [INFO] CONSOLE: Forcing save..
2013-05-25 12:42:03 [INFO] CONSOLE: Save complete.
2013-05-25 12:42:03 [INFO] [Server] World saved.
2013-05-25 12:42:11 [INFO] There are 1 out of maximum 49 players online.
2013-05-25 12:42:11 [INFO] Connected players: [Merchant]~xt
2013-05-25 12:42:29 [INFO] Connection reset
2013-05-25 12:42:29 [INFO] xtranchida lost connection: disconnect.quitting
2013-05-25 12:42:44 [INFO] There are 0 out of maximum 49 players online.
2013-05-25 12:42:44 [INFO] Connected players:');
		return json_encode(array(
			'result' => 'success',
			'data' => array($foostuff[rand(0,13)],$foostuff[rand(0,13)]),
		));
	}
}