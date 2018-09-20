<?php

namespace Sparclex\NovaImportCard;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Http\Requests\NovaRequest;

class ImportController extends Controller
{
    use ValidatesRequests;

    public function handle(NovaRequest $request)
    {
        $resource = $request->newResource();
        $rules = $resource->availableFields($request)->mapWithKeys(function($field) use($request) {
            return $field->getCreationRules($request);
        })->mapWithKeys(function($rule, $key) {
            return ["*.".$key => $rule];
        });
        $fileReader = $resource::$importFileReader ?? CsvFileReader::class;
        $importHandler = $resource::$importHandler ?? ImportHandler::class;

        $data = $this->validate($request, [
            'file' => 'required|file|mimes:' . $fileReader::mimes()
        ]);

        $fileReader = new $fileReader($data['file']);
        $data = $fileReader->read();
        $fileReader->afterRead();

        $importHandler = new $importHandler($data);
        $importHandler->validate($rules->toArray());

        if($error = $importHandler->handle($resource->resource)) {
            return Action::danger($error);
        } else {
            return Action::message(__('Import successful'));
        }
    }
}