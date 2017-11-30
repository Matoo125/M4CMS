self.settings = function () {

  console.log('settings function has been called. ')

  $('form .submit').click(function () {
    var formData = {
      title: $('input[name=title]').val(),
      description: $('input[name=desc]').val(),
      theme: $('select[name=theme]').val()
    }

    console.log($('input#title').val())

    $.post('/admin/settings/update', formData)
    .done(function (response) {
      console.log(response)
      toastr.success(response.message)
    }).fail (function (xhr, error) {
      console.log(error)
      toastr.error(xhr.responseJSON.message)
    })
  })

  return true;
}
