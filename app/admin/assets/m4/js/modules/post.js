/*
*  Post form initialization
*  @param  {String}  method  Method to be called.              Required
*  @param  {Object}  quill   Quill object to get content from
*/
var post = function(method, quill) {

  console.log('post function called')

  $(".select-page").select2({
    theme: "bootstrap",
    minimumResultsForSearch: Infinity,
  });
 
  $(".select-category").select2({
    theme: "bootstrap",
    minimumResultsForSearch: Infinity,
  });

  // show categories of selected page
  $(".select-page").change(function () {
    console.log('page was selected ' + $(this).val())
    $.get('/api/categories/listBasic/' + $(this).val(), {})
    .done(function (r) {
      console.log(r.categories)
      var c_select = document.querySelector('.select-category')
      if (r.categories.length > 0) {
        document.querySelector('.select-category').parentElement.setAttribute('style','display:block')
        document.querySelector('.select-category').innerHTML = '<option value=""></option>'
      } else {
        document.querySelector('.select-category').parentElement.setAttribute('style','display:none')
        document.querySelector('.select-category').value = null
      }
      for (var i = r.categories.length - 1; i >= 0; i--) {
        var o = document.createElement('OPTION')
        o.setAttribute('value', r.categories[i].value)
        o.innerHTML = r.categories[i].label
        document.querySelector('.select-category').append(o)
        console.log(r.categories[i])
      }
    }).fail(function(err) { console.log(err) })
  })

  $("form .submit").click(function() {
    var formData = {
      id: document.querySelector('input[name=id]').value,
      title: $("input[name=title]").val(),
      description: $("textarea[name=description").val(),
      content: quill.root.innerHTML,
      content_delta: JSON.stringify(quill.getContents()),
      image_id: document.querySelector('#headerImage input').value,
      page_id: document.querySelector('.select-page').value,
      category_id: document.querySelector('.select-category').value,
      is_published: $("input[name=is_published]").is(':checked'),
      tmp_id: document.querySelector('input[name=tmp_id]').value      
    }
    console.log(formData)
    $.ajax({
      type: 'post',
      url: '/admin/posts/' + method,
      data: formData
    }).done(function(response) {
      console.log(response)
      toastr.success(response.message)
      if (method == 'createAjax') {
        router.go('/admin/posts/update/' + response.id)
      }
    }).fail(function(xhr, err) {
      console.log(err)
      console.log(xhr)
      console.log(xhr.responseJSON)
      toastr.error(xhr.responseJSON.message)
    })
  })

  return true;

}

module.exports = post