<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| such as the size rules. Feel free to tweak each of these messages.
	|
	*/

	"accepted"         => "Tenga que aceptar el :attribute.",
	"active_url"       => "El :attribute no es una URL válida",
	"after"            => "El :attribute tiene que esta una fecha después de :date.",
	"alpha"            => "El :attribute sólo debe contener letras.",
	"alpha_dash"       => "El :attribute sólo debe contener letras, numeros, y guiones.",
	"alpha_num"        => "El :attribute sólo debe contener letras y numeros.",
	"before"           => "El :attribute tiene que esta una fecha antes de :date.",
	"between"          => array(
		"numeric" => "El :attribute debe estar entre :min - :max.",
		"file"    => "El :attribute debe estar entre :min - :max kilobytes.",
		"string"  => "El :attribute debe estar entre :min - :max letras.",
	),
	"confirmed"        => "El :attribute no coincide.",
	"date"             => "El :attribute no es una fecha correctamente.",
	"date_format"      => "El :attribute tiene que estar en el formato :format.",
	"different"        => "El :attribute y :other deben ser diferente.",
	"digits"           => "El :attribute debe ser :digits digits.",
	"digits_between"   => "El :attribute debe ser entre :min y :max letras.",
	"email"            => "El :attribute formato no es válido.",
	"exists"           => "El seleccionado :attribute no es válido.",
	"image"            => "El :attribute debe ser un imagen.",
	"in"               => "El seleccionado :attribute no es válido.",
	"integer"          => "El :attribute debe ser un numero.",
	"ip"               => "El :attribute no es un IP válido.",
	"max"              => array(
		"numeric" => "El :attribute no puede ser mayor que :max.",
		"file"    => "El :attribute no puede ser mayor que :max kilobytes.",
		"string"  => "El :attribute no puede ser mayor que :max letras.",
	),
	"mimes"            => "El :attribute debe ser a file of type: :values.",
	"min"              => array(
		"numeric" => "El :attribute debe ser at least :min.",
		"file"    => "El :attribute debe ser at least :min kilobytes.",
		"string"  => "El :attribute debe ser at least :min letras.",
	),
	"not_in"           => "El seleccionado :attribute no es válido.",
	"numeric"          => "El :attribute debe ser a number.",
	"regex"            => "El :attribute formato no es válido.",
	"required"         => "El :attribute es necesario.",
	"required_if"      => "El :attribute es necesario cuando :other is :value.",
	"required_with"    => "El :attribute es necesario cuando :values está presente.",
	"required_without" => "El :attribute es necesario cuando :values no está presente.",
	"same"             => "El :attribute and :other must match.",
	"size"             => array(
		"numeric" => "El :attribute debe ser :size.",
		"file"    => "El :attribute debe ser :size kilobytes.",
		"string"  => "El :attribute debe ser :size letras.",
	),
	"unique"           => "El :attribute ya se ha tomado.",
	"url"              => "El :attribute formato no es válido.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom' => array(),

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => array(),

);
