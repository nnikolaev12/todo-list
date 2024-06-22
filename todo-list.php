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
 * Text Domain:       tdl
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
        $db->register_rest_routes();

        $views = new TLD\Views();
        $views->register_public_views();
    }

    public function include_admin_assets() {
        // load only on the todo-list page
        if ( isset ( $_GET['page'] ) && $_GET['page'] === 'todo-list' ) {
            wp_enqueue_style( 'todo-list-admin', plugin_dir_url( __FILE__ ) . 'assets/css/admin.min.css', [], '1.0.0' );
            wp_enqueue_script( 'todo-list-admin', plugin_dir_url( __FILE__ ) . 'assets/js/admin.min.js', [ 'jquery' ], '1.0.0', true );
        }

        wp_enqueue_style( 'todo-list-admin', plugin_dir_url( __FILE__ ) . 'assets/css/admin.min.css', [], '1.0.0' );
        wp_enqueue_script( 'todo-list-admin', plugin_dir_url( __FILE__ ) . 'assets/js/admin.min.js', [ 'jquery' ], '1.0.0', true );
    }

    public function include_public_assets() {
        wp_enqueue_style( 'todo-list-public', plugin_dir_url( __FILE__ ) . 'assets/css/styles.min.css', [], '1.0.0' );
        wp_enqueue_script( 'todo-list-public', plugin_dir_url( __FILE__ ) . 'assets/js/main.min.js', [ 'jquery' ], '1.0.0', true );

        // add ajax url to the public script
        wp_localize_script( 'todo-list-public', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
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
 