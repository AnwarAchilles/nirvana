<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Twig
{
    
    // set var codeigniter
    private $CI;
    // set var twig
    private $_twig;
    // set var template directory
    private $_template_dir;
    // set cache directory
    private $_cache_dir;


    # init function
    function __construct()
    {
        // load codeigniter
        $this->CI =& get_instance();
        // load twig configuration
        $this->CI->config->load('twig');


        // set init for require
        ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . APPPATH . 'third_party/Twig');
        // load autoloader twig
        require_once (string)'Autoloader.php';


        // load codeigniter helper
        $this->CI->load->helper('url');
        

        // log message twig
        log_message('debug', "Twig Autoloader Loaded");

        // registering twig
        Twig_Autoloader::register();

        
        // set template directory to codeigniter views
        $this->_template_dir = $this->CI->config->item('twig_template_dir');
        // set chache directory to codeigniter cache
        $this->_cache_dir = $this->CI->config->item('twig_cache_dir');

        
        // plugin : (un mapping)
        $path_plugin_view = './plugins/views/';
        if (is_dir($path_plugin_view)) {
            # lihat pengaturan off plugin
            $turn_off_all_plugin = $this->CI->config->item('turn_off_all_plugin');
            if ($turn_off_all_plugin) {
                $twig_template_dir = array($this->_template_dir);
            } else {
                $twig_template_dir = array($path_plugin_view, $this->_template_dir);
            }
        } else {
            $twig_template_dir = array($this->_template_dir);
        }

        
        // set var twig loader
        $loader = new Twig_Loader_Filesystem($twig_template_dir);

        // set twig by loader
        $this->_twig = new Twig_Environment($loader, $this->CI->config->item('twig_environment'));

        
        // get php native function
        foreach(get_defined_functions() as $functions) {
            foreach($functions as $function) {
                $this->_twig->addFunction($function, new Twig_Function_Function($function));
            }
        }
    }

    
    # for add function to twig
    public function add_function($name)
    {
        $this->_twig->addFunction($name, new Twig_Function_Function($name));
    }

    
    # render template and data
    public function render($template, $data = array())
    {
        $template = $this->_twig->loadTemplate($template);
        return $template->render($data);
    }
    
    # set display for render template
    public function display($template, $data = array())
    {
        $template = $this->_twig->loadTemplate($template);
        $this->CI->output->set_output($template->render($data));
    }

    # set view
    public function view($template, $data = array(), $render=FALSE)
    {
        $template = $this->_twig->loadTemplate($template.$this->CI->config->item('twig_extension'));
        if ($render == TRUE) {
            return $template->render($data);
        }else {
            $this->CI->output->set_output($template->render($data));
        }
    }
}
