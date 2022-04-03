<?php

declare(strict_types=1);

use Dasher\Pages;
use Dasher\Widgets;
use Dasher\Resources;
use Dasher\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Dasher\Http\Middleware\MirrorConfigToSubpackages;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Dasher\Http\Middleware\DispatchServingDasherEvent;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

return [
    'multiple_dashboards' => true,
    'dashboards'          => [
        [
            'name'      => 'Admin', // module name.
            'root_path' => 'app/Modules/Admin', // location to generate files.
            'namespace' => 'Modules\\Admin', // namespace for stubs
            'route'     => 'admin', // route
            'core_path' => 'admin', // routes and views
            'domain'    => null, // specific domain if relevent.. i.e. admin.example.com
            'home_url' => '/',
            'brand'     => env('APP_NAME'),
            'dark_mode' => false,
            'favicon'   => null,
            'logo'      => null,
            'pages' => [
                Pages\Dashboard::class,
            ],
            'resources' => [

            ],
            'widgets' => [
                Widgets\AccountWidget::class,
                Widgets\DasherInfoWidget::class,
            ]

            // Include any of the settings below to override the defaults.
    ],


    /*
    |--------------------------------------------------------------------------
    | Auth
    |--------------------------------------------------------------------------
    |
    | This is the configuration that Dasher will use to handle authentication
    | into the admin panel.
    |
    */

    'auth' => [
        'guard' => env('DASHER_AUTH_GUARD', 'web'),
        'pages' => [
            'login' => \Dasher\Http\Livewire\Auth\Login::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Pages
    |--------------------------------------------------------------------------
    |
    | This is the namespace and directory that Dasher will automatically
    | register pages from. You may also register pages here.
    |
    */

    // 'pages' => [
    //     'namespace' => 'App\\Dasher\\Pages',
    //     'path'      => app_path('Dasher/Pages'),
    //     'register'  => [
    //         Pages\Dashboard::class,
    //     ],
    // ],

    /*
    |--------------------------------------------------------------------------
    | Resources
    |--------------------------------------------------------------------------
    |
    | This is the namespace and directory that Dasher will automatically
    | register resources from. You may also register resources here.
    |
    */

    // 'resources' => [
    //     'namespace' => 'App\\Dasher\\Resources',
    //     'path'      => app_path('Dasher/Resources'),
    //     'register'  => [],
    // ],

    /*
    |--------------------------------------------------------------------------
    | Widgets
    |--------------------------------------------------------------------------
    |
    | This is the namespace and directory that Dasher will automatically
    | register dashboard widgets from. You may also register widgets here.
    |
    */

    // 'widgets' => [
    //     'namespace' => 'App\\Dasher\\Widgets',
    //     'path'      => app_path('Dasher/Widgets'),
    //     'register'  => [
    //         Widgets\AccountWidget::class,
    //         Widgets\DasherInfoWidget::class,
    //     ],
    // ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | This is the namespace and directory that Dasher will automatically
    | register Livewire components inside.
    |
    */

    // 'livewire' => [
    //     'namespace' => 'App\\Dasher',
    //     'path'      => app_path('Dasher'),
    // ],

    /*
    |--------------------------------------------------------------------------
    | Dark mode
    |--------------------------------------------------------------------------
    |
    | By enabling this feature, your users are able to select between a light
    | and dark appearance for the admin panel, or let their system decide.
    |
    */

    'dark_mode' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the general layout of the admin panel.
    |
    | You may configure the max content width from `xl` to `7xl`, or `full`
    | for no max width.
    |
    */

    'layout' => [
        'forms' => [
            'actions' => [
                'alignment' => 'left',
            ],
            'have_inline_labels' => false,
        ],
        'footer' => [
            'should_show_logo' => true,
        ],
        'max_content_width' => null,
        'notifications'     => [
            'vertical_alignment' => 'top',
            'alignment'          => 'center',
        ],
        'sidebar' => [
            'is_collapsible_on_desktop' => false,
        ],
        'tables' => [
            'actions' => [
                'type' => \Dasher\Tables\Actions\LinkAction::class,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | This is the path to the favicon used for pages in the admin panel.
    |
    */

    'favicon' => null,

    /*
    |--------------------------------------------------------------------------
    | Default Avatar Provider
    |--------------------------------------------------------------------------
    |
    | This is the service that will be used to retrieve default avatars if one
    | has not been uploaded.
    |
    */

    'default_avatar_provider' => \Dasher\AvatarProviders\UiAvatarsProvider::class,

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | This is the storage disk Dasher will use to put media. You may use any
    | of the disks defined in the `config/filesystems.php`.
    |
    */

    'default_filesystem_disk' => env('DASHER_FILESYSTEM_DRIVER', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | You may customise the middleware stack that Dasher uses to handle
    | requests.
    |
    */

    'middleware' => [
        'auth' => [
            Authenticate::class,
        ],
        'base' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DispatchServingDasherEvent::class,
            MirrorConfigToSubpackages::class,
        ],
    ],

];
