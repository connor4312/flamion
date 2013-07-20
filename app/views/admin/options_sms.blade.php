				<div class="row-fluid">
					<div class="span12 box">
						<h1>SMS Messaging</h1>
						<p class="padded">Flamion supports automated SMS messaging through two services - <a href="https://www.nexmo.com" target="_blank">Nexmo</a> and <a href="http://www.twilio.com/" target="_blank">Twilio</a>. These, queued by Flamion, send text messages to users upon them receiving a notification.
					</div>
				</div>
				{{ Form::open(array('url' => 'admin/options/config', 'method' => 'POST')) }}
				<div class="row-fluid">
					<div class="span6">
						<div class="box form-horizontal">
							<h1>What to Send</h1>
							
							<div class="control-group">
								{{ Form::label('sms_enable_user', 'Enable SMS for Users', array('class' => 'control-label')) }}
								<div class="controls">
									<label class="radio">{{
										Form::radio('flamion_sms_enable_user', 'true', Config::get('flamion.sms_enable_user') == true ? array('checked' => 'true') : array())
									}} Yes</label>
									<label class="radio">{{
										Form::radio('flamion_sms_enable_user', 'false', Config::get('flamion.sms_enable_user') == false ? array('checked' => 'true') : array())
									}} No</label>
								</div>
							</div>
							<div class="control-group">
								{{ Form::label('sms_cooldown', 'SMS Cooldown', array('class' => 'control-label')) }}
								<div class="controls">
									<div class="input-append">
										{{ Form::text('flamion_sms_cooldown', Config::get('flamion.sms_cooldown')) }}<span class="add-on">Hours</span>
									</div>
									<div><small class="muted">Min. period between texts. Leave blank or &quot;-1&quot; to enable dynamic cooldown - we will automatically base the cooldown on your maximum texts per month</small></div>
								</div>
							</div>
							
							<div class="control-group">
								{{ Form::label('sms_max', 'Max Texts per Month', array('class' => 'control-label')) }}
								<div class="controls">
									{{ Form::text('flamion_sms_max', Config::get('flamion.sms_max')) }}
								</div>
							</div>
							<div class="control-group">
								{{ Form::label('sms_level', 'SMS Log Level', array('class' => 'control-label')) }}
								<div class="controls">
									<label class="radio">{{
										Form::radio('flamion_sms_level', 'fatal', Config::get('flamion.sms_cooldown') == 'fatal' ? array('checked' => 'true') : array())
									}} Fatal - Server crashes</label>
									<label class="radio">{{
										Form::radio('flamion_sms_level', 'error', Config::get('flamion.sms_cooldown') == 'error' ? array('checked' => 'true') : array())
									}} Error - Unique warnings, lag outs</label>
									<label class="radio">{{
										Form::radio('flamion_sms_level', 'warn', Config::get('flamion.sms_cooldown') == 'warn' ? array('checked' => 'true') : array())
									}} Warning - Permissions change, world created, other administrative actions...</label>
								</div>
							</div>

							<div class="control-group">
								<div class="controls">
									{{ Form::submit('Save', array('class' => 'btn btn-inverse')) }}
								</div>
							</div>
						</div>
						<div class="box form-horizontal">
							<h1>Current Stats</h1>
							<div class="padded">
								<table class="table table-striped table-bordered">
									<thead>
										<td>SMS Messages Sent</td>
										<td>Today</td>
										<td>This Week</td>
										<td>This Month</td>
									</thead>
									<tbody>
										<td>&nbsp;</td>
										<td>{{ isset($sms_sums[0]) ? $sms_sums[0] : 0 }}</td>
										<td>{{ isset($sms_sums[6]) ? $sms_sums[6] : 0 }}</td>
										<td>{{ isset($sms_sums[29]) ? $sms_sums[29] : 0 }}</td>
									</tbody>
								</table>
								<?php
								$chart = new Chart();
								$chart->makeDataset($chart_data, 'count', 'created_at');
								echo $chart->getHTML('100%', '250px');
								?>
							</div>
						</div>
					</div>

					<div class="span6">
						<div class="box form-horizontal">
							<h1>How To Send</h1>
							
							<div class="control-group">
								{{ Form::label('sms_service', 'Service', array('class' => 'control-label')) }}
								<div class="controls">
									<div class="btn-group" data-toggle="buttons-radio">
										<button type="button" class="btn serversw{{
											Config::get('flamion.sms_service') == 'nexmo' || !Config::get('flamion.sms_service') ? ' active' : ''
										}}" data-id="nexmo">Nexmo</button>
										<button type="button" class="btn serversw{{
											Config::get('flamion.sms_service') == 'twilio' ? ' active' : ''
										}}" data-id="twilio">Twilio</button>

										{{ Form::hidden('flamion_sms_service', Config::get('flamion.sms_service')) }}
									</div>
								</div>
							</div>
							
							<div id="nexmo" class="sw"{{
											Config::get('sms_service') == 'twilio' ? '  style="display:none"' : ''
										}}>
								<div class="control-group">
									{{ Form::label('sms_nexmo_from', '&quot;From&quot; Name', array('class' => 'control-label')) }}
									<div class="controls">
										{{ Form::text('flamion_sms_nexmo_from', Config::get('flamion.sms_nexmo_from')) }}
									</div>
								</div>
								<div class="control-group">
									{{ Form::label('sms_nexmo_api_key', 'API Key', array('class' => 'control-label')) }}
									<div class="controls">
										{{ Form::text('flamion_sms_nexmo_api_key', Config::get('flamion.sms_nexmo_api_key')) }}
									</div>
								</div>
								<div class="control-group">
									{{ Form::label('sms_nexmo_api_secret', 'API Secret', array('class' => 'control-label')) }}
									<div class="controls">
										{{ Form::text('flamion_sms_nexmo_api_secret', Config::get('flamion.sms_nexmo_api_secret')) }}
									</div>
								</div>
							</div>
							<div id="twilio" class="sw"{{
											Config::get('sms_service') == 'nexmo' || !Config::get('sms_service') ? '  style="display:none"' : ''
										}}>
								<div class="control-group">
									{{ Form::label('sms_twilio_from', '&quot;From&quot; Number', array('class' => 'control-label')) }}
									<div class="controls">
										{{ Form::text('flamion_sms_twilio_from', Config::get('flamion.sms_twilio_from')) }}
									</div>
								</div>
								<div class="control-group">
									{{ Form::label('sms_twilio_sid', 'Account SID', array('class' => 'control-label')) }}
									<div class="controls">
										{{ Form::text('flamion_sms_twilio_sid', Config::get('flamion.sms_twilio_sid')) }}
									</div>
								</div>
								<div class="control-group">
									{{ Form::label('sms_twilio_token', 'Auth Token', array('class' => 'control-label')) }}
									<div class="controls">
										{{ Form::text('flamion_sms_twilio_token', Config::get('flamion.sms_twilio_token')) }}
									</div>
								</div>
							</div>

							<div class="control-group">
								<div class="controls">
									{{ Form::submit('Save', array('class' => 'btn btn-inverse')) }}
								</div>
							</div>
						</div>
						<div class="box form-horizontal">
						<h1>For Admins</h1>
							<div class="control-group">
								{{ Form::label('sms_enable_superuser', 'Enable SMS for Supersers', array('class' => 'control-label')) }}
								<div class="controls">
									<label class="radio">{{
										Form::radio('flamion_sms_enable_superuser', 'true', Config::get('flamion.sms_enable_superuser') == true ? array('checked' => 'true') : array())
									}} Yes</label>
									<label class="radio">{{
										Form::radio('flamion_sms_enable_superuser', 'false', Config::get('flamion.sms_enable_superuser') == false ? array('checked' => 'true') : array())
									}} No</label>
									<div><small class="muted">Superuser SMS sends messages to global superusers (i.e. staff) when something is wrong with a node.</small></div>
								</div>
							</div>
							<div class="control-group">
								{{ Form::label('sms_level', 'SMS Log Level', array('class' => 'control-label')) }}
								<div class="controls">
									<label class="radio">{{
										Form::radio('flamion_sms_level', 'fatal', Config::get('flamion.sms_cooldown') == 'fatal' ? array('checked' => 'true') : array())
									}} Fatal - Daemon crashes, server inaccessible...</label>
									<label class="radio">{{
										Form::radio('flamion_sms_level', 'error', Config::get('flamion.sms_cooldown') == 'error' ? array('checked' => 'true') : array())
									}} Error - If the daemon keeps running, but Minecraft servers can't start</label>
									<label class="radio">{{
										Form::radio('flamion_sms_level', 'warn', Config::get('flamion.sms_cooldown') == 'warn' ? array('checked' => 'true') : array())
									}} Warning - Generally when CPU/disk usages are too high</label>
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									{{ Form::submit('Save', array('class' => 'btn btn-inverse')) }}
								</div>
							</div>
						</div>
					</div>
				</div>
				{{ Form::close() }}
			</div>

<script type="text/javascript">
{{ $chart->getJS() }}
</script>