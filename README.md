CSV Validator
=============

Read a list of items from a CSV and write 2 new lists of valid and invalid items.

Example usage:
```php
include 'lib/CSV.php';
include 'lib/ClipValidator.php';

$reader = new CSVReader('clips.csv');
$csv = array();

while ($row = $reader->readRow()) {
    $csv[] = $row;
}

$csv = new ArrayObject($csv);
$csv = $csv->getIterator();

// write a list of valid clip ids
$valid = new ClipValidator($csv, $returnValid = true);
$validFile = new CSVWriter('valid.csv');
foreach ($valid as $v) {
    $validFile->writeRow($v, 'id');
}

// write a list of invalid clip ids
$invalid = new ClipValidator($csv, $returnValid = false);
$invalidFile = new CSVWriter('invalid.csv');
foreach ($invalid as $i) {
    $invalidFile->writeRow($i, 'id');
}
```
