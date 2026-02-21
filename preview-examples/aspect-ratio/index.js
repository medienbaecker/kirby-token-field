panel.plugin("example/aspect-ratio-preview", {
	components: {
		"k-token-preview-aspect-ratio": {
			props: {
				value: String,
				text: String,
			},
			template: `
				<span class="k-token-preview k-token-preview--aspect-ratio">
					<span :style="{ aspectRatio: value }">{{ value }}</span>
				</span>
			`,
		},
	},
});
