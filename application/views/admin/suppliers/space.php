<div id="content">
    <div class="box-element"> 
        <div class="box-head">
              <h3><?php echo $title;?></h3>			  
            </div>
        <div class="productbox">
            <?php if (is_array($plandata) && sizeof($plandata) > 0) { ?>

                <table class="table" width="100%" cellspacing="0" cellpadding="4" border="0" class="data">
                    <tbody>

                        <tr>
                            <th valign="middle" width="6%">Sr.No</td>
                            <th valign="middle" width="6%">Username</td>
                                <th valign="middle" width="6%">EmailAddress</td>
                            <th valign="middle" width="6%">PlanSpace</td>
                                 <th valign="middle" width="6%">PlanTime</td>
                                      <th valign="middle" width="6%">PlanAmount</td>
                             <th valign="middle" width="6%">PurchaseDate</td>
                            <th valign="middle" width="6%">ExpireDate</td>
                             <th valign="middle" width="6%">PaymentOption</td>
                        </tr>

                        <?php
                        $n = 0;
                        foreach ($plandata as $key => $value) {
                            $n++;
                            
                            ?>
                     
                            <tr class="<?php //echo $class;?>" style="background-color:#CCCCCC">
                                <td><?php echo $n; ?></td>
                        <td> <?php echo $username; ?></td>
                         <td> <?php echo $EmailAddress; ?></td>
                                <td> <?php echo $value['PlanSpace']; ?></td>
                                 <td> <?php echo $value['PlanTime']; ?></td>
                                  <td> <?php echo $value['PlanAmount']; ?></td>
                                   <td> <?php echo $value['PurchaseDate']; ?></td>
                                    <td> <?php echo $value['ExpireDate']; ?></td>
                                     <td> <?php echo $value['PaymentOption']; ?></td>                              
                                

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