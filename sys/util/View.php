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


    function render($load_template=true, $load_layout=true) {
    	ob_start();

    	foreach ((array)$this->_vars as $_name => $_value)
    		$$_name = $_value;

    	$request = array();
	    foreach ((array)$this->_request as $_name => $_value)
	    	$request[$_name] = $_value;
	    
	    $context = $this->_context->basic_info();
	    
	    $notify = Notify::$strings;


        if ($load_template)
    		include_once(BASEPATH . DS . "app" . DS . "templates" . DS . $this->_context->controller . DS . $this->_context->action .".". $this->_context->media);


	    $fullcontent = ob_get_clean();

        if ($load_layout)
    	    include_once(BASEPATH . DS . "app" . DS . "templates" . DS . $this->_context->layout .".". $this->_context->media);

	    $content = ob_get_contents();

	    ob_end_clean();

	    return $content;
    }
}
