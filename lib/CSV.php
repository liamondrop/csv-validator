<?php

// common properties & methods
class CSVBase {
    protected $fileHandle;
    protected $delimiter = ',';
    protected $enclosure = '"';

    public function __construct($fileName, $mode = 'r') {
        $f = fopen($fileName, $mode);
        if (!is_resource($f)) {
            throw new Exception("{$fileName} is not a valid resource.");
        }
        $this->fileHandle = $f;
    }

    public function __destruct() {
        if (is_resource($this->fileHandle)) {
            fclose($this->fileHandle);
        }
    }
}


class CSVReader extends CSVBase {
    private $headers;

    public function __construct($fileName) {
        if (!is_readable($fileName)) {
            throw new Exception("{$fileName} is not readable.");
        }
        parent::__construct($fileName);
        $this->headers = $this->readHeaders();
    }

    public function readHeaders() {
        return $this->readRow(true);
    }
    
    public function readRow($isHeader = false) {
        if (($row = fgetcsv($this->fileHandle, 1000, $this->delimiter, $this->enclosure)) !== false) {
            // the first row contains the headers
            return $isHeader ? $row : array_combine($this->headers, $row);
        } else {
            return false;
        }
    }
}


class CSVWriter extends CSVBase {
    public function __construct($fileName) {
        parent::__construct($fileName, 'w');
    }

    static function selectColumns($row, $columns) {
        $ret = array();
        foreach (array_keys($row) as $key) {
            if (in_array($key, $columns)) {
                $ret[] = $row[$key];
            }
        }
        return $ret;
    }

    public function writeRow($row) {
        if (!is_array($row)) {
            return;
        }
        $columns = array_slice(func_get_args(), 1);
        $row = (count($columns) > 0) ? $this->selectColumns($row, $columns) : array_values($row);
        return fputcsv($this->fileHandle, $row, $this->delimiter, $this->enclosure);
    }
}
