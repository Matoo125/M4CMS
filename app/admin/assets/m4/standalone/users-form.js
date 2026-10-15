/*
 * Submit handler for the admin user form.
 * Loaded from Top.twig so it exists after router.go() swaps the page,
 * which does not run scripts that are part of the swapped markup.
 */
window.submitUserForm = function (method, done) {
  $('form .submit').off('click').click(function () {
    var formData = {}
    $('#validationForm').serializeArray().forEach(function (f) { formData[f.name] = f.value })
    formData.image_id = document.querySelector('#headerImage input').value
    $.ajax({
      type: 'post',
      url: '/admin/users/' + method,
      data: formData
    }).done(function (response) {
      if (response.status === 'ERROR') return toastr.error(response.message)
      toastr.success(response.message)
      if (done) done(response)
    }).fail(function (xhr) {
      toastr.error(xhr.responseJSON ? xhr.responseJSON.message : 'Request failed')
    })
  })
}
