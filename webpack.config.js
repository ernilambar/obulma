require( 'dotenv' ).config();

const themePath = './';
const path = require( 'path' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const BrowserSyncPlugin = require( 'browser-sync-v3-webpack-plugin' );
const { CleanWebpackPlugin } = require( 'clean-webpack-plugin' );

const isProd = process.env.NODE_ENV === 'production';

module.exports = {
	context: __dirname,
	entry: {
		custom: `${ themePath }src/custom.js`,
		welcome: `${ themePath }src/welcome.js`,
	},
	output: {
		path: path.resolve( __dirname, `${ themePath }/build` ),
		filename: '[name].js',
		publicPath: themePath,
	},
	target: 'browserslist',
	externals: {
		jquery: 'jQuery',
	},
	performance: {
		hints: false,
	},
	mode: isProd ? 'production' : 'development',
	devtool: isProd ? false : 'source-map',
	module: {
		rules: [
			{
				test: /\.(js|jsx)$/,
				exclude: /(node_modules|bower_components)/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: [ '@babel/preset-env' ],
					},
				},
			},
			{
				test: /\.css$/,
				use: [ MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader' ],
			},
			{
				test: /\.(png|svg|jpg|jpeg|gif|webp)$/,
				type: 'asset/resource',
				generator: {
					filename: 'images/[name][ext]',
				},
			},
			{
				test: /\.(woff|woff2|eot|ttf|otf)$/,
				type: 'asset/resource',
				generator: {
					filename: 'fonts/[name][ext]',
				},
			},
		],
	},
	plugins: [
		new CleanWebpackPlugin(),
		new MiniCssExtractPlugin( { filename: '[name].css' } ),
		new BrowserSyncPlugin( {
			files: [ '**/*.php' ],
			injectChanges: true,
			proxy: process.env.DEV_SERVER_URL,
			open: 'yes' === process.env.BROWSERSYNC_OPEN ? true : false,
		} ),
	],
	optimization: {
		minimize: false,
	},
};
