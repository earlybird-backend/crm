<div id="content">
    <div class="box-element"> 
        <div class="box-head">
              <h3><?php echo $title;?></h3>			  
            </div>
        <div class="productbox">
            <?php if (is_array($record) && sizeof($record) > 0) { ?>

                <table class="table" width="100%" cellspacing="0" cellpadding="4" border="0" class="data">
                    <tbody>

                        <tr>
                            <th valign="middle" width="6%">Sr.No</td>
                            <th valign="middle" width="6%">User Name</td>
                             <th valign="middle" width="6%">EmailAddress</td>
                            <th valign="middle" width="6%">Last Reset Password Date</td>	        
                        </tr>

                        <?php
                        $n = 0;
                        foreach ($record as $key => $value) {
                            $n++;
                            ?>

                            <tr class="<?php //echo $class;?>" style="background-color:#CCCCCC">
                                <td><?php echo $n; ?></td>
                                <td> <?php echo $username; ?></td>
                                 <td> <?php echo $EmailAddress; ?></td>
                                <td><?php
                                    echo $value->Resetpassworddate;
                                    ;
                                    ?></td>

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