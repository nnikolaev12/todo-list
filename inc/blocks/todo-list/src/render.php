<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>
<section class="todo-list" <?php echo get_block_wrapper_attributes(); ?>>
	<h2>ToDo List</h2>
	<ul class="todo-list__tasks"></ul>
	<div class="todo-list__message todo-list__empty">There are no tasks.</div>
	<div class="todo-list__message todo-list__error">Sorry, tasks cannot be fetched at this time!</div>
</section>
