var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('./public/')
    .setPublicPath('')
    .setManifestKeyPrefix('')

    .cleanupOutputBeforeBuild()
    .enableSassLoader()
    .enableSourceMaps(false)
    .enableVersioning(false)
    .disableSingleRuntimeChunk()

    .addStyleEntry('app', './assets/scss/app.scss')
;

module.exports = Encore.getWebpackConfig();