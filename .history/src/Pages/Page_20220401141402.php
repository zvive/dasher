<?php

declare(strict_types=1);

namespace Admin\Pages;

use Closure;
use Admin\Forms;
use Livewire\Component;
use Illuminate\Support\Str;
use Admin\Concerns\CanNotify;
use Illuminate\Contracts\View\View;
use Admin\Navigation\NavigationItem;
use Illuminate\Support\Facades\Route;

class Page extends Component implements Forms\Contracts\HasForms
{
    use CanNotify;
    use Concerns\HasActions;
    protected static string $layout                 = 'filament::components.layouts.app';
    protected static string | array  $middlewares   = [];
    protected static ?string $navigationGroup       = null;
    protected static ?string $navigationIcon        = null;
    protected static ?string $navigationLabel       = null;
    protected static ?int $navigationSort           = null;
    protected static bool $shouldRegisterNavigation = true;
    protected static ?string $slug                  = null;
    protected static ?string $title                 = null;
    protected static string $view;

    protected function getActions() : array | View | null
    {
        return null;
    }

    protected function getBreadcrumbs() : array
    {
        return [];
    }

    protected function getFooter() : ?View
    {
        return null;
    }

    protected function getFooterWidgets() : array
    {
        return [];
    }

    protected function getHeader() : ?View
    {
        return null;
    }

    protected function getHeaderWidgets() : array
    {
        return [];
    }

    protected function getHeading() : string
    {
        return $this->getTitle();
    }

    protected function getLayoutData() : array
    {
        return [
            'breadcrumbs' => $this->getBreadcrumbs(),
            'title'       => $this->getTitle(),
        ];
    }

    public static function getMiddlewares() : string | array
    {
        return static::$middlewares;
    }

    protected static function getNavigationBadge() : ?string
    {
        return null;
    }

    protected static function getNavigationGroup() : ?string
    {
        return static::$navigationGroup;
    }

    protected static function getNavigationIcon() : string
    {
        return static::$navigationIcon ?? 'heroicon-o-document-text';
    }

    public static function getNavigationItems() : array
    {
        return [
            NavigationItem::make()
                ->group(static::getNavigationGroup())
                ->icon(static::getNavigationIcon())
                ->isActiveWhen(fn () : bool => \request()->routeIs(static::getRouteName()))
                ->label(static::getNavigationLabel())
                ->sort(static::getNavigationSort())
                ->badge(static::getNavigationBadge())
                ->url(static::getNavigationUrl()),
        ];
    }

    protected static function getNavigationLabel() : string
    {
        return static::$navigationLabel ?? static::$title ?? Str::of(\class_basename(static::class))
            ->kebab()
            ->replace('-', ' ')
            ->title();
    }

    protected static function getNavigationSort() : ?int
    {
        return static::$navigationSort;
    }

    protected static function getNavigationUrl() : string
    {
        return static::getUrl();
    }

    public static function getRouteName() : string
    {
        $slug = static::getSlug();

        return "filament.pages.{$slug}";
    }

    public static function getRoutes() : Closure
    {
        return function () {
            $slug = static::getSlug();

            Route::get($slug, static::class)
                ->middleware(static::getMiddlewares())
                ->name($slug);
        };
    }

    public static function getSlug() : string
    {
        return static::$slug ?? Str::of(static::$title ?? \class_basename(static::class))
            ->kebab()
            ->slug();
    }

    protected function getTitle() : string
    {
        return static::$title ?? (string) Str::of(\class_basename(static::class))
            ->kebab()
            ->replace('-', ' ')
            ->title();
    }

    public static function getUrl(array $parameters = [], bool $absolute = true) : string
    {
        return \route(static::getRouteName(), $parameters, $absolute);
    }

    protected function getViewData() : array
    {
        return [];
    }

    public static function registerNavigationItems() : void
    {
        if ( ! static::shouldRegisterNavigation()) {
            return;
        }

        Filament::registerNavigationItems(static::getNavigationItems());
    }

    public function render() : View
    {
        return \view(static::$view, $this->getViewData())
            ->layout(static::$layout, $this->getLayoutData());
    }

    protected static function shouldRegisterNavigation() : bool
    {
        return static::$shouldRegisterNavigation;
    }
}
