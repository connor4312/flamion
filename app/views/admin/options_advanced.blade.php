
				{{ Form::open(array('url' => 'admin/options/config', 'method' => 'POST')) }}
				<div class="fluid-row">
					<div class="span6">
						<div class="box form-horizontal">
							<h1>App Settings</h1>
							<div class="padded">
								
								<div class="control-group">
									{{ Form::label('app_debug', 'App Mode', array('class' => 'control-label')) }}
									<div class="controls">
										<div class="btn-group" data-toggle="buttons-radio">
											<button type="button" class="btn {{
												Config::get('app.debug') ? ' active' : ''
											}}" data-id="true">Debug</button>
											<button type="button" class="btn {{
												!Config::get('app.debug') ? ' active' : ''
											}}" data-id="false">Production</button>

											{{ Form::hidden('app_debug', (string) Config::get('app.debug')) }}
										</div>
									</div>
								</div>
								<div class="control-group">
									{{ Form::label('app_timezone', 'Timezone', array('class' => 'control-label')) }}
									<div class="controls">
										{{ Form::text('app_timezone', Config::get('app.timezone')) }}
									</div>
								</div>
								<div class="control-group">
									{{ Form::label('session_lifetime', 'Session Lifetime', array('class' => 'control-label')) }}
									<div class="controls">
										<div class="input-append">
											{{ Form::text('session_lifetime', Config::get('session.lifetime'), array('class' => 'appendedInput')) }}
											<span class="add-on">Minutes</span>
										</div>
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										{{ Form::submit('Save', array('class' => 'btn btn-inverse')) }}
									</div>
								</div>
							</div>
						</div>
						<div class="box form-horizontal">
							<h1>CSS Control</h1>
							<div class="padded">
								<p>CSS can be changed in more depth by editing the files in <code>/app/storage/less/dashboard.less</code>, in <a href="http://lesscss.org/">LESS</a> syntax. 
								<div class="control-group">
									{{ Form::label('color', 'Primary Color', array('class' => 'control-label')) }}
									<div class="controls">
										{{ Form::text('flamion_color', Config::get('flamion.color')) }}
									</div>
								</div>
								<div class="control-group">
									{{ Form::label('primary_font', 'Font Family', array('class' => 'control-label')) }}
									<div class="controls">
										{{ Form::text('flamion_primary_font', Config::get('flamion.primary_font')) }}
									</div>
								</div>
								<div class="control-group">
									{{ Form::label('console_font', 'Console Font Family', array('class' => 'control-label')) }}
									<div class="controls">
										{{ Form::text('flamion_console_font', Config::get('flamion.console_font')) }}
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										{{ Form::submit('Save', array('class' => 'btn btn-inverse')) }}
										{{ HTML::link('/admin/options/buildless', 'Compile LESS', array('class' => 'btn btn-primary', 'id' => 'lessbuild', 'data-loading-text' => 'Building...')) }}
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="span6">
						<div class="box form-horizontal">
							<h1>Mail Settings</h1>
					
							<div class="control-group">
								{{ Form::label('mail_driver', 'Mail Driver', array('class' => 'control-label')) }}

								<div class="controls">
									<div class="btn-group" data-toggle="buttons-radio">
										<button type="button" data-id="smtp" class="serversw btn {{
											Config::get('mail.driver') == 'smtp' ? ' active' : ''
										}}" data-id="smtp">SMTP</button>
										<button type="button" data-id="mail" class="serversw btn {{
											Config::get('mail.driver') == 'mail' ? ' active' : ''
										}}" data-id="mail">PHP Mail</button>

										{{ Form::hidden('mail_driver', (string) Config::get('mail.driver')) }}
									</div>
								</div>
							</div>
							<div class="sw" id="smtp" style="{{
											Config::get('mail.driver') != 'smtp' ? ' display:none' : ''
										}}">
								<div class="control-group">
									{{ Form::label('mail_host', 'SMTP Host', array('class' => 'control-label')) }}
									<div class="controls">
										{{ Form::text('mail_host', Config::get('mail.host')) }}
									</div>
								</div>
								<div class="control-group">
									{{ Form::label('mail_port', 'SMTP Port', array('class' => 'control-label')) }}
									<div class="controls">
										{{ Form::text('mail_port', Config::get('mail.port')) }}
									</div>
								</div>
								<div class="control-group">
									{{ Form::label('mail_encryption', 'Encryption', array('class' => 'control-label')) }}
									<div class="controls">
										{{ Form::text('mail_encryption', Config::get('mail.encryption')) }}
									</div>
								</div>
								<div class="control-group">
									{{ Form::label('mail_username', 'Username', array('class' => 'control-label')) }}
									<div class="controls">
										{{ Form::text('mail_username', Config::get('mail.username')) }}
									</div>
								</div>
								<div class="control-group">
									{{ Form::label('mail_password', 'Password', array('class' => 'control-label')) }}
									<div class="controls">
										{{ Form::text('mail_password', Config::get('mail.password')) }}
									</div>
								</div>
							</div>
							
							<div class="control-group">
								<div class="controls">
									{{ Form::submit('Save', array('class' => 'btn btn-inverse')) }}
									{{ HTML::link('/admin/options/testmail', 'Send Test Email', array('class' => 'btn btn-primary', 'id' => 'testmail', 'data-loading-text' => 'Trying to Send...')) }}
								</div>
							</div>
							<div class="text-center"><small class="muted">The test email will be sent to {{ Auth::user()->email }}</small></div>
						</div>
					</div>
				</div>
				{{ Form::close() }}