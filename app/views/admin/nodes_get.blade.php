<?php use Carbon\Carbon; ?>
@if ($wrap)
<div class="row-fluid">
	<div class="span12">
		<div class="box">
		<h1>View Server {{ $nodedata->name }}</h1>
@endif
		
		<div id="map-canvas">
			{{ $nodedata->xloc }},{{ $nodedata->yloc }}
		</div>
		<div class="padded">
		<div class="row-fluid">
			<div class="span4">
				<h4>Server Data</h4>
				<table class="table table-striped table-bordered">
					<tr>
						<td>ID</td>
						<td>{{ $nodedata->id }}</td>
					</tr>
					<tr>
						<td>Address</td>
						<td class="gridedit" id="address-{{ $nodedata->id }}">{{ $nodedata->address }}</td>
					</tr>
					<tr>
						<td>Disk MB</td>
						<td class="gridedit" id="disk_space-{{ $nodedata->id }}">{{ $nodedata->disk_space }}</td>
					</tr>
					<tr>
						<td>CPU Speed</td>
						<td class="gridedit" id="cpu_speed-{{ $nodedata->id }}">{{ $nodedata->cpu_speed }}</td>
					</tr>
					<tr>
						<td>CPU Cores</td>
						<td class="gridedit" id="cpu_cores-{{ $nodedata->id }}">{{ $nodedata->cpu_cores }}</td>
					</tr>
					<tr>
						<td>Location</td>
						<td>{{ $nodedata->location }}</td>
					</tr>
					<tr>
						<td>SSH Password</td>
						<td class="gridedit" id="ssh_password-{{ $nodedata->id }}">{{ Crypt::decrypt($nodedata->ssh_password) }}</td>
					</tr>
				</table>

			</div>
			<div class="span8">
				<h4>Uptime</h4>
			</div>
		</div>

		</div>
@if ($wrap)
		</div>
	</div>
</div>
@endif