<br/><br/><br/><br/>
<?php
if (is_array($data) && sizeof($data) > 0) {
    extract($data[0]);
    //echo '<pre>';print_r($data[0]);
	
}
?>
<div class="row">
					<div class="col-xs-4">
						<!-- Centered text -->
						<div class="stat-panel text-center">
							<div class="stat-row">
								<!-- Dark gray background, small padding, extra small text, semibold text -->
								<div class="stat-cell bg-dark-gray padding-sm text-xs text-semibold">
									<i class="fa fa-globe"></i>&nbsp;&nbsp;Total Amount
								</div>
							</div> <!-- /.stat-row -->
							<div class="stat-row">
								<!-- Bordered, without top border, without horizontal padding -->
								<div class="stat-cell bordered no-border-t no-padding-hr">
									<div class="pie-chart" data-percent="43" id="easy-pie-chart-1">
									<?php																			
									 $Currencynames = "SELECT * FROM `apr_currency_by_admin` "
                                                    . "WHERE `CurrencyId` ='" .$CurrencyType . "'";

                                            $CurrencyTypequery = $this->db->query($Currencynames);
                                            $CurrencyName = $CurrencyTypequery->result_array();
                                           $CurrencyName[0]['CurrencySign'] . '' . $totalcount;
									 ?>
										<div class="pie-chart-label"><?php echo  $CurrencyName[0]['CurrencySign'] . '' . $totalcount;?>in(<?php echo ucwords($CurrencyName[0]['CurencyName']);?>)</div>
									</div>
								</div>
							</div> <!-- /.stat-row -->
						</div> <!-- /.stat-panel -->
					</div>
					<div class="col-xs-4">
						<div class="stat-panel text-center">
							<div class="stat-row">
								<!-- Dark gray background, small padding, extra small text, semibold text -->
								<div class="stat-cell bg-dark-gray padding-sm text-xs text-semibold">
									<i class="fa fa-flash"></i>&nbsp;&nbsp;Total Invoice
								</div>
							</div> <!-- /.stat-row -->
							<div class="stat-row">
								<!-- Bordered, without top border, without horizontal padding -->
								<div class="stat-cell bordered no-border-t no-padding-hr">
									<div class="pie-chart" data-percent="93" id="easy-pie-chart-2">
										<div class="pie-chart-label"><?php echo $suppliercount; ?></div>
									</div>
								</div>
							</div> <!-- /.stat-row -->
						</div> <!-- /.stat-panel -->
					</div>
					
</div>


<!--Default tabs-->
				<div class="panel">
					<div class="panel-heading">
						<span class="panel-title">Suppliers Data</span>
					</div>
					<div class="panel-body">
						<ul id="uidemo-tabs-default-demo" class="nav nav-tabs">
							<li class="active">
								<a href="#uidemo-tabs-default-demo-supplier" data-toggle="tab">Supplier</a>
							</li>
							<li class="">
								<a href="#uidemo-tabs-default-demo-paydate" data-toggle="tab">Paydate</a>
							</li>
							
							<li class="">
								<a href="#uidemo-tabs-default-demo-invoice" data-toggle="tab">Invoice</a>
							</li>
							
							<li class="pull-right"><a href="<?php echo site_url('admin_bak/plans/export/'.$planid); ?>" class="btn btn-success btns-download">Download</a></li>
						</ul>
						
							
							
						<div class="tab-content tab-content-bordered">
							<div class="tab-pane fade active in" id="uidemo-tabs-default-demo-supplier">
						
						<table class="table table-bordered">
							<thead>
								<tr>
									
									<th>Supplier</th>
									<th>Amount</th>
									
								</tr>
							</thead>
							<tbody>
							<?php 
							if(is_array($firsttabdata) && sizeof($firsttabdata)>0)
							{ 
							
								foreach($firsttabdata as $s=>$i)
								{
							?>
							
								<tr>
									
									<td><?php echo $i['supplier']; ?></td>
									<td><?php
									 $plansql = "SELECT * FROM `customer_early_pay_plans` "
                                                    . "WHERE `PlanId` ='" . $i['PlanId'] . "'";

                                            $planquery = $this->db->query($plansql);
                                            $plandata = $planquery->result_array();											
									$CurrencyType = "SELECT * FROM `apr_currency_by_admin` "
                                                    . "WHERE `CurrencyId` ='" .$plandata[0]['CurrencyType'] . "'";

                                            $CurrencyTypequery = $this->db->query($CurrencyType);
                                            $CurrencyName = $CurrencyTypequery->result_array();
                                           echo $CurrencyName[0]['CurrencySign'] . '' .  $i['InvAmount'];
									 ?></td>
									
								</tr>
							
							<?php	
								}
							}
								
							?>
							</tbody>
						</table>
					</div> <!-- / .tab-pane -->
							
							<div class="tab-pane fade" id="uidemo-tabs-default-demo-paydate">
								<table class="table table-bordered">
							<thead>
								<tr>
									
									<th>VendorCode</th>
									<th>Paydate</th>
									<th>Amount</th>
									
								</tr>
							</thead>
							<tbody>
							<?php 
							if(is_array($secondtabdata) && sizeof($secondtabdata)>0)
							{ 
							
								foreach($secondtabdata as $v=>$e)
								{
							?>
								<tr>
									
									<td><?php echo $e['Vendorcode']; ?></td>
									<td><?php echo $e['EstPaydate']; ?></td>
									<td><?php
									 $plansql = "SELECT * FROM `customer_early_pay_plans` "
                                                    . "WHERE `PlanId` ='" . $e['PlanId'] . "'";

                                            $planquery = $this->db->query($plansql);
                                            $plandata = $planquery->result_array();											
									$CurrencyType = "SELECT * FROM `apr_currency_by_admin` "
                                                    . "WHERE `CurrencyId` ='" .$plandata[0]['CurrencyType'] . "'";

                                            $CurrencyTypequery = $this->db->query($CurrencyType);
                                            $CurrencyName = $CurrencyTypequery->result_array();
                                           echo $CurrencyName[0]['CurrencySign'] . '' .  $e['InvAmount'];
									 ?>
									</td>
									
								</tr>
							<?php	
								}
							}
								
							?>	
								
							</tbody>
						</table>
							</div> <!-- / .tab-pane -->
							
							<div class="tab-pane fade" id="uidemo-tabs-default-demo-invoice">
								<table class="table table-bordered">
							<thead>
								<tr>
									
									<th>Supplier</th>
									<th>Invoice#</th>
									<th>Amount</th>
									<th>Paydate</th>
									
								</tr>
							</thead>
							<tbody>
								<?php 
							if(is_array($thirdtabdata) && sizeof($thirdtabdata)>0)
							{ 
							
								foreach($thirdtabdata as $p=>$s)
								{
							?>
								
								<tr>
									
									<td><?php echo $s['supplier']; ?></td>
									<td><?php echo $s['InvoiceId']; ?></td>
									<td><?php
									 $plansql = "SELECT * FROM `customer_early_pay_plans` "
                                                    . "WHERE `PlanId` ='" . $s['PlanId'] . "'";

                                            $planquery = $this->db->query($plansql);
                                            $plandata = $planquery->result_array();											
									$CurrencyType = "SELECT * FROM `apr_currency_by_admin` "
                                                    . "WHERE `CurrencyId` ='" .$plandata[0]['CurrencyType'] . "'";

                                            $CurrencyTypequery = $this->db->query($CurrencyType);
                                            $CurrencyName = $CurrencyTypequery->result_array();
                                           echo $CurrencyName[0]['CurrencySign'] . '' .  $s['InvAmount'];
									 ?></td>
									<td><?php echo $s['EstPaydate']; ?></td>
								</tr>
								<?php	
								}
							}
								
							?>
							</tbody>
						</table>
							</div> <!-- / .tab-pane -->
							
						</div> <!-- / .tab-content -->
					</div>
				</div>