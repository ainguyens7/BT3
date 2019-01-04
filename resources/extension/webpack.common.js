const webpack = require("webpack");
const path = require("path");
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const env = require("./config");
const getProvideEnv = {
  API_ORIGIN: JSON.stringify(env.get("apiOrigin")),
  NODE_ENV: JSON.stringify(env.get("env"))
};

module.exports = {
  entry: {
    oberlo_content: path.resolve(__dirname, "oberlo/main.js"),
    alireviewContent: path.resolve(__dirname, "alireviewContent.js"),
    affiliate: path.resolve(__dirname, "affiliate.js")
  },
  output: {
    path: path.resolve(__dirname, "alireviews_extension/assets"),
    publicPath: "assets/",
    filename: "[name].js"
  },
  resolve: {
    modules: [
      "node_modules",
      path.resolve(__dirname, "oberlo"),
      path.resolve(__dirname, "oberlo/assets")
    ],
    extensions: [".js", ".scss"]
  },
  devtool: "cheap-source-map",
  target: "web",
  module: {
    rules: [
      {
        test: /\.js$/,
        include: [path.resolve(__dirname, "oberlo")],
        exclude: /(node_mdoules|bower_components)/,
        use: [
          {
            loader: "babel-loader"
          }
        ]
      },
      {
        test: /\.scss$/,
        exclude: /(bower_components)/,
        use: ExtractTextPlugin.extract({
          use: [
            {
              loader: "css-loader",
              options: {
                sourceMap: true
              }
            },
            {
              loader: "postcss-loader", // Run post css actions
              options: {
                plugins: function() {
                  // post css plugins, can be exported to postcss.config.js
                  return [require("autoprefixer")];
                }
              }
            },
            {
              loader: "resolve-url-loader"
            },
            {
              loader: "sass-loader",
              options: {
                sourceMap: process.env.NODE_ENV === "production" ? true : false,
                outputStyle: "compressed"
              }
            }
          ],
          fallback: "style-loader"
        })
      },
      {
        test: /\.(png|jpg)$/,
        use: [
          {
            loader: "url-loader",
            options: {
              limit: 200 * 1024
            }
          }
        ]
      },
      {
        test: /\.hbs$/,
        exclude: /(node_modules|bower_components)/,
        include: [path.resolve(__dirname, "oberlo/views")],
        use: [
          {
            loader: "handlebars-loader",
            query: {
              helperDirs: [path.resolve(__dirname, "oberlo/views/helpers")]
            }
          }
        ]
      }
    ]
  },
  plugins: [
    new webpack.DefinePlugin(getProvideEnv),
    new ExtractTextPlugin({
      filename: "integrateOberlo.css"
    }),
    new webpack.ProvidePlugin({
      $: "jquery",
      jQuery: "jquery",
      _: ["lodash"],
      "window.jQuery": "jquery",
      Util: "exports-loader?Util!bootstrap/js/dist/util",
      Popper: ["popper.js", "default"]
    })
  ]
};
