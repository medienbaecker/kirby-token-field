<template>
	<span class="k-token-preview k-token-preview--color">
		<span :style="{ background: value }" />
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
.k-token-preview--color {
	width: var(--input-height);
	height: var(--input-height);
	background: var(--color-frame-pattern, var(--pattern-light));
	background-clip: padding-box;
	overflow: hidden;
}

.k-token-preview--color span {
	position: absolute;
	inset: 0;
	border-radius: var(--rounded);
	box-shadow: 0 0 0 1px inset hsla(0, 0%, 0%, 0.175);
}
</style>
