<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Your Company Name
	|--------------------------------------------------------------------------
	|
	| This will be displayed on the login page and in the copyright information.
	|
	*/

    'company' => 'Host-Seed',

	/*
	|--------------------------------------------------------------------------
	| Enforce HTTPS
	|--------------------------------------------------------------------------
	|
	| If true, all non-HTTPS requests will be redirected to https versions.
	|
	*/

    'https_enforce' => true,

	/*
	|--------------------------------------------------------------------------
	| Login Lockouts
	|--------------------------------------------------------------------------
	|
	| Controls the locking out of users on failed login attempts. The former is
	| the number of attempts before a lockout occurs, the latter is the amount
	| of time (in seconds) of a lockout.
	|
	*/

    'login_attempts' => 5,
    'login_lockout_time' => 60 * 15,

	/*
	|--------------------------------------------------------------------------
	| Theme Color
	|--------------------------------------------------------------------------
	|
	| Your site's main theme color. It is recommended to keep the same tone and
	| saturation, only change the hue. If you adjust this value in the panel,
	| the CSS will automatically be recompiled. If you change it here, you
	| will have to compile manually.
	|
	*/

    'color' => '#F15A2B',

	/*
	|--------------------------------------------------------------------------
	| CSS Fonts
	|--------------------------------------------------------------------------
	|
	| These fonts are compiled with LESS, and do what they say... By default,
	| console_font should be fine, and primary_font is set up to use Typekit
	| identifiers. You may wish to change this to something more common,
	| if you do not have a Typekit account.
	|
	*/

    'primary_font' => '"Myriad Pro", "myriad-pro", sans-serif',
    'console_font' => '"Consolas", "Lucidia Console", "Courier New", Monospace',

	/*
	|--------------------------------------------------------------------------
	| Message Closure
	|--------------------------------------------------------------------------
	|
	| This closure will be displayed on email messages send from the system.
	|
	*/

    'messageclosure' => "Kind Regards,<br>\nThe Host-Seed Team",

	/*
	|--------------------------------------------------------------------------
	| SMS Enabled
	|--------------------------------------------------------------------------
	|
	| If enabled, Flamion will send automatic SMS messages to server admins
	| when something goes wrong! Isn't it awesome? :3
	|
	*/

	'sms_enable_user' => true,
	'sms_enable_superuser' => true,

	/*
	|--------------------------------------------------------------------------
	| SMS Method
	|--------------------------------------------------------------------------
	|
	| Service to use for SMS messaging. Possible choices are "nexmo" and
	| "twilio"
	|
	*/

	'sms_method' => 'nexmo',

	/*
	|--------------------------------------------------------------------------
	| SMS Cooldown
	|--------------------------------------------------------------------------
	|
	| Cooldown between text messages. If "-1", we'll try to guess the best
	| cooldown to use but no go over the sms_max messages per month.
	| This should be given in hours, decimals are OK.
	|
	*/

	'sms_cooldown' => -1,

	/*
	|--------------------------------------------------------------------------
	| SMS Log Level
	|--------------------------------------------------------------------------
	|
	| In Flamion, exceptions are integrated into the PowerLogger system. When a
	| log is thrown with a level equal to or higher than what's given below
	| (log4j levels), an SMS will be sent.
	|
	*/

	'sms_level' => 'fatal',

	/*
	|--------------------------------------------------------------------------
	| SMS Max
	|--------------------------------------------------------------------------
	|
	| Maximum texts per month to send. When this is number is hit, an error
	| is thrown to let the admins know what's up.
	|
	*/

	'sms_max' => 200,

	/*
	|--------------------------------------------------------------------------
	| Nexmo Options
	|--------------------------------------------------------------------------
	|
	| These options are required auth items for the Nexmo API. We don't know
	| how, but they work...
	|
	*/

	'sms_nexmo_from' => '',
	'sms_nexmo_api_key' => '',
	'sms_nexmo_api_secret' => '',

	/*
	|--------------------------------------------------------------------------
	| Twilio Options
	|--------------------------------------------------------------------------
	|
	| These options are required auth items for the Twilio API. We don't know
	| how, but they work...
	|
	*/

	'sms_twilio_from' => '',
	'sms_twilio_sid' => '',
	'sms_twilio_token' => '',

	/*
	|--------------------------------------------------------------------------
	| Permissions Listing
	|--------------------------------------------------------------------------
	|
	| This is a full list of all available permissions used in Flamion. Don't
	| change it unless you're really doing some building/changing under the
	| hood; it's mainly for cross-referencing a and listing display.
	|
	*/

	'permissions' => array(

		/* Permissions for managing permissions! --------------------------- */

		'permissions.view' => 'Can view permissions listing.'
		'permissions.adduser' => 'Adds a new user to the server listing',
		'permissions.modify.loweruser' => 'Allows modification of the permissions on *other* users which the current user also has.',
		'permissions.modify.greateruser' => 'Allows modification of permissions on all users.',
		'permissions.modify.self' => 'Allows self promotion/demotion. Warning: a user with this permission can effectively gain any other permission!',
		'permissions.removeuser' => 'Allows the user to remove other users for any permissions access.',

		/* Permissions for the CONSOLE page -------------------------------- */

		'console.view' => 'Allows the user to view the server console.',
		'console.issuecommand' => 'Allows the user to issue commands on the server console.',
		'console.clear' => 'Allows the user to clear console history and server logs.',
		
		'console.task.view' => 'Allows viewing of scheduled tasks.',
		'console.task.manage' => 'Allows creation, editing, and deletion of scheduled tasks.',

		/* Permissions for the PLAYERS page -------------------------------- */

		'players.view' => 'Viewing of player history and information.',
		'players.tempban' => 'Allows temporary banning of users via the panel (not in game).',
		'players.ban' => 'Allows banning of users via the panel (not in game).',
		'players.kick' => 'Allows kicking of users via the panel (not in game).',
		'players.mute' => 'Allows muting of users via the panel (not in game).',

		/* Permissions for the PLUGINS page -------------------------------- */

		'plugins.view' => 'Allows viewing of installed plugins.',
		'plugins.install' => 'Allows installation of new plugins.',
		'plugins.remove' => 'Allows removing of plugins.',

		/* Permissions for the FTP page *and* system ----------------------- */

		'ftp.read' => 'Allows viewing of FTP files and configs.',
		'ftp.edit' => 'Allows editing of FTP files and configs.',
		'ftp.upload' => 'Allows creation/upload of new FTP files and configs.',
		'ftp.upload.jars' => 'Allows upload of jar files.',
		'ftp.client' => 'Allows remote client connection of FTP clients like FileZilla.',

		/* Permissions for the RESOURCES pages ----------------------------- */

		'resources.view' => 'Allows user to view historic resource usage.',
		'resources.database.view' => 'Allows viewing of MySQL database information.',
		'resources.database.edit' => 'Allows full panel access to the database.',
		'resources.database.login' => 'Allows access to MySQL login information.',

		/* Permissions for the DASHBOARD and various ----------------------- */

		'server.view' => 'Allos the user to view the server.',
		'server.start' => 'Allows starting of server.',
		'server.stop' => 'Allows stopping of server.',
		'server.restart' => 'Allows restarting of server.',
		'server.stats' => 'Allows viewing of server stats, such a players online.',

		'server.edit.name' => 'User can edit the server\'s name.',
		'server.edit.jar' => 'User can edit the server\'s jar file.',
		'server.edit.slot' => 'User can edit the server\'s slot count.',

		'alert.receive' => 'Allows receiving a viewing a server alerts.',
		'alert.sms' => 'Allows SMS sending of server alerts to user.',
	)
);