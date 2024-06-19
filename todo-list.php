<?php
/*
 * Plugin Name:       ToDo List
 * Description:       A simple todo list.
 * Version:           1.0.0
 * Requires at least: 6.5
 * Requires PHP:      8.1
 * Author:            Nikolay Nikolaev
 * Author URI:        https://nikolaynikolaev.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       todo-list
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

require_once __DIR__ . '/inc/autoloader.php';

class ToDoList
{
    public function __construct() {
        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );
    }

    public function activate() {
        flush_rewrite_rules();
        $db = new TLD\DB();
        $db->migrate();
    }

    public function deactivate() {
        flush_rewrite_rules();
    }
}

if ( class_exists( 'ToDoList' ) ) {
    $todo_list = new ToDoList();
    $todo_list->init();
}
 