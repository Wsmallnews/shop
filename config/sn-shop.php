<?php

// config for Wsmallnews/Shop
return [

    'media' => [
        'fallback' => [
            'url' => env('SHOP_FALLBACK_IMAGE_URL', null),
            'path' => env('SHOP_FALLBACK_IMAGE_PATH', null),
        ],
    ],
];
