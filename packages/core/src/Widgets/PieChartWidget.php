<?php

namespace Dasher\Widgets;

class PieChartWidget extends ChartWidget
{
    protected function getType(): string
    {
        return 'pie';
    }
}
