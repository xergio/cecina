<?php

class String {
    
    // http://www.php.net/manual/en/function.mysql-real-escape-string.php#101248
    public static function mysql_escape($str) {
        if (!empty($str) && is_string($str))
            return str_replace(
                array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), 
                array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), 
                $str); 
        return $str; 
    } 


    // http://www.php.net/manual/en/function.debug-backtrace.php#47644
    static function trace_to_str($trace) {
        $return = array();

        foreach ((array)$trace as $i => $bt) {
            $args = '';

            foreach ($bt['args'] as $a) {
                if (!empty($args))
                    $args .= ', ';
                
                switch (gettype($a)) {
                    case 'integer':
                    case 'double':
                        $args .= $a;
                        break;
                    case 'string':
                        $a = htmlspecialchars(substr($a, 0, 15)).((strlen($a) > 15) ? '...' : '');
                        $args .= "\"$a\"";
                        break;
                    case 'array':
                        $args .= 'array('.count($a).')';
                        break;
                    case 'object':
                        $args .= 'Object('.get_class($a).')';
                        break;
                    case 'resource':
                        $args .= 'Resource('.strstr($a, '#').')';
                        break;
                    case 'boolean':
                        $args .= $a ? 'true' : 'false';
                        break;
                    case 'NULL':
                        $args .= 'null';
                        break;
                    default:
                        $args .= 'Unknown';
                }
            }
            $return[] = "#{$i} ". str_replace(" ". BASEPATH ."/", " ", " ". $bt['file']) ."({$bt['line']}) {$bt['class']}{$bt['type']}{$bt['function']}($args)";
        }
        $return[] = "#". ++$i ." {main}";

        return implode("\n", $return);
    }
}