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

    public function init() {
        add_action( 'admin_enqueue_scripts', [ $this, "include_admin_assets" ] );
        add_action( 'wp_enqueue_scripts', [ $this, "include_public_assets" ] );
        add_action( 'admin_menu', [ $this, 'add_admin_pages' ] );

        $db = new TLD\DB();
        $db->register_ajax_hooks();
    }

    public function include_admin_assets() {
        wp_enqueue_style( 'todo-list-admin', plugin_dir_url( __FILE__ ) . 'assets/css/admin.min.css', [], '1.0.0' );
        wp_enqueue_script( 'todo-list-admin', plugin_dir_url( __FILE__ ) . 'assets/js/admin.min.js', [ 'jquery' ], '1.0.0', true );
    }

    public function add_admin_pages() {
        add_menu_page(
            'ToDo List',
            'ToDo List',
            'manage_options',
            'todo-list',
            [ $this, 'admin_index' ],
            'dashicons-list-view',
            30
        );
    }

    public function admin_index() {
        $views = new TLD\Views();
        $views->admin_view();
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
 