<?php 
$section = $this->uri->segment(2);
$function = $this->uri->segment(3);

?>
  
<ul class="navigation">
				<li <?php if($section=='dashboard'){ echo 'class="active"';}else{echo 'class=""';}?> ><a class="dashboard-16" href="<?php echo site_url('admin_bak/dashboard')?>" title="DashBoard Home"><i class="menu-icon fa fa-dashboard"></i><span class="mm-text">DashBoard Home</span></a></li>
				
				<li class="">
					<a href="<?php echo site_url('admin_bak/customers')?>"><i class="menu-icon fa fa-th"></i><span class="mm-text">Manage Customers</span></a>
				</li>             
					
				<li  class="">
					<a href="<?php echo site_url('admin_bak/users')?>"><i class="menu-icon fa fa-th"></i><span class="mm-text">Inquiry Users</span></a>
				</li>	
					
				<li  class="">
					<a href="<?php echo site_url('admin_bak/suppliers')?>"><i class="menu-icon fa fa-th"></i><span class="mm-text">Manage Suppliers</span></a>
				</li>
				
				<li>
					<a href="<?php echo site_url('admin_bak/plans')?>"><i class="menu-icon fa fa-desktop"></i><span class="mm-text">Manage Plans</span></a>
				</li>
				
				<!--li>
					<a href="#"><i class="menu-icon fa fa-desktop"></i><span class="mm-text">Proposal Calculate</span></a>
				</li-->
				
				<li>
					<a href="<?php echo site_url('admin_bak/historyplan')?>"><i class="menu-icon fa fa-desktop"></i><span class="mm-text">History Plan</span></a>
				</li>
				
				<li class="">
					<a href="<?php echo site_url('admin_bak/configuration')?>"><i class="menu-icon fa fa-th"></i><span class="mm-text">Plan Time Settings</span></a>
				</li>
				
				<li  class="">
					<a href="<?php echo site_url('admin_bak/currency')?>"><i class="menu-icon fa fa-th"></i><span class="mm-text">Manage Currency</span></a>
				</li>
				<li  class="">
					<a href="<?php echo site_url('admin_bak/sendinvitation')?>"><i class="menu-icon fa fa-th"></i><span class="mm-text">Send Invitation</span></a>
				</li>
				
							
			
               		
			
			</ul> <!-- / .navigation -->