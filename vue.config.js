const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
module.exports = {
    publicPath: process.env.NODE_ENV === 'production' ? '/app/' : '/',
    transpileDependencies: [
        'vuetify', '@koumoul/vjsf'
    ],
    devServer: {
        open: true,
        clientLogLevel: 'warning',
        host: process.env.HOST || 'localhost',
        port: process.env.PORT || 8080,
        https: false,
        hotOnly: false,
        // proxy: {
        //     '/api': {
        //         target: 'http://dist:81',
        //         changeOrigin: true,
        //         ws: false,
        //         pathRewrite: {
        //             '^/stocks': ''
        //         }
        //     }
        // }
    },

    configureWebpack: {
        output: { filename: '[name].[hash].bundle.js' },
        plugins: [
            // new BundleAnalyzerPlugin()
        ]
    },

    productionSourceMap: false,

    pwa: {
      manifestCrossorigin: 'anonymous'
    },
    chainWebpack: (config) => {
        config
            .plugin('html')
            .tap((args) => {
                args[0].title = 'Ederra Stok Takip';
                return args;
            });
    }
};
