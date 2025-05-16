<?php
if (!defined('ABSPATH')) exit;

if (defined('WP_CLI') && WP_CLI) {
    class Multisite_CLI_Command extends WP_CLI_Command {

        public function list_sites($args, $assoc_args) {
            $blogs = get_multisite_blogs();
            
            if (empty($blogs)) {
                WP_CLI::warning(__('Keine Sites gefunden.', 'multisite-site-manager'));
                return;
            }

            $formatted_blogs = array_map([$this, 'format_blog_item'], $blogs);
            
            $format = WP_CLI\Utils::get_flag_value($assoc_args, 'format', 'table');
            WP_CLI\Utils::format_items(
                $format, 
                $formatted_blogs, 
                ['Blog-ID', 'Name', 'Domain', 'Pfad', 'Erstellt', 'Status']
            );
        }

        private function format_blog_item($blog) {
            return [
                'Blog-ID' => $blog['blog_id'],
                'Name'    => $blog['site_name'],
                'Domain'  => $blog['domain'],
                'Pfad'    => $blog['path'],
                'Erstellt' => date_i18n('d.m.Y H:i', strtotime($blog['registered'])),
                'Status'  => $this->get_status_label($blog['status'])
            ];
        }

        private function get_status_label($status) {
            $status_map = [
                0 => __('Aktiv', 'multisite-site-manager'),
                1 => __('Archiviert', 'multisite-site-manager'),
                2 => __('Gelöscht', 'multisite-site-manager')
            ];
            return $status_map[$status] ?? __('Unbekannt', 'multisite-site-manager');
        }
    }

    WP_CLI::add_command('multisite', 'Multisite_CLI_Command');
}