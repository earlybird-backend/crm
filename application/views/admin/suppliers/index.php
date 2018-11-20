        <div class="panel">   
          
            <?php if (is_array($result) && sizeof($result) > 0) { ?>        
			
					<div class="panel-body">
     <div class="table-light panel mr-custom-strip">                   
					<?php if($this->session->flashdata('activatemessage')){?>
                    <div class="alert alert-success alert-dark mr-custom-wrap"> <?php echo $this->session->flashdata('activatemessage');                    
                    ?>
                        <button type="button" class="close" data-dismiss="alert">×</button>
                    </div>
                    <?php } ?>
                     <?php if($this->session->flashdata('changecompanyname')){?>
                    <div class="alert alert-success alert-dark"> <?php echo $this->session->flashdata('changecompanyname');                    
                    ?>
                        <button type="button" class="close" data-dismiss="alert">×</button>
                    </div>
                    <?php } ?>
              
                </div>
					<div class="table-primary">
						  <table class="table table-bordered" id="supplierdatatables">
                        <thead>
                            <tr>

                                
                                <th>Supplier Name</th>
								<th>Vendor Code</th>
                                <th>Company Profile</th>
                                <th>New Company Name</th>
                                <th>Change Company Name Request</th> 
                                <th>Contact</th>
                                <th>Customers</th>
                                <th>Contact Person</th>
                                <th>Register Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $n = 0;
                            $counter = 0;
                            $counter = $counter + $per_page;
                 $yusersql = "SELECT * from supplier_by_customer Group BY Vendorcode";
                 $yuserstatus = $this->db->query($yusersql);
                   $yuserdata = $yuserstatus->result_array();                        
					   
                            foreach ($result as $key => $value) {         
                              $counter++;
                                 $vendersql = "SELECT * from supplier_by_customer where  Email='" . $value['EmailAddress']. "'";
                                  $venderstatus = $this->db->query($vendersql);
                                  $venderdata = $venderstatus->result_array();
                                ?>
                                <tr>                                    
                                    
                                    <td><?php echo $value['CompanyName']; ?></td>
									<td><?php echo $venderdata[0]['Vendorcode']?></td>
                                    <td><a href="<?php echo site_url('admin_bak/suppliers/companyprofile/' . $value['UserId']) ?>">View</a></td>
                                    <td><?php echo $value['NewCompanyName']; ?></td>
                                    <td><a href="<?php echo site_url('admin_bak/suppliers/changecompanyname/' . $value['UserId']) ?>">Change Name</a></td>
                                    <td><a href="<?php echo site_url('admin_bak/suppliers/contact/' . $value['UserId']) ?>">View</a></td>
                                    <td><a href="<?php echo site_url('admin_bak/suppliers/customers/' . $value['UserId']) ?>">View</a></td>
                                    <td><?php echo $value['ContactName']; ?></td>
                                    <td><?php if($value['RegisterStatus']==0){
										echo 'No Register';
									}else{ echo 'Registered'; }
									 ?></td>
                                </tr>
                                <?php
                            }
							    $counts=$counter;
							 foreach ($yuserdata as $key => $value) {
								 $counts++;
                                  if($value['RegisterStatus']==0){
								 ?>
								  <tr>                                    
                                   
                                    <td><?php echo $value['supplier']; ?></td>
									<td><?php echo $value['Vendorcode']; ?></td>
                                    <td class="text-center"><?php echo '-';?></td>
                                     <td class="text-center"><?php echo '-';?></td>
                                    <td class="text-center"><?php echo '-';?></td>
                                     <td class="text-center"><?php echo '-';?></td>
                                     <td class="text-center"><?php echo '-';?></td>
                                    <td><?php echo $value['ContactPerson']; ?></td>
                                    <td>No Register</td>
                                </tr>
								  <?php } }
                            ?>
                        </tbody>
                    </table>							
						</div>
					</div>				

            <?php } ?>           
            


            <p>&nbsp;</p>

        </div>
     