<?php use Carbon\Carbon; ?>
<div class="row-fluid">
	<div class="span8">
		<div class="box">
			<h1>Console Viewer</h1>
			@if ($perm->can('console.view') == 'yes')
			<div id="console" data-id="{{ $server->id }}"></div>
			@endif
			@if ($perm->can('console.issuecommand') == 'yes')
			<div class="form-inline">
				<div class="input-append fluid-row padded" style="width:100%">
					{{ Form::text('command', '', array('class' => 'appendedInputButton span9', 'id' => 'command')) }}
					{{ Form::submit('Send', array('class' => 'btn btn-inverse span3', 'id' => 'issuecommand')) }}
				</div>
			</div>
			@endif
		</div>
	</div>
	<div class="span4">
		<div class="box">
			<h1>Console Control</h1>
			<div class="btn-group padded">
				<button class="btn" id="pause">Pause</button>
				<button class="btn" id="clear">Clear</button>
				@if ($perm->can('console.clear') == 'yes')
				<button class="btn btn-danger" id="delete">Delete Logs</button>
				@endif
			</div>
		</div>
		@if ($perm->can('console.task.view') == 'yes')
		<div class="box">
			<h1>Scheduled Tasks</h1>
			<table class="table table-striped">
				<thead>
					<td>Command</td>
					<td>Every</td>
				</thead>
				<tbody>
				@foreach ($tasks as $task)
				<tr>
					<td>{{ $task->command }}</td>
					<td>{{ str_replace(' from now', '', Carbon::now()->addSeconds($task->increment)->diifForHumans()) }}</td>
				</tr>
				@endforeach
				@if (!count($tasks))
				<tr>
					<td colspan="2"><em class="muted">No Tasks Added Yet</em></td>
				</tr>
				@endif
				</tbody>
			</table>
			@if ($perm->can('console.task.manage') == 'yes')
			{{ HTML::link('/server/tasks', 'Manage Tasks', array('class' => 'smallblock')) }}
			@endif
		</div>
		@endif
	</div>
</div>