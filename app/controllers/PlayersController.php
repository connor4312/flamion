<?php

class PlayersController extends BaseController {

	public function index() {
		return View::make('standard')->
			with('content', 'PLAYER MANAGEMENT')->
			with('title', 'Player Management')->
			nest('content', 'user.players');
	}
}