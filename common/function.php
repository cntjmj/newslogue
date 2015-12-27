<?php
  function monthDropdown($name="month", $selected=null)
  {
          $dd = '<select name="'.$name.'" id="'.$name.'">';

          /*** the current month ***/
          $selected = is_null($selected) ? date('n', time()) : $selected;

          for ($i = 1; $i <= 12; $i++)
          {
                  $dd .= '<option value="'.$i.'"';
                  if ($i == $selected)
                  {
                          $dd .= ' selected';
                  }
                  /*** get the month ***/
                  $mon = date("F", mktime(0, 0, 0, $i+1, 0, 0));
                  $dd .= '>'.$mon.'</option>';
          }
          $dd .= '</select>';
          return $dd;
  }

  function createRandomCode() { 

        $chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
        srand((double)microtime()*1000000); 
        $i = 0; 
        $pass = '' ; 
    
        while ($i <= 7) { 
            $num = rand() % 33; 
            $tmp = substr($chars, $num, 1); 
            $pass = $pass . $tmp; 
            $i++; 
        } 
    
        return $pass; 
    
    }

    
    function getBrowserInfoArray()
    {
        $browser = array(
            'version'   => '0.0.0',
            'majorver'  => 0,
            'minorver'  => 0,
            'build'     => 0,
            'name'      => 'unknown',
            'useragent' => ''
          );
        
          $browsers = array(
            'firefox', 'msie', 'opera', 'chrome', 'safari', 'mozilla', 'seamonkey', 'konqueror', 'netscape',
            'gecko', 'navigator', 'mosaic', 'lynx', 'amaya', 'omniweb', 'avant', 'camino', 'flock', 'aol'
          );
        
          if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $browser['useragent'] = $_SERVER['HTTP_USER_AGENT'];
            $user_agent = strtolower($browser['useragent']);
            foreach($browsers as $_browser) {
              if (preg_match("/($_browser)[\/ ]?([0-9.]*)/", $user_agent, $match)) {
                $browser['name'] = $match[1];
                $browser['version'] = $match[2];
                @list($browser['majorver'], $browser['minorver'], $browser['build']) = explode('.', $browser['version']);
                break;
              }
            }
          }
        return $browser;
    }    
    
    
    function generateInput($mode = "text",$id,$class="",$name,$selectedValue="",$customHTML="")
    {   
        if($mode == "text")
        {
            $inputHTML = '<input type="text" id="'.$id.'" name="'.$name.'" class="'.$class.'" value="'.$selectedValue.'" '.$customHTML.' />';
        }
        else if($mode == "password")
        {
            $inputHTML = '<input type="password" id="'.$id.'" name="'.$name.'" class="'.$class.'" value="'.$selectedValue.'" '.$customHTML.' />';
        }
        else if($mode == "textarea")
        {
            $inputHTML = '<textarea id="'.$id.'" name="'.$name.'" class="'.$class.'" '.$customHTML.'>'.$selectedValue.'</textarea>';
        }
        else if($mode == "checkbox")
        {
            $selected = ($selectedValue == 't')? "checked='checked'":"";
            $inputHTML = '<input type="checkbox" id="'.$id.'" name="'.$name.'" class="'.$class.'" value="t" '.$customHTML.'  '.$selected.'/>';
        }
        
        return $inputHTML;
    }
    
    function RemoveNumbersFromString($string)
    {
        $vowels = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
  		$string = str_replace($vowels, '', $string);
        
        return $string;
    }
    
    function CboGender($id,$name,$selectedValue,$firstText="",$customHTML = ""){
        global $connection,$database;
        
        $genderArray = array("male"=> "Male", "female"=> "Female" );
        if(is_array($genderArray) && count($genderArray) > 0)
        {
            echo "<select id='".$id."' name='".$name."' ".$customHTML.">";
            echo ($firstText != "")? "<option value=''>".$firstText."</option>":"";
            foreach($genderArray as $id => $value)
            {
                $selected = "";
                if($selectedValue == $id)
                    $selected = "selected='selected'";
                    
                echo "<option value='".$id."' ".$selected.">".$database->cleanData($value)."</option>";
            }
            echo "</select>";
        }
    } 
    
    
    
    function CboDay($id,$name,$selectedValue,$customHTML = ""){
        
        $html = '
        <select id="'.$id.'" name="'.$name.'" '.$customHTML.'>
            <option value="">Select the day</option>';
                for($dd=1;$dd<=31;$dd++)
                {
                    $selectedDay = ($dd==$selectedValue)? "selected='selected'":"";
                    $html .= "<option value='".$dd."' ".$selectedDay.">".$dd."</option>";
                }
            
        $html .= '</select>';
        
        return $html;
    }
    
    function CboMonth($id,$name,$selectedValue,$customHTML = ""){
        
        $html = '<select id="'.$id.'" name="'.$name.'" '.$customHTML.'>';
                                    
        $month_array = array(
        "1"=>"Jan",
        "2"=>"Feb",
        "3"=>"Mar",
        "4"=>"Apr",
        "5"=>"May",
        "6"=>"Jun",
        "7"=>"Jul",
        "8"=>"Aug",
        "9"=>"Sep",
        "10"=>"Oct",
        "11"=>"Nov",
        "12"=>"Dec",
        );
        
        $html .= '<option value="">Select the month</option>';
                                    
        foreach($month_array as $monthID => $monthValue)
        {
            $selectedMonth = ($monthID==$selectedValue)? "selected='selected'":"";
            $html .= "<option value='".$monthID."' ".$selectedMonth.">".$monthValue."</option>";
        }
            
        $html .= '</select>';
        
        return $html;
    }
    
    
    function GetMonth($selectedValue){
        
        
        $month_array = array(
        "1"=>"Jan",
        "2"=>"Feb",
        "3"=>"Mar",
        "4"=>"Apr",
        "5"=>"May",
        "6"=>"Jun",
        "7"=>"Jul",
        "8"=>"Aug",
        "9"=>"Sep",
        "10"=>"Oct",
        "11"=>"Nov",
        "12"=>"Dec",
        );
        
        
        return $month_array[$selectedValue];
    }
    
    
    function CboYear($id,$name,$selectedValue,$startYear,$endYear,$customHTML = ""){
        $html = '<select id="'.$id.'" name="'.$name.'" '.$customHTML.'>
            <option value="">Select the year</option>';
            
            for($yy=$startYear;$yy<=$endYear;$yy++)
            {
                $selectedYear = ($yy==$selectedValue)? "selected='selected'":"";
                $html .= "<option value='".$yy."' ".$selectedYear.">".$yy."</option>";
            }
            
        $html .= '</select>';
        
        return $html;
    }
    
    
    
    
    
    function randLetter()
    {
        $int = rand(0,51);
        $a_z = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $rand_letter = $a_z[$int];
        return $rand_letter;
    }

    function redirect($seconds,$url,$exit = "exit"){
        $seconds = intval($seconds);
        echo '<meta http-equiv="refresh" content="'.$seconds.';url='.$url.'">';
        
        if($exit == "exit")
            exit;
    }
    
    
    function validEmail($email)
    {
       $isValid = true;
       $atIndex = strrpos($email, "@");
       if (is_bool($atIndex) && !$atIndex)
       {
          $isValid = false;
       }
       else
       {
          $domain = substr($email, $atIndex+1);
          $local = substr($email, 0, $atIndex);
          $localLen = strlen($local);
          $domainLen = strlen($domain);
          if ($localLen < 1 || $localLen > 64)
          {
             // local part length exceeded
             $isValid = false;
          }
          else if ($domainLen < 1 || $domainLen > 255)
          {
             // domain part length exceeded
             $isValid = false;
          }
          else if ($local[0] == '.' || $local[$localLen-1] == '.')
          {
             // local part starts or ends with '.'
             $isValid = false;
          }
          else if (preg_match('/\\.\\./', $local))
          {
             // local part has two consecutive dots
             $isValid = false;
          }
          else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
          {
             // character not valid in domain part
             $isValid = false;
          }
          else if (preg_match('/\\.\\./', $domain))
          {
             // domain part has two consecutive dots
             $isValid = false;
          }
          else if
    (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                     str_replace("\\\\","",$local)))
          {
             // character not valid in local part unless 
             // local part is quoted
             if (!preg_match('/^"(\\\\"|[^"])+"$/',
                 str_replace("\\\\","",$local)))
             {
                $isValid = false;
             }
          }
          /*
		  if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
          {
             // domain not found in DNS
             $isValid = false;
          }
		  */
       }
       return $isValid;
    }

?>