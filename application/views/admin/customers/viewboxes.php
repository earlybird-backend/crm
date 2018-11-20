<div id="content">
    <div class="box-element"> 
        <div class="box-head">
            <h3><?php echo $title; ?></h3>			  
        </div>
        <div class="productbox">
            <?php if (is_array($boxdata) && sizeof($boxdata) > 0) { ?>

                <table class="table" width="100%" cellspacing="0" cellpadding="4" border="0" class="data">
                    <tbody>

                        <tr>
                            <th valign="middle" width="6%">Sr.No</td>
                            <th valign="middle" width="6%">Username</td>
                            <th valign="middle" width="6%">EmailAddress</td>                            
                            <th valign="middle" width="6%">BoxName</td>
                            <th valign="middle" width="6%">BoxCreatedDate</td>                               
                            <th valign="middle" width="6%">Box Open Date</td>
                            <th valign="middle" width="6%">Box Visit</td>
                            <th valign="middle" width="6%">BoxSpace (in kb )</td>
                            <th valign="middle" width="6%">Pay Time</td>
                            <th valign="middle" width="6%">Pay Amount</td>
                            <th valign="middle" width="6%">Pay Type</td>
                                
                        </tr>

                        <?php
                        $n = 0;
                        foreach ($boxdata as $key => $value) {
                            $n++;
                             $boxspacesql = "SELECT sum(FileSize) from user_box_data where UserId='" . $value['UserId'] . "' AND BoxId='" . $value['BoxId'] . "' ";
                            $boxspacestatus = $this->db->query($boxspacesql);
                            $boxspacedata = $boxspacestatus->result_array();                            
                            
                            if($boxspacedata)
			{	
				foreach($boxspacedata as $p=>$s)
				{
					$sumboxspace = $s['sum(FileSize)'];                                         
                                          	
					
				}
			}
                     
                         $lastsql = "SELECT * from user_box_open_details where UserId='" . $value['UserId'] . "' AND BoxId='" . $value['BoxId'] . "' ORDER BY OpenId ASC ";
                            $laststatus = $this->db->query($lastsql);
                            $lastdata = $laststatus->result_array();
                             
                            $totalvisit= count($lastdata);           
                            
                        if($lastdata)
			{	
				foreach($lastdata as $p=>$s)
				{
				       $OpenDate = $s['OpenDate']; 
                                        $paytype = $s['PaymentOption'];
                                        $boxid = $s['BoxId'];                                          
                                          	
					
				}
			}
                        
                        
                        
                            ?>

                            <tr class="<?php //echo $class; ?>" style="background-color:#CCCCCC">
                                <td><?php echo $n; ?></td>
                                <td> <?php echo $username; ?></td>
                                <td> <?php echo $EmailAddress; ?></td>                                
                                <td> <?php echo $value['BoxName']; ?></td>
                                 <td> <?php echo $value['BoxCreatedDate']; ?></td>
                                 <td> <?php if($boxid==$value['BoxId']) { echo $OpenDate; } ?></td>
                               <td> <?php if($boxid==$value['BoxId']) { echo $totalvisit; } ?></td>  
                                <td> <?php echo round($sumboxspace, 2);?></td>
                                 <td> <?php if($boxid==$value['BoxId']) { echo $OpenDate; } ?></td>
                                 <td> <?php if($boxid==$value['BoxId']) { echo $paytype; } ?></td>
                                 <td> <?php if($boxid==$value['BoxId']) { echo $paytype; } ?></td>


                            </tr>		

                            <?php
                        }
                        ?>


                    </tbody>
                </table>

            <?php } else { ?>
                <p align="center">No Record Found!</p>
            <?php } ?>


            <p>&nbsp;</p>

        </div>
        <div class="clear"></div>			
    </div>
    <div class="clear"></div>
</div>