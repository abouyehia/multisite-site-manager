<?php
if (!defined('ABSPATH')) exit;

//hier werden alle Blogs/Seiten gelistet.

function get_multisite_blogs() {
    $sites = get_sites([
        'number' => 9999,
        'orderby' => 'id',
        'order' => 'ASC'
    ]);
    
    $blog_list = [];
    foreach ($sites as $site) {
        $blog_list[] = [
            'blog_id' => $site->blog_id,
            'site_name' => $site->blogname,
            'domain' => $site->domain,
            'path' => $site->path,
            'registered' => $site->registered,
            'last_updated' => $site->last_updated,
            'status' => $site->deleted ? 2 : ($site->archived ? 1 : 0)
        ];
    }
    
    return $blog_list;
}