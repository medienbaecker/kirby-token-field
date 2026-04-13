<template>
	<k-field v-bind="$props" :class="['k-token-field', $attrs.class]" :input="id" :style="$attrs.style">
		<fieldset :disabled="disabled" class="k-token-input">
			<legend class="sr-only">{{ label }}</legend>
			<ul>
				<li v-for="(option, index) in options" :key="index">
					<label :title="option.text">
						<input :aria-label="option.text" :checked="option.value === value" :disabled="disabled" :name="name || id"
							:value="option.value" class="input-hidden" type="radio" @click="toggle(option.value)"
							@input="$emit('input', option.value)" />
						<component v-if="previewComponent" :is="previewComponent" :value="previewValue(option)"
							:text="previewText" />
					</label>
				</li>
			</ul>
		</fieldset>
	</k-field>
</template>

<script>
import previews from "./previews/index.js";
import { toGradient } from "../utils.js";

export default {
	inheritAttrs: false,
	props: {
		disabled: Boolean,
		endpoints: Object,
		help: String,
		id: [String, Number],
		label: String,
		name: String,
		required: Boolean,
		value: { default: null },
		options: { type: Array, default: () => [] },
		preview: { type: String, default: "color" },
		previewText: { type: String, default: "" },
	},
	computed: {
		previewComponent() {
			const custom = `k-token-preview-${this.preview}`;
			if (this.$helper.isComponent(custom)) return custom;
			return previews[this.preview] || null;
		},
	},
	methods: {
		toggle(value) {
			if (value === this.value && !this.required) {
				this.$emit("input", "");
			}
		},
		previewValue(option) {
			if (this.preview === "text") return option.text;
			if (!option.display) return option.value;
			if (Array.isArray(option.display)) return toGradient(option.display);
			return option.display;
		},
	},
};
</script>

<style>
.k-token-input {
	all: unset;
	display: block;
}

.k-token-input ul {
	display: flex;
	flex-wrap: wrap;
	gap: var(--spacing-1);
	list-style: none;
	padding: 0;
	margin: 0;
}

/* Shared preview base */
.k-token-preview {
	position: relative;
	display: flex;
	align-items: center;
	justify-content: center;
	border-radius: var(--token-preview-rounded, var(--rounded));
}

.k-token-input input:checked+.k-token-preview {
	outline: 1px solid var(--color-gray-600);
	outline-offset: 2px;
}

.k-token-input input:focus-visible+.k-token-preview {
	outline: var(--outline);
}

.k-token-input input:checked:focus-visible+.k-token-preview {
	outline: var(--outline);
	outline-offset: 2px;
}
</style>
