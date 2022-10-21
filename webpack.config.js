var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('./public/')
    .setPublicPath('/')
    .setManifestKeyPrefix('')

    .cleanupOutputBeforeBuild()
    .enableSassLoader()
    .enableSourceMaps(false)
    .enableVersioning(false)
    .disableSingleRuntimeChunk()

    .addEntry('app', './assets/js/app.js')
;

module.exports = Encore.getWebpackConfig();