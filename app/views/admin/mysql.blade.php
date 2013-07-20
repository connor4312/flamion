<?php use Carbon\Carbon; ?>
<div class="row-fluid">
	<div class="span8">
		<div class="box">
			<h1>User Management</h1>
			{{ ($message ? '<div class="padded">' . $message . '</div>' : '') }}
			<table class="table table-hover table-striped">
				<thead>
					<td>Server</td>
					<td>Node</td>
					<td>Address</td>
					<td>User &amp; Database</td>
					<td>Created</td>
					<td>&nbsp;</td>
				</thead>
				<tbody>
				@foreach ($mysqls as $mysql)
					<tr>
						<td>{{ $mysql->id }}</td>
						<td><a href="/admin/daemons/{{ $mysql->node_id }}/get">{{ $mysql->node_name }}</td>
						<td>{{ $mysql->address }}</td>
						<td>mc{{ $mysql->id }}</td>
						<td><?php 
							$carbon = new Carbon($mysql->created_at);
							echo $carbon->diffForHumans();
						?></td>
						<td class="text-right"><a class="btn" href="{{ URL::to('/admin/database/' . $mysql->id . '/get') }}">More</button>
					</tr>
				@endforeach
				</tbody>
			</table>
			{{ $mysqls->links() }}
		</div>
	</div>
	<div class="span4">
		<div class="box">
			<h1>Create New Setup</h1>
			{{ Form::open(array('url' => 'admin/database/add', 'method' => 'POST')) }}
			<table class="table">
				<tr>
					<td>{{ Form::label('server_id', 'Linked Server', array('class' => 'control-label')) }}</td>
					<td>{{ Form::text('server_id') }}</td>
				</tr>
				<tr>
					<td>{{ Form::label('node_id', 'Node', array('class' => 'control-label')) }}</td>
					<td>{{ Form::select('node_id', $nodes) }}</td>
				</tr>
				<tr>
					<td>{{ Form::label('password', 'Password', array('class' => 'control-label')) }}</td>
					<td>
						{{ Form::text('password') }}
						<span class="help-block">Leave blank to generate automatically.</span>
					</td>
				</tr>
				<tr>
					<td>{{ Form::label('action', 'Action', array('class' => 'control-label')) }}</td>
					<td>
						<label class="radio">{{ Form::radio('action', 'create') }} Create user on node</label>
						<label class="radio">{{ Form::radio('action', 'add') }} Do nothing</label>
						<span id="gateway"></span>
					</td>
				</tr>
				<tr>
					<td>{{ Form::label('email', 'Email User', array('class' => 'control-label')) }}</td>
					<td>
						<label class="radio">{{ Form::radio('email', 'yes') }} Yes</label>
						<label class="radio">{{ Form::radio('email', 'no') }} No</label>
						<span id="gateway"></span>
					</td>
				</tr>
				<tr>
					<td><div class="controls"></td>
					<td>{{ Form::submit('Create Setup', array('class' => 'btn btn-primary')) }}</td>
				</tr>
			</table>
			{{ Form::close() }}
		</div>
	</div>
</div>