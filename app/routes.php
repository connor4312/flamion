<?php
	/*
	|--------------------------------------------------------------------------
	| Routing for Index and Core Pages
	|--------------------------------------------------------------------------
	*/

	Route::get('/', function() {
		if (Auth::check()) {
			return Redirect::to('/dashboard');
			Log::info('Redirecting to dashboard...');
		} else {
			return Redirect::to('/login');
		}
	});

	Route::get('language/{lang}', function($lang) {

		if (in_array($lang, Config::get('app.available_langs'))) {
			App::setLocale($lang);
			Session::put('language', $lang);
		}

		return Redirect::to('/');
	});

	/* 404 error page */
	App::missing(function($exception) {
	    $content = View::make('error_page')->with('content', 'Error 404 not found!');
	    return Response::make($content, 404);
	});

	/*
	|--------------------------------------------------------------------------
	| Routing for Authentication-Based Pages
	|--------------------------------------------------------------------------
	*/

	Route::get('login', function() {
		Auth::logout();
		return View::make('login');
	});
	Route::get('login/logout', function() {
		return View::make('login');
	});

	Route::post('login/validate', 'LoginController@validate');
	Route::post('login/register', 'LoginController@register');

	Route::post('api/private', 'APIPrivateController@handle');
	Route::post('api/public', 'APIPublicController@handle');

	/*
	|--------------------------------------------------------------------------
	| Routing for "My Server" Pages
	|--------------------------------------------------------------------------
	*/

	Route::get('dashboard', 'DashboardController@index');
	Route::post('dashboard', 'DashboardController@update');


	Route::get('server/permissions/{user?}', 'PermissionsController@index');
	Route::post('server/permissions/{user?}', 'PermissionsController@set');


	Route::get('server/console', 'ConsoleController@index');
	Route::post('server/console/delete', 'ConsoleController@delete');

	Route::get('server/tasks', 'TasksController@index');
	Route::post('server/tasks/add', 'TasksController@index');
	Route::get('server/tasks/{id}/delete', 'TasksController@index')->where('id', '[0-9]+');;
	//Route::get('server/console/update', 'ConsoleController@index');


	Route::get('server/players', 'PlayersController@index');
	Route::get('server/players/{name}', array('as' => 'name', 'users' => 'PlayersController@index'));

	Route::get('server/plugins', 'PluginsController@index');

	/*
	|--------------------------------------------------------------------------
	| Routing for "Resource" Pages
	|--------------------------------------------------------------------------
	*/

	Route::get('database', 'UserDatabaseController@overview');
	Route::get('database/{table}', 'UserDatabaseController@tableview');
	Route::post('database/query', 'UserDatabaseController@query');

	/*
	|--------------------------------------------------------------------------
	| Routing for "Administration" Pages
	|--------------------------------------------------------------------------
	*/

/* Import - Get data from other control panels */

	Route::any('admin/import', 'AdminImportController@index');

/* Users - View and manage user data */

	Route::get('admin/users',  'AdminUserController@index');

	Route::post('admin/users/add', 'AdminUserController@makeUser');
	Route::post('admin/users/update', 'AdminUserController@updateUser');

	Route::get('admin/users/{id}/get', 'AdminUserController@getUser')->where('id', '[0-9]+');
	Route::get('admin/users/{id}/remind', 'AdminUserController@remindUser')->where('id', '[0-9]+');

/* Log - Powerlog implementation */

	Route::get('admin/log/view/{p2?}/{p3?}/{p4?}', 'AdminPowerlogController@index');
	Route::get('admin/log', 'AdminPowerlogController@index');

/* Daemons - Daemon and Node Management */
	
	Route::get('admin/nodes', 'AdminNodeController@index');
	Route::post('admin/nodes/add', 'AdminNodeController@makeNode');

	Route::get('admin/nodes/{id}/get', 'AdminNodeController@getNode')->where('id', '[0-9]+');

/* Database - Creation and management of user databases */

	Route::get('admin/database', 'AdminDatabaseController@index');
	Route::post('admin/database/add', 'AdminDatabaseController@makeDatabase');

	Route::get('admin/database/{id}/get', 'AdminDatabaseController@getDatabase')->where('id', '[0-9]+');

/* Panel Options */

	Route::get('admin/options', 'AdminPanelController@index');
	Route::get('admin/options/page/{name}', 'AdminPanelController@getPage');

	Route::get('admin/options/buildless', 'AdminPanelController@buildless');
	Route::get('admin/options/testmail', 'AdminPanelController@testmail');

	Route::post('admin/options/config', 'AdminPanelController@save');