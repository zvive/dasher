<?php

declare(strict_types=1);

namespace Dasher\Resources\Relations\Concerns;

use Dasher\Tables;
use Dasher\Resources\Form;
use Dasher\Forms\Components\Select;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Dasher\Resources\Relations\RelationManager;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Dasher\Tables\Actions\Modal\Actions\ButtonAction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait CanAssociateRecords
{
    protected static bool $canAssociateAnother = true;
    protected static bool $hasAssociateAction  = false;
    protected ?Form $resourceAssociateForm     = null;

    public function associate(bool $another = false) : void
    {
        $form = $this->getMountedTableActionForm();

        $this->callHook('beforeValidate');
        $this->callHook('beforeCreateValidate');

        $data = $form->getState();

        $this->callHook('afterValidate');
        $this->callHook('afterCreateValidate');

        $this->callHook('beforeAssociate');

        /** @var HasMany $relationship */
        $relationship = $this->getRelationship();

        $recordToAssociate = $relationship->getRelated()->query()->find($data['recordId']);

        /** @var BelongsTo $inverseRelationship */
        $inverseRelationship = $this->getInverseRelationshipFor($recordToAssociate);

        $inverseRelationship->associate($this->ownerRecord);
        $recordToAssociate->save();

        $this->callHook('afterAssociate');

        if ($another) {
            $form->fill();
        }

        $this->notify('success', \__('dasher::resources/relation-managers/associate.action.messages.associated'));
    }

    public function associateAndAssociateAnother() : void
    {
        $this->associate(another: true);
    }

    public static function associateForm(Form $form) : Form
    {
        return $form->schema([
            static::getAssociateFormRecordSelect(),
        ]);
    }

    protected function canAssociate() : bool
    {
        return $this->hasAssociateAction() && $this->can('associate');
    }

    protected static function canAssociateAnother() : bool
    {
        return static::$canAssociateAnother;
    }

    public static function disableAssociateAnother() : void
    {
        static::$canAssociateAnother = false;
    }

    protected function fillAssociateForm() : void
    {
        $this->callHook('beforeFill');
        $this->callHook('beforeAssociateFill');

        $this->getMountedTableActionForm()->fill();

        $this->callHook('afterFill');
        $this->callHook('afterAssociateFill');
    }

    protected function getAssociateAction() : Tables\Actions\ButtonAction
    {
        return Tables\Actions\ButtonAction::make('associate')
            ->label(\__('dasher::resources/relation-managers/associate.action.label'))
            ->form($this->getAssociateFormSchema())
            ->mountUsing(fn () => $this->fillAssociateForm())
            ->modalActions($this->getAssociateActionModalActions())
            ->modalHeading(\__('dasher::resources/relation-managers/associate.action.modal.heading', ['label' => static::getRecordLabel()]))
            ->modalWidth('lg')
            ->action(fn () => $this->associate())
            ->color('secondary');
    }

    protected function getAssociateActionAssociateAndAssociateAnotherModalAction() : Tables\Actions\Modal\Actions\Action
    {
        return ButtonAction::make('associateAndAssociateAnother')
            ->label(\__('dasher::resources/relation-managers/associate.action.modal.actions.associate_and_associate_another.label'))
            ->action('associateAndAssociateAnother')
            ->color('secondary');
    }

    protected function getAssociateActionAssociateModalAction() : Tables\Actions\Modal\Actions\Action
    {
        return ButtonAction::make('associate')
            ->label(\__('dasher::resources/relation-managers/associate.action.modal.actions.associate.label'))
            ->submit('callMountedTableAction')
            ->color('primary');
    }

    protected function getAssociateActionCancelModalAction() : Tables\Actions\Modal\Actions\Action
    {
        return ButtonAction::make('cancel')
            ->label(\__('tables::table.actions.modal.buttons.cancel.label'))
            ->cancel()
            ->color('secondary');
    }

    protected function getAssociateActionModalActions() : array
    {
        return \array_merge(
            [$this->getAssociateActionAssociateModalAction()],
            static::canAssociateAnother() ? [$this->getAssociateActionAssociateAndAssociateAnotherModalAction()] : [],
            [$this->getAssociateActionCancelModalAction()],
        );
    }

    protected static function getAssociateFormRecordSelect() : Select
    {
        return Select::make('recordId')
            ->label(\__('dasher::resources/relation-managers/associate.action.modal.fields.record_id.label'))
            ->searchable()
            ->getSearchResultsUsing(function (Select $component, RelationManager $livewire, string $query) : array {
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
            ->getOptionLabelUsing(fn (RelationManager $livewire, $value) : ?string => static::getRecordTitle($livewire->getRelationship()->getRelated()->query()->find($value)))
            ->disableLabel();
    }

    protected function getAssociateFormSchema() : array
    {
        return $this->getResourceAssociateForm()->getSchema();
    }

    protected function getResourceAssociateForm() : Form
    {
        if ( ! $this->resourceAssociateForm) {
            $this->resourceAssociateForm = static::associateForm(Form::make());
        }

        return $this->resourceAssociateForm;
    }

    protected function hasAssociateAction() : bool
    {
        return static::$hasAssociateAction;
    }
}
