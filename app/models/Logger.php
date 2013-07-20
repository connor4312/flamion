<?php
class Logger extends Eloquent {
	
	function servers() {
		return $this->hasOne('Server');
	}
	
	function user() {
		return $this->hasOne('Server');
	}
	
	function node() {
		return $this->hasOne('Node');
	}

	public static function add($opts) {

		$log = new Logger();

		$opts['ip'] = isset($opts['ip']) ? $opts['ip'] : Request::getClientIp();

		foreach ($opts as $key => $opt) {
			$log->$key = $opt;
		}

		$log->save();

		return $log;
	}
}
?>