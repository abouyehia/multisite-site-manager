<?php
/*
Plugin Name: Multisite Site Manager
Description: Testaufgabe
    1. Schreibe eine PHP-Funktion, die eine Liste aller Blogs innerhalb einer WordPress-Multisite
    zurückgibt.
    2. Implementiere eine WP-CLI-Erweiterung (wp multisite list-sites), die diese Liste in der
    Kommandozeile ausgibt.
    3. Optionale Erweiterung: Erstelle eine Admin-Seite innerhalb des Network Dashboards, die
    eine Tabelle aller Sites anzeigt.
Version: 1.0
Author: Mohamad Abou Yehia
Text Domain: multisite-site-manager
*/

// WP Sicherheit
if (!defined('ABSPATH')) {
    exit;
}

// Hier wird der Hook Aktiviert
register_activation_hook(__FILE__, 'msm_activate_plugin');
function msm_activate_plugin() {
    if (!file_exists(plugin_dir_path(__FILE__) . 'includes')) {
        wp_mkdir_p(plugin_dir_path(__FILE__) . 'includes');
    }
}

// Die dateien aus den Sub Ordner mit require_once einbinden
require_once plugin_dir_path(__FILE__) . 'includes/multisite-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/wp-cli-command.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';