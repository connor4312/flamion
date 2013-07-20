<?php
class Notification extends Eloquent {
	public function server() {
		return $this->belongsTo('Server');
	}
}
?>