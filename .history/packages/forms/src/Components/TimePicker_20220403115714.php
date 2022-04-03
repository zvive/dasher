<?php

namespace Dasher\Forms\Components;

class TimePicker extends DateTimePicker
{
    public function hasDate(): bool
    {
        return false;
    }
}
