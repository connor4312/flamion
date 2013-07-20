<?php use Carbon\Carbon; ?>
<div class="row-fluid">
	<div class="span12">
		<div class="box">
			<h1>My Plugins</h1>

			<table class="table table-striped">
				<thead>
					<tr>
						<td>Plugin</td>
						<td>Description</td>
						<td>Info</td>
						<td>&nbsp;</td>
					</tr>
				</thead>
				<tbody>
				@foreach ($plugins as $plugin)
				<tr>
					<th>{{ $plugin->name }}</th>
					<td>{{ $plugin->description }}</td>
					<td>For {{ $plugin->cb_version }} updated <?php 
						$carbon = new Carbon($plugin->lastrelease);
						echo $carbon->diffForHumans();
					?></td>
					<td>
						<div class="btn-group">
							{{ HTML::link('/plugins/view/' . $plugin->slug, 'More', array('class' => 'btn'))}} 
							{{ HTML::link('/plugins/install/' . $plugin->slug, 'Install', array('class' => 'btn btn-primary'))}} 
						</div>
					</td>
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>