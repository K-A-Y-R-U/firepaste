<?php

return [
    'show_application_tab' => true,
    'show_analytics_tab' => true,
    'show_seo_tab' => true,
    'show_email_tab' => true,
    'show_social_networks_tab' => true,
    'show_logo_and_favicon' => true,
    'expiration_cache_config_time' => 60,

    // Pestañas personalizadas
    'show_custom_tabs' => true,
    'custom_tabs' => [
        'more_configs' => [
            'label' => 'Configuraciones Adicionales',
            'icon' => 'heroicon-o-cog',
            'columns' => 1,
            'fields' => [
                'footer_text' => [
                    'type' => \Joaopaulolndev\FilamentGeneralSettings\Enums\TypeFieldEnum::Textarea->value,
                    'label' => 'Texto del Footer',
                    'placeholder' => 'Ingresa el texto del pie de página',
                    'required' => false,
                    'rows' => 3,
                    'rules' => '',
                ],
                'footer_copyright' => [
                    'type' => \Joaopaulolndev\FilamentGeneralSettings\Enums\TypeFieldEnum::Text->value,
                    'label' => 'Copyright del Footer',
                    'placeholder' => '© 2025 Tu Empresa',
                    'required' => false,
                    'rules' => '',
                ],
            ],
        ],
        'api_configs' => [
            'label' => 'Configuraciones de API',
            'icon' => 'heroicon-o-code-bracket',
            'columns' => 1,
            'fields' => [
                // Configuraciones del acortador de URLs
                'url_shortener_enabled' => [
                    'type' => \Joaopaulolndev\FilamentGeneralSettings\Enums\TypeFieldEnum::Boolean->value,
                    'label' => 'Activar Acortador de URLs',
                    'placeholder' => 'Boolean',
                    'required' => false,
                    'rules' => '',
                    'helperText' => 'Activar/desactivar el acortamiento automático de enlaces externos',
                ],
                'url_shortener_api_full' => [
                    'type' => \Joaopaulolndev\FilamentGeneralSettings\Enums\TypeFieldEnum::Textarea->value,
                    'label' => 'URL Completa de la API Acortador',
                    'placeholder' => 'https://url.firepaste.com/api?api=29e30ac9485d9951a742f5488aac8f8c2a44e513&url=',
                    'required' => false,
                    'rows' => 3,
                    'rules' => '',
                    'helperText' => 'URL completa del servicio de acortamiento (incluye API key y parámetros)',
                ],
            ],
        ],
    ],
];