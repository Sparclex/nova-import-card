<?php

namespace Sparclex\NovaImportCard;

use Illuminate\Contracts\Validation\Factory;

/**
 * Imports the uploaded data which was extracted by the.
 */
class ImportHandler
{
    /**
     * Uploaded data.
     *
     * @var array
     */
    protected $data;

    /**
     * Imports the uploaded data.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Handles the data import.
     *
     * @param $model
     * @return string|null error message
     */
    public function handle($resource)
    {
        $data = $this->data;
        foreach ($data as $entry) {
            [$model, $callbacks] = $resource::fill(
                new ImportNovaRequest($entry), $resource::newModel()
            );
            $model->save();
            collect($callbacks)->each->__invoke();
        }
    }

    public function getValidationFactory()
    {
        return app(Factory::class);
    }
}
