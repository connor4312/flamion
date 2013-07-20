<?php use Carbon\Carbon; ?>
@if ($wrap)
<div class="row-fluid">
	<div class="span12">
		<div class="box">
		<h1>View User {{ $userdata->email }}</h1>
		<div class="padded">
@endif

		<div class="row-fluid">
			<div class="span4">
				<h4>User Data</h4>
				<table class="table table-striped table-bordered">
					<tr>
						<td>ID</td>
						<td>{{ $userdata->id }}</td>
					</tr>
					<tr>
						<td>Email</td>
						<td class="gridedit" id="email-{{ $userdata->id }}">{{ $userdata->email }}</td>
					</tr>
					<tr>
						<td rowspan="2">Password</td>
						<td class="gridedit" id="password-{{ $userdata->id }}">Click to Change</td>
					</tr>
					<tr>
						<td><a href="{{ URL::to('/admin/users/' . $userdata->id .'/remind') }}">Send Reminder</td>
					</tr>
					<tr>
						<td>Role</td>
						<td class="gridedit" id="global_role-{{ $userdata->id }}">{{ $userdata->global_role }}</td>
					</tr>
					<tr>
						<td>Created</td>
						<td><?php 
							$carbon = new Carbon($userdata->created_at);
							echo $carbon->diffForHumans();
						?></td>
					</tr>
				</table>

				<h4>Servers</h4>
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<td>Name</td>
					</thead>
				@foreach ($servers as $server)
					<tr class="clickable" data-url="{{ URL::to('/server/' . $server->id . '/permissions') }}">
						<td>{{ $server->name }}</td>
					</tr>
				@endforeach
				</table>
			</div>
			<div class="span8">
				<h4>Activity</h4>
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<td>Date</td>
						<td>&nbsp;</td>
						<td>Action</td>
						<td>Message</td>
					</thead>
				@foreach ($logs as $log)
					<tr class="clickable">
						<td><?php 
							$carbon = new Carbon($log->created_at);
							echo $carbon->diffForHumans();
						?></td>
						<td>{{ $carbon->format('j M, H:i') }}</td>
						<td><?php
							$parts = explode('.', $log->action);
							$thread = '';
							foreach ($parts as $part) {
								$thread .= $part . '/';
								echo '&gt; <a href="' . URL::to('/admin/logs/view/' . $thread) . '">' . ucwords($part) .'</a> ';
							}
						?></td>
						<td>{{ $log->message }}</td>
					</tr>
				@endforeach
				</table>
			</div>
		</div>

@if ($wrap)
		</div>
		</div>
	</div>
</div>
@endif