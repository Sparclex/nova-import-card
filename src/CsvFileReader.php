<?php

namespace Sparclex\NovaImportCard;

class CsvFileReader extends ImportFileReader
{
    public static function mimes()
    {
        return 'csv,txt';
    }

    public function read(): array
    {
        $data = [];
        $columns = null;
        $handle = fopen($this->file->getRealPath(), 'r');
        while (($row = fgetcsv($handle)) !== false) {
            if (! $columns) {
                $columns = $row;
                continue;
            }
            $currentRow = count($data);
            $data[$currentRow] = [];
            foreach ($row as $key => $value) {
                $data[$currentRow][$columns[$key]] = $value;
            }
        }
        fclose($handle);

        return $data;
    }
}
