<?php
/*
Plugin Name: RC To-Do List
Plugin URI: http://rc-creation.fr
Description: Gérez vos tâches grâce à RC To-Do List !
Version: 0.1
Author: RC Création
Author URI: http://rc-creation.fr
*/

// Installation du Plugin

function installer()
{
    //Récupère la variable global qui permet d'échanger avec la base de données
    global $wpdb;

    $wpdb->query("CREATE TABLE IF NOT EXISTS
    {$wpdb->prefix}to_do_list (id INT
    AUTO_INCREMENT PRIMARY KEY, nom_tache
    VARCHAR(255) NOT NULL, categorie_id
    INT, description VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL);");

    $wpdb->query("CREATE TABLE IF NOT EXISTS
    {$wpdb->prefix}to_do_categorie (id INT
    AUTO_INCREMENT PRIMARY KEY, nom
    VARCHAR(255) NOT NULL);");

    $nom_categorie = 'Default';

    $wpdb->insert(
        "{$wpdb->prefix}to_do_categorie",
        array('nom' => $nom_categorie)
    );
}

// Désinstallation du Plugin

function desinstaller()
{
    global $wpdb;

    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}to_do_list");

    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}to_do_categorie");
}


// Ajouter le plugin au Menu Administrateur

function ajouterToDoAuMenu()
{
    add_menu_page(
        'To-Do List',
        'To-Do List',
        'manage_options',
        'gestion-taches',
        'afficherFormulaire',
        'dashicons-index-card',
        4
    );

    add_submenu_page(
        'gestion-taches',
        'Catégories',
        'Catégories',
        'manage_options',
        'to-do-categorie',
        'afficherCategories',
        1

    );
}


// Afficher les Taches

function afficherFormulaire()
{
    //Vérifie si l'utilisateur à les droits pour afficher la page
    if (!current_user_can('manage_options')) {
        wp_die(__('Vous n\'avez pas les droits pour
    accéder à cette page.'));
    }

    include(sprintf(
        "%s/formulaire.php",
        dirname(__FILE__)
    ));
}


// Afficher les Catégories

function afficherCategories()
{
    //Vérifie si l'utilisateur à les droits pour afficher la page
    if (!current_user_can('manage_options')) {
        wp_die(__('Vous n\'avez pas les droits pour
    accéder à cette page.'));
    }

    include(sprintf(
        "%s/categories.php",
        dirname(__FILE__)
    ));
}

// Définition du Shortcode Général

function listeTaches()
{
    global $wpdb;
    $liste_taches = $wpdb->get_results("SELECT *
    FROM {$wpdb->prefix}to_do_list");
    $html = array();
    if ($liste_taches) {
        $html[] = "<ul>";
        foreach ($liste_taches as $tache) {
            $html[] = "<li>" . $tache->nom_tache . "<br/>" . $tache->description . "</li>";
        }
        $html[] = "</ul>";
    } else {
        $html[] = "Pas de tâches à faire.";
    }
    return implode('', $html);
}

function tacheSeule($atts)
{
    global $wpdb;
    $attributs = shortcode_atts(array('id' => false), $atts);
    $liste_taches = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}to_do_list WHERE id={$attributs['id']} ");
    $html = array();
    if ($liste_taches) {
        $html[] = "<ul>";
        foreach ($liste_taches as $tache) {
            $html[] = "<li>" . $tache->nom_tache . "<br/>" . $tache->description . "</li>";
        }
        $html[] = "</ul>";
    } else {
        $html[] = "Pas de tâches à faire.";
    }
    return implode('', $html);
}

function plugin_enqueue_styles()
{
    wp_register_style('plugin-style', plugins_url('css/style.css', __FILE__));
    wp_enqueue_style('plugin-style');
    wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css');
    wp_enqueue_script('fontawesome', 'https://kit.fontawesome.com/8c3f33f870.js');
    wp_enqueue_script('mdbcss', 'https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/css/mdb.min.css');
    wp_enqueue_script('mdbjs', 'https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/js/mdb.min.js');
    wp_enqueue_script('bootstrap4js', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js');
    wp_enqueue_script('popperjs', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js');
    wp_enqueue_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js');
}

// Hook qui se déclenche lors de l'installation du plugin

register_activation_hook(__FILE__, 'installer');
register_uninstall_hook(__FILE__, 'desinstaller');
add_shortcode('liste_taches', 'listeTaches');
add_shortcode('tache_seule', 'tacheSeule');
add_action('admin_menu', 'ajouterToDoAuMenu');
add_action('wp_enqueue_scripts', 'plugin_enqueue_styles');
add_action('admin_enqueue_scripts', 'plugin_enqueue_styles');
