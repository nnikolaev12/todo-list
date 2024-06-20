<?php

namespace TLD;

defined( 'ABSPATH' ) ?: exit;

class Views {
    /**
     * Register the public components for the plugin
     */
    public function register_public_views() {
        add_shortcode( 'todo-list', [ $this, 'shortcode_callback' ] );
    }

    public function shortcode_callback() {
        ob_start();
        $this->public_view();
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Task view for the admin
     */
    public function admin_view()
    {
        ?>
        <section class="todo-list">
            <h1>ToDo List</h1>
            <div>
                <button class="todo-list__btn todo-list__btn--primary todo-list__add-btn">&#43; Add New Task</button>
            </div>
            
            <?php $this->add_new_task_form(); ?>

            <ul class="todo-list__tasks"></ul>

            <?php $this->edit_task_form(); ?>
        </section>
        <?php
    }

    /**
     * Task view for the public facing site
     */
    public function public_view()
    {
        ?>
        <section class="todo-list">
            <h2>ToDo List</h2>
            <ul class="todo-list__tasks"></ul>
            <div class="todo-list__message todo-list__empty">There are no tasks.</div>
            <div class="todo-list__message todo-list__error">Sorry, tasks cannot be fetched at this time!</div>
        </section>
        <?php
    }

    protected function add_new_task_form()
    {
        ?>
        <div class="todo-list__add-task">
            <form action="POST">
                <div>
                    <label for="tldTitle">Title</label>
                    <input id="tldTitle" type="text" name="title" required />
                </div>
                <div>
                    <label for="tldDescription">Description</label>
                    <textarea id="tldDescription" rows="10" cols="40" required></textarea>
                </div>
                <div>
                    <button class="todo-list__btn todo-list__btn--primary todo-list__add-task--submit">Submit</button>
                </div>
            </form>
        </div>
        <?php
    }

    protected function edit_task_form()
    {
        ?>
        <div class="todo-list__edit-task">
            <form action="POST">
                <div>
                    <label for="tldEditTitle">Title</label>
                    <input id="tldEditTitle" type="text" name="title" required />
                </div>
                <div>
                    <label for="tldEditDescription">Description</label>
                    <textarea id="tldEditDescription" rows="10" cols="40" required></textarea>
                </div>
                <div>
                    <input type="hidden" name="id" value="">
                    <button class="todo-list__btn todo-list__btn--primary todo-list__edit-task--submit">Submit</button>
                </div>
            </form>
            <button class="todo-list__btn todo-list__btn--secondary todo-list__edit-task--close">&#10005;</button>
        </div>
        <?php
    }
}