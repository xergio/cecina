<?php

class Tests extends DB {
    public $_table = "tests";
    public $_fields = array(
        "id"    => array("int"), 
        "name"  => array("varchar", 50), 
        "n"     => array("int"), 
        "date"  => array("datetime")
    );
}
