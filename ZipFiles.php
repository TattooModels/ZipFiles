<?php
/**
 * ZipFiles
 * 
 * 
 * @package    TattooModels
 * @subpackage Backup
 * @version    1.0.0
 * @author     Daniel Ruf
 */
class ZipFiles {
  /**
   * 
   * zip files in directories
   *
   * @param string $zipfile  name of the final zip file
   * @param string $datafile  csv file, one file name per line, relative paths allowed
   * @param string $subdirectory  subdirectory where all files can be found
   * @return boolean
   */
  function __construct($zipfile, $datafile, $subdirectory='') {
    // keep the script running
    set_time_limit(0);
    // create zip file
    $zip = new ZipArchive();
    if ($zip->open($zipfile, ZipArchive::CREATE|ZipArchive::OVERWRITE)!==TRUE) {
      exit("cannot open $zipfile\n");
    }
    // you should check the ulimit value of the server before executing this script
    // read csv file
    $handle = fopen($datafile, 'r'); // $datafile
    if ($handle) {
      // add file to zip file
      while (($buffer = fgets($handle, 4096)) !== false) {
        $file = null;
        $file = $subdirectory.trim($buffer);
        if(file_exists($file) && is_readable($file)){
          $zip->addFile($file);
        }
      }
      if (!feof($handle)) {
        echo "error: unexpected fgets() failure\n";
      }
      fclose($handle);
    }
    // output zip file information
    // echo 'numfiles: ' . $zip->numFiles . '\n';
    // echo 'status:' . $zip->status . '\n';
    $zip->close();
    return ($zip->status === ZipArchive::ER_OK);
  }
}

// example
// $test = new ZipFiles('backup.zip', 'images.csv');
