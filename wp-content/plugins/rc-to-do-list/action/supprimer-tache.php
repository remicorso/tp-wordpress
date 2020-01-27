<?php


global $wpdb;


$wpdb->prepare("DELETE FROM {$wpdb->prefix}to_do_list WHERE id={$_GET['id']}");

wp_redirect(get_admin_url() . 'admin.php?page=gestion-taches');
