<?php

declare(strict_types=1);

namespace Dasher\Resources\Relations\Concerns;

use Dasher\Tables;
use Dasher\Resources\Form;
use Illuminate\Support\Arr;
use Dasher\Forms\Components\Select;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Dasher\Resources\Relations\Relationship;
use Dasher\Tables\Actions\Modal\Actions\ButtonAction;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait CanAttachRecords
{
    protected static bool $canAttachAnother = true;
    protected ?Form $resourceAttachForm     = null;

    public function attach(bool $another = false) : void
    {
        $form = $this->getMountedTableActionForm();

        $this->callHook('beforeValidate');
        $this->callHook('beforeCreateValidate');

        $data = $form->getState();

        $this->callHook('afterValidate');
        $this->callHook('afterCreateValidate');

        $this->callHook('beforeAttach');

        /** @var BelongsToMany $relationship */
        $relationship = $this->getRelationship();

        $pivotColumns = $relationship->getPivotColumns();

        $record = $relationship->getRelated()->query()->find($data['recordId']);
        $relationship->attach($record, Arr::only($data, $pivotColumns));

        $this->callHook('afterAttach');

        if ($another) {
            $form->fill();
        }

        if (\filled($this->getAttachedNotificationMessage())) {
            $this->notify('success', $this->getAttachedNotificationMessage());
        }
    }

    public function attachAndAttachAnother() : void
    {
        $this->attach(another: true);
    }

    public static function attachForm(Form $form) : Form
    {
        return $form->schema([
            static::getAttachFormRecordSelect(),
        ]);
    }

    protected function canAttach() : bool
    {
        return $this->can('attach');
    }

    protected static function canAttachAnother() : bool
    {
        return static::$canAttachAnother;
    }

    public static function disableAttachAnother() : void
    {
        static::$canAttachAnother = false;
    }

    protected function fillAttachForm() : void
    {
        $this->callHook('beforeFill');
        $this->callHook('beforeAttachFill');

        $this->getMountedTableActionForm()->fill();

        $this->callHook('afterFill');
        $this->callHook('afterAttachFill');
    }

    protected function getAttachAction() : Tables\Actions\ButtonAction
    {
        return Tables\Actions\ButtonAction::make('attach')
            ->label(\__('dasher::resources/relation-managers/attach.action.label'))
            ->form($this->getAttachFormSchema())
            ->mountUsing(fn () => $this->fillAttachForm())
            ->modalActions($this->getAttachActionModalActions())
            ->modalHeading(\__('dasher::resources/relation-managers/attach.action.modal.heading', ['label' => static::getRecordLabel()]))
            ->modalWidth('lg')
            ->action(fn () => $this->attach())
            ->color('secondary');
    }

    protected function getAttachActionAttachAndAttachAnotherModalAction() : Tables\Actions\Modal\Actions\Action
    {
        return ButtonAction::make('attachAndAttachAnother')
            ->label(\__('dasher::resources/relation-managers/attach.action.modal.actions.attach_and_attach_another.label'))
            ->action('attachAndAttachAnother')
            ->color('secondary');
    }

    protected function getAttachActionAttachModalAction() : Tables\Actions\Modal\Actions\Action
    {
        return ButtonAction::make('attach')
            ->label(\__('dasher::resources/relation-managers/attach.action.modal.actions.attach.label'))
            ->submit('callMountedTableAction')
            ->color('primary');
    }

    protected function getAttachActionCancelModalAction() : Tables\Actions\Modal\Actions\Action
    {
        return ButtonAction::make('cancel')
            ->label(\__('tables::table.actions.modal.buttons.cancel.label'))
            ->cancel()
            ->color('secondary');
    }

    protected function getAttachActionModalActions() : array
    {
        return \array_merge(
            [$this->getAttachActionAttachModalAction()],
            static::canAttachAnother() ? [$this->getAttachActionAttachAndAttachAnotherModalAction()] : [],
            [$this->getAttachActionCancelModalAction()],
        );
    }

    protected function getAttachedNotificationMessage() : ?string
    {
        return \__('dasher::resources/relation-managers/attach.action.messages.attached');
    }

    protected static function getAttachFormRecordSelect() : Select
    {
        return Select::make('recordId')
            ->label(\__('dasher::resources/relation-managers/attach.action.modal.fields.record_id.label'))
            ->searchable()
            ->getSearchResultsUsing(function (Select $component, Relationship $livewire, string $query) : array {
                $relationship = $livewire->getRelationship();

                $displayColumnName = static::getRecordTitleAttribute();

                /** @var Builder $relationshipQuery */
                $relationshipQuery = $relationship->getRelated()->query()->orderBy($displayColumnName);

                $query = \strtolower($query);

                /** @var Connection $databaseConnection */
                $databaseConnection = $relationshipQuery->getConnection();

                $searchOperator = match ($databaseConnection->getDriverName()) {
                    'pgsql' => 'ilike',
                    default => 'like',
                };

                $searchColumns = $component->getSearchColumns() ?? [$displayColumnName];
                $isFirst = true;

                foreach ($searchColumns as $searchColumnName) {
                    $whereClause = $isFirst ? 'where' : 'orWhere';

                    $relationshipQuery->{$whereClause}(
                        $searchColumnName,
                        $searchOperator,
                        "%{$query}%",
                    );

                    $isFirst = false;
                }

                return $relationshipQuery
                    ->whereDoesntHave($livewire->getInverseRelationshipName(), function (Builder $query) use ($livewire) : void {
                        $query->where($livewire->ownerRecord->getQualifiedKeyName(), $livewire->ownerRecord->getKey());
                    })
                    ->pluck($displayColumnName, $relationship->getRelated()->getKeyName())
                    ->toArray();
            })
            ->getOptionLabelUsing(fn (Relationship $livewire, $value) : ?string => static::getRecordTitle($livewire->getRelationship()->getRelated()->query()->find($value)))
            ->disableLabel();
    }

    protected function getAttachFormSchema() : array
    {
        return $this->getResourceAttachForm()->getSchema();
    }

    protected function getResourceAttachForm() : Form
    {
        if ( ! $this->resourceAttachForm) {
            $this->resourceAttachForm = static::attachForm(Form::make());
        }

        return $this->resourceAttachForm;
    }
}
