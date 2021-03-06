const path = require("path");
const webpack = require("webpack");
const ManifestPlugin = require("webpack-manifest-plugin");
const ExtractTextPlugin = require("extract-text-webpack-plugin");

module.exports = {
  entry: {
    vendor: ["jquery", "lodash"],
    main: "./src/index.js",
    second: "./src/second.js",
    inline: "./src/inline.js",
  },
  output: {
    filename: "js/[name].[chunkhash].js",
    path: path.resolve(__dirname, "../public/build"),
    publicPath: "/build/",
  },
  plugins: [
    new webpack.optimize.CommonsChunkPlugin({
      name: "vendor",
      minChunks: Infinity,
    }),
    new ManifestPlugin(),
    new ExtractTextPlugin({
      filename: "css/[name].[chunkhash].css",
      publicPath: "/build/",
    }),
  ],
  module: {
    rules: [
      {
        test: /\.css$/,
        use: ExtractTextPlugin.extract({
          fallback: "style-loader",
          use: "css-loader",
        }),
      },
    ],
  },
};
