<?php



class Layout {

  private $Version = "2.0";

  private $Codeigniter;

  private $Twig;
  
  public $Data = [];

  public $Configure = [];

  public $Stylesheet = [];

  public $Javascript = [];


  public function __construct() {
    
    $Codeigniter =& get_instance();    
    
    $Configure = [];
    if ($Codeigniter->config->item('twig_cache_enable')) {
      $Configure['cache'] = $Codeigniter->config->item('twig_cache_path');
    }

    $Loader = new \Twig\Loader\FilesystemLoader( $Codeigniter->config->item('twig_template_path') );

    $Environment = new \Twig\Environment( $Loader, $Configure );

    try {
      foreach(get_defined_functions()['internal'] as $function) {
        $Environment->addFunction(new \Twig\TwigFunction($function, $function));
      }
      foreach(get_defined_functions()['user'] as $function) {
        $Environment->addFunction(new \Twig\TwigFunction($function, $function));
      }
    }catch(Exception $e) {}

    $this->Codeigniter = $Codeigniter;
    $this->Twig = $Environment;
  }


  public function data( $name, $value='' ) {
    if (!empty($value)) {
      $this->Data[$name] = $value;
    }else {
      if ($this->Data[$name]) {
        return $this->Data[$name];
      }else {
        return false;
      }
    }
  }


  public function view( $source, $render=FALSE ) {
    if ($render) {
      return $this->Twig->render( $source, (array) $this->Data );
    }else {
      echo $this->Twig->render( $source, (array) $this->Data );
    }
  }


  public function style( $variable='', $data='' ) {
    if (!empty($variable)) {
      if (!empty($data)) {
        $this->Stylesheet[$variable] = $data;
      }else {
        if (isset($this->Stylesheet[$variable])) {
          return $this->Stylesheet[$variable];
        }else {
          return false;
        }
      }
    }else {
      return $this->Stylesheet;
    }
  }
  private function styleSetup( $data ) {
    $parse = [];
    $parse[] = "<style>";
    $parse[] = ":root {";
      foreach ($data as $key=>$data) {
        if (is_string($data)) {
          if ((str_contains($data, 'http')) || (str_contains($data, 'https'))) {
            $parse[] = "\t--LAYOUT-".$key.": url('".$data."');";
          }else {
            $parse[] = "\t--LAYOUT-".$key.": ".$data.";";
          }
        }
      }
    $parse[] = "}";
    $parse[] = "</style>";
    return implode("\n", $parse);
  }

  public function script( $variable='', $data='' ) {
    if (!empty($variable)) {
      if (!empty($data)) {
        $this->Javascript[$variable] = $data;
      }else {
        if (isset($this->Javascript[$variable])) {
          return $this->Javascript[$variable];
        }else {
          return false;
        }
      }
    }else {
      return $this->Javascript;
    }
  }
  public function _script_secure( $Object ) {
    $Data = $this->Codeigniter->security->xss_clean($Object);
    $Result = [];
    foreach ($Data as $key => $value) {
      if (is_array($value)) {
        $Data[$key] = base64_encode(json_encode($this->Codeigniter->security->xss_clean($value)));
      }else {
        $Data[$key] = base64_encode($this->Codeigniter->security->xss_clean($value));
      }
      $Result[$key] = $Data[$key];
    }
    return $Result;
  }
  public function _script_classed( $Object ) {
    $Class = [];
    $Class[] = "<script>";
    $Class[] = "class Layout {";
    foreach ($this->_script_secure( $Object ) as $Variable=>$Value) {
      $Class[] = "\tstatic ".$Variable.' = "'.$Value.'"';
    }
    $Class[] = "\tstatic load".'( $Variable ) { if (Layout[$Variable]) { let Data = atob(Layout[$Variable]); if (Layout.isJSON(Data)) { return JSON.parse(Data); }else { return Data; } }else {return false;} }';
    $Class[] = "\tstatic isJSON(str) { try { JSON.parse(str); return true; } catch (e) { return false; } }";
    $Class[] = "}";
    $Class[] = "const LAYOUT = Layout;";
    $Class[] = "window.LAYOUT = Layout;";
    $Class[] = "</script>";
    return implode("\n", $Class);
  }


  public function config( $name='default' ) {
    if (file_exists(PATH_APPLICATION.'/config/layout/'.strtolower($name).'.php')) {
      $this->Codeigniter->config->load('/layout/'.strtolower($name));
    }else {
      $this->Codeigniter->config->load('/layout/default');
    }
    $this->Configure = $this->Codeigniter->config->item('layout');
  }

  public function use( $name, $value=[]) {
    if (!empty($value)) {
      if (is_array($value)) {
        $this->Configure[$name] = array_merge_recursive($this->Configure[$name], $value);
      }else {
        $this->Configure[$name] = $value;
      }
    }else {
      if (isset($this->Configure[$name])) {
        return $this->Configure[$name];
      }else {
        return false;
      }
    }
  }

  public function render() {
    $Data = $this->Data;
    $Configure = $this->Configure;
    
    $Configure['viewSlice'] = explode('.', $Configure['view']);

    $Configure['usePath'] = str_replace('.', '/', $Configure['path']).'/';
    $Configure['useLayout'] = $Configure['usePath'].'layout.html';
    $Configure['useHead'] = $Configure['usePath'].'layout.head.html';
    $Configure['useDraw'] = $Configure['usePath'].'layout.draw.html';
    $Configure['useView'] = str_replace('.', '/', $Configure['path'].'.'.$Configure['view']).'.html';
    
    if ($Configure['offline']) {
      $Configure['source']['javascript'][] = resource('javascript/upup/upup.min.js');
    }
    
    $Configure = $this->render_source( $Configure );
    $Configure = $this->bundle_source( $Configure );
    
    if ($Configure['offline']) {
      $target = $_SERVER['DOCUMENT_ROOT'].'/upup.sw.min.js';
      if (!file_exists($target)) {
        $worker = file_get_contents(resource('javascript/upup/upup.sw.min.js'));
        file_put_contents($target, $worker);
      }
      $offline = [];
      foreach ($this->style() as $css) {
        if (str_contains($css, 'http') || str_contains($css, 'https')) {
          $cleaning = str_replace('url(', '', $css);
          $cleaning = str_replace(')', '', $css);
          $offline[] = $cleaning;
        }
      }
      $Configure['useOffline'][] = $offline;
    }

    $this->Data['LAYOUT'] = $Configure;
    $this->Data['STYLESHEET'] = $this->styleSetup( $this->style() );
    $this->Data['JAVASCRIPT'] = $this->_script_classed( array_merge($Configure, $this->script()) );

    $this->view($Configure['useLayout']);
  }

  private function render_source( $Configure ) {
    // stylesheet
    $Configure['source']['stylesheet'] = array_merge(
      $Configure['source']['stylesheet'], [
        base_url('application/views/'.$Configure['usePath'].'layout.css'),
        base_url('application/views/'.str_replace('html', 'css', $Configure['useView'])),
      ]
    );
    // javascript
    $Configure['source']['javascript'] = array_merge(
      $Configure['source']['javascript'], [
        base_url('application/views/'.$Configure['usePath'].'layout.js'),
        base_url('application/views/'.str_replace('html', 'js', $Configure['useView'])),
      ]
    );
    return $Configure;
  }

  private function bundle_source( $Configure ) {
    if ( ($Configure['bundle']['stylehseet']) || ($Configure['bundle']['javascript']) ) {
      $this->Codeigniter->load->library("minify");
    }
    
    if ($Configure['bundle']['stylehseet']) {
      $stylesheetBundle = [];
      foreach ($Configure['source']['stylesheet'] as $stylesheet) {
        $stylesheetBundle[] = "\n/* Source: $stylesheet */ \n".$this->Codeigniter->minify->css(
          file_get_contents($stylesheet)
        );
      }
      file_put_contents(resource('stylesheet/layout.bundle.css', true), implode("\n", $stylesheetBundle));
      $Configure['source']['stylesheet'] = [];
      $Configure['source']['stylesheet'][0] = resource('stylesheet/layout.bundle.css');
    }

    if ($Configure['bundle']['javascript']) {
      $javascriptBundle = [];
      foreach ($Configure['source']['javascript'] as $javascript) {
        $javascriptBundle[] = "\n/* Source: $javascript */ \n".$this->Codeigniter->minify->js(
          file_get_contents($javascript)
        );
      }
      file_put_contents(resource('javascript/layout.bundle.js', true), implode("\n", $javascriptBundle));
      $Configure['source']['javascript'] = [];
      $Configure['source']['javascript'][0] = resource('javascript/layout.bundle.js');
    }
    
    return $Configure;
  }

}