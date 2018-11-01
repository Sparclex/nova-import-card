<?php

namespace Sparclex\NovaImportCard;

use Illuminate\Http\UploadedFile;

abstract class ImportFileReader
{
    /**
     * @var UploadedFile
     */
    protected $file;

    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * Allowed mime types.
     *
     * @return string
     */
    public static function mimes()
    {
        return '';
    }

    public function afterRead()
    {
        unlink($this->file->getRealPath());
    }
}
