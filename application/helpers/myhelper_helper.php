<?php
if(!function_exists('pr'))
{
  function pr($value)
  {
	echo '<pre>'; print_r($value); echo '</pre>';
  }
}

function contextualTime($small_ts, $large_ts=false) {
  if(!$large_ts) $large_ts = strtotime(date('Y-m-d h:i:s',time()));
  $n = $large_ts - $small_ts;
   //echo '<br>'.$small_ts.'<br>'.$large_ts.'<br>'.$n.'<br>';  
  if($n <= 1) return 'less than 1 second ago';
  
  if($n < (60)) return $n . ' seconds ago';
  
  if($n < (60*60)) { $minutes = round($n/60); return 'about ' . $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago'; }
  
  if($n < (60*60*16)) { $hours = round($n/(60*60)); return 'about ' . $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago'; }
  
  if($n < (time() - strtotime('yesterday'))) return 'yesterday';
  
  //if($n < (60*60*24*2)) { $hours = round($n/(60*60)-24); return '1 day, ' . $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago'; } 
  
  if($n < (60*60*24)) { $hours = round($n/(60*60)); return 'about ' . $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago'; }
  
  if($n < (60*60*24*6.5)) return 'about ' . round($n/(60*60*24)) . ' days ago';
  
  if($n < (time() - strtotime('last week'))) return 'last week';
  
  if(round($n/(60*60*24*7))  == 1) return 'about a week ago';
  
  if($n < (60*60*24*7*3.5)) return 'about ' . round($n/(60*60*24*7)) . ' weeks ago';
  
  if($n < (time() - strtotime('last month'))) return 'last month';
  
  if(round($n/(60*60*24*7*4))  == 1) return 'about a month ago';
  
  if($n < (60*60*24*7*4*11.5)) return 'about ' . round($n/(60*60*24*7*4)) . ' months ago';
  
  if($n < (time() - strtotime('last year'))) return 'last year';
  
  if(round($n/(60*60*24*7*52)) == 1) return 'about a year ago';
  
  if($n >= (60*60*24*7*4*12)) return 'about ' . round($n/(60*60*24*7*52)) . ' years ago'; 
  
  return false;
}

if ( ! function_exists('validation_errors_array'))
{
    function validation_errors_array()
    {
        if (FALSE === ($OBJ =& _get_validation_object()))
        {
            return '';
        }
        // No errrors, validation passes!
        if (count($OBJ->_error_array) === 0)
        {
            return '';
        }
        // Generate the error string
        $array = '';
        foreach ($OBJ->_error_array as $key => $val)
        {
            if ($val != '')
            {
                $array[$key]= $val;
            }
        }
        return $array;
    }
}

?>