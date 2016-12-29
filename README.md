# Twig webpack extension

### Install

```bash
$ composer require fullpipe/twig-webpack-extension
```

### Set up webpack
Install webpack-manifest-plugin

```bash
$ npm install webpack-manifest-plugin --save-dev
$ yarn add webpack-manifest-plugin --dev
```

Configure `webpack.config.js`

```js
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
        
        publicPath: '/build/', //required!

        (...)
    },
    plugins: [
        new ManifestPlugin(),

        (...)
    ]
}
```

### Add twig extension

In symfony 2/3

```yaml
parameters:
    (...)

    webpack.manifest: "%kernel.root_dir%/../web/build/manifest.json" #should be absolute
    webpack.public_path: /build/ #should be same as output.publicPath in webpack config

services:
    (...)

    app.twig_extension:
        class: Fullpipe\Twig\Extension\Webpack\WebpackExtension
        arguments:
            - '%webpack.manifest%'
            - '%webpack.public_path%'
        tags:
            - { name: twig.extension }
```

### Inject entry points to your templates

```twig
    {% webpack_entry_js 'vendor' %}
    {% webpack_entry_js 'main' %}
```

### Others
If you use `[hash]` or `[chunkhash]` in webpack.output

```js
    output: {
        filename: '[name].[chunkhash].js',
        chunkFilename: '[name].[chunkhash].js'
    },
```

You should clear twig cache after each webpack compilation.
So for dev environment do not use `[hash]` or `[chunkhash]`.

#### Works with extract-text-webpack-plugin
