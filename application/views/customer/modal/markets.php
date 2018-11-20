<?php  include  $GLOBALS['view_folder'].'customer/__header.php'; ?>


<style>
div .col-md-4.center{
	text-align:center;
}


</style>


<div class="half-strip">

<?php         
foreach($CurrencyData as $key=>$C ) {               
?>
	<div class="usd-main">
		<div class="market-strip">				
	        <a href="<?php echo base_url($uri.'/'.$this->base64url_encode($CompanyId).'/'.$this->base64url_encode($C['CurrencyId']) ); ?>">
    			<?php
	               if( (!isset($CurrencyId) && $key === 0) || $C['CurrencyId'] === $CurrencyId){
                ?>
    			<span class="market-box active">
    				<p class="pull-left"><?php  echo $C['CurrencyName']; ?></p><i class="fa fa-arrow-right" aria-hidden="true"></i>
    			</span>
    			<?php
	               }else{ 
    			?>
    			<span class="market-box">
    				<p class="pull-left"><?php  echo $C['CurrencyName']; ?></p>
    			</span>
    			<?php
	               } 
    			?>
			</a>
			<span class="market-strip-sec">
				<p><?php echo $C['CurrencySign'].$C['CashAmount'];?></p>
				<p><?php echo $C['DesiredAPR'];?>%</p>
				<div class="market-img">

				</div>
				<div class="dot-con">
					<p><?php echo $C['CurrencySign'].$C['Payments'];?> AP</p>
				</div>
			</span>
		</div>		
	</div>	
	<div style="height:15px;"></div>
<?php 
    }
?>
</div>

<?php if (  count($CurrencyData) > 0 ){ ?>
<div class="full-strip">
    <!-- 统计数据 -->
	<div class="clearing-status">
    		<div class="today-main">
    			<div class="today-wrap">
    				<div class="today-heading">
    					<i class="fa fa-bar-chart" aria-hidden="true"></i> Clearing Stats
    				</div>
    			</div>
    
    			<div class="grapha-bar">
        			 <div class="row">
        			    <div class="col-md-4 center">
            				<div class="grapha-black">
            					<p>Market Total</p>
            					<h3 class="open-b"><?php echo $CashPool['CurrencySign'];?>57,873.69</h3>
            					<p><?php echo $CashPool['CurrencySign'];?>11111 AP</p>
            				</div>
        				</div>
        				<div class="col-md-4 center">
            				<div class="grapha-green">
            					<p>Current Clearing</p>
            					<h3 class="open-b"><?php echo $CashPool['CurrencySign'];?>44,215.94</h3>
            					<p><?php echo $CashPool['CurrencySign'];?>11111 AP</p>
            				</div>
        				</div>
        				<div class="col-md-4 center">
            				<div class="grapha-red">
            					<p>Non-Clearing Total</p>
            					<h3 class="open-b"><?php echo $CashPool['CurrencySign'];?>13,657.75</h3>
            					<p><?php echo $CashPool['CurrencySign'];?>11111 AP</p>
            				</div>
        				</div>
        			</div>
    		  </div>
    	</div>
	</div>
	
	<!-- 图表统计 -->
	<div class="clearing-status">	
		<div class="incoming-wrap" >
			    <div style="width: 100%">
                    <canvas id="canvas"></canvas>
                </div>
		</div>		
		<div class="graph-menu">
			<div class="graph-menu-strip">
				<ul class="wtd-50 custom-width">
					<li class="open-b ftw-b">
						<a href="#">Current Officer</a>
					</li>
					<li>
						<a href="#">Market Claring</a>
					</li>
					<li>
						<a href="#">Market Award</a>
					</li>
					<li>
						<a href="#">Current Clearing</a>
					</li>
				</ul>
				<ul class="wtd-50 text-right">
					<li class="open-b ftw-b">
						<a href="#">Amount</a>
					</li>
					<li>
						<a href="#">$44,215.94</a>
					</li>
					<li>
						<a href="#">$0.00</a>
					</li>
					<li>
						<a href="#">$44,215.94</a>
					</li>
				</ul>
			</div>
			<div class="graph-menu-strip">
				<ul class="wtd-33 text-right">
					<li class="open-b ftw-b">
						<a href="#">APR</a>
					</li>
					<li>
						<a href="#">4.5%</a>
					</li>
					<li>
						<a href="#">0.00%</a>
					</li>
					<li>
						<a href="#">4.5%</a>
					</li>
				</ul>
				<ul class="wtd-33 text-center">
					<li class="open-b ftw-b">
						<a href="#">DPE</a>
					</li>
					<li>
						<a href="#">25</a>
					</li>
					<li>
						<a href="#">0</a>
					</li>
					<li>
						<a href="#">25</a>
					</li>
				</ul>
				<ul class="wtd-33 text-left">
					<li class="open-b ftw-b">
						<a href="#">Cash Deployed</a>
					</li>
					<li>
						<a href="#">Current</a>
					</li>
					<li>
						<a href="#">Pending</a>
					</li>
					<li>
						<a href="#">Total</a>
					</li>
				</ul>
			</div>
			<div class="graph-menu-strip">
				<ul class="wtd-33 text-right">
					<li class="open-b ftw-b">
						<a href="#">Amount</a>
					</li>
					<li>
						<a href="#">$44,215.94</a>
					</li>
					<li>
						<a href="#">$0.00</a>
					</li>
					<li>
						<a href="#">$44,215.94</a>
					</li>
				</ul>
				<ul class="wtd-33 text-right">
					<li class="open-b ftw-b">
						<a href="#">Suppliers</a>
					</li>
					<li>
						<a href="#">5</a>
					</li>
					<li>
						<a href="#">125</a>
					</li>
					<li>
						<a href="#">130</a>
					</li>
				</ul>
				<ul class="wtd-33 text-right">
					<li class="open-b ftw-b">
						<a href="#">Invoice no.</a>
					</li>
					<li>
						<a href="#">5</a>
					</li>
					<li>
						<a href="#">125</a>
					</li>
					<li>
						<a href="#">130</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- 当前待处理 -->
	<div class="clearing-status">
		<div class="today-wrap">
			<div class="today-heading mr-custom">
				<i class="circle-icon" aria-hidden="true"></i>Non Clearing
			</div>
		</div>
		<div class="grapha-bar">
		      <div class="row">
			     <div class="col-md-6"><h4 class="pull-left">Additional <span><?php echo $CashPool['CurrencySign'].$stat['clearing']['discount']?></span> income opportunity</h4></div>
			     <div class="col-md-6"><h4 class="pull-right"><a href="<?php echo base_url('customer/market_detail/'.$this->base64url_encode($CompanyId).'/'.$this->base64url_encode($CurrencyId)); ?>">View Opportunity<i class="fa fa-arrow-right" aria-hidden="true" style="padding-left:5px;"></i></a></h4></div>
			 </div>
		</div>
		<div class="graph-body">
		      <div class="graph-table">
    		  <table class="discription-graph" cellspacing="0" cellpadding="0">
        			<thead>
        				<tr>
        					<th class="text-left pdt-left">Vendor Name</th>
        					<th class="text-left">Vendor Code</th>
        					<th class="text-left">Discount</th>
        					<th class="text-left" class="text-left">AP Eligible</th>
        					<th class="text-left">APR</th>
        					<th></th>
        				</tr>
    				</thead>
    				<tbody>
        				<?php 
        				    foreach($award_data as $v){        				        
        				?>
        				<tr>
        					<td>
        						<a href="javascript:;"><?php echo $v['supplier'];?></a>
        					</td>
        					<td>
        						<p><?php echo $v['Vendorcode'];?></p>
        					</td>
        					<td>
        						<p><?php echo $v['Discount'];?></p>
        					</td>
        					<td>
        						<p><?php echo $v['Eligible'];?></p>
        					</td>
        					<td>
        						<p><?php echo $v['APR'];?></p>
        					</td>
        					<td class="text-center">
        						<a href="<?php 
            						echo base_url("customer/market_award/".$this->base64url_encode($CompanyId)."/".$this->base64url_encode($CurrencyId)."/".
                						$this->base64url_encode($this->session->userdata('UserId')).'-'.$this->base64url_encode($v['Vendorcode'])
            						);        						    
    						     ?>" class="btn-award"><i class="fa fa-arrow-up" aria-hidden="true"></i>Award</a>
        					</td>
        				</tr>
        				<?php
        				    } 
        				?>				
        			</tbody>        			
        		</table>
        		
	      </div>
	      
		</div>
	    <div class="graph-footer">
    		<div class="row">
    		  <div class="col-md-3">
    		      Eligible AP <?php echo $CashPool['CurrencySign'].$stat['clearing']['payable']?>
    		  </div>
    		  <div class="col-md-3">
    		      APR range from <?php echo $stat['clearing']['min_bid'];?>%-<?php echo $stat['clearing']['max_bid'];?>%
    		  </div>
    		  <div class="col-md-6">
    		      Offer from  <?php echo $stat['clearing']['supplier_cnt']?> suppliers with <?php echo $stat['clearing']['invoice_cnt']?> invoices
    		  </div>
    		</div>
	     </div>
	</div>
	
	<!-- 供应商统计 -->
	<div class="clearing-status">
		<div class="supplier-main">
			<div class="today-wrap">
				<div class="today-heading mr-custom">
					<i class="circle-icon" aria-hidden="true"></i>Supplier Network
				</div>
			</div>
		</div>
		<div class="grapha-bar">
		      <div class=" row"> 
		          <div class="col-md-4">
        			<div class="grapha-black" style="margin-left:55px;">
        				<p>Total Suppliers</p>
        				<h3 class="open-b mr-bt"><?php echo $supplier['total']['count'] ; ?></h3>
        				<progress max="100" value="<?php echo $supplier['total']['ap']*100/$supplier['total']['count']; ?>" class="node-js">
        					<!-- Browsers that support HTML5 progress element will ignore the html inside `progress` element. Whereas older browsers will ignore the `progress` element and instead render the html inside it. -->
        					<div class="progress-bar">
        						<span style="width: <?php echo $supplier['total']['ap']*100/$supplier['total']['count']; ?>%"><?php echo $supplier['total']['ap']*100/$supplier['total']['count']; ?>%</span>
        					</div>
        				</progress> 
        				<p><?php echo $supplier['total']['ap'] ; ?> with AP</p>
        
        			</div>
        		  </div>
        		  <div class="col-md-4">
        			<div class="grapha-green" style="margin-left:55px;">
        				<p>Registered Suppliers</p>
        				<h3 class="open-b clr-sky mr-bt"><?php echo $supplier['registered']['count'] ; ?></h3>
        				<progress max="100" value="<?php echo $supplier['registered']['ap']*100/$supplier['registered']['count']; ?>" class="node-js">
        					<!-- Browsers that support HTML5 progress element will ignore the html inside `progress` element. Whereas older browsers will ignore the `progress` element and instead render the html inside it. -->
        					<div class="progress-bar">
        						<span style="width: <?php echo $supplier['registered']['ap']*100/$supplier['registered']['count']; ?>%"><?php echo $supplier['registered']['ap']*100/$supplier['registered']['count']; ?>%</span>
        					</div>
        				</progress> 
        				<p><?php echo $supplier['registered']['ap'] ; ?> with AP</p>
        			</div>
        		  </div>
        		   
        		  <div class="col-md-4">
        			<div class="grapha-red" style="margin-left:55px;">
        				<p>Participated Suppliers</p>
        				<h3 class="open-b clr-grn mr-bt"><?php echo $supplier['bided']['count'] ; ?></h3>
        				<progress max="100" value="<?php echo $supplier['bided']['ap']*100/$supplier['bided']['count']; ?>" class="node-js">
        					<!-- Browsers that support HTML5 progress element will ignore the html inside `progress` element. Whereas older browsers will ignore the `progress` element and instead render the html inside it. -->
        					<div class="progress-bar">
        						<span style="width: <?php echo $supplier['bided']['ap']*100/$supplier['bided']['count']; ?>%"><?php echo $supplier['registered']['ap']*100/$supplier['registered']['count']; ?>%</span>
        					</div>
        				</progress> 
        				<p><?php echo $supplier['bided']['ap'] ; ?> with AP</p>
        			</div>
    			</div>
			</div>
		</div>
		<div class="graph-body">
    		<div class ="row" style="padding:20px;">		
    		  <div class="col-md-6">
    		      <table class="discription-graph" cellspacing="0" cellpadding="0">
        		      <thead>
        		          <tr>
        		              <th col-span="3" style="height:32px;">Top 5 registered with AP</th>
        	              </tr>
        		      </thead>
        			  <tbody>
        			   <?php if(count($reg_supplier_rank) <= 0){?>    			   
        			     <tr>
        			         <th col-span="3" style="padding-left:25px;"><h3>No Data</h3></th>
        	              </tr>
        			   <?php }?>
        			   
        			    <?php foreach($reg_supplier_rank as $rs){?>
        				<tr>
        				    <td>
        				        <?php echo $rs['supplier'];?>
        				    </td>
        				    <td>
        				        <?php echo $CashPool['CurrencySign'].$rs['TotalAmount'];?>
        				    </td>
        				    <td>
        				        <a href="#" class="btn-arrow"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
        				    </td>	
        				</tr>
        				
        				<?php }?>
        				</tbody>
    				</table>		  
    		  </div>
    		  <div class="col-md-6">
    		      <table class="discription-graph" cellspacing="0" cellpadding="0">
        		      <thead>
        		          <tr>
        		              <th col-span="3" style="height:32px;">Top 5 by invoice</th>
        	              </tr>
        		      </thead>
        			  <tbody>
            			  <?php if(count($all_supplier_rank) <= 0){?>    			   
            			     <tr>
            			         <th col-span="3" style="padding-left:25px;"><h3>No Data</h3></th>
            	              </tr>
            			   <?php }?>
            			   
        			     <?php foreach($all_supplier_rank as $as){?>
        				<tr>
        				    <td>
        				        <?php echo $as['supplier'];?>
        				    </td>
        				    <td>
        				        <?php echo $CashPool['CurrencySign'].$as['TotalAmount'];?>
        				    </td>
        				    <td>
        				        <a href="#" class="btn-arrow"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
        				    </td>	
        				</tr>
        				
        				<?php }?>
        				</tbody>
    				</table>		  
    		  </div>
		</div>
		  
		</div>
	</div>
	
</div>
  
    <script>
        var chartData = {
            labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19"],
            datasets: [{
                type: 'line',
                label: 'Dataset 1',
                borderColor: window.chartColors.gray,
                borderWidth: 2,
                fill: false,
                data: [20 ,20, 10, 8, 8, 8, 10, 8, 8 , 8 , 2 , 5, 5, 20, 8, 5, 5, 70, 19]
            }, {
                type: 'bar',
                label: 'Dataset 2',
                backgroundColor: window.chartColors.blue,
                data: [10, 20, 10, 8, 0, 0, 10, 8 , 20 , 8 , 2, 0, 0, 5, 3, 10, 0, 0 , 0 , 75],
                borderColor: 'white',
                borderWidth: 2
            }, ]

        };
        window.onload = function() {            
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myMixedChart = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Chart.js Combo Bar Line Chart'
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: true
                    },
                    legend: {
                        onClick: (e) => e.stopPropagation(),
                         display: true,
		position: 'top',
		fullWidth: true, 
		reverse: false,
                    }
                }
            });
        };
        /*
        document.getElementById('randomizeData').addEventListener('click', function() {
            chartData.datasets.forEach(function(dataset) {
                dataset.data = dataset.data.map(function() {
                    return randomScalingFactor();
                });
            });
            window.myMixedChart.update();
        });
        */
        
    </script>
        
    <?php }else{ ?>
    	
    	   <div class="upper">
    	       <div class="upper-wrap">
    				<h1>Add New Currency</h1>				
    			</div> 
    			<a data-toggle="modal" data-target="#currency_add" href="javascript:void(0)"> 
    			<div class="upper-apr add-image-sec" style="height:180px">
    			
    			</div>
    			</a>
    	    </div>
    	    <div class="margin_custom_top"></div>
    	
    
    <?php } ?>
    


    
<?php  include  $GLOBALS['view_folder'].'customer/__footer.php'; ?>