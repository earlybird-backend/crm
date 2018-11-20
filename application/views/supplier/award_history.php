<?php  include  $GLOBALS['view_folder'].'supplier/__header.php'; ?>


<style>        

    td.highlight {
        background-color: whitesmoke !important;
    }
		.history-title{
			background: #ffffff;
			padding:25px;
		}
		.history-title h3{
			margin: 0 20px 0 0;
		}
		.history-title{
			display: flex;
			align-items: center;
			justify-content: flex-start;
		}


		.sub-title{
			display: flex;
			align-items: center;
			justify-content: flex-start;
		}
		.sub-title h4{
			margin-right:15px ;
		}

		.table-body h4{
			font-weight: bold;
		}

		.data-box td{
			text-align: center;
		}

		.useremail.clicked{
			background-color: #1484da !important;
			color:#fff;
		}

</style>

<div class="clearing-status history-title">
	<h3>选择市场</h3>
	<div class="title-filter">
			<input type="hidden" class="vid" value="<?php echo $vid ?>" />
			<select id="vendor" name="vendor">
				<option value="all" <?php echo (!isset($cashpoolcode)||$cashpoolcode=='all')?'selected':'' ?>>全部市场</option>
				<?php if(isset($supplier) && is_array($supplier)): ?>
						<?php foreach($supplier as $item): ?>
							<option value="<?php echo $item['CashpoolCode'] ?>" <?php echo (isset($cashpoolcode)&&$cashpoolcode==$item['CashpoolCode'])?'selected':''?>><?php echo $item['CompanyDivision'] ?></option>
						<?php endforeach ?>
				<?php endif ?>
			</select>
	</div>
</div>
<!-- main content start -->

	<div class="">
    	<div class="clearing-status">
            <div class="table-detail">
								<div class="table-body" style="padding:25px;">
										<h4>市场明细</h4>
									<table id="myTable" class="display data-box" cellspacing="0">
										<thead>
										<tr>
											<th style="text-align: center;width: 80px;"><span>供应商名称</span></th>
											<th style="text-align: center;width: 80px;"><span>买家中的名称</span></th>
											<th style="text-align: center;width: 80px;"><span>编号</span></th>
											<th style="text-align: center;width:120px;"><span>支付日期</span></th>
											<th style="text-align: center;width: 100px;"><span>发票总金额</span></th>
											<th style="text-align: center;width: 120px;"><span>折扣金额</span></th>
											<th style="text-align: center;width: 120px;"><span>平均到期天数</span></th>
										</tr>
										</thead>
										<tbody>
										<?php if(isset($histories) && is_array($histories)){
											    foreach($histories as $item){?>
										<tr>
											<td style="text-align: center;cursor: pointer;"><?php echo $item['VendorDivision'] ?></td>
											<td style="text-align: center;cursor: pointer;"><?php echo $item['Supplier'] ?></td>
											<td style="text-align: center;cursor: pointer;"><?php echo $item['Vendorcode'] ?></td>
											<td style="text-align: center;cursor: pointer;"><?php echo $item['PayDate'] ?></td>
											<td style="text-align: center;cursor: pointer;"><?php echo $item['TotalAmount'] ?></td>
											<td style="text-align: center;cursor: pointer;"><?php echo $item['TotalDiscount'] ?></td>
											<td style="text-align: center;cursor: pointer;"><?php echo $item['AvgDpe'] ?></td>
										</tr>
										<?php } } ?>
										</tbody>
									</table>
								</div>
            </div>
        </div>

        <!-- clearing end -->


    </div>
    <!--  main content end -->





<script type="text/javascript">
    var table ;

    $(document).ready(function(){
    	var colIdx;
        var lastIdx = null;	  

    	  //<!-- 配置 datatable 开始-->
	     table = $('#myTable').DataTable({
           bStateSave:true,
           bFiltered:false,
           info:true,
           ordering:false,
           searching:false,
           bLengthChange: true,
           paging:true,
  	     });

  	   	//<!-- 配置 datatable 结束-->
    	 $('.data-box tbody')
          .on( 'mouseover', 'td', function () {

              if(table.cell(this).index() != undefined ){
                  colIdx = table.cell(this).index().column;
                  if ( colIdx !== lastIdx && colIdx > 0 ) {
                      $(table.cells().nodes() ).removeClass( 'highlight' );
                      $(table.column( colIdx ).nodes() ).addClass( 'highlight' );
                  }
              }
          } )
          .on( 'mouseleave', function () {

      	    $(table.cells().nodes() ).removeClass( 'highlight' );

          })
          .on( 'click', 'tr', function () {
           	if($(this).hasClass('normal')){
                   $(this).toggleClass('selected');
                   $('#'+$(this).attr('id').replace('tr_','chk_')).prop('checked',$(this).hasClass('selected'));

                   if($(this).hasClass('selected') ){
                       selectedCount ++ ;
                   }else{
               	       selectedCount -- ;

                   	$('#myTable .check-all').each(function(){
           	  	    	 if($(this).is(':checked'))
           	  	  	    	 $(this).prop('checked',false);
           	    	 })
                   }
           	}
          });
			 $('#vendor').change(function(){
			     var code = $(this).find("option:selected").val();
			     var id = $('.vid').val();
			     window.location.href = '/supplier/supplier/history?vid='+id+'&code='+code;
			 });


   });

</script>




<?php  include  $GLOBALS['view_folder'].'supplier/__footer.php'; ?>