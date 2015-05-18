<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopYagoodsPluginBackendSaveController extends waJsonController {

    public function execute() {
        try {
            $app_settings_model = new waAppSettingsModel();
            $settings = waRequest::post('shop_yagoods', array());
            $domains_settings = waRequest::post('domains_settings', array());
            foreach ($settings as $name => $value) {
                $app_settings_model->set(shopYagoodsPlugin::$plugin_id, $name, $value);
            }
            shopYagoods::saveDomainsSettings($domains_settings);

            $this->response['message'] = "Сохранено";
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }

}
