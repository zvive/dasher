<?php

namespace Dasher\Forms\Components;

class DatePicker extends DateTimePicker
{
    public function hasTime(): bool
    {
        return false;
    }
}
