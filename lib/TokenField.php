<?php

namespace Medienbaecker\TokenField;

use Kirby\Cms\Block;
use Kirby\Cms\File;
use Kirby\Cms\Page;
use Kirby\Cms\StructureObject;
use Kirby\Cms\User;
use Kirby\Form\Field\OptionField;
use Kirby\Form\FieldOptions;
use Kirby\Reflection\Attributes\Prop;
use Kirby\Toolkit\Obj;
use Kirby\Toolkit\Str;

class TokenField extends OptionField
{
	#[Prop]
	protected string $preview = 'color';

	#[Prop]
	protected string|null $template = null;

	public function default(): mixed
	{
		if ($this->default === true) {
			return $this->options()[0]['value'] ?? null;
		}

		return parent::default();
	}

	public function preview(): string
	{
		return $this->preview;
	}

	public function previewText(): string
	{
		$key = match ($this->preview) {
			'font-family' => 'font-family.text',
			'font-size'   => 'font-size.text',
			default       => null
		};

		if ($key === null) {
			return '';
		}

		return $this->kirby()->option('medienbaecker.token-field.' . $key, '');
	}

	public function props(): array
	{
		return [
			...parent::props(),
			'previewText' => $this->previewText()
		];
	}

	public function template(): string|null
	{
		return $this->template;
	}

	protected function fetchOptions(): array
	{
		$options = $this->isDynamic() ? $this->resolveDynamic() : $this->resolveStatic();

		return $this->applyTemplate($options);
	}

	protected function applyTemplate(array $options): array
	{
		if ($this->template === null) {
			return $options;
		}

		foreach ($options as &$option) {
			if ($option['display'] === null) {
				$option['display'] = Str::template($this->template, ['value' => $option['value']]);
			}
		}

		return $options;
	}

	protected function isDynamic(): bool
	{
		return is_string($this->options) || isset($this->options['type']);
	}

	protected function resolveDynamic(): array
	{
		$options  = $this->options;
		$query    = is_string($options) ? $options : ($options['query'] ?? null);
		$hasText  = is_array($options) && isset($options['text']);
		$hasValue = is_array($options) && isset($options['value']);
		$model    = $this->model();

		// Kirby's OptionsQuery has no extension point for extra templates,
		// so explicit text/value templates are resolved here to also apply
		// an optional `display` template per item
		if ($hasText && $hasValue) {
			$display = $options['display'] ?? null;
			$result  = [];

			foreach ($model->query($query) as $item) {
				// Mirrors Kirby\Option\OptionsQuery::itemToDefaults
				$alias = match (true) {
					$item instanceof Page            => 'page',
					$item instanceof File            => 'file',
					$item instanceof User            => 'user',
					$item instanceof Block           => 'block',
					$item instanceof StructureObject => 'structureItem',
					is_array($item), $item instanceof Obj => 'arrayItem',
					default                          => 'item',
				};
				$data = [$alias => $item, 'item' => $item];

				$result[] = [
					'value'   => (string)$model->toString($options['value'], $data),
					'text'    => $model->toSafeString($options['text'], $data),
					'display' => match (true) {
						$display === null  => null,
						is_array($display) => array_map(
							fn ($template) => $model->toSafeString($template, $data),
							$display
						),
						default            => $model->toSafeString($display, $data),
					},
				];
			}

			return $result;
		}

		$result = $model->query($query);

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

		$props    = FieldOptions::polyfill(['options' => $options]);
		$resolved = FieldOptions::factory($props['options'])->render($model);

		return array_map(fn ($option) => [
			'value'   => (string)$option['value'],
			'text'    => $option['text'],
			'display' => null,
		], $resolved);
	}

	protected function resolveStatic(): array
	{
		$result = [];

		foreach ($this->options ?? [] as $key => $option) {
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
	}
}
