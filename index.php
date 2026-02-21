<?php

use Kirby\Cms\App as Kirby;
use Kirby\Field\FieldOptions;
use Kirby\Toolkit\Str;

Kirby::plugin('medienbaecker/token-field', [
	'options' => [
		'font-family.text' => 'Lorem Ipsum',
		'font-size.text'   => 'Aa',
	],
	'fields' => [
		'token' => [
			'props' => [
				'options' => function ($options = []) {
					return $options;
				},
				'preview' => function (string $preview = 'color'): string {
					return $preview;
				},
				'template' => function (string|null $template = null): string|null {
					return $template;
				},
			],
			'computed' => [
				'options' => function (): array {
					if ($this->isDynamic()) {
						$options = $this->resolveDynamic();
					} else {
						$options = $this->resolveStatic();
					}

					return $this->applyTemplate($options);
				},
				'previewText' => function (): string {
					$map = [
						'font-family' => 'font-family.text',
						'font-size'   => 'font-size.text',
					];
					$key = $map[$this->preview] ?? null;
					if ($key === null) {
						return '';
					}
					return option('medienbaecker.token-field.' . $key);
				},
			],
			'methods' => [
				'isDynamic' => function (): bool {
					return is_string($this->options)
						|| isset($this->options['type']);
				},
				'resolveDynamic' => function (): array {
					$props = FieldOptions::polyfill([
						'options' => $this->options,
					]);
					$options = FieldOptions::factory($props['options']);
					$resolved = $options->render($this->model());

					return array_map(fn($opt) => [
						'value'   => (string)$opt['value'],
						'text'    => $opt['text'],
						'display' => null,
					], $resolved);
				},
				'resolveStatic' => function (): array {
					$result = [];

					foreach ($this->options as $key => $option) {
						if (is_string($option)) {
							$result[] = [
								'value'   => (string)$key,
								'text'    => $option,
								'display' => null,
							];
						} elseif (is_array($option)) {
							$result[] = [
								'value'   => (string)$key,
								'text'    => $option['label'] ?? $option['text'] ?? (string)$key,
								'display' => $option['display'] ?? null,
							];
						}
					}

					return $result;
				},
				'applyTemplate' => function (array $options): array {
					if ($this->template === null) {
						return $options;
					}

					foreach ($options as &$option) {
						if ($option['display'] === null) {
							$option['display'] = Str::template($this->template, ['value' => $option['value']]);
						}
					}

					return $options;
				},
				'emptyValue' => function () {
					return '';
				}
			],
		]
	]
]);
