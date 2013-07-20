<?php
class User extends Eloquent {

	protected $permission;
	
	public function permissions() {
		return $this->hasMany('Permission');
	}

	public function roles() {
		return $this->belongsToMany('Permission');
	}

	public function getReminderEmail() {
		return $this->email;
	}
}