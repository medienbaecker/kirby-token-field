<?php

use Kirby\Cms\App as Kirby;
use Medienbaecker\TokenField\TokenField;

load([
	TokenField::class => 'lib/TokenField.php'
], __DIR__);

Kirby::plugin('medienbaecker/token-field', [
	'options' => [
		'font-family.text' => 'Lorem Ipsum',
		'font-size.text'   => 'Aa',
	],
	'fields' => [
		'token' => TokenField::class
	]
]);
