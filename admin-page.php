<?php
if (!defined('ABSPATH')) exit;

// Menüeintrag im Admin Bereich
add_action('network_admin_menu', 'msm_add_network_admin_menu');
function msm_add_network_admin_menu() {
    add_menu_page(
        __('Site-Übersicht', 'multisite-site-manager'),
        __('Site-Übersicht', 'multisite-site-manager'),
        'manage_network',
        'site-overview',
        'msm_render_site_overview_page',
        'dashicons-admin-multisite',
        6
    );
}

// Tabellenstruktur 
class Site_Overview_Table extends WP_List_Table {
    public function prepare_items() {
        $per_page = 20;
        $current_page = $this->get_pagenum();
        
        $this->items = array_map(function($site) {
            return [
                'blog_id' => $site->blog_id,
                'site_name' => $site->blogname,
                'domain' => $site->domain,
                'path' => $site->path,
                'registered' => date_i18n('d.m.Y H:i', strtotime($site->registered)),
                'status' => $this->get_status_label($site)
            ];
        }, get_sites([
            'number' => $per_page,
            'offset' => ($current_page - 1) * $per_page,
            'orderby' => 'id'
        ]));

        $this->set_pagination_args([
            'total_items' => get_sites(['count' => true]),
            'per_page' => $per_page
        ]);
        
        $this->_column_headers = [$this->get_columns(), [], []];
    }

    public function get_columns() {
        return [
            'blog_id' => __('Blog-ID', 'multisite-site-manager'),
            'site_name' => __('Name', 'multisite-site-manager'),
            'domain' => __('Domain', 'multisite-site-manager'),
            'path' => __('Pfad', 'multisite-site-manager'),
            'registered' => __('Erstellt', 'multisite-site-manager'),
            'status' => __('Status', 'multisite-site-manager')
        ];
    }

    public function column_default($item, $column_name) {
        return $item[$column_name] ?? '';
    }

    public function column_site_name($item) {
        $actions = [
            'visit' => sprintf(
                '<a href="%s" target="_blank">%s</a>',
                esc_url(get_home_url($item['blog_id'])),
                __('Besuchen', 'multisite-site-manager')
            ),
            'admin' => sprintf(
                '<a href="%s">%s</a>',
                esc_url(get_admin_url($item['blog_id'])),
                __('Admin', 'multisite-site-manager')
            )
        ];
        return sprintf('%s %s', 
            esc_html($item['site_name']),
            $this->row_actions($actions)
        );
    }

    private function get_status_label($site) {
        if ($site->deleted) return __('Gelöscht', 'multisite-site-manager');
        if ($site->archived) return __('Archiviert', 'multisite-site-manager');
        return __('Aktiv', 'multisite-site-manager');
    }
}

// Admin-Seiten Rendering
function msm_render_site_overview_page() {
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php esc_html_e('Site-Übersicht', 'multisite-site-manager') ?></h1>
        
        <?php
        $table = new Site_Overview_Table();
        $table->prepare_items();
        $table->display();
        ?>
    </div>
    <?php
}