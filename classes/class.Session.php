<?php
class TSession
{
    private static $_start_flag = Null;

    /**
     * Start the session if it isn't started
     */
	static function start() 
    {
        if(self::$_start_flag == Null)
        {
		     session_start();
             self::$_start_flag = true;
        }
	}

    /**
     * Destroy the stored Session data 
     */
    static public function destroy()
    {
        if(self::$_start_flag != Null) 
        {
            session_destroy();
            self::$_start_flag = Null;
            return true;
        }
    }

    /**
     * add function to add a value the easy way
     * 
     *  Param $data_path as string  
     *  Param $data      as mixed => the data to add
     * 
     *  eg: Session::add("user:profile:username", "Morphius");
     *  will create $_SESSION["user"]["profile"]["username"] = "Morphius"
     */
    static public function add($data_path, $data) 
    {
        if(!empty($data) && !empty($data_path)) {
            $array = array_reverse(explode(":", $data_path));
            $first = array_pop($array);
            $result = $data;
             foreach($array as $key => $value){
                  $result = array($value => $result);
             }
             $_SESSION[$first] = $result;
        }
    }

    /**
     * set function to set a value the easy way
     * 
     *  Param $data_path as string  
     *  Param $data      as mixed => the data to set
     * 
     *  eg: Session::set("user:profile:username", "Morphius");
     *  will set $_SESSION["user"]["profile"]["username"] = "Morphius"
     */
    static public function set($data_path, $value)
    {
        $keys = explode(":", $data_path);
        $looper = @$_SESSION;
        $length = count($keys) - 1;
        for ($i=0; $i <= $length ; $i++) {
            if(isset($looper[$keys[$i]])){
                $looper = @$looper[$keys[$i]];
            }
        }// end for loop
        $looper = $value;
    }

    /**
     * get function to find the value the easy way
     * 
     *  Param $data_path as string  
     * 
     *  eg: Session::get("user:profile:username");
     *  will get $_SESSION["user"]["profile"]["username"]
     * 
     *  Return mixed : session data or Null
     */
    static public function get($data_path)
    {
        if(is_array($data_path)) {
            $looper = $_SESSION;
            $length = count($data_path) - 1;
            for ($i=0; $i <= $length ; $i++) {
                if(isset($looper[$data_path[$i]])){
                    $looper = $looper[$data_path[$i]];
                } else {
                    $looper = Null;
                }
            }// end for loop
            return $looper;
        } else if(is_string($data_path)) {
            $path = trim($data_path,": ");
             return self::get(explode(":", $data_path));
        }
    }

    /**
     * Static function to display the session array
     */
    static public function display()
    {
                echo '<pre>';
                print_r($_SESSION);
                echo '</pre>';
    }

}// Class end