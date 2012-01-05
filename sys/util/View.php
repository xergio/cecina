<?php

class View {
    
    function __construct($context, $vars=array(), $params=array(), $request=array()) {
        $this->_context = $context;
        $this->_vars = $vars;
        $this->_params = $params;
        $this->_request = $request;
    }


    function render($load_template=true, $load_layout=true) {
    	ob_start();

    	foreach ((array)$this->_vars as $_name => $_value)
    		$$_name = $_value;

    	foreach (array("params", "request") as $_type)
	    	foreach ((array)$this->{"_". $_type} as $_name => $_value)
	    		${$_type}[$_name] = $_value;


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
