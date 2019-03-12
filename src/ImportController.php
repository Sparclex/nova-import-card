<?php

namespace Sparclex\NovaImportCard;

use Laravel\Nova\Actions\Action;
use Laravel\Nova\Rules\Relatable;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Exceptions\NoTypeDetectedException;

class ImportController
{
    public function handle(NovaRequest $request)
    {
        $resource = $request->newResource();
        $importerClass = $resource::$importer ?? config('nova-import-card.importer');

        $data = Validator::make($request->all(), [
            'file' => 'required|file',
        ])->validate();

        $importer = new $importerClass(
            $resource,
            $resource->creationFields($request)->pluck('attribute'),
            $this->extractValidationRules($request, $resource)->toArray(),
            get_class($resource->resource)
        );

        try {
            $importer->import($data['file']);
        } catch (ImportException $e) {
            $this->responseError($e->getMessage());
        } catch (NoTypeDetectedException $e) {
            $this->responseError(__('Invalid file type'));
        }

        $message = method_exists($importer, 'message') ? $importer->message() : __('Import successful');

        return Action::message($message);
    }

    protected function extractValidationRules($request, $resource)
    {
        return collect($resource::rulesForCreation($request))->mapWithKeys(function ($rule, $key) {
            foreach ($rule as $i => $r) {
                if (! is_object($r)) {
                    continue;
                }

                // Make sure relation checks start out with a clean query
                if (is_a($r, Relatable::class)) {
                    $rule[$i] = function () use ($r) {
                        $r->query = $r->query->newQuery();

                        return $r;
                    };
                }
            }

            return [$key => $rule];
        });
    }

    private function responseError($error)
    {
        throw ValidationException::withMessages([
            0 => [$error],
        ]);
    }
}
