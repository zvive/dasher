<?php

namespace Dasher\Forms\Components\Concerns;

use Closure;
use Dasher\Forms\Components\Contracts\CanHaveNumericState;

trait CanBeLengthConstrained
{
    protected int | Closure | null $length = null;

    protected int | Closure | null $maxLength = null;

    protected int | Closure | null $minLength = null;

    public function length(int | Closure $length): static
    {
        $this->length = $length;
        $this->maxLength = $length;
        $this->minLength = $length;

        $this->rule(function (): string {
            $length = $this->getLength();

            if ($this instanceof CanHaveNumericState && $this->isNumeric()) {
                return "digits:{$length}";
            }

            return "size:{$length}";
        });

        return $this;
    }

    public function maxLength(int | Closure $length): static
    {
        $this->maxLength = $length;

        $this->rule(function (): string {
            $length = $this->getMaxLength();

            return "max:{$length}";
        });

        return $this;
    }

    public function minLength(int | Closure $length): static
    {
        $this->minLength = $length;

        $this->rule(function (): string {
            $length = $this->getMinLength();

            return "min:{$length}";
        });

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->evaluate($this->length);
    }

    public function getMaxLength(): ?int
    {
        return $this->evaluate($this->maxLength);
    }

    public function getMinLength(): ?int
    {
        return $this->evaluate($this->minLength);
    }
}
