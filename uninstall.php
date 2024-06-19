<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

require_once __DIR__ . '/inc/autoloader.php';

$db = new TLD\DB();
$db->clean();
