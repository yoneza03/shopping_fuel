<?php

use Illuminate\Foundation\Configuration\Configuration;

return function (Configuration $config) {
    $config
        ->withProviders([
            Illuminate\Foundation\Providers\FoundationServiceProvider::class,
            Illuminate\View\ViewServiceProvider::class,
            Illuminate\Routing\RoutingServiceProvider::class,
            Illuminate\Filesystem\FilesystemServiceProvider::class,
            Intervention\Image\ImageServiceProvider::class,
        ])
        ->withAliases([
            'Image' => Intervention\Image\Facades\Image::class,
        ]);
};