<?php

return [
    /*
     * A file reader converts a type of file (xml, csv, xlsx..) to an array
     *
     * This is the default file reader used, when there is no static variable $importFileReader
     * present in the resource class
     */
    'file_reader' => Sparclex\NovaImportCard\CsvFileReader::class,

    /*
     * An import handler stores the output of the file reader into the database
     *
     * This is the default import handler used, when there is no static variable $importHandler
     * present in the resource class
     */
    'import_handler' => Sparclex\NovaImportCard\ImportHandler::class,
];
