<?php

namespace Dasher\Resources\Pages;

class ManageRecords extends ListRecords
{
    use ListRecords\Concerns\CanCreateRecords;
    use ListRecords\Concerns\CanDeleteRecords;
    use ListRecords\Concerns\CanEditRecords;
}
