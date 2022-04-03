<?php

declare(strict_types=1);

namespace Admin\Pages\Actions\Concerns;

use Closure;

trait CanOpenUrl
{
    protected bool $shouldOpenUrlInNewTab  = false;
    protected string | Closure | null $url = null;

    public function getUrl() : ?string
    {
        return \value($this->url);
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

    public function url(string | Closure | null $url, bool $shouldOpenInNewTab = false) : static
    {
        $this->shouldOpenUrlInNewTab = $shouldOpenInNewTab;
        $this->url                   = $url;

        return $this;
    }
}
