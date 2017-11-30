self.selectHeaderImage = function () {
  imageSelector.modal(function (data) {
    document.querySelector('#headerImage img').src = (
      '/public/uploads/' + data.folder + '/' + data.filename
    )
    document.querySelector('#headerImage input').value = data.id
    toastr.success(data.message)
  })
}
