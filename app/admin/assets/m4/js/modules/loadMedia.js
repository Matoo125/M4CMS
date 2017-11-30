self.loadMedia = function () {
  $.get('/api/media/list').done(function(r) {
    var media = document.querySelector('#mediaSelect')
    for (var x = 0; x < r.media.length; x++) {
      var el = document.createElement('img')
      el.src = '/public/uploads/' + r.media[x].folder + '/' + r.media[x].filename
      el.width = 100
      el.setAttribute('data-id', r.media[x].id)
      el.setAttribute('class', 'img-thumbnail')
      el.onclick = function () {
        galleryModalSelectedImage = this.dataset.id
        $('.img-selected').removeClass('img-selected'); // removes the previous selected class
        $(this).addClass('img-selected'); // adds the class to the clicked image
        console.log('clicked')
      }
      console.log(r.media[x])
      console.log(el)
      media.appendChild(el)
    }
    document.querySelector('#mediaSelect .loader').style = "display:none"
  }).fail(function(e) { console.log(e) }) 
}

