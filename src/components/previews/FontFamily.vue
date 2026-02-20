<template>
	<span class="k-token-preview k-token-preview--font" :style="{ fontFamily: value }">{{ text }}</span>
</template>

<script>
export default {
	props: {
		value: String,
		text: String,
	},
};
</script>

<style>
.k-token-preview--font {
	border: 1px solid var(--color-border);
	font-size: var(--text-sm);
	color: var(--color-text);
	padding: 0.5em 0.75em;
	white-space: nowrap;
}
</style>
