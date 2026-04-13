<template>
	<div class="k-token-field-preview">
		<component v-if="previewComponent && displayValue" :is="previewComponent" :value="displayValue"
			:text="optionText" :compact="true" />
		<span v-else-if="preview === 'font-family' && displayValue" :style="{ fontFamily: displayValue }">{{ optionText }}</span>
		<template v-else>{{ optionText }}</template>
	</div>
</template>

<script>
import previews from "./previews/index.js";
import { toGradient } from "../utils.js";

const textPreviews = new Set(["text", "font-family", "font-size", "size"]);

export default {
	inheritAttrs: false,
	props: {
		value: { default: null },
		field: { type: Object, default: () => ({}) },
		column: { type: Object, default: () => ({}) },
	},
	computed: {
		preview() {
			return this.field.preview || "color";
		},
		matchedOption() {
			if (!this.value || !this.field.options) return null;
			return this.field.options.find((o) => o.value === this.value) || null;
		},
		optionText() {
			return this.matchedOption ? this.matchedOption.text : this.value;
		},
		displayValue() {
			if (!this.value) return null;
			const opt = this.matchedOption;

			if (this.preview === "text") return opt ? opt.text : this.value;
			if (!opt) return this.value;
			if (!opt.display) return opt.value;
			if (Array.isArray(opt.display)) return toGradient(opt.display);
			return opt.display;
		},
		previewComponent() {
			if (textPreviews.has(this.preview)) return null;

			const custom = `k-token-preview-${this.preview}`;
			if (this.$helper.isComponent(custom)) return custom;
			return previews[this.preview] || null;
		},
	},
};
</script>

<style>
.k-token-field-preview {
	--token-preview-size: var(--tag-height);
	--token-preview-rounded: var(--tag-rounded);
	padding-inline: var(--table-cell-padding);
	display: flex;
	align-items: center;
	gap: var(--spacing-2);
}
</style>
