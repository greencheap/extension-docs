const VueLoaderPlugin = require('vue-loader/lib/plugin');
const path = require('path');

module.exports = {
    entry: {
        "admin/index":"./app/views/admin/index",
        "admin/edit":"./app/views/admin/edit",
        "admin/docs-settings":"./app/views/admin/docs-settings",
        "docs":"./app/views/docs",
    },
    output: {
        path: path.resolve(__dirname, './app/bundle'),
        filename: "[name].js"
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: "vue-loader"
            },
            {
                test: /\.js$/,
                loader: 'babel-loader'
            },
            {
                test: /\.css$/,
                use: [
                    'vue-style-loader',
                    'css-loader'
                ]
            }
        ]
    },
    plugins: [
        new VueLoaderPlugin()
    ]
};
