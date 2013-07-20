Hello!

You've requested a password reset. Your new generated password is <b>{{ $password }}</b>, you can use that to {{ HTML::link('/login', 'Login') }} when ready.

{{ Config::get('aksel.email-closure') }}