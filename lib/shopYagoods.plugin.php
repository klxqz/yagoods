<?php

class shopYagoodsPlugin extends shopPlugin {

    public static $plugin_id = array('shop', 'yagoods');
    public static $default_settings = array(
        'status' => 0,
        'counter' => 1,
        'counter_id' => 'XXXXXXXX',
        'webvisor' => 1,
        'clickmap' => 1,
        'trackLinks' => 1,
        'accurateTrackBounce' => 1,
        'informer' => 1,
        'ut' => 0,
        'async' => 0,
        'trackHash' => 0,
        'order_statistic' => 1,
        'target_name' => 'order',
    );

    public function frontendCheckout($param) {
        $settings = shopYagoods::getDomainSettings();
        if (!$this->getSettings('status') || !$settings['status'] || $param['step'] != 'success' || !$settings['order_statistic']) {
            return false;
        }

        $order_id = waRequest::get('order_id');
        if (!$order_id) {
            $order_id = wa()->getStorage()->get('shop/order_id');
        }

        $order_model = new shopOrderModel();
        $order = $order_model->getById($order_id);

        $order_params_model = new shopOrderParamsModel();
        $order['params'] = $order_params_model->get($order_id);
        $order_items_model = new shopOrderItemsModel();
        $order['items'] = $order_items_model->getByField('order_id', $order_id, true);

        $view = wa()->getView();
        $view->assign('settings', $settings);
        $view->assign('order', $order);
        return $view->fetch($this->path . '/templates/CheckoutSuccess.html');
    }

    public function frontendHead() {
        $settings = shopYagoods::getDomainSettings();
        if (!$this->getSettings('status') || !$settings['status'] || !$settings['counter']) {
            return false;
        }

        $view = wa()->getView();
        $view->assign('settings', $settings);
        return $view->fetch($this->path . '/templates/FrontendHead.html');
    }

    public function frontendFooter() {
        return self::informer();
    }

    public static function informer() {
        $app_settings_model = new waAppSettingsModel();
        $settings = shopYagoods::getDomainSettings();
        if (!$app_settings_model->get(shopYagoodsPlugin::$plugin_id, 'status') || !$settings['status'] || !$settings['informer']) {
            return false;
        }
        $view = wa()->getView();
        $view->assign('settings', $settings);
        $path = wa()->getAppPath('plugins/yagoods/templates/FrontendFooter.html', 'shop');
        return $view->fetch($path);
    }

}
