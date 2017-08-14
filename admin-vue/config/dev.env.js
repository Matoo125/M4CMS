var merge = require('webpack-merge')
var prodEnv = require('./prod.env')

module.exports = merge(prodEnv, {
  NODE_ENV: '"development"',
  API: '"http://localhost/M4CMS/public/index.php?url=admin/"',
  BASE_URL: '"http://localhost/M4CMS/public/"',
  UPLOADS: '"http://localhost/M4CMS/public/uploads/"'
})