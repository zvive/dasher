<?php

declare(strict_types=1);

namespace Dasher\Forms;

use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Dasher\Forms\Components\Tab;
use Dasher\Forms\Components\Field;
use Dasher\Forms\Components\Select;
use Dasher\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

trait HasForm
{
    use WithFileUploads;
    public $temporaryUploadedFiles = [];

    public function clearTemporaryUploadedFile($name)
    {
        $this->syncInput(
            static::getTemporaryUploadedFilePropertyName($name),
            null,
            false
        );
    }

    public function fillWithFormDefaults()
    {
        $this->fill($this->getPropertyDefaults());
    }

    public function focusTabbedField($field)
    {
        if ($field) {
            $possibleTab = $field->parent;

            while ($possibleTab) {
                if ($possibleTab instanceof Tab) {
                    $this->dispatchBrowserEvent(
                        'switch-tab',
                        $possibleTab->parent->id.'.'.$possibleTab->id,
                    );

                    break;
                }

                $possibleTab = $possibleTab->parent;
            }
        }
    }

    public function getPropertyDefaults()
    {
        return $this->getForm()->getDefaults();
    }

    public function getRules()
    {
        $rules = $this->getForm()->getRules();

        foreach (parent::getRules() as $field => $conditions) {
            if ( ! \is_array($conditions)) {
                $conditions = \explode('|', $conditions);
            }

            $rules[$field] = \array_merge($rules[$field] ?? [], $conditions);
        }

        return $rules;
    }

    public function getSelectFieldDisplayValue($fieldName, $value)
    {
        $field = \collect($this->getForm()->getFlatSchema())
            ->first(fn ($field) => $field instanceof Select && $field->name === $fieldName);

        if ( ! $field) {
            return [];
        }

        return $field->getDisplayValue($value);
    }

    public function getSelectFieldOptionSearchResults($fieldName, $search = '')
    {
        $field = \collect($this->getForm()->getFlatSchema())
            ->first(fn ($field) => $field instanceof Select && $field->name === $fieldName);

        if ( ! $field) {
            return [];
        }

        return $field->getOptionSearchResults($search);
    }

    public function getTemporaryUploadedFile($name)
    {
        return $this->getPropertyValue(
            static::getTemporaryUploadedFilePropertyName($name)
        );
    }

    public static function getTemporaryUploadedFilePropertyName($fieldName)
    {
        return "temporaryUploadedFiles.{$fieldName}";
    }

    public function getUploadedFileUrl($name, $disk)
    {
        $path = $this->getPropertyValue($name);

        if ( ! $path) {
            return;
        }

        return Storage::disk($disk)->url($path);
    }

    public function getValidationAttributes()
    {
        $attributes = $this->getForm()->getValidationAttributes();

        foreach (parent::getValidationAttributes() as $name => $label) {
            $attributes[$name] = $label;
        }

        return $attributes;
    }

    public function removeUploadedFile($name)
    {
        $this->syncInput($name, null, false);
        $this->clearTemporaryUploadedFile($name);
    }

    public function reset(...$properties)
    {
        parent::reset(...$properties);

        $defaults = $this->getPropertyDefaults();

        if (\count($properties) && \is_array($properties[0])) {
            $properties = $properties[0];
        }

        if (empty($properties)) {
            $properties = \array_keys($defaults);
        }

        $propertiesToFill = \collect($properties)
            ->filter(fn ($property)      => \in_array($property, $defaults, true))
            ->mapWithKeys(fn ($property) => [$property => $defaults[$property]])
            ->toArray();

        $this->fill($propertiesToFill);
    }

    public function resetTemporaryUploadedFiles()
    {
        $this->temporaryUploadedFiles = [];
    }

    public function storeTemporaryUploadedFiles()
    {
        foreach ($this->getForm()->getFlatSchema() as $field) {
            if ( ! $field instanceof FileUpload) {
                continue;
            }

            $temporaryUploadedFile = $this->getTemporaryUploadedFile($field->name);
            if ( ! $temporaryUploadedFile) {
                continue;
            }

            $storeMethod = $field->visibility === 'public' ? 'storePublicly' : 'store';
            $path        = $temporaryUploadedFile->{$storeMethod}($field->directory, $field->disk);
            $this->syncInput($field->name, $path, false);
        }

        $this->resetTemporaryUploadedFiles();
    }

    public function validate($rules = null, $messages = [], $attributes = [])
    {
        try {
            return parent::validate($rules, $messages, $attributes);
        } catch (ValidationException $exception) {
            $fieldToFocus = \collect($this->getForm()->getFlatSchema())
                ->first(function ($field) use ($exception) {
                    return $field instanceof Field && \array_key_exists($field->name, $exception->validator->failed());
                });

            if ($fieldToFocus) {
                $this->focusTabbedField($fieldToFocus);
            }

            throw $exception;
        }
    }

    public function validateOnly($field, $rules = null, $messages = [], $attributes = [])
    {
        try {
            return parent::validateOnly($field, $rules, $messages, $attributes);
        } catch (ValidationException $exception) {
            $fieldToFocus = \collect($this->getForm()->getFlatSchema())
                ->first(function ($field) use ($exception) {
                    return $field instanceof Field && \array_key_exists($field->name, $exception->validator->failed());
                });

            if ($fieldToFocus) {
                $this->focusTabbedField($fieldToFocus);
            }

            throw $exception;
        }
    }

    public function validateTemporaryUploadedFiles()
    {
        $rules = \collect($this->getRules())
            ->filter(fn ($conditions, $field) => Str::of($field)->startsWith('temporaryUploadedFiles.'))
            ->toArray();

        if ( ! \count($rules)) {
            return;
        }

        try {
            return parent::validate($rules);
        } catch (ValidationException $exception) {
            $fieldToFocus = \collect($this->getForm()->getFlatSchema())
                ->first(function ($field) use ($exception) {
                    return $field instanceof Field && \array_key_exists(
                            static::getTemporaryUploadedFilePropertyName($field->name),
                            $exception->validator->failed()
                        );
                });

            if ($fieldToFocus) {
                $this->focusTabbedField($fieldToFocus);
            }

            $this->setErrorBag($exception->validator->errors());

            foreach ($this->getErrorBag()->messages() as $field => $messages) {
                $field = (string) Str::of($field)->after('temporaryUploadedFiles.');

                foreach ($messages as $message) {
                    $this->addError($field, $message);
                }
            }

            throw $exception;
        }
    }
}
