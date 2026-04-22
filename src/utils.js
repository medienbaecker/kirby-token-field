/**
 * Builds a CSS hard-stop gradient from an array of colors.
 * Each color occupies an equal share of the gradient.
 */
export function toGradient(colors) {
	if (colors.length === 0) return "transparent";
	if (colors.length === 1) return colors[0];
	const step = 100 / colors.length;
	const stops = colors
		.map((c, i) => `${c} ${i * step}% ${(i + 1) * step}%`)
		.join(", ");
	return `linear-gradient(to right, ${stops})`;
}

/**
 * Splits an option's `display` into a scalar preview `value` (what a single-value
 * preview component reads) and a raw `display` object (what multi-value previews
 * like `contrast` read). Keeps the dispatch logic out of the two components.
 */
export function splitDisplay(option) {
	const d = option?.display;
	if (!d) return { value: option?.value, display: undefined };
	if (Array.isArray(d)) return { value: toGradient(d), display: undefined };
	if (typeof d === "object") return { value: option.value, display: d };
	return { value: d, display: undefined };
}
