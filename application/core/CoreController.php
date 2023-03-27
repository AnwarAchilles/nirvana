<?php
defined('BASEPATH') OR exit('No direct script access allowed');

# SET CORE CONTROLLER
class CoreController extends CI_Controller
{
  /* ---- ---- ---- ----
   * MAIN CONSTRUCT
   * ---- ---- ---- ---- */
	public function __construct()
	{
    // set codeigniter
		parent::__construct();
    // load config
    $this->config->load('core');
    $this->config->controller = $this->config->item('controller');
    $this->config->loader = $this->config->item('loader');
    // loader library
    foreach ($this->config->loader['libraries'] as $key=>$value) {
      if (!is_numeric($key)) {
        $this->load->library($value, $key);
      }else {
        $this->load->library($value);
      }
    }
    // loader helper
    foreach ($this->config->loader['helper'] as $value) {
      $this->load->helper($value);
    }
	}
  
  /* ---- ---- ---- ----
   * LAYOUT BUILDER
   * ---- ---- ---- ---- */
  public function layout( $data=array(), $render=false )
  {
    $output=[];
    // get config for layout
    if (isset($data['layout']['module'])) {
      $config = $this->config->controller['layout'][$data['layout']['module']];
    }else {
      $config = $this->config->controller['layout']['default'];
    }
    // set layout
    $layout = $data['layout'];
    $layout['stylesheet'] = (isset($layout['stylesheet'])) ? $layout['stylesheet'] : [];
    $layout['javascript'] = (isset($layout['javascript'])) ? $layout['javascript'] : [];
    // set frontend
    $frontend = (isset($data['frontend'])) ? $data['frontend'] : [];
    // reset layout
    $data['layout'] = [];
    // layout controlling
    if (isset($layout['layout'])) {
      $output['use'] = '/'.$layout['layout'];
    }else {
      $output['use'] = '/layout';
    }
    // view/source controlling
    if (isset($layout['source'])) {
      $output['view'] = implode('/', $layout['source']);
    }else {
      $output['source'] = explode('/', $layout['view']);
    }
    // create path
    $path = explode('/', $output['view']);
    end($path);
    $pathKey = key($path);
    unset($path[$pathKey]);
    $output['path'] = implode('/', $path);
    
    // stylesheet loader
    $source = $this->layoutStylesheet( $layout, $config, $output );
    $config['stylesheet']['source'] = [];
    $output['stylesheet']['source'] = $source;
    
    // javascript loader
    $source = $this->layoutJavascript( $layout, $config, $output, $frontend );
    $config['javascript']['source'] = [];
    $output['javascript']['source'] = $source;

    // set output by config and output
    $data['layout'] = array_merge($config, $layout, $output);
    
    // pack data to use in javascript
    $data['__javascript'] = $data;
    $data['__javascript']['site_url'] = site_url();
    $data['__javascript']['base_url'] = site_url();
    $data['__javascript']['current_url'] = current_url();
    $data['__javascript']['baseurl'] = base_url();

    // output layout
    if ($render==true) {
      return $this->twig->view($data['layout']['use'], $data, true);
    }else {
      $this->twig->view($data['layout']['use'], $data);
    }
  }
  private function layoutStylesheet( $layout, $config, $output ) {
    /*
    * todo stylesheet/css functions */
    $stylesheet = @array_merge($config['stylesheet'], $layout['stylesheet']);
    $source=[];
    if ($layout['stylesheet'] !== false) {
      // select data from config
      if (isset($stylesheet['select'])) {
        foreach ($stylesheet['select'] as $key=>$val) {
          $val = $val - 1;
          $source[] = $config['stylesheet']['source'][$val];
        };
      }else {
        $source = $config['stylesheet']['source'];
      }
      // builder
      if ($stylesheet['builder'] == true) {
        $source[] = base_url('/application/views'.$output['use'].'.css');
        $source[] = base_url('/application/views/'.$output['view'].'.css');
      }
      // insert data from user
      if (isset($layout['stylesheet']['source'])) {
        $source = array_merge($source, $layout['stylesheet']['source']);
      }
      // validate source if exists, and remove if not exists
      if ($stylesheet['validate'] == true) {
        $validate = [];
        foreach ($source as $x ) {
          if (@file_get_contents( $x )) {
            $validate[] = $x;
          }
        }
        $source = $validate;
      }
    }
    return $source;
  }
  private function layoutJavascript( $layout, $config, $output, $frontend ) {
    /*
    * todo javascript/js functions */
    $javascript = @array_merge($config['javascript'], $layout['javascript']);
    $source=[];
    if ($layout['javascript'] !== false) {
      // select data from config
      if (isset($javascript['select'])) {
        foreach ($javascript['select'] as $key=>$val) {
          $source[] = $config['javascript']['source'][$val];
        }
      }else {
        $source = $config['javascript']['source'];
      }
      // nirvana js
      $source = array_merge($source, $this->layoutJavascriptNirvana());
      // builder
      if ($javascript['builder'] == true) {
        $source[] = base_url('/application/views'.$output['use'].'.js');
        $source[] = base_url('/application/views/'.$output['view'].'.js');
      }
      // nirvana frontend component
      foreach ($frontend as $component) {
        $source[] = base_url('application/views/'.$component[1]); 
       }
      // insert data from user
      if (isset($layout['javascript']['source'])) {
        $source = array_merge($source, $layout['javascript']['source']);
      }
      // validate source if exists, and remove if not exists
      if ($config['javascript']['validate'] == true) {
        $validate = [];
        foreach ($source as $x ) {
          if (@file_get_contents( $x )) {
            $validate[] = $x;
          }
        }
        $source = $validate;
      }
    }
    return $source;
  }
  private function layoutJavascriptNirvana() {
    $x = [];
    foreach (glob(APPPATH.'/../resource/js/nirvana/package/*') as $row) {
      $x[] = base_url('/resource/js/nirvana/package/'.basename($row));
    }
    $x[] = base_url('/resource/js/nirvana/loader.js');
    $x[] = base_url('/resource/js/nirvana/frontend.js');
    $x[] = base_url('/resource/js/nirvana/framework.js');
    return $x;
  }
  
  /* ---- ---- ---- ----
   * REPORT BUILDER
   * ---- ---- ---- ---- */
  public function report( $data=array() )
  {
    // get config for layout
    $config = $this->config->controller['report'];
    // set report
    $report = array_merge($config, $data['report']);
    // reverse
    if (isset($data['layout'])) {
      // set layout
      $data['layout'] = array_merge($config['layout'], $data['layout']);
      $data['report'] = $report;
      // set source
      $source = $this->layout($data, TRUE);
    }

    // PDF type report
    if ($report['type'] == 'PDF') {
      // file reader type
      if (isset($report['file'])) {
        $source = file_get_contents(APPPATH.'/../'.$report['file']);
        header('Content-Type: application/pdf');
        if ($report['download']==TRUE) {
          header('Content-Disposition: attachment; filename="'.$report['filename'].'.pdf"');
        }
        echo $source;
      // dom reader type
      }else {
        if ($report['viewer']==TRUE) {
          if ($report['download']==TRUE) {
            $stream = $this->pdftools->generate($source, $report['filename'], $report['paper'], $report['direction'], FALSE);
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="'.$report['filename'].'.pdf"');
            echo $stream;
          }else {
            $this->pdftools->generate($source, $report['filename'], $report['paper'], $report['direction']);
          }
        }else {
          echo $source;
        }
      }
    }

    if ($report['type'] == 'EXCEL') {
      if (isset($report['file'])) {
        $excel = $this->office->excel([
          'mode'=> 'read',
          'file'=> $report['file']
        ]);
        return $excel;
      }else {
        $excel = $this->office->excel_html( $source );

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$report['filename'].'.xlsx"');
        header('Cache-Control: max-age=0');
        $excel->save('php://output');
      }
    }
  }

  /* ---- ---- ---- ----
   * HTTP REQUEST TO API
   * ---- ---- ---- ---- */
  public function api( $method, $url, $data=array() )
  {
    if ( (strpos($url, 'https')==true) OR (strpos($url, 'http')==true)) {
      $http = $url;
    }else {
      $http = base_url("/api/".$url);
    }
    $curl = 'simple_'.strtolower($method);
    if (count($data) !== 0) {
      $return = $this->curl->$curl( $http, $data );
      return json_decode($return, true) ['data'];
    }else {
      $return = $this->curl->$curl( $http );
      return json_decode($return, true) ['data'];
    }
  }

  /* ---- ---- ---- ----
   * HTTP REQUEST
   * ---- ---- ---- ---- */
  public function http( $method, $url, $data=array() )
  {
    if ( (strpos($url, 'https')==true) OR (strpos($url, 'http')==true)) {
      $http = $url;
    }else {
      $http = base_url($url);
    }
    $curl = 'simple_'.strtolower($method);
    if (count($data) !== 0) {
      $return = $this->curl->$curl( $http, $data );
      return json_decode($return, true);
    }else {
      $return = $this->curl->$curl( $http );
      return json_decode($return, true);
    }
  }

  /* ---- ---- ---- ----
   * STORAGE HANDLING
   * ---- ---- ---- ---- */
  public function resource( $source, $data=null )
  {
    $target = APPPATH.'../resource/'.$source;
    if ($data!==null) {
      file_put_contents( $target, $data );
      return TRUE;
    }else {
      return file_get_contents( $target );
    }
  }

}


# LOAD 
require_once APPPATH."/core/CoreApi.php";
require_once APPPATH."/core/CoreBase.php";


# SET BASE API & CONTROLLER
$BASE = new CoreBase();
$BASE->api();
$BASE->controller();