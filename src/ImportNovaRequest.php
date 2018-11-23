<?php

namespace Sparclex\NovaImportCard;

use Laravel\Nova\Http\Requests\NovaRequest;

class ImportNovaRequest extends NovaRequest
{
    protected $data;

    public function __construct($data)
    {
        parent::__construct();
        $this->data = $data;
    }

    /**
     * Retrieve an input item from the request.
     *
     * @param  string|null $key
     * @param  string|array|null $default
     * @return string|array|null
     */
    public function input($key = null, $default = null)
    {
        if (! $key) {
            return $this->data;
        }
        if (! isset($this->data[$key])) {
            return $default;
        }

        return $this->data[$key];
    }
}
