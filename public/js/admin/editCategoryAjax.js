var categoryId;

// get category details
$("#categories").on('click', '.category', function() {
  var category = $(this);
  categoryId = category.data('id');
  console.log(categoryId);
  // fetch category data
  $.ajax({
    url: '/admin/categories/getAjax/'+categoryId,
    type: "GET",
    success: function (data) {
      console.log(data)
      data = data.data;
      // open modal and append data
      $("#eTitle").val(data.title);
      $("#eDescription").val(data.description);

      $("#editCategoryModal #page_id option[value="+data.page_id+"]").attr('selected','selected');

      $("#currentCategoryImage").html("");
      if (data.image) {
        $("#currentCategoryImage").html("<img src='/uploads/thumbs/150x150/"+data.image+"' />")
      }
      $("#editCategoryModal").modal('show');

    },
    error: function (xhr, desc, err) {
      console.log(err)
    }
  });
})


// edit category post
$("#editCategory").submit(function(event) {
  event.preventDefault();
  var formData = new FormData(this);
  $.ajax({
    url: $(this).attr('action') + "/" + categoryId,
    type: $(this).attr('method'),
    data: formData,
    cache:false,
    contentType: false,
    processData: false,
    success : function (data) {
      //alert(data)
      console.log(data)
      obj = JSON.parse(data)
      $("#editCategoryModal").modal('hide');
      toastr.success('You have updated category!', 'Success')
      editCategoryAppendData(obj)


    },
    error: function (xhr, desc, err) {
      alert('error')
    }
  });
})

var editCategoryAppendData = function (obj) {
  $(".category[data-id='" + categoryId +"']").text(obj.title);
}
