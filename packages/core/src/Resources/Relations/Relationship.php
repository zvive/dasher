<?php

declare(strict_types=1);

namespace Dasher\Resources\Relations;

use Dasher\Tables;
use Livewire\Component;
use Dasher\Facades\Dasher;
use Dasher\Resources\Form;
use Dasher\Resources\Table;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Dasher\Http\Livewire\Concerns\CanNotify;
use Illuminate\Database\Eloquent\Relations\Relation;

class Relationship extends Component implements Tables\Contracts\HasTable
{
    use CanNotify;
    use Tables\Concerns\InteractsWithTable;
    public Model $ownerRecord;
    protected static ?string $inverseRelationship  = null;
    protected static ?string $label                = null;
    protected static ?string $pluralLabel          = null;
    protected static ?string $recordTitleAttribute = null;
    protected static string $relationship;
    protected ?Form $resourceForm   = null;
    protected ?Table $resourceTable = null;
    protected static ?string $title = null;
    protected static string $view;

    protected function callHook(string $hook) : void
    {
        if ( ! \method_exists($this, $hook)) {
            return;
        }

        $this->{$hook}();
    }

    protected function can(string $action, ?Model $record = null) : bool
    {
        $policy = Gate::getPolicyFor($model = $this->getRelatedModel());

        if ($policy === null || ( ! \method_exists($policy, $action))) {
            return true;
        }

        return Gate::forUser(Dasher::auth()->user())->check($action, $record ?? $model);
    }

    public static function canViewForRecord(Model $ownerRecord) : bool
    {
        $model = $ownerRecord->{static::getRelationshipName()}()->getQuery()->getModel()::class;

        $policy = Gate::getPolicyFor($model);
        $action = 'viewAny';

        if ($policy === null || ( ! \method_exists($policy, $action))) {
            return true;
        }

        return Gate::forUser(Dasher::auth()->user())->check($action, $model);
    }

    public static function form(Form $form) : Form
    {
        return $form;
    }

    protected function getDefaultTableSortColumn() : ?string
    {
        return $this->getResourceTable()->getDefaultSortColumn();
    }

    protected function getDefaultTableSortDirection() : ?string
    {
        return $this->getResourceTable()->getDefaultSortDirection();
    }

    protected function getInverseRelationshipFor(Model $record) : Relation | Builder
    {
        return $record->{$this->getInverseRelationshipName()}();
    }

    public function getInverseRelationshipName() : string
    {
        return static::$inverseRelationship ?? (string) Str::of(\class_basename($this->ownerRecord))
            ->lower()
            ->plural()
            ->camel();
    }

    protected static function getPluralRecordLabel() : string
    {
        return static::$pluralLabel ?? (string) Str::of(static::getRelationshipName())
            ->kebab()
            ->replace('-', ' ');
    }

    protected static function getRecordLabel() : string
    {
        return static::$label ?? Str::singular(static::getPluralRecordLabel());
    }

    public static function getRecordTitle(?Model $record) : ?string
    {
        return $record?->getAttribute(static::getRecordTitleAttribute()) ?? $record?->getKey();
    }

    public static function getRecordTitleAttribute() : ?string
    {
        return static::$recordTitleAttribute;
    }

    protected function getRelatedModel() : string
    {
        return $this->getRelationship()->getQuery()->getModel()::class;
    }

    protected function getRelationship() : Relation | Builder
    {
        return $this->ownerRecord->{static::getRelationshipName()}();
    }

    public static function getRelationshipName() : string
    {
        return static::$relationship;
    }

    protected function getResourceForm(?int $columns = null) : Form
    {
        if ( ! $this->resourceForm) {
            $this->resourceForm = static::form(
                Form::make()->columns($columns),
            );
        }

        return $this->resourceForm;
    }

    protected function getResourceTable() : Table
    {
        if ( ! $this->resourceTable) {
            $this->resourceTable = Table::make();
        }

        return $this->resourceTable;
    }

    protected function getTableActions() : array
    {
        return $this->getResourceTable()->getActions();
    }

    protected function getTableBulkActions() : array
    {
        return $this->getResourceTable()->getBulkActions();
    }

    protected function getTableColumns() : array
    {
        return $this->getResourceTable()->getColumns();
    }

    protected function getTableFilters() : array
    {
        return $this->getResourceTable()->getFilters();
    }

    protected function getTableHeaderActions() : array
    {
        return $this->getResourceTable()->getHeaderActions();
    }

    protected function getTableHeading() : ?string
    {
        return static::getTitle();
    }

    protected function getTableQuery() : Builder
    {
        return $this->getRelationship()->getQuery();
    }

    protected function getTableQueryStringIdentifier() : ?string
    {
        return \lcfirst(\class_basename(static::class));
    }

    public static function getTitle() : string
    {
        return static::$title ?? Str::title(static::getPluralRecordLabel());
    }

    protected function getViewData() : array
    {
        return [];
    }

    public function render() : View
    {
        return \view(static::$view, $this->getViewData());
    }

    public static function table(Table $table) : Table
    {
        return $table;
    }
}
