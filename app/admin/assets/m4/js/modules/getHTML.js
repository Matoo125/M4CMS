/*
 * Get HTML asynchronously
 * @param  {String}   url       The URL to get HTML from
 * @param  {Function} callback  A callback function. Pass in "response" variable to use returned HTML.
 */
self.getHTML = function(url, callback) {

  // Feature detection
  if (!window.XMLHttpRequest) return;

  // Create new request
  var xhr = new XMLHttpRequest();

  // Setup callback
  xhr.onload = function() {
    if (callback && typeof(callback) === 'function') {
      callback(this.responseXML);
    }
  }

  // Get the HTML
  xhr.open('GET', url);
  xhr.responseType = 'document';
  xhr.send();

};