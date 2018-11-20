<?php
class Pager {


##############################################################
//$this->load->library('pager');			
//$sql = "SELECT * FROM category";
//$url = 'http://192.168.2.13/site.com/admin/seekers/index/';
//$result = $this->pager->public_pager($sql,'2',$url);
//echo '<pre>'; print_r($result);

function public_pager($sqlstring, $length='',$url)
	{   
		//global $db, $system;
		//$page_length = $GLOBALS['default_page_length'];
		
		if($length)
		{
		   $page_length = $length;
		}
		
		$query = mysql_query($sqlstring);
		$cnt = mysql_num_rows($query);
		//echo $cnt; 
		if(!$url)
		{
		 $url = $_SERVER['PHP_SELF']; 
		}
		
		//$url  = $_SERVER['REQUEST_URI'];
		//echo $_SERVER['QUERY_STRING'] ;
		
		//die;
		
		if($cnt > $page_length)
			{
				//$pageset = public_fix_get('pageset');
				
				$pageset = 1;
				
				if(isset($_GET['pageset']))
					{
						$pageset = intval($_GET['pageset']);
					
					}
					
				if($pageset == 0)
					{
						$pageset = 1;
					}
					
					
				$q = $_GET;
				
				$querystring = '';
				
				foreach($q as $key => $value)
					{
						if($key != 'pageset')
							{
								//$querystring .= '&amp;' . $key . '=' . $value;
								$querystring .= '/' . $key . '/' . $value;
							}
					}
					
				//echo($querystring);
				
				
									
				$total_pages = ceil($cnt / $page_length);
				
				if($pageset > $total_pages)
					{
						$pageset = 1;
					}
					
				$xfrom = (($pageset * $page_length) - $page_length); //set the delimiter for the paging
				
				
				$sqlstring = $sqlstring . " LIMIT " . $xfrom . ", " . $page_length . " ";
				
				$xto = $xfrom + $page_length;
				 
				if($xto > $cnt)
					{
					$xto = $cnt;
					}
					
				$xf = $xfrom + 1;
			}
		else
			{
				$xf = 1;
				$xto = $cnt;
				$pageset = 1;
				$total_pages = 1;
			}
			
			
		$pgr = '';
		$pgr2 = '';
		
		if($total_pages > 1)
			{
				
				
				$pgr = '<p class="rpager"><strong>Page ' . $pageset . ' of ' . $total_pages . '</strong>&nbsp;&nbsp showing records ' . $xf . ' to ' .  $xto . ' of ' . $cnt . ' in this selection</p>';
				
				$pgr2 = '<p class="rpagernav">';
				
				if($pageset > 1)
						{
							$p = $pageset - 1;
							$pgr2 .= '|<a href="' . $url . '?pageset=1">&lt;&lt;&nbsp;</a> &nbsp;<span>|</span>';
							if($pageset > 2)
								{
									$pgr2 .= '<a href="' . $url . '?pageset=' . $p . $querystring . '">&nbsp;&lt;&nbsp;</a> &nbsp;<span>|</span>';
								}
						}
					
					// now write the numbering bit
					
					if($pageset > 5)
						{
							$ii = $pageset - 4;
						}
					else
						{
							$ii = 1;
						}
						
					if($total_pages > 9)
						{
							$t = $pageset + 4;
							if($t > $total_pages)
								{
									$t = $total_pages;
								}
						}
					else
						{
							$t = $total_pages;
						}
					
					for($i = $ii; $i <= $t; $i++)
						{
							if($pageset != $i)
								{
									$pgr2 .= '<a href="' . $url . '?pageset=' . $i . $querystring . '">&nbsp;' . $i . '&nbsp;</a> &nbsp;<span>|</span>';
								}
							else
								{
									$pgr2 .= '&nbsp;<strong>' . $i . '</strong>&nbsp;&nbsp;&nbsp;|';
								}
						}
					
					
				
					if($pageset < $total_pages)
						{
							$p = $pageset + 1;
							$pgr2 .= '<a href="' . $url . '?pageset=' . $p . $querystring .'">&nbsp;&gt;&nbsp;</a> &nbsp;<span>|</span>';
							if($pageset < ($total_pages - 1))
								{
									$pgr2 .= '<a href="' . $url . '?pageset=' . $total_pages . $querystring . '">&nbsp;&gt;&gt;</a>|&nbsp;<span></span>';
								}
						}
										
				
				
				
				$pgr2  .= '</p>';
				
				
			}
			
			$pray = array();
			$pray[0] = $sqlstring;
			$pray[1] = $pgr;
			$pray[2] = $pgr2;
			
			return $pray;
	}


}

?>