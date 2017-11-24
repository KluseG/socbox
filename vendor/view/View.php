<?php

namespace vendor\view;

class View {

  private $template;
  private $compiled;
  private $vars;

  public function __construct($templateName, $arr = null) {
      $this->vars = $arr;

      $this->template = '../templates/' . $templateName . '.php';

      if (file_exists($this->template)) {
        $this->compiled = __DIR__ . '/compiled/' . $md5 = md5_file($this->template) . '.php';

        if (file_exists($this->compiled)) {
          return $this->getTemplate();
        }
        else {
          return $this->compileTemplate();
        }
      }
      else {
        echo 'View "' . $templateName . '" does not exists!';
      }
  }

  public function compileTemplate() {
    $content = file_get_contents($this->template);

    $variabledContent = preg_replace('/{% each:(\w+) %}/', '<?php foreach ($$1 as $key => $value): ?>', $content);
    $variabledContent = preg_replace('/{% endeach %}/', '<?php endforeach; ?>', $variabledContent);
    $variabledContent = preg_replace('/{% (\w+) %}/', '<?php echo $$1; ?>', $variabledContent);

    file_put_contents($this->compiled, $variabledContent);

    return $this->getTemplate();
  }

  public function getTemplate() {
    if ($this->vars) {
      extract($this->vars);
    }

    return require $this->compiled;
  }
}


 ?>
