<?php
class AdminImportController extends BaseController {

	public function index() {
		return View::make('standard')->with('content', '');
	}
}
?>