<?php use Carbon\Carbon; ?>
<div class="fluid-row">
	<div class="span8">
		<div class="box">
			<h1>Server Status</h1>
			<table class="table">
				<tr>
					<td><canvas id="serverstat" class="checking" data-id="{{ $server->id }}" width="20" height="20"></canvas></td>
					<td>
						<div class="button-group">
							@if ($perm->can('server.start') == 'yes')
							<a href="{{ URL::to('/api/private') }}" class="serverbtn btn" data-action="start" data-id="{{ $server->id }}">Start</a>
							@endif
							@if ($perm->can('server.restart') == 'yes')
							<a href="{{ URL::to('/api/private') }}" class="serverbtn btn" data-action="restart" data-id="{{ $server->id }}">Restart</a>
							@endif
							@if ($perm->can('server.stop') == 'yes')
							<a href="{{ URL::to('/api/private') }}" class="serverbtn btn" data-action="stop" data-id="{{ $server->id }}">Stop</a>
							@endif
						</div>
					</td>
				</tr>
			</table>
		</div>
		<div class="box">
			<div class="fluid-row">
				{{ Form::open(array('url' => '/dashboard', 'method' => 'POST')) }}

					<?php if (isset($error)) { echo '<div class="alert">' . $error . '</div>'; }?>

					<table class="table table-striped">
						@if ($perm->can('server.edit.name') == 'yes')
						<tr>
							<td>{{ Form::label('name', 'Server Name') }}</td>
							<td>{{ Form::text('name', $server->name) }}</td>
						</tr>
						@endif
						@if ($perm->can('server.edit.jar') == 'yes')
						<tr>
							<td>{{ Form::label('jar', 'Jar File') }}</td>
							<td>
								<div class="btn-group" data-toggle="buttons-radio">
									<button type="button" class="serversw btn {{
										is_numeric($server->jar) ? ' active' : ''
									}}" data-id="false">Reactor Channel</button>
									<button type="button" class="serversw btn {{
										!is_numeric($server->jar) ? ' active' : ''
									}}" data-id="true">Custom</button>
									
									{{ Form::hidden('jar_custom', is_numeric($server->jar) ? 'false' : 'true') }}
								</div>
							</td>
						</tr>
						<tr class="sw" id="false" style="{{
								!is_numeric($server->jar) ? 'display:none' : ''
							}}">
							<td>{{ Form::label('jar_channel', 'Jar Channel') }}</td>
							<td>{{ Form::text('jar_channel', $server->jar) }}</td>
						</tr>
						<tr class="sw" id="true" style="{{
								is_numeric($server->jar) ? 'display:none' : ''
							}}">
							<td>{{ Form::label('jar_name', 'Jar Name') }}</td>
							<td>{{ Form::text('jar_name', $server->jar) }}
								<span class="help-block">Exact name of the jar file in your server base directory.</span>
							</td>
						</tr>
						@endif
						@if ($perm->can('server.edit.slot') == 'yes')
						<tr>
							<td>{{ Form::label('slots', 'Server Slots') }}</td>
							<td>{{ Form::text('slots', $server->slots) }}</td>
						</tr>
						@endif
						@if (Auth::user()->global_role == 'superuser')
						<tr>
							<td>{{ Form::label('ip', 'Server IP') }}</td>
							<td>{{ Form::text('ip', $server->ip) }}</td>
						</tr>
						<tr>
							<td>{{ Form::label('port', 'Server Port') }}</td>
							<td>{{ Form::text('port', $server->port) }}</td>
						</tr>
						<tr>
							<td>Created</td>
							<td><?php 
								$carbon = new Carbon($server->created_at);
								echo $carbon->diffForHumans();
							?></td>
						</tr>
						<tr>
							<td>Modified</td>
							<td><?php 
								$carbon = new Carbon($server->updated_at);
								echo $carbon->diffForHumans();
							?></td>
						</tr>
						@endif
						<tr>
							<td>Server History</td>
							<td>{{ HTML::link('/server/log', 'Log History')}}</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								{{ Form::submit('Save', array('class' => 'btn btn-inverse')) }}
								<span class="help-block">Note: some changes may require a server restart to take effect.</span>
							</tr>
						</tr>
					</table>
				{{ Form::close() }}
			</div>
		</div>
	</div>
	<div class="span4 box">
		<h1>Resource Usage</h1>
		<div class="fluid-row">
			<div class="span4 usage" style="color:rgb(32, 109, 250)">
				<div class="ticks"><span style="height:30%"></span></div>
				<em>CPU</em>
				<b>30%</b>
			</div>
			<div class="span4 usage" style="color:rgb(250, 179, 32)">
				<div class="ticks"><span style="height:74%"></span></div>
				<em>RAM</em>
				<b>2.4GB</b>
			</div>
			<div class="span4 usage" style="color:rgb(250, 32, 135)">
				<div class="ticks"><span style="height:50%"></span></div>
				<em>Players</em>
				<b>12/24</b>
			</div>
		</div>
		<h4 class="padded" style="clear:both">Historical View</h4>
		<?php

		function fooArray() {
			$out = array();
			$ttime = time() - (60 * 60 * 12);
			while ($ttime < time()) {

				$out[] = array(
					'time' => date('H:i', $ttime),
					'val' => round(rand(0, 100)),
				);

				$ttime += 60 * 60;

			}
			return $out;
		}

		$chart = new Chart();


		$ds = $chart->makeDataset(fooArray(), 'val', 'time');
		$chart->setDataOpt($ds, array(
			'fillColor' => 'rgba(32, 109, 250, 0.5)',
			'strokeColor' => 'rgba(32, 109, 250, 1)',
			'pointColor' => 'rgba(32, 109, 250, 1)'
		));


		$ds = $chart->makeDataset(fooArray(), 'val', 'time');
		$chart->setDataOpt($ds, array(
			'fillColor' => 'rgba(250, 179, 32, 0.5)',
			'strokeColor' => 'rgba(250, 179, 32, 1)',
			'pointColor' => 'rgba(250, 179, 32, 1)'
		));


		$ds = $chart->makeDataset(fooArray(), 'val', 'time');
		$chart->setDataOpt($ds, array(
			'fillColor' => 'rgba(250, 32, 135, 0.5)',
			'strokeColor' => 'rgba(250, 32, 135, 1)',
			'pointColor' => 'rgba(250, 32, 135, 1)'
		));

		echo $chart->
			setChartOpt(array(
				'scaleOverride' => true,
				'scaleSteps' => 4,
				'scaleStepWidth' => 25,
				'scaleLabel' => '<%=value%>%'
			))->
			getHTML('100%', '300px');
		?>
		{{ HTML::link('/server/resources', 'More Details', array('class' => 'smallblock'))}}
	</div>
</div>

<script type="text/javascript">
{{ $chart->getJS() }}
</script>