<?php

use Kirby\Cms\App as Kirby;
use Kirby\Toolkit\Str;

Kirby::plugin('medienbaecker/token-field', [
	'options' => [
		'font-family.text' => 'Lorem Ipsum',
		'font-size.text'   => 'Aa',
	],
	'fields' => [
		'token' => [
			'props' => [
				'options' => function (array $options = []): array {
					return $options;
				},
				'preview' => function (string $preview = 'color'): string {
					return $preview;
				},
				'query' => function (string|null $query = null): string|null {
					return $query;
				},
			],
			'computed' => [
				'options' => function (): array {
					$result = [];

					foreach ($this->options as $key => $option) {
						if (is_string($option)) {
							$display = null;
							if ($this->query !== null) {
								$display = Str::template($this->query, ['value' => (string)$key]);
							}
							$result[] = [
								'value'   => (string)$key,
								'text'    => $option,
								'display' => $display,
							];
						} elseif (is_array($option)) {
							$display = $option['display'] ?? null;
							if ($display === null && $this->query !== null) {
								$display = Str::template($this->query, ['value' => (string)$key]);
							}
							$result[] = [
								'value'   => (string)$key,
								'text'    => $option['label'] ?? $option['text'] ?? (string)$key,
								'display' => $display,
							];
						}
					}

					return $result;
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
				'emptyValue' => function () {
					return '';
				}
			],
		]
	]
]);
