import TokenField from "./components/TokenField.vue";
import TokenFieldPreview from "./components/TokenFieldPreview.vue";

panel.plugin("medienbaecker/token-field", {
	fields: {
		token: TokenField,
	},
	components: {
		"k-token-field-preview": TokenFieldPreview,
	},
});
