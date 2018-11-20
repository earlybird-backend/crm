<?php  include  $GLOBALS['view_folder'].'customer/__header.php'; ?>

<?php

$index = 0;

foreach($marks as $key=>$mark)
{
	$index += 1;
?>

<?php if ( $key%3 == 0 ) { ?>
	<div class="row">
<?php } ?>

    <div class="col-md-4">
    
       <div class="stat-main">
			
			<div class="stat-header">
				<i class="fa fa-bar-chart" aria-hidden="true"></i> <span><?php echo $mark['buyer'] ?></span>
					<?php if($mark['division']!=null && $mark['division']!=''): ?>|<span><?php echo $mark['division'] ?></span><?php endif ?>
				|<span><?php echo $mark['currency'] ?></span>
			</div>

			<div class="stat-content" id="<?php echo $mark['cashpoolcode'] ?>">
				<div class="grapha-black">
					<p><label>Next Paydate</label><span><?php echo $mark['Paydate'] ?></span></p>
					<p><label>Availabel Cash</label><span><?php echo $mark['currencysign'].$mark['avaamount'] ?></span>|<label>Total Cash</label><span><?php echo $mark['currencysign'].$mark['totalamount'] ?></span> </p>
					<p><label>Awarded AP</label><span><?php echo $mark['currencysign'].$mark['awardamount'] ?></span>|<label>Total AP</label><span><?php echo $mark['currencysign'].$mark['avapayment'] ?></span></p>
					<p><label>Offer Suppliers</label><span> <?php echo $mark['bidcount'] ?> </span>|<label>Total Suppliers</label><span> <?php echo $mark['suppliercount'] ?> </span></p>
				</div>
			</div>
		</div>
	</div>


<?php if ( $index%3 == 0 ) { ?>
	</div>
<?php } ?>

<?php } // end foreach ?>
<style>
	.row .col-md-4{
		margin-bottom: 15px;
		cursor: pointer;
	}
	.stat-main{
		min-height: 220px;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		$('div.stat-content').click(function(){

				location.href = "<?php echo base_url($link."/market_current?id="); ?>" + $(this).attr('id');

		})
	})
</script>




<?php  include  $GLOBALS['view_folder'].'customer/__footer.php'; ?>