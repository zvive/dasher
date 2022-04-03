<?php

declare(strict_types=1);

namespace Admin\Navigation;

use Closure;

class NavigationItem
{
    protected ?string $badge = null;
    protected ?string $group = null;
    protected string $icon;
    protected ?Closure $isActiveWhen = null;
    protected string $label;
    protected bool $shouldOpenUrlInNewTab  = false;
    protected ?int $sort                   = null;
    protected string | Closure | null $url = null;

    final public function __construct() {}

    public function badge(?string $badge) : static
    {
        $this->badge = $badge;

        return $this;
    }

    public function getBadge() : ?string
    {
        return $this->badge;
    }

    public function getGroup() : ?string
    {
        return $this->group;
    }

    public function getIcon() : string
    {
        return $this->icon;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function getSort() : int
    {
        return $this->sort ?? -1;
    }

    public function getUrl() : ?string
    {
        return \value($this->url);
    }

    public function group(?string $group) : static
    {
        $this->group = $group;

        return $this;
    }

    public function icon(string $icon) : static
    {
        $this->icon = $icon;

        return $this;
    }

    public function isActive() : bool
    {
        $callback = $this->isActiveWhen;

        if ($callback === null) {
            return false;
        }

        return \app()->call($callback);
    }

    public function isActiveWhen(Closure $callback) : static
    {
        $this->isActiveWhen = $callback;

        return $this;
    }

    public function label(string $label) : static
    {
        $this->label = $label;

        return $this;
    }

    public static function make() : static
    {
        return \app(static::class);
    }

    public function openUrlInNewTab(bool $condition = true) : static
    {
        $this->shouldOpenUrlInNewTab = $condition;

        return $this;
    }

    public function shouldOpenUrlInNewTab() : bool
    {
        return $this->shouldOpenUrlInNewTab;
    }

    public function sort(?int $sort) : static
    {
        $this->sort = $sort;

        return $this;
    }

    public function url(string | Closure | null $url, bool $shouldOpenInNewTab = false) : static
    {
        $this->shouldOpenUrlInNewTab = $shouldOpenInNewTab;
        $this->url                   = $url;

        return $this;
    }
}
