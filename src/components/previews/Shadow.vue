<template>
	<span class="k-token-preview k-token-preview--shadow">
		<span :style="{ boxShadow: value }" />
	</span>
</template>

<script>
export default {
	props: {
		value: String,
	},
};
</script>

<style>
.k-token-preview--shadow {
	width: var(--input-height);
	height: var(--input-height);
	border: 1px solid var(--color-border);
	background: var(--color-white);
}

.k-token-preview--shadow span {
	display: block;
	width: 50%;
	height: 50%;
	border-radius: var(--rounded);
	background: var(--color-white);
}
</style>
