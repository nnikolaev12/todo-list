<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>
<section class="todo-list" <?php echo get_block_wrapper_attributes(); ?>>
	<?php if ( ! empty( $attributes['title'] ) ) : ?>
		<h2 class="todo-list__title"><?php echo esc_html( $attributes['title'] ); ?></h2>
	<?php endif; ?>
	<ul class="todo-list__tasks"></ul>
	<div class="todo-list__message todo-list__empty"><?php _e( "There are no tasks.", 'tdl' ); ?></div>
	<div class="todo-list__message todo-list__error"><?php _e( "Sorry, tasks cannot be fetched at this time!", 'tdl' ); ?></div>
</section>
