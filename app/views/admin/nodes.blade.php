<?php use Carbon\Carbon; ?>
<div class="row-fluid">
	<div class="span8">
		<div class="box">
			<h1>Nodes</h1>
			{{ ($message ? '<div class="padded">' . $message . '</div>' : '') }}
			<table class="table table-hover table-striped">
				<thead>
					<td>Name</td>
					<td>Hostname</td>
					<td>Location</td>
					<td>&nbsp;</td>
				</thead>
				<tbody>
				@foreach ($nodes as $node)
					<tr>
						<td>{{ $node->name }}</td>
						<td>{{ $node->address }}</td>
						<td>{{ $node->location }}</td>
						<td class="text-right"><button class="node-expand btn" data-id="{{ $node->id }}">More</button>
					</tr>
				@endforeach
				</tbody>
			</table>
			{{ $nodes->links() }}
		</div>

		<div class="box" id="node" style="display:none">
			<h1>View Node</h1>
			<div id="nodein"></div>
		</div>
	</div>
	<div class="span4">
		<div class="box">
			<h1>Create New Node</h1>
			{{ Form::open(array('url' => 'admin/daemons/add', 'method' => 'POST')) }}
			<table class="table">
				<tr>
					<td>{{ Form::label('name', 'Name', array('class' => 'control-label')) }}</td>
					<td>{{ Form::text('name') }}</td>
				</tr>
				<tr>
					<td>{{ Form::label('hostname', 'Hostname', array('class' => 'control-label')) }}</td>
					<td>{{ Form::text('hostname') }}</td>
				</tr>
				<tr>
					<td>{{ Form::label('disk_space', 'Disk Space', array('class' => 'control-label')) }}</td>
					<td class="input-append">{{ Form::text('disk_space', null, array('class' => 'appendedInput')) }}<span class="add-on">GB</span></td>
				</tr>
				<tr>
					<td>{{ Form::label('cpu_speed', 'CPU Speed', array('class' => 'control-label')) }}</td>
					<td class="input-append">{{ Form::text('cpu_speed', null, array('class' => 'appendedInput')) }}<span class="add-on">GHz</span></td>
				</tr>
				<tr>
					<td>{{ Form::label('cpu_cores', 'CPU Cores', array('class' => 'control-label')) }}</td>
					<td>{{ Form::text('cpu_cores') }}</td>
				</tr>
				<tr>
					<td>{{ Form::label('location', 'Location', array('class' => 'control-label')) }}</td>
					<td>{{ Form::text('location') }}</td>
				</tr>
				<tr>
					<td>{{ Form::label('ssh-password', 'SSH Password', array('class' => 'control-label')) }}</td>
					<td>{{ Form::text('ssh-password') }}</td>
				</tr>
				<tr>
					<td></td>
					<td><div id="gateway"></div>{{ Form::submit('Add Node', array('class' => 'btn btn-primary', 'id' => 'newnode')) }}</td>
				</tr>
			</table>
			{{ Form::close() }}
		</div>
	</div>
</div>