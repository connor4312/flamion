<?php use Carbon\Carbon; ?>
<div id="newUserModal" class="modal hide" tabindex="-1" role="dialog" aria-hidden="true" style="display:none">
	{{ Form::open(array('url' => 'admin/users/add', 'method' => 'POST', 'class' => 'form-horizontal')) }}
	<div class="modal-header">
		<h3>Create New User</h3>
	</div>
	<div class="modal-body">

		<div class="control-group">
			{{ Form::label('email', 'E-Mail Address', array('class' => 'control-label')) }}
			<div class="controls">
				{{ Form::text('email') }}
			</div>
		</div>
		<div class="control-group">
			{{ Form::label('password', 'Password', array('class' => 'control-label')) }}
			<div class="controls">
				{{ Form::text('password') }}
			</div>
		</div>
		<div class="control-group">
			{{ Form::label('global_role', 'Role', array('class' => 'control-label')) }}
			<div class="controls">
				<label class="radio">{{ Form::radio('global_role', 'superuser') }} Superuser</label>
				<label class="radio">{{ Form::radio('global_role', 'owner') }} Owner</label>
				<label class="radio">{{ Form::radio('global_role', 'admin') }} Admin</label>
				<label class="radio">{{ Form::radio('global_role', 'mod') }} Moderator</label>
				<label class="radio">{{ Form::radio('global_role', '', true) }} None</label>
				<span id="gateway"></span>
			</div>
		</div>

	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</a>
		{{ Form::submit('Create User', array('class' => 'btn btn-primary', 'id' => 'newuser')) }}
	</div>
	{{ Form::close() }}
</div>
<div class="row-fluid">
	<div class="span12">
		<div class="box">
			<h1>User Management <button class="btn btn-primary pull-right" onclick="$('#newUserModal').modal('show');">Create User</button></h1>
			{{ ($message ? '<div class="padded">' . $message . '</div>' : '') }}
			<table class="table table-hover table-striped">
				<thead>
					<td>ID</td>
					<td>Email</td>
					<td>Role</td>
					<td>Created</td>
					<td>Updated</td>
					<td>&nbsp;</td>
				</thead>
				<tbody>
				@foreach ($users as $user)
					<tr>
						<td>{{ $user->id }}</td>
						<td>{{ $user->email }}</td>
						<td>{{ $user->global_role }}</td>
						<td><?php 
							$carbon = new Carbon($user->created_at);
							echo $carbon->diffForHumans();
						?></td>
						<td><?php 
							$carbon = new Carbon($user->updated_at);
							echo $carbon->diffForHumans();
						?></td>
						<td><button class="login-expand btn" data-id="{{ $user->id }}">More</button>
					</tr>
				@endforeach
				</tbody>
			</table>
			{{ $users->links() }}
		</div>

		<div class="box" id="user" style="display:none">
			<h1>Edit User</h1>
			<div class="padded"></div>
		</div>
	</div>
	<!--<div class="span4">
		<div class="box">
			<h1>Create New User</h1>
			{
		</div>
	</div>-->
</div>