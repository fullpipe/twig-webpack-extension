# Twig Webpack extension
> Inject your webpack entry points into twig templates with easy.

This repo provides a Twig extension that joins Webpack resultant files with Twig template engine in an easy way.
This approach allows the dynamic insertion of the css stylesheets and js scripts with Webpack generated hash.
>Also works well with **extract-text-webpack-plugin**

### Install
```bash
$ composer require fullpipe/twig-webpack-extension
```

### Set up Webpack
You need to install the `webpack-manifest-plugin`
```bash
$ npm install webpack-manifest-plugin --save-dev

# or with Yarn
$ yarn add webpack-manifest-plugin --dev
```

Configure `webpack.config.js`
```js
// webpack.config.js

var ManifestPlugin = require('webpack-manifest-plugin');

(...)

module.exports = {

  (...)

  entry: {
    vendor: ["jquery", "lodash"],
    main: './src/main.js'
  },
  output: {
    (...)
    path: './js'
    publicPath: '/',    // required

    (...)
  },
  plugins: [
    new ManifestPlugin(),
    new ExtractTextPlugin({
      filename: './../css/[name].css',
      publicPath: '/'
    }),

  (...)
  ]
}
```

### Register as a service the Twig extension inside Symfony
```yaml
# app/config/services.yml

parameters:
    (...)

    webpack.manifest: "%kernel.root_dir%/../web/build/manifest.json" #should be absolute
    webpack.public_path_js: /js/
    webpack.public_path_css: /css/

services:
    (...)

    twig_extension.webpack:
        class: Fullpipe\TwigWebpackExtension\WebpackExtension
        public: false
        arguments:
            - "%webpack.manifest%"
            - "%webpack.public_path_js%"
            - "%webpack.public_path_css%"
        tags:
            - { name: twig.extension }
```

### Inject entry points to your Twig
```twig
{# app/Resources/views/base.html.twig #}

(...)

<head>
(...)

    {% webpack_entry_css 'main' %}
</head>

<body>
(...)

    {% webpack_entry_js 'vendor' %}
    {% webpack_entry_js 'main' %}
</body>
```

### Hashing output files avoiding the browser cache
If you use `[hash]` or `[chunkhash]`:
```js
// webpack.config.js

(...)

output: {
  (...)

  filename: '[name].[chunkhash].js',
  chunkFilename: '[name].[chunkhash].js'
},
plugins: [
  (...)

  new ExtractTextPlugin({
    filename: './../css/[name].[contenthash].css',
    publicPath: '/'
  }),

  (...)
]
```
>You should clear twig cache after each webpack compilation. So for dev environment do not use `[hash]` or `[chunkhash]`.
