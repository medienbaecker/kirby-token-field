panel.plugin("example/icon-preview", {
	components: {
		"k-token-preview-icon": {
			props: {
				value: String,
				text: String,
			},
			template: `
				<span class="k-token-preview k-token-preview--icon">
					<img :src="'/assets/icons/' + value + '.svg'" :alt="text" />
				</span>
			`,
		},
	},
});
