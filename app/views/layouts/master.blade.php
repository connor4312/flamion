<!DOCTYPE html>
<html lang="{{ Config::get('app.locale') }}">
	<head>
		<meta charset="UTF-8">
		<title>Flamion - {{ $title }}</title>
		{{ HTML::style('css/dashboard.css') }}
	</head>
	<body>
	<div id="sidebar-mask" class="span4"></div>
	<div class="container-fluid" id="navbar-upper">
		<div id="topheader" class="row-fluid">
			<div class="span4">
				<div id="logo"></div>
			</div>
			<div class="span4 text-center">
				<span>
					Logged in as {{ Auth::user()->email }}<br>
					Server time:  {{ date('H:i \o\n F jS') }}
				</span>
			</div>
			<div class="span4 text-right">
				{{ HTML::link('account/manage', 'My Account', array('class' => 'btn icon-key')) }}
				{{ HTML::link('login/logout', 'Logout', array('class' => 'btn icon-exit')) }}
			</div>
		</div>
	</div>
	<div class="container-fluid" id="navbar-lower">
		<div class="row-fluid">
			<div class="span3">
				<select id="serverchoose" name="server" selected="{{ Session::get('current-server')['id'] }}">
					@foreach (Session::get('navbar') as $nav)
					<option value="{{ $nav->id }}">{{ $nav->name }}</option>
					@endforeach
				</select>
			</div>
			<div id="naver">
				<ul>
					<li><a href="#">Start</a></li>
					<li class="disabled"><a href="#">Stop</a></li>
					<li><a href="#">Restart</a></li>
					<li><a href="#">Notifications <i>1</i></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="container-fluid" id="main">
		<div class="row-fluid">
			<div class="span4" id="sidebar">
				{{ $navigationSidebar }}
			</div>
			<div id="content">
				@yield('content')
			</div>
		</div>
	</div>

	@include ('layouts.scripts')
	</body>
</html>