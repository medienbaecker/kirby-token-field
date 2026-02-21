# Kirby Token Field

Kirby's [color field](https://getkirby.com/docs/reference/panel/fields/color) is great for visually selecting colours but I've wanted to save `color-primary` instead of `#d74894` since forever.

<img width="1038" height="934" alt="Screenshot of the token field with previews for colors, fonts, shadows, radii, spacings and font sizes" src="https://github.com/user-attachments/assets/977fa359-8973-46c7-a360-4741cc6b543c" />

This plugin adds a `token` field type that lets you select from visual options while saving whatever you want. It can not only be used for colours but also fonts, spacing values, shadows, or any other design token. So far I've built previews for colours, font families, font sizes, spacing, shadows, border radii, and plain text.

## Installation

```bash
composer require medienbaecker/kirby-token-field
```

> [!TIP]
> You can also install this plugin manually by downloading the repository and placing it in `site/plugins/token-field`.

## Usage

```yaml
my_color:
  label: Colour
  type: token
  query: "var(--{{ value }})"
  options:
    color-red: Red
    color-blue: Blue
    color-green: Green
```

The option key (e.g. `color-red`) is what gets saved to your content file. The `query` option wraps the value for the visual preview — so `var(--color-red)` is used as the CSS colour for the swatch, as long as `--color-red` is defined in your `panel.css`.

### Display

By default, the option key is used for the visual preview, too. Use `display` to decouple the preview from the saved value:

```yaml
my_theme:
  label: Theme
  type: token
  options:
    sunrise:
      label: Sunrise
      display: "#ff6b35"
    ocean:
      label: Ocean
      display: "#006994"
```

You can even display multiple values, for example for things like background and foreground colour combinations:

```yaml
my_theme:
  label: Theme
  type: token
  options:
    sunrise:
      label: Sunrise
      display:
        - "#ff6b35"
        - "#ffa07a"
    ocean:
      label: Ocean
      display:
        - "#006994"
        - "#40e0d0"
```

## Preview Types

Set the `preview` option to change how options are displayed. Default is `color`.

### `color`

Colour swatches with checkerboard background for transparency.

```yaml
type: token
preview: color
query: "var(--{{ value }})"
options:
  color-primary: Primary
  color-secondary: Secondary
```

### `font-family`

Renders "Lorem Ipsum" in the given font family. The text can be [customised](#options).

```yaml
type: token
preview: font-family
query: "var(--font-{{ value }})"
options:
  sans-serif: Sans Serif
  serif: Serif
```

### `font-size`

Renders "Aa" at the actual font size. The text can be [customised](#options).

```yaml
type: token
preview: font-size
options:
  0.875rem: Small
  1.25rem: Medium
  2rem: Large
```

### `size`

A filled square that grows to the token value. Useful for spacing scales.

```yaml
type: token
preview: size
options:
  1.5rem: Small
  2.25rem: Medium
  3rem: Large
```

### `shadow`

Shows a box shadow on a white card.

```yaml
type: token
preview: shadow
options:
  small:
    label: Small
    display: "0 1px 3px rgba(0,0,0,.12)"
```

### `radius`

Shows a shape with the given border radius.

```yaml
type: token
preview: radius
options:
  0: None
  0.5rem: Rounded
  50%: Circle
```

### `text`

Renders the option label as a text pill. Useful when no visual preview makes sense.

```yaml
type: token
preview: text
options:
  left: Left
  center: Center
  right: Right
```

## Custom Previews

You can register your own preview types via `panel.plugin()`. The token field checks for a globally registered component named `k-token-preview-{type}` before falling back to the built-in previews. See the `preview-examples/` folder for working examples.

Every custom preview plugin needs three files:

```php
<?php
// site/plugins/my-preview/index.php
Kirby::plugin('my/preview', []);
```

```js
// site/plugins/my-preview/index.js
panel.plugin("my/preview", {
  components: {
    "k-token-preview-icon": {
      props: {
        value: String,
        text: String,
      },
      template: `
        <span class="k-token-preview k-token-preview--icon">
          <k-icon :type="value" />
        </span>
      `,
    },
  },
});
```

```css
/* site/plugins/my-preview/index.css */
.k-token-preview--icon {
  height: var(--input-height);
  aspect-ratio: 1;
  background: var(--color-gray-800);
  color: var(--color-gray-300);
}
```

```yaml
my_icon:
  label: Icon
  type: token
  preview: icon
  options:
    heart: Heart
    star: Star
    bolt: Bolt
```

Your component receives two props: `value` (the resolved preview value) and `text` (the configured preview text). Use the `k-token-preview` base class to inherit shared styles like the selection outline.

## Using CSS Variables in Previews

If your options reference CSS custom properties (e.g. `var(--color-primary)`), the Panel needs to know about them. Create a `panel.css` in your assets folder and import your colour definitions:

```css
--color-primary: #d74894;
```

Then register it in your `config.php`:

```php
return [
    'panel' => [
        'css' => 'assets/css/panel.css'
    ]
];
```

This makes your design tokens available in the Panel, so colour swatches render with the actual colours.

## Options

You can customise the preview text for font previews in your `config.php`:

```php
return [
    'medienbaecker.token-field.font-family.text' => 'Hamburgevons',
    'medienbaecker.token-field.font-size.text'   => 'Abc',
];
```

The defaults are `"Lorem Ipsum"` for `font-family` and `"Aa"` for `font-size`.

## License

MIT
