<?php
use \PhpOffice\PhpSpreadsheet;


class SpreadSheetHandler {

    private $spreadsheet;

    public function __construct( string $file_url ) {
        $this->spreadsheet = $file_url;
        // $this->get_spreadsheet_json();
    }

    public function get_spreadsheet_json() {
        // Use PHPSpreadsheet to determine the file type of uploaded spreadsheet
        $target = \PhpOffice\PhpSpreadsheet\IOFactory::load($this->spreadsheet);
        // Convert the first sheet into an associative array
        $spreadsheet_json = $target->getActiveSheet()->toArray(null, true, true, true);
        // // Remove the first row of the array
        // array_shift($spreadsheet_json);

        $spreadsheet_json = json_encode($spreadsheet_json);
        return $spreadsheet_json;

    }



} // End of class