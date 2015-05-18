<?php

class shopYagoods {

    public static function getRouteHash() {
        $domain = wa()->getRouting()->getDomain(null, true);
        $route = wa()->getRouting()->getRoute();
        return md5($domain . '/' . $route['url']);
    }

    public static function getDomainsSettings() {

        $cache = new waSerializeCache('shopYagoodsPlugin');

        if ($cache && $cache->isCached()) {
            $domains_settings = $cache->get();
        } else {
            $app_settings_model = new waAppSettingsModel();
            $routing = wa()->getRouting();
            $domains_routes = $routing->getByApp('shop');
            $app_settings_model->get(shopYagoodsPlugin::$plugin_id, 'domains_settings');
            $domains_settings = json_decode($app_settings_model->get(shopYagoodsPlugin::$plugin_id, 'domains_settings'), true);

            if (empty($domains_settings)) {
                $domains_settings = array();
            }

            foreach ($domains_routes as $domain => $routes) {
                foreach ($routes as $route) {
                    $domain_route = md5($domain . '/' . $route['url']);
                    if (empty($domains_settings[$domain_route])) {
                        $domains_settings[$domain_route] = shopYagoodsPlugin::$default_settings;
                    }
                }
            }
            if ($domains_settings && $cache) {
                $cache->set($domains_settings);
            }
        }

        return $domains_settings;
    }

    public static function saveDomainsSettings($domains_settings) {
        $app_settings_model = new waAppSettingsModel();
        $routing = wa()->getRouting();
        $domains_routes = $routing->getByApp('shop');

        foreach ($domains_routes as $domain => $routes) {
            foreach ($routes as $route) {
                $domain_route = md5($domain . '/' . $route['url']);
            }
        }

        $app_settings_model->set(shopYagoodsPlugin::$plugin_id, 'domains_settings', json_encode($domains_settings));
        $cache = new waSerializeCache('shopYagoodsPlugin');
        if ($cache && $cache->isCached()) {
            $cache->delete();
        }
    }

    public static function getDomainSettings() {
        $domains_settings = self::getDomainsSettings();
        $hash = self::getRouteHash();
        return $domains_settings[$hash];
    }

}
