<template>
	<span class="k-token-preview k-token-preview--contrast"
		:class="{ 'k-token-preview--contrast-compact': compact }"
		:style="{ background: display.background, color: display.color }">
		Aa
	</span>
</template>

<script>
export default {
	props: {
		text: String,
		display: { type: Object, default: () => ({}) },
		compact: Boolean,
	},
};
</script>

<style>
.k-token-preview--contrast {
	width: var(--token-preview-size, var(--input-height));
	height: var(--token-preview-size, var(--input-height));
	font-weight: 600;
	font-size: var(--text-sm);
	box-shadow: 0 0 0 1px inset hsla(0, 0%, 0%, 0.175);
}

.k-token-preview--contrast-compact {
	font-size: var(--text-xs);
}
</style>
