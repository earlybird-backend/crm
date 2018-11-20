<div class="half-strip">

<?php         
foreach($CurrencyData as $key=>$C ) {               
?>
	<div class="usd-main">
		<div class="market-strip">				
	        <a href="<?php echo base_url($uri.'/'.$this->base64url_encode($CompanyId).'/'.$this->base64url_encode($C['CurrencyId']) ); ?>">
    			<?php
	               if( (!isset($CurrencyId) && $key === 0) || $C['CurrencyId'] === $CurrencyId){
	                   $Cur = $C;
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
