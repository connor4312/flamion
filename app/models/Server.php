<?php
class Server extends Eloquent {
	public function daemon() {
		return $this->belongsTo('Daemon');
	}
	public function players() {
		return $this->hasMany('Player');
	}
	public function notifications() {
		return $this->hasMany('Notification');
	}
	public function access() {
		return $this->hasMany('Permission');
	}
	public function permission() {
		return $this->belongsToMany('Permission');
	}
}
?>