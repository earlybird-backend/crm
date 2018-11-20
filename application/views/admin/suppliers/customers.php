<script language="javascript">
    $(document).ready(function () {
        $('#save').click(function () {
            window.location = "<?php echo site_url('admin_bak/suppliers/customers') ?>";
        });
    });

</script>
<?php
if (is_array($data) && sizeof($data) > 0) {
    // extract($data[0]);
    // echo '<pre>';print_r($data); die; 
}
?>


<div id="content">
    <div class="box-element">
        <div class="box-head">
            <h3><?php echo $title; ?></h3>			 
        </div>
        <div class="productbox ">
            <?php echo form_open('admin_bak/suppliers/customers/' . $datas, array('name' => 'search', 'id' => 'search')); ?>

            <table class="table" width="100%" border="0" cellspacing="2" cellpadding="0">
                <tr>
                    <td align="left" class="currency-intro">
                        Search key :
                        <input type="text" class="custom-input" name="key" size="32" value="<?php echo $this->input->post("key"); ?>" />
                                    <i class="fa fa-search" aria-hidden="true"></i>
                        <input type="submit" name="submit" value="Search" id="search" class="save btn custom-btn" />
                        <input type="button" name="showall" class="save btn custom-btn" value="Show All" id="search" onclick="window.location = '<?php site_url('admin_bak/customers/suppliers' . $datas) ?>'" />
                    </td>
                </tr>
            </table>
            <?php echo form_close(); ?>

            <?php if (is_array($data) && sizeof($data) > 0) { ?>
                <div class="pagination" style="float:right;">
                    <?php echo $paginglinks; ?></div>

                <div class="table-light panel">
                    <div class="table-header ">
                        <div class="table-caption">
                           <?php echo $title; ?>
                        </div>
                    </div>
                    <table class="table table-bordered panel-body">
                        <thead>
                            <tr>
                                
                                <th>Customer ID</th>
                                <th>Customer Name</th>                               
                                <th>Contact</th>
                                <th>Phone</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $n = 0;
                            $counter = 0;
                            $counter = $counter + $per_page;

                            foreach ($data as $value) {
								
								
								 $supemailsql = "SELECT * from supplier_by_customer where Email='" .$value->EmailAddress . "' order by SupplierId DESC";
								 $supemailstatus = $this->db->query($supemailsql);
                                 $supemaildata = $supemailstatus->result_array();
                              //pr($supemaildata);

                               foreach ($supemaildata as $svalue) { 						  
								
								$customer_where = array('UserId' => $svalue['CustomerId']);
				           $customer_data = $this->UniversalModel->getRecords('site_users', $customer_where, NULL, NULL, NULL);
                           	     //pr($customer_data);								
				
                                ?>
                                <tr>
								
								  <td><?php echo $customer_data[0]['UserId']; ?></td>
                                    <td><?php echo $customer_data[0]['CompanyName']; ?></td>                                    
                                    <td><?php echo $customer_data[0]['ContactName']; ?></td>
                                    <td><?php echo $customer_data[0]['Cellphone']; ?></td>
                                    <td><?php echo $customer_data[0]['EmailAddress']; ?></td>
                                    
                                    
                                </tr>

                                <?php
								 }
                                $counter++;
                            }
                            ?>

                        </tbody>
                    </table>
<!--                    <div class="table-footer">
                        Footer
                    </div>-->
                </div>
                
                <div class="pagination" style="float:right;">
                    <?php echo $paginglinks; ?></div>

            <?php } else { ?>
                <p align="center">No Record Found!</p>
            <?php } ?>


            <p>&nbsp;</p>

        </div>
        <div class="clear"></div>			
    </div>
    <div class="clear"></div>
</div>
