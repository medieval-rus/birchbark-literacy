/*
 * This file is part of «Birchbark Literacy from Medieval Rus» database.
 *
 * Copyright (c) Department of Linguistic and Literary Studies of the University of Padova
 *
 * «Birchbark Literacy from Medieval Rus» database is free software:
 * you can redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software Foundation, version 3.
 *
 * «Birchbark Literacy from Medieval Rus» database is distributed
 * in the hope  that it will be useful, but WITHOUT ANY WARRANTY; without even
 * the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. If you have not received
 * a copy of the GNU General Public License along with
 * «Birchbark Literacy from Medieval Rus» database,
 * see <http://www.gnu.org/licenses/>.
 */

const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
    .enableSassLoader()
    .addStyleEntry('css/site/index/index', './assets/scss/pages/site/index/index.scss')
    .addStyleEntry('css/site/document/list', './assets/scss/pages/site/document/list.scss')
    .addEntry('js/site/document/list', './assets/js/pages/site/document/list.js')
    .addStyleEntry('css/site/document/show', './assets/scss/pages/site/document/show.scss')
    .addEntry('js/site/document/show', './assets/js/pages/site/document/show.js')
    .addStyleEntry('css/site/security/login', './assets/scss/pages/site/security/login.scss')
    .addStyleEntry('css/site/content/post', './assets/scss/pages/site/content/post.scss')
    .addStyleEntry('css/site/book/list', './assets/scss/pages/site/book/list.scss')
    .addStyleEntry('css/site/book/show', './assets/scss/pages/site/book/show.scss')
    .addStyleEntry('css/site/bibliography/list', './assets/scss/pages/site/bibliography/list.scss')
    .addStyleEntry('css/site/maps/index', './assets/scss/pages/site/maps/index.scss')
    .addStyleEntry('css/site/maps/towns', './assets/scss/pages/site/maps/towns.scss')
    .addEntry('js/site/maps/towns', './assets/js/pages/site/maps/towns.js')
    .addStyleEntry('css/site/maps/excavations', './assets/scss/pages/site/maps/excavations.scss')
    .copyFiles({
        from: './assets/fonts',
        to: 'fonts/[path][name].[ext]',
    })
    .copyFiles({
        from: './assets/images',
        to: 'images/[path][name].[ext]',
    })
;

module.exports = Encore.getWebpackConfig();
