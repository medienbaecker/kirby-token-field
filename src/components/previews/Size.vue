<template>
	<span class="k-token-preview k-token-preview--size" :style="{ width: value, height: value }" />
</template>

<script>
export default {
	props: {
		value: String,
	},
};
</script>

<style>
.k-token-preview--size {
	min-width: 4px;
	min-height: 4px;
	border: 1px solid var(--color-border);
	background: var(--color-text);
	border-radius: var(--rounded-sm);
}
</style>
