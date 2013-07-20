<?php
function canDo($key, $permissions) {

	foreach ($permissions as $p) {
		if ($p->permission == '*') {
			return true;
		}
		if ($key == $p->permission) {
			return true;
		}
		if (substr($key, -1) == '*') {
			if (!strpos($p, substr($key, 0, -1)) === false) {
				return true;
			}
		}
		return false;
	}
}
?>
<div class="row-fluid">
	<div class="span4">
		<div class="box">
			<h1>Users</h1>
			<div class="padded">
				<ul class="nav nav-tabs nav-stacked">
				@foreach ($users as $user)
					<li{{ $theuser->id == $user->id ? ' class="active"' : ''}}>{{ HTML::link('/server/' . $server . '/permissions/' . $user->id, $user->email) }}</li>
				@endforeach
				</ul>
				{{ Form::open(array('url' => '/server/' . $server . '/permissions', 'method' => 'POST')) }}
				<div class="input-append fluid-row">
					{{ Form::text('email', null, array('class' => 'appendedInputButton span8', 'placeholder' => 'Add New User')) }}
					{{ Form::submit('Add User', array('class' => 'btn span4')) }}
				</div>
			</div>
		</div>
	</div>
	<div class="span8">
		<div class="box padded">
			<h1>Permissions for {{ $theuser->email }}</h1>
			<div class="fluid-row">
				<div class="span6 small_slap">
					<table class="table table-striped">
						<?php
						$config_perm = Config::get('flamion.permissions');
						ksort($config_perm);
						$break = floor(count($config_perm) * 0.5);
						$header = '';
						$i = 0;

						foreach ($config_perm as $key => $desc) {
							$i++;

							list($title, ) = explode('.', $key, 2);
							if ($title !== $header) {
								$header = $title;
								if ($i > $break) {
									echo '</table></div><div class="span6 small_slap"><table class="table table-striped">';
									$break = 99999;
								}

								echo '<tr><th>' . ucwords($header) . '</th>' .
									'<th>' . $header . '.*</th>' .
									'<th>' . Form::checkbox('permissions', strtok($key, '.') . '_STAR', canDo($key, $permissions)) . '</th></tr>';
							}

							echo '<tr><td>' . ucwords($key) . '</td>' .
								'<td>' . $desc . '</td>' .
								'<td>' . Form::checkbox('permissions', str_replace('.', '_', $key), canDo($key, $permissions)) . '</td></tr>';
						}
						?>
					</table>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>