const defaultConfiguration = require( '@wordpress/scripts/config/webpack.config' );
const removeEmptyScriptsPlugin = require( 'webpack-remove-empty-scripts' );
const path = require( 'path' );

module.exports = {
	...defaultConfiguration,
	...{
		entry: {
			'scripts/hacu-app': path.resolve( process.cwd(), 'resources/scripts', 'hacu-app.js' ),
			'styles/hacu-app': path.resolve( process.cwd(), 'resources/styles', 'hacu-app.scss' ),
		},
		plugins: [
			...defaultConfiguration.plugins,
			new removeEmptyScriptsPlugin( {
				stage: removeEmptyScriptsPlugin.STAGE_AFTER_PROCESS_PLUGINS,
			} ),
		],
	},
};
