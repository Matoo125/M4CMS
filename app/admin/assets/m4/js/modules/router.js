/*
 * Router object
 */
self.router = {
  /*
   *  Method go
   *  @param  {String}  URL to visit.
   */
  go: function(url) {

    getHTML(url, function(response) {

      console.log('response is delivered')
      var customScript = document.getElementById('customScript')
      if (customScript) customScript.remove()
      runThisOnLoad = null

      document.getElementById('app').innerHTML = response.getElementById('app').innerHTML;


      if (response.getElementById('customScript')) {
        var newdiv = document.createElement('div');
        var script = document.createElement('script');
        script.id = 'customScript';
        script.innerHTML = response.getElementById('customScript').innerHTML;
        newdiv.appendChild(script);
        document.getElementById('app').appendChild(newdiv);

      } else {
        console.log('no custom script to run')
        console.log(response)
        var r = response
      }

      if (typeof runThisOnLoad === 'function') {
        runThisOnLoad()
      } else {
        console.log('run this on load is not a function')
      }

    });

    history.pushState(null, null, url)
    console.log(url)

  }
}