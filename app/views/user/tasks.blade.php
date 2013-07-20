<?php use Carbon\Carbon; ?>
<div class="row-fluid">
	<div class="span8 box">
		<h1>Scheduled Tasks</h1>
		{{ Form::open(array('url' => '/server/tasks/add', 'method' => 'POST')) }}
			<table class="table table-striped">
				<thead>
					<td>Command</td>
					<td>Starts</td>
					<td>Interval</td>
					<td>Active</td>
					<td>&nbsp;</td>
				</thead>
				<tbody>
					@foreach ($tasks as $task)
					<tr>
						<td>{{ $task->command }}</td>
						<td>{{ str_replace(' from now', '', Carbon::now()->addSeconds($task->increment)->diifForHumans()) }}</td>
						<td>{{ date('m-d ', strtotime($task->start)) }}</td>
						<td>{{ $task->active ? '<span class="label label-success">Active</span>' : '<span class="label">Inactive</span>' }}</td>
						<td>
							<div class="button-group">
								<a class="btn">Edit</a>
								<a class="btn">Run Now</a>
								{{ HTML::link('/server/tasks/' . $task->id . '/delete', 'Delete', 'btn btn-danger') }}
							</div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		{{ Form::close() }}
	</div>
	<div class="span4 box">
		<h1>Add New</h1>
		
		<table class="table table-striped">
			<tr>
				<td>Command</td>
				<td>{{ Form::text('command', '', array('placeholder' => 'Command'))}}</td>
			</tr>
			<tr>
				<td rowspan="3">Start In...</td>
				<td>
					<?php function numlist($to){$out=array();for($i=0;$i<$to;$i++){$out[$i+1]=$i+1;}return $out;}?>

					{{ Form::select('start_days', numlist(30), '0', array('style' => 'width:60px;padding:2px')) }} Days,
				</td>
			</tr>
			<tr>
				<td>
					{{ Form::select('start_hours', numlist(23), '0', array('style' => 'width:60px;padding:2px')) }} Hours,
				</td>
			<tr>
				<td>
					{{ Form::select('start_minutes', numlist(59), '0', array('style' => 'width:60px;padding:2px')) }} Minutes, from now
				</td>
			</tr>
			<tr>
				<td rowspan="3">Execute Every</td>
				<td>
					{{ Form::select('interval_days', numlist(30), '0', array('style' => 'width:60px;padding:2px')) }} Days,
				</td>
			<tr>
				<td>
					{{ Form::select('interval_hours', numlist(23), '0', array('style' => 'width:60px;padding:2px')) }} Hours,
				</td>
			<tr>
				<td>
					{{ Form::select('interval_minutes', numlist(59), '0', array('style' => 'width:60px;padding:2px')) }} Minutes
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>{{ Form::submit('Add Event', array('class' => 'btn btn-inverse')) }}</td>
			</td>
		</table>
	</div>
</div>