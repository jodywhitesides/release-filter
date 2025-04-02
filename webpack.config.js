const path = require('path');

module.exports = {
    mode: 'development', // Set the mode to development
    entry: './index-filter.js',
    output: {
        filename: 'index-filter.bundle.js',
        path: path.resolve(__dirname, 'dist'),
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                },
            },
        ],
    },
    resolve: {
        extensions: ['.js'],
    },
};