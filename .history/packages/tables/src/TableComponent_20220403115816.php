<?php

namespace Dasher\Tables;

use Dasher\Forms;
use Livewire\Component;

abstract class TableComponent extends Component implements Forms\Contracts\HasForms, Contracts\HasTable
{
    use Concerns\InteractsWithTable;
}
