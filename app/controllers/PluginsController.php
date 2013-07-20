<?php

class PluginsController extends BaseController {

	public function index() {

		$obs = array('popularity', 'name', 'lastrelease');
		if (in_array(Input::get('ob'), $obs))
			$ob = Input::get('ob');
		else
			$ob = $obs[0];


		$plugins = DB::table('plugins')->
			select('name', 'slug', 'id', 'description', 'cb_version', 'popularity', 'lastrelease')->
			orderBy($ob, Input::get('asc') ? 'ASC' : 'DESC')->
			paginate(15);

		return View::make('standard')->
			with('content', 'PLUGIN MANAGEMENT')->
			with('title', 'Plugin Management')->
			nest('content', 'user.plugins', array(
				'plugins' => $plugins
			));
	}
}