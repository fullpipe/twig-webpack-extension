# Twig webpack extension

### Install

`composer require fullpipe/twig-webpack-extension`

### Set up webpack
Install webpack-manifest-plugin

```
npm install webpack-manifest-plugin --save
```

Configure `webpack.config.js`

```js
var ManifestPlugin = require('webpack-manifest-plugin');
...
module.exports = {
    ...
    entry: {
        vendor: ["jquery", "lodash"],
        main: './src/main.js'
    },
    output: {
        ...
        publicPath: '/build/', //required!
        ...
    },
    plugins: [
        new ManifestPlugin(),
        ...
    ]
}
```

### Add twig extension

In symfony 2/3

```yaml
parameters:
    ...
    webpack.manifest: /var/www/web/build/manifest.json
    webpack.publicPath: /build/

services:
    ...
    app.twig_extension:
        class: Fullpipe\Twig\Extension\Webpack\WebpackExtension
        arguments:
            - '%webpack.manifest%'
            - '%webpack.publicPath%'
        tags:
            - { name: twig.extension }
```

### Inject entry points to your templates

```twig
    {% webpack_entry 'vendor' %}
    {% webpack_entry 'main' %}
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
