<template>
	<k-field v-bind="$props" :class="['k-token-field', $attrs.class]" :input="id" :style="$attrs.style">
		<fieldset :disabled="disabled" class="k-token-input">
			<legend class="sr-only">{{ label }}</legend>
			<k-input-validator :required="required" :value="JSON.stringify(value)">
				<ul>
					<li v-for="(option, index) in choices" :key="index">
						<label :title="option.text">
							<input :aria-label="option.text" :checked="option.value === value" :disabled="disabled" :name="name || id"
								:value="option.value" class="input-hidden" type="radio" @click="toggle(option.value)"
								@input="$emit('input', option.value)" />
							<text-preview v-if="index >= options.length" :value="option.text" class="k-token-preview--unknown" />
							<component v-else-if="previewComponent" :is="previewComponent" :value="previewValue(option)"
								:text="previewText" :display="previewDisplay(option)" />
						</label>
					</li>
				</ul>
			</k-input-validator>
		</fieldset>
	</k-field>
</template>

<script>
import previews from "./previews/index.js";
import { splitDisplay } from "../utils.js";

export default {
	components: { TextPreview: previews.text },
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
	emits: ["input"],
	computed: {
		choices() {
			return this.$helper.field.options(this.options, this.value);
		},
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
			return splitDisplay(option).value;
		},
		previewDisplay(option) {
			return splitDisplay(option).display;
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

.k-token-input label {
	display: flex;
	align-items: flex-start;
	height: 100%;
}

/* Shared preview base */
.k-token-preview {
	position: relative;
	display: flex;
	align-items: center;
	justify-content: center;
	border-radius: var(--token-preview-rounded, var(--rounded));
}

.k-token-preview--unknown {
	align-self: stretch;
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
