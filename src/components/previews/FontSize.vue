<template>
	<span class="k-token-preview k-token-preview--font-size" :style="{ fontSize: value }">{{ text }}</span>
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
.k-token-preview--font-size {
	border: 1px solid var(--color-border);
	color: var(--color-text);
	padding: 0.25em 0.5em;
	white-space: nowrap;
	line-height: 1;
}
</style>
