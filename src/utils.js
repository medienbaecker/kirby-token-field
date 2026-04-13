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
