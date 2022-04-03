<?php

namespace Dasher\Tables\Filters;

use Closure;
use Dasher\Forms\Components\MultiSelect;
use Illuminate\Database\Eloquent\Builder;

class MultiSelectFilter extends Filter
{
    use Concerns\HasOptions;
    use Concerns\HasPlaceholder;
    use Concerns\HasRelationship;

    protected string | Closure | null $column = null;

    protected bool | Closure $isStatic = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->placeholder(__('tables::table.filters.multi_select.placeholder'));
    }

    public function apply(Builder $query, array $data = []): Builder
    {
        if ($this->evaluate($this->isStatic)) {
            return $query;
        }

        if ($this->hasQueryModificationCallback()) {
            return parent::apply($query, $data);
        }

        if (blank($data['values'] ?? null)) {
            return $query;
        }

        if ($this->queriesRelationships()) {
            return $query->whereHas(
                $this->getRelationshipName(),
                fn (Builder $query) => $query->whereIn(
                    $this->getRelationshipKey(),
                    $data['values'],
                ),
            );
        }

        /** @var Builder $query */
        $query = $query->whereIn($this->getColumn(), $data['values']);

        return $query;
    }

    public function column(string | Closure | null $name): static
    {
        $this->column = $name;

        return $this;
    }

    public function static(bool | Closure $condition = true): static
    {
        $this->isStatic = $condition;

        return $this;
    }

    public function getColumn(): string
    {
        return $this->evaluate($this->column) ?? $this->getName();
    }

    public function getFormSchema(): array
    {
        return $this->formSchema ?? [
            MultiSelect::make('values')
                ->label($this->getLabel())
                ->options($this->getOptions())
                ->placeholder($this->getPlaceholder())
                ->default($this->getDefaultState()),
        ];
    }
}
