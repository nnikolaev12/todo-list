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