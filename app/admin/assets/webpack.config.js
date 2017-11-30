module.exports = {
    entry: './m4/js/m4.js',
    output: {
        filename: './bundle.js',
        library: 'm4',
        libraryTarget: 'var'
    },
    devtool: 'source-map',
    module: {
        loaders: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader',
                query: {
                    presets: ['es2015']
                }
            }
        ],
    }
};