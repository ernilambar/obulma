/** @type {import('postcss-load-config').Config} */
const config = {
	plugins: [
		require( 'postcss-import' ),
		require( 'postcss-preset-env' ),
		require( 'postcss-nested' ),
	],
};

module.exports = config;
