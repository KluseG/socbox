<?php

namespace vendor\dotenv;

  class Dotenv {

    private $filename = '.env';
    public $file;

    function __construct() {
        $this->file = file_get_contents('../'.$this->filename);
        $this->file = explode(PHP_EOL, $this->file);

        foreach ($this->file as $key => $value) {
          if (strlen($value)) {
            putenv($value);
          }
        }
    }
  }

 ?>
