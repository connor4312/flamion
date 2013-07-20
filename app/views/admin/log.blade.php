<?php use Carbon\Carbon; ?>
<div class="row-fluid">
	<div class="span4">
		<div class="box">
			<h1>Search</h1>
			
			<div class="padded">
				{{ Form::open(array('url' => URL::current(), 'method' => 'get', 'class' => 'form-inline')) }}
				<div class="input-append">
					{{ Form::text('query', '', array('class' => 'appendedInputButton')) }}
					{{ Form::submit('Search', array('class' => 'btn')) }}
				</div>
				<table width="100%">
					<tr>
						<td><label class="radio">{{ Form::radio('type', 'user') }} Find by user</label></td>
						<td><label class="radio">{{ Form::radio('type', 'action') }} Find by action</label>&nbsp;</td>
					</tr>
					<tr>
						<td><label class="radio">{{ Form::radio('type', 'time') }} Find by date/time</label>&nbsp;</td>
						<td><label class="radio">{{ Form::radio('type', 'ip') }} Find by IP</label></td>
						<td></td>
					</tr>
				</table>
				{{ Form::close() }}
			</div>
		</div>
		<div class="box">
			<h1>Action Info</h1>
			<a class="smallblock" href="{{ URL::to('/admin/log/view/' . implode('/', array_slice($parts, 0, -1))) }}">Up a Level</a>
			<a class="smallblock" href="{{ URL::to('/admin/log') }}">Back to Index</a>
			<table class="table">
				<thead>
					<td>Action</td>
					<td>&nbsp;</td>
				</thead>
				<tbody>
				@for ($i=0; $i < count($descriptions); $i++)
					@if ($descriptions[$i]->action == $path)
					<tr style="background:#eee;color:#000">
						<td>{{ $descriptions[$i]->action }}</td>
						<td>{{ $descriptions[$i]->info }}</td>
					</tr>
					@else
					<tr>
						<td>{{ $descriptions[$i]->action }}</td>
						<td class="text-right">
							<a href="{{ URL::to('/admin/log/view/' . str_replace('.', '/', $descriptions[$i]->action)) }}" class="icon-arrow-right faded"></a></td>
					</tr>
					@endif
				@endfor
				</tbody>
			</table>
		</div>
	</div>
	<div class="span8">
		<div class="box">
			<h1>Action History in {{ $path ? $path : 'Overall' }}</h1>
			{{ $err ? '<div class="alert">' . $err . '</div>' : ''}}
			
			<?php
			$chart = new Chart();
			$chart->makeDataset($chart_data, 'count', 'created_at');
			echo $chart->getHTML('100%', '250px');
			?>

			@if (count($logs))
			<table class="table">
				<thead>
					<td>User</td>
					<td>IP</td>
					<td>Action</td>
					<td>Message</td>
					<td>Date</td>
					<td>Level</td>
				</thead>
				<tbody>
				@foreach ($logs as $log)
					<tr>
						<td><a href="{{ URL::to('/admin/users/' . $log->id .'/get') }}"> {{ $log->email }}</a></td>
						<td><a href="{{ URL::to('/admin/log?type=ip&query=' . $log->ip) }}">{{ $log->ip }}</a></td>
						<td><?php
								$parts = explode('.', $log->action);
								$thread = '';
								foreach ($parts as $part) {
									$thread .= $part . '/';
									echo '&gt; <a href="' . URL::to('/admin/log/view/' . $thread) . '">' . ucwords($part) .'</a> ';
								}
							?></td>
						<td><?php
							if (preg_match('/(user |admin )(\d+)/', $log->message, $m))
								echo str_replace($m[0], '<a href="'. URL::to('/admin/log?type=ip&query=' . $m[2]) .'">' . $m[0] . '</a>', $log->message);
							else
								$log->message;
						?></td>
						<td><?php 
								$carbon = new Carbon($log->created_at);
								echo $carbon->diffForHumans();
							?></td>
						<td><span class="label<?php
							switch ($log->level) {
								case 'error': echo ' label-warning'; break;
								case 'fatal': echo ' label-important'; break;
								case 'warn': echo ' label-info'; break;
							}
						?>">{{ $log->level }} </td>
					</tr>
				@endforeach
				</tbody>
			</table>
			@else
			<div class="padded">
				<div class="alert alert-info">
					<b>No logs found</b>. Whatever you searched for does not exist!
				</div>
			</div>
			@endif
			{{ $logs->links() }}
		</div>
	</div>
</div>

<script type="text/javascript">
{{ $chart->getJS() }}
</script>