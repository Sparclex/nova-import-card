<?php

namespace Sparclex\NovaImportCard;

use Laravel\Nova\Actions\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Http\Requests\NovaRequest;

class ImportController
{
    public function handle(NovaRequest $request)
    {
        $resource = $request->newResource();
        $fileReader = $resource::$importFileReader ?? config('sparclex-nova-import-card.file_reader');

        $data = Validator::make($request->all(), [
            'file' => 'required|file|mimes:'.$fileReader::mimes(),
        ])->validate();
        try {
            $fileReader = new $fileReader($data['file']);
            $data = $fileReader->read();
            $fileReader->afterRead();
        } catch (\Exception $e) {
            Action::danger(__('An error occurred during the import'));
        }

        $this->validateFields($data, $request, $resource);

        DB::transaction(function () use ($resource, $data) {
            $importHandler = $resource::$importHandler ?? config('sparclex-nova-import-card.import_handler');
            (new $importHandler($data))->handle($resource);
        });

        return Action::message(__('Import successful'));
    }

    /**
     * @param $data
     * @param NovaRequest $request
     * @param $resource
     */
    protected function validateFields($data, $request, $resource): void
    {
        $rules = collect($resource::rulesForCreation($request))->mapWithKeys(function ($rule, $key) {
            return ['*.'.$key => $rule];
        });
        Validator::make($data, $rules->toArray())->validate();
    }
}
