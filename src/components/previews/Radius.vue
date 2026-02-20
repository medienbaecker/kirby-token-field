<template>
	<span class="k-token-preview k-token-preview--radius">
		<span :style="{ borderRadius: value }" />
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
.k-token-preview--radius {
	width: var(--input-height);
	height: var(--input-height);
	border: 1px solid var(--color-border);
}

.k-token-preview--radius span {
	display: block;
	width: 60%;
	height: 60%;
	background: var(--color-text);
}
</style>
