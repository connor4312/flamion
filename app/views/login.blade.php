<!DOCTYPE html>
<html lang="{{ Config::get('app.locale') }}">
	<head>
		<meta charset="	utf-8">
		<title>Login</title>
		{{ HTML::style('css/login.css') }}
	</head>
	<body>
		<div id="login-container">
			<div>
				<h1>Flamion <span>{{ Lang::get('login.aksel_by') }}</span></h1>
				{{ Form::open(array('url' => '/ajax/login', 'method' => 'POST', 'class' => 'form-horizontal')) }}
					<div class="control-group">
						{{ Form::label('email', Lang::get('login.email'), array('class' => 'control-label')) }}
						<div class="controls">
							{{ Form::text('email') }}
						</div>
					</div>
					<div class="control-group">
						{{ Form::label('password', Lang::get('login.password'), array('class' => 'control-label')) }}
						<div class="controls">
							{{ Form::password('password') }}
							<span class="help-inline" id="gateway"></span>
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<button class="btn btn-primary" id="loginbtn">{{ Lang::get('login.signin') }}</button>
							<button class="btn" id="registerbtn">{{ Lang::get('login.register') }}</button>
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<label><a id="#forgot">{{ Lang::get('login.forgot') }}</a></label>
						</div>
					</div>
				{{ Form::close() }}
			</div>
		</div>
		@include ('layouts.scripts')
		<script> 
			$(document).ready(function() { 
				$('button').click(function(){

					var attr = $(this).attr('disabled');
					var theid = $(this).attr('id');

					if (typeof attr == 'undefined' || attr == false) {
						$('form').ajaxSubmit({
							dataType: 'json',
							url: theid == 'loginbtn' ? '{{ URL::to('login/validate') }}' : '{{ URL::to('login/register') }}',
							beforeSubmit: function() {
								$('.btn').attr('disabled', 'true');
							},
							success: function(response) {
								$('.btn').removeAttr('disabled');

								if (response.result == 'success') {
									$('#gateway').html("{{ Lang::get('login.loggingin') }}");
									window.location = "{{ URL::to('dashboard') }}";
								} else {
									$('#gateway').html(response.result);
								}
							}
						});
					}
					return false;
				}); 
			}); 
		</script> 
	</body>
</html>