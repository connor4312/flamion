<?php

class Permission extends Eloquent {
	
	function server() {
		return $this->hasOne('Server');
	}
	function user() {
		return $this->belongsTo('User');
	}
}