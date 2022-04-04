<?php

declare(strict_types=1);

namespace Dasher\Resources\Pages\Concerns;

trait HasRecordBreadcrumb
{
    protected function getBreadcrumbs() : array
    {
        $resource = static::getResource();

        $breadcrumbs = [
            $resource::getUrl() => $resource::getBreadcrumb(),
        ];

        if ($resource::hasRecordTitle()) {
            if ($resource::hasPage('view') && $resource::canView($this->record)) {
                $breadcrumbs[
                    $resource::getUrl('view', ['record' => $this->record])
                ] = $this->getRecordTitle();
            } elseif ($resource::hasPage('edit') && $resource::canEdit($this->record)) {
                $breadcrumbs[
                    $resource::getUrl('edit', ['record' => $this->record])
                ] = $this->getRecordTitle();
            } else {
                $breadcrumbs[] = $this->getRecordTitle();
            }
        }

        $breadcrumbs[] = $this->getBreadcrumb();

        return $breadcrumbs;
    }
}
