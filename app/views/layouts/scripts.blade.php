	{{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js') }}
	{{ HTML::script('//use.typekit.net/eun5hjs.js') }}
	{{ HTML::script('js/jquery.common.js') }}
	{{ HTML::script('js/bootstrap.min.js') }}
	<script type="text/javascript">try{Typekit.load();}catch(e){} window.baseurl = '{{ URL::to('/') }}';</script>
	<?php if (isset($footer_scripts)) {
		foreach ($footer_scripts as $script) {
			echo HTML::script($script) . "\n";
		}
	}
	?>