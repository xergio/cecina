<?php

class TestController extends AppController {
    
    function index() {
        $t = new Tests();
        $t->aaa();
        //aa();
        return array(
            "dump" => print_r($t, true),
            "query" => print_r($t->select()->where(array("name" => "adios"))->go(), true)
        );
    }
}
