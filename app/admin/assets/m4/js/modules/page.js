/*
*  Page
*  @param  {String}  method  Method to be called.              Required
*  @param  {Object}  quill   Quill object to get content from
*/
self.page = function(method, quill) {

  console.log('page function called')

  $("form .submit").click(function() {
    var formData = {
      id: $('input[name=id]').val(),
      title: $("input[name=title]").val(),
      description: $("textarea[name=description").val(),
      content: quill.root.innerHTML,
      content_delta: JSON.stringify(quill.getContents()),
      image_id: $('#headerImage input').val(),
      is_published: $("input[name=is_published]").is(':checked'),
      tmp_id: $('input[name=tmp_id]').val()
    }
    console.log(formData)
    $.ajax({
      type: 'post',
      url: '/admin/pages/' + method,
      data: formData
    }).done(function(response) {
      console.log(response)
      toastr.success(response.message)
      if (method == 'createAjax') {
        router.go('/admin/pages/update/' + response.id)
      }
    }).fail(function(xhr, err) {
      console.log(err)
      console.log(xhr.responseJSON)
      toastr.error(xhr.responseJSON.message)
    })
  })

}
