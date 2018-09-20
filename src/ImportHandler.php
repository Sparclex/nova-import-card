<?php

namespace Sparclex\NovaImportCard;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Facades\DB;

/**
 * Imports the uploaded data which was extracted by the
 *
 * @package Sparclex\NovaImportCard
 */
class ImportHandler
{

    /**
     * Uploaded data
     *
     * @var array
     */
    protected $data;

    /**
     * Imports the uploaded data
     *
     * @param array $data
     */
    public function __construct(array $data)
    {

        $this->data = $data;
    }

    /**
     * Handles the data import
     *
     * @param $model
     * @return string|null error message
     */
    public function handle($model)
    {
        $data = $this->data;
        try {
            DB::beginTransaction();
            foreach ($data as $entry) {
                foreach ($entry as $attribute => $value) {
                    $model->{$attribute} = $value;
                }
                $model->save();
                $model = $model->newInstance();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
        return null;
    }

    /**
     * Validates the uploaded data
     *
     * @param $rules resource field creation rules
     */
    public function validate($rules)
    {

        $this->getValidationFactory()->make(
            $this->data, $rules
        )->validate();
    }

    public function getValidationFactory()
    {
        return app(Factory::class);
    }
}