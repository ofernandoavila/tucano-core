<?php

namespace Ofernandoavila\TucanoCore\Trait;

trait AddAdminMenuTrait
{
    protected function add_menu(array $menu = [])
    {
        add_action('admin_menu', function () use ($menu) {
            $slug = sanitize_title($menu['nome']) . '-admin-menu';

            add_menu_page(
                $menu['nome'],
                $menu['nome'],
                'manage_options',
                $slug,
                $menu['callback'],
                $menu['icon'],
                56
            );

            if (isset($menu['subitems'])) {
                foreach ($menu['subitems'] as $item) {
                    if (!isset($item['slug']))
                        $item['slug'] = $slug . '-' . sanitize_title($item['nome']);

                    add_submenu_page(
                        $slug,
                        $item['nome'],
                        $item['nome'],
                        'manage_options',
                        $item['slug'],
                        $item['callback'],
                    );
                }
            }

            add_action('admin_head', function () use ($slug) {
                remove_submenu_page($slug, $slug);
            });
        });
    }
}
