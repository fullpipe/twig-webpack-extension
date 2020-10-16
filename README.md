# Twig Webpack extension

[![Latest Version on Packagist](https://img.shields.io/github/release/fullpipe/twig-webpack-extension.svg)](https://packagist.org/packages/fullpipe/twig-webpack-extension)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/fullpipe/twig-webpack-extension.svg)](https://packagist.org/packages/fullpipe/twig-webpack-extension/stats)
[![Tests](https://github.com/fullpipe/twig-webpack-extension/workflows/Tests/badge.svg)](https://github.com/fullpipe/twig-webpack-extension/actions)  

Inject your webpack entry points into twig templates with easy.

This repo provides a Twig extension that joins Webpack resultant files with Twig template engine in an easy way.

This approach allows the dynamic insertion of the css stylesheets and js scripts with Webpack generated hash.

> Also works well with **extract-text-webpack-plugin**

## Install

```bash
composer require fullpipe/twig-webpack-extension
```

### Set up Webpack

You need to install the `webpack-manifest-plugin`
```bash
npm install webpack-manifest-plugin --save
```

or with Yarn
```bash
yarn add webpack-manifest-plugin
```

Configure `webpack.config.js`
```js
// webpack.config.js

var ManifestPlugin = require('webpack-manifest-plugin');
const path = require("path");

module.exports = {
  ...
  entry: {
    vendor: ["jquery", "lodash"],
    main: './src/main.js'
  },
  output: {
    ...
    filename: "js/[name].js",
    path: path.resolve(__dirname, "../public/build"),
    publicPath: '/build/', // required
  },
  plugins: [
    new ManifestPlugin(),
    new ExtractTextPlugin({
      filename: "css/[name].css",
      publicPath: "/build/",
    }),
  ]
}
```

### Register as a Twig extension

```yaml
# app/config/services.yml

parameters:
    webpack.manifest: "%kernel.root_dir%/../public/build/manifest.json" #should be absolute
    webpack.public_dir: "%kernel.root_dir%/../public" #your public-dir

services:
    twig_extension.webpack:
        class: Fullpipe\TwigWebpackExtension\WebpackExtension
        public: false
        arguments:
            - "%webpack.manifest%"
            - "%webpack.public_dir%"
        tags:
            - { name: twig.extension }
```

### Inject entry points to your Twig

```twig
{# app/Resources/views/base.html.twig #}

<head>
    ...
    {% webpack_entry_css 'main' %}
    {% webpack_entry_css 'inline' inline %}
</head>

<body>
    ...
    {% webpack_entry_js 'vendor' %}
    {% webpack_entry_js 'main' defer %}
    {% webpack_entry_js 'second' async %}
    {% webpack_entry_js 'inline' inline %}
</body>
```

### Example

See working [example](example) with [webpack.config.js](example/frontend/webpack.config.js).

## Hashing output files avoiding the browser cache

If you use `[hash]` or `[chunkhash]`:

```js
// webpack.config.js
...
output: {
  ...
  filename: '[name].[chunkhash].js',
  chunkFilename: '[name].[chunkhash].js'
},
plugins: [
  new ExtractTextPlugin({
    ...
    filename: 'css/[name].[contenthash].css',
  }),
]
```

You should clear twig cache after each webpack compilation.  
So for dev environment do not use `[hash]` or `[chunkhash]`.
