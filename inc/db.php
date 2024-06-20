<?php

namespace TLD;

defined( 'ABSPATH' ) ?: exit;

class DB {

    public function __construct() {
        global $wpdb;
        
        $this->db = $wpdb;
        $this->table_name = $wpdb->prefix . 'tasks';
    }

    /**
     * Register the AJAX hooks for the plugin
     */
    public function register_ajax_hooks() {
        add_action( 'wp_ajax_get_tasks', [ $this, 'get_all_tasks' ] );
        add_action( 'wp_ajax_nopriv_get_tasks', [ $this, 'get_all_tasks' ]);

        add_action( 'wp_ajax_create_task', [ $this, 'create_new_task' ] );
        add_action( 'wp_ajax_update_task', [ $this, 'update_task' ] );
        add_action( 'wp_ajax_delete_task', [ $this, 'delete_task' ] );
    }

    /**
     * Register REST route for Guttenberg block consumption
     */
    public function register_rest_routes()
    {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'tasks/v1', '/all', [
                'methods' => 'GET',
                'callback' => [ $this, 'rest_get_all_tasks' ],
                'permission_callback' => '__return_true'
            ] );
        } );
    }

    public function rest_get_all_tasks() {
        $result = $this->db->get_results("SELECT * FROM $this->table_name");

        return rest_ensure_response( $result );
    }

    /**
     * Get all tasks from the database from an AJAX request
     */
    public function get_all_tasks() {
        $tasks = $this->db->get_results("SELECT * FROM $this->table_name");
        $result = [
            'type' => 'error',
            'data' => $tasks
        ];

        if ( ! empty( $tasks ) ) {
            $result['type'] = 'success';

        }

        echo json_encode($result);
        die;
    }

    /**
     * Create a new task in the database from an AJAX request
     */
    public function create_new_task() {
        $result = [
            'type' => 'error',
        ];

        if ( empty( $_POST['title']) || empty( $_POST['description'] ) ) {
            echo json_encode( $result );
            die;
        }

        $title = sanitize_text_field( $_POST['title'] );
        $description = sanitize_text_field( $_POST['description'] );

        $create = $this->db->insert($this->table_name, [
            'title' => $title,
            'description' => $description
        ]);

        if ( $create ) {
            $result['type'] = 'success';
        }

        echo json_encode( $result );
        die;
    }

    /**
     * Update a task in the database from an AJAX request
     */
    public function update_task()
    {
        $result = [
            'type' => 'error',
        ];

        if ( empty( $_POST['id'] ) || empty( $_POST['title'] ) || empty( $_POST['description'] ) ) {
            echo json_encode( $result );
            die;
        }

        $id = intval( $_POST['id'] );
        $title = sanitize_text_field( $_POST['title'] );
        $description = sanitize_text_field( $_POST['description'] );

        $update = $this->db->update( $this->table_name, [
            'title' => $title,
            'description' => $description
        ], ['id' => $id]);

        if ( $update ) {
            $result['type'] = 'success';
        }

        echo json_encode( $result );
        die;
    }

    /**
     * Delete a task from the database from an AJAX request
     */
    public function delete_task() {
        $result = [
            'type' => 'error',
        ];

        if ( empty( $_POST['id'] ) ) {
            echo json_encode( $result );
            die;
        }

        $id = intval( $_POST['id'] );

        $delete = $this->db->delete( $this->table_name, [
            'id' => $id
        ] );

        if ( $delete ) {
            $result['type'] = 'success';
        }

        echo json_encode( $result );
        die;
    }

    /**
     * Create the tasks table on plugin activation
     */
    public function migrate() {
        $charset_collate = $this->db->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            description text NOT NULL,
            created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    /**
     * Drop the tasks table on plugin uninstall
     */
    public function clean() {
        $this->db->query("DROP TABLE IF EXISTS $this->table_name");
    }
}