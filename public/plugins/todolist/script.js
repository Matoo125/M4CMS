function runThisOnLoad () {
  console.log('hello')
  m4.data.plugin.todolist = { ajaxActive: false}
  
  function toggleMe (id)
  {
    if (todoAjaxActive) {
      return false;
    }
    todoAjaxActive = true
    console.log(id)
    $.post('/admin/todolist/toggle', {
      id: id
    }).done(function (r) {
      console.log(r)
    }).fail(function (e) {
      console.log(e)
    })
  
    setTimeout(function () { // to avoid double click
       todoAjaxActive = false;
    }  
    , 100)
  
  }
}