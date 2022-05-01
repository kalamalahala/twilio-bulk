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
        // $spreadsheet_json = $target->getActiveSheet()->toArray(null, false, false, false);
        // Get maximum cell of sheet, then convert to array in that range
        $max_cell = $target->getActiveSheet()->getHighestRowAndColumn();
        $spreadsheet_json = $target->getActiveSheet()->rangeToArray('A1:' . $max_cell['column'] . $max_cell['row']);
        // Remove the first row of the array to get headers
        $keys = array_shift($spreadsheet_json);
        // List each header for payload
        $payload = [];
        foreach ($keys as $key => $value) {
            $payload['keys'][$key] = $value;
        }
        // How many rows in the sheet?
        $payload['rows'] = count($spreadsheet_json);
        // $payload = json_encode($payload);
        
        return $payload;

    }



} // End of class