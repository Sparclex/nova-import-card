<?php

namespace Sparclex\NovaImportCard;

use Laravel\Nova\Actions\Action;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ImportController extends Controller
{
    use ValidatesRequests;

    public function handle(NovaRequest $request)
    {
        $resource = $request->newResource();
        $fileReader = $resource::$importFileReader ?? config('sparclex-nova-import-card.file_reader');

        $data = $this->validate($request, [
            'file' => 'required|file|mimes:'.$fileReader::mimes(),
        ]);
        $fileReader = new $fileReader($data['file']);
        $data = $fileReader->read();
        $fileReader->afterRead();

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
        $this->getValidationFactory()->make($data, $rules->toArray())->validate();
    }
}
