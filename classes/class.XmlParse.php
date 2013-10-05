<?PHP

class TxmlParse
{
    private $_nodes = Null;
    
    public function __construct($path)
    {
        $file       = file($path);
        $div_string = Null;

        foreach ($file as $key => $value) 
        {
            if(preg_match('!<+[a-zA-Z0-9_]+>!', $value, $Open_matches)){
                $node_name = $this->clean_node($Open_matches[0]);
                $div_string .= '<div class="'.$node_name.'">'."\n";
            }
            
            if(preg_match('!<+[a-zA-Z0-9_]+/>!', $value, $child_matches)){
                $node_name = $this->clean_node($child_matches[0]);
                $div_string .= "\t".'<div class="'.$node_name.'">{'.$node_name."}</div>\n";
                
            }

            if(preg_match('</+[a-zA-Z0-9_]+>', $value, $End_matches)){
                $div_string .= "</div>\n";
            }
        }
        $this->_nodes = $div_string;
    }

    public function get_divs()
    {
        return $this->_nodes;
    }

     private function clean_node($arg)
        {
            preg_match('![^</]+[a-zA-Z0-9_]+[^/>]!ixsm', $arg, $match);
            return $match[0];
        }
            
}