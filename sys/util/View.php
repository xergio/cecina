<?php

class View {
    
    function __construct($vars, $context, $params, $request) {
        $this->_vars = $vars;
        $this->_context = $context;
        $this->_params = $params;
        $this->_request = $request;
    }


    function render() {
    	ob_start();

    	foreach ((array)$this->_vars as $_name => $_value)
    		$$_name = $_value;

    	foreach (array("context", "params", "request") as $_type)
	    	foreach ((array)$this->{"_". $_type} as $_name => $_value)
	    		${$_type}[$_name] = $_value;


		include_once(BASEPATH . DS . "app" . DS . "templates" . DS . 
	    	$context["controller"] . DS . $context["action"] .".". $context["media"]);


	    $fullcontent = ob_get_clean();

	    include_once(BASEPATH . DS . "app" . DS . "templates" . DS . 
	    	$context["layout"] .".". $context["media"]);

	    $content = ob_get_contents();
	    ob_end_clean();

	    return $content;
    }
}
