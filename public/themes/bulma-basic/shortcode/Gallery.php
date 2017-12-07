<div class="columns">
<?php foreach ($items as $item): ?>
  <div class="column">
    <a href="#" class="d-block mb-4 h-100">
       <img class="img-fluid img-thumbnail" src="/public/uploads/<?=$item['folder']?>/<?=$item['filename']?>">
    </a>
  </div>
<?php endforeach; ?>
</div>
