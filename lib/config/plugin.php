<?php

return array(
    'name' => 'Яндекс.Метрика',
    'description' => 'Отслеживание статистики',
    'vendor' => 985310,
    'version' => '1.0.0',
    'img' => 'img/yagoods.png',
    'shop_settings' => true,
    'frontend' => true,
    'handlers' => array(
        'frontend_head' => 'frontendHead',
        'frontend_checkout' => 'frontendCheckout',
        'frontend_footer' => 'frontendFooter'
    ),
);
//EOF
