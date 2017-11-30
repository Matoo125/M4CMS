/*
*    Category
*    @param  {String}  Method to be called.  Required
*/
self.category = function(method) {

  console.log('category function called')

  $(".select-page").select2({
    theme: "bootstrap",
    minimumResultsForSearch: Infinity,
  });

  $('form .submit').click(function() {
    var formData = {
      id: document.querySelector('input[name=id]').value,
      page_id: document.querySelector('.select-page').value,
      title: $("input[name=title]").val(),
      description: $("textarea[name=description").val(),
      image_id: document.querySelector('#headerImage input').value,
      tmp_id: document.querySelector('input[name=tmp_id]').value      
    }
    console.log(formData)
    $.ajax({
      type: 'post',
      url: '/admin/categories/' + method,
      data: formData
    }).done(function(response) {
      console.log(response)
      toastr.success(response.message)
      if (method == 'createAjax') {
        router.go('/admin/categories/update/' + response.id)
      }
    }).fail(function(xhr, err) {
      console.log(err)
      console.log(xhr.responseJSON)
      toastr.error(xhr.responseJSON.message)
    })
  })
}