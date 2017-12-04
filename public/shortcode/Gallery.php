<div class="row text-center text-lg-left">
<?php foreach ($items as $item): ?>
  <div class="col-lg-3 col-md-4 col-xs-6">
    <a href="#" class="d-block mb-4 h-100">
       <img class="img-fluid img-thumbnail" src="/public/uploads/<?=$item['folder']?>/<?=$item['filename']?>">
    </a>
  </div>
<?php endforeach; ?>
</div>
