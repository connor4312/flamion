
				{{ Form::open(array('url' => 'admin/options/config', 'method' => 'POST')) }}
				<div class="fluid-row">
					<div class="span6">
						<div class="box form-horizontal">
							<h1>App Settings</h1>
							<div class="padded">
								
								<div class="control-group">
									{{ Form::label('flamion_https_enforce', 'Enforce HTTPS', array('class' => 'control-label')) }}
									<div class="controls">
										<div class="btn-group" data-toggle="buttons-radio">
											<button type="button" class="btn {{
												Config::get('flamion.https_enforce') ? ' active' : ''
											}}" data-id="true">Yes</button>
											<button type="button" class="btn {{
												!Config::get('flamion.https_enforce') ? ' active' : ''
											}}" data-id="false">No</button>

											{{ Form::hidden('flamion_https_enforce', (string) Config::get('flamion.https_enforce')) }}
										</div>
										<div><small class="muted">Note: only works if Debug Mode is not active</small></div>
									</div>
								</div>
								<div class="control-group">
									{{ Form::label('app_url', 'Application URL', array('class' => 'control-label')) }}
									<div class="controls">
										{{ Form::text('app_url', Config::get('app.url')) }}
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
				</div>
				{{ Form::close() }}