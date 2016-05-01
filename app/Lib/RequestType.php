<?php

namespace App\Lib;

class RequestType
{
  public $type;
  public $path;
  public $params;
  public $examples;
  public $endpoint;

  public function __construct($path)
  {
    $this->type = strtoupper(basename($path));
    $this->path = $path.'/';
    $this->params = $this->get_params();
  }

  public function get_examples()
  {
    $path = ($this->path) ? glob($this->path . '*example*.*') : null;
    $examples = array();
    if ($path) {
      foreach ($path as $example) {
        $filetype = new \SplFileInfo($example);
        $filetype = $filetype->getExtension();
        $ex = array();
        $ex['type'] = $filetype;
        $ex['example'] = htmlentities(file_get_contents($example));
        $examples[] = $ex;
      }
      $this->examples = $examples;
      return $examples;
    }
    return false;
  }

  protected function get_params()
  {
    $path = ($this->path) ? file_get_contents($this->path . 'params.json') : null;
    if ($path) {
      return json_decode($path, true)['params'];
    }
    return false;
  }
}
