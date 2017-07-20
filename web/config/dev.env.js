var merge = require('webpack-merge')
var prodEnv = require('./prod.env')

module.exports = merge(prodEnv, {
  NODE_ENV: '"development"',
  API: '"http://m4cms.dev/"',
  UPLOADS: '"http://m4cms.dev/uploads/"'
})
