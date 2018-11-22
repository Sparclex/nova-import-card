<?php

namespace Sparclex\NovaImportCard;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BasicImporter implements ToModel, WithValidation, WithHeadingRow
{
    use Importable;

    protected $attributes;

    protected $rules;

    protected $modelClass;

    public function __construct($attributes, $rules, $modelClass)
    {
        $this->attributes = $attributes;
        $this->rules = $rules;
        $this->modelClass = $modelClass;
    }

    public function model(array $row)
    {
        $model = new $this->modelClass;

        foreach($this->attributes as $attribute) {
            $model->{$attribute} = $row[$attribute] ?? null;
        }

        return $model;
    }

    public function rules(): array
    {
        return $this->rules;
    }
}
