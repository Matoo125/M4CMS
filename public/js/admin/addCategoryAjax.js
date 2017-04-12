// add new category ajax
$("#addNewCategory").submit(function(event) {
  event.preventDefault();
  var formData = new FormData(this);
  $.ajax({
    url: $(this).attr('action'),
    type: $(this).attr('method'),
    data: formData,
    cache:false,
    contentType: false,
    processData: false,
    success : function (data) {
      //alert(data)
      console.log(data)
      obj = JSON.parse(data)
      $("#addCategoryModal").modal('hide');
      toastr.success('You have created new category!', 'Success')
      // remove paragraph if first category
      $("#categories p").remove()
      $("#categories").append(addCategoryAppendData(obj))

      // wipe data
      $("#addCategoryModal #title").val('');
      $("#addCategoryModal #description").val('');
      $("#addCategoryModal #image").val('');

    },
    error: function (xhr, desc, err) {
      alert('error')
    }
  });
})

var addCategoryAppendData = function (obj) {
  return '<button data-id="'+obj.id+'" class="list-group-item list-group-item-action category">'+obj.title+'</button>';
}
