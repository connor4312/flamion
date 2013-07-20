<?php
function rowAdjust($config, $name){
	$nconfig = str_replace('.', '_', $config);
	echo '
	<tr>
		<td>' . $name . '</td>
		<td>
			<div class="input-append">
				<input type="text" id="' . $nconfig . '" name="cfg-' . $nconfig . '" value="' . Config::get($config) . '">
				<button class=".conf-update" data-id="' . $nconfig . '" value="" class="btn">Save</button>
			</div>
		</td>
		<td>
			&nbsp;
		</td>
	</tr>';
}
?>

<div class="row-fluid">

	<div class="span12">

		<ul class="nav nav-tabs" id="myTab" style="margin-top:20px">
			<li class="active"><a href="#general">General</a></li>
			<li><a href="#advanced">Advanced</a></li>
			<li><a href="#sms">SMS</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="general">
				@include('admin.options_general')
			</div>
			<div class="tab-pane" id="advanced">
				
			</div>
			<div class="tab-pane" id="sms">
			
			</div>
		</div>

	</div>
</div>