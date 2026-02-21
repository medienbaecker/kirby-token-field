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
					$options = $this->options;
					$query   = is_string($options) ? $options : ($options['query'] ?? null);
					$hasText  = is_array($options) && isset($options['text']);
					$hasValue = is_array($options) && isset($options['value']);

					// If user provided explicit templates, use FieldOptions as-is
					if ($hasText && $hasValue) {
						$props = FieldOptions::polyfill(['options' => $options]);
						$resolved = FieldOptions::factory($props['options'])->render($this->model());

						return array_map(fn($opt) => [
							'value'   => (string)$opt['value'],
							'text'    => $opt['text'],
							'display' => null,
						], $resolved);
					}

					// Otherwise, run query once and normalize common formats
					$result = $this->model()->query($query);

					if (is_array($result)) {
						$normalized = [];

						foreach ($result as $key => $item) {
							if (is_array($item) && isset($item['value'])) {
								$normalized[] = [
									'value'   => (string)$item['value'],
									'text'    => $item['text'] ?? $item['label'] ?? (string)$item['value'],
									'display' => null,
								];
							} elseif (is_scalar($item)) {
								$normalized[] = [
									'value'   => (string)$key,
									'text'    => (string)$item,
									'display' => null,
								];
							}
						}

						return $normalized;
					}

					// Fall back to FieldOptions for non-array results (pages, files, etc.)
					$props = FieldOptions::polyfill(['options' => $options]);
					$resolved = FieldOptions::factory($props['options'])->render($this->model());

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
