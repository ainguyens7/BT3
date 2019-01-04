const path = require("path");
const convict = require("convict");

const config = convict({
  apiOrigin: {
    doc: "API origin.",
    format: "url",
    default: "http://127.0.0.1:8000/api"
  },
  env: {
    doc: "ENV: production, development.",
    format: "*",
    default: "development",
    env: "NODE_ENV"
  }
});

config.loadFile(path.resolve(__dirname, "config/prod.json"));
config.validate({ allowed: "strict" });

module.exports = config;
