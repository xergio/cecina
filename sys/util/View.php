<?php

class View {
	
	protected $_context;
	protected $_request;
	protected $_vars;
	
    
    function __construct($context, $vars=array(), $request=array()) {
        $this->_context = $context;
        $this->_vars = $vars;
        $this->_request = $request;
    }


    function render($load_template=true, $load_layout=true, $load_controller_layout=true) {
	    
	    $mimetypes = array(
	    	'html' => 'text/html',
	    	'htm' => 'text/html',
	    	'json' => 'application/x-javascript',
	    	'xml' => 'text/xml',
	    	'txt' => 'text/plain'
	   	);
	    $mime = (array_key_exists($this->_context->media, $mimetypes)? 
	    		$mimetypes[$this->_context->media]: 
	    		ini_get('default_mimetype'));
        header("Content-Type: ". $mime ."; charset=utf-8");
        
        
    	ob_start();

    	$__vars = $this->_vars;
    	$__request = $this->_request;
	    $context = $this->_context->basic_info();
	    $notify = Notify::consume();
	    
	    
    	foreach ((array)$this->_vars as $_name => $_value)
    		$$_name = $_value;

    	
    	$request = array();
	    foreach ((array)$this->_request as $_name => $_value)
	    	$request[$_name] = $_value;
	    
        if ($load_template) {
    		include_once(BASEPATH . DS . "app" . DS . "templates" . DS . $this->_context->controller . DS . $this->_context->action .".". $this->_context->media);
    		
    		if ($load_controller_layout) {
    			$__controller_layout = BASEPATH . DS . "app" . DS . "templates" . DS . $this->_context->controller . DS . "__common.". $this->_context->media;
    			if (file_exists($__controller_layout)) {
	    			$content = ob_get_clean();
    				include_once($__controller_layout);
    			}
    		}
        }

	    $content = ob_get_clean();

        if ($load_layout)
    	    include_once(BASEPATH . DS . "app" . DS . "templates" . DS . $this->_context->layout .".". $this->_context->media);

	    $content = ob_get_contents();

	    ob_end_clean();

	    return $content;
    }
}
