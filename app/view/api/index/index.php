hello from api
<?php if ( isset( $data['page'] ) ): ?>
<p> This is <?php echo $data['page']; ?> page </p>
<?php endif; ?>
<?php if ( isset( $data['post'] ) ): ?>
<p> And <?php echo $data['post']; ?> post </p>
<?php endif; ?>
