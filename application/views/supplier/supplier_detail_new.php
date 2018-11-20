<?php  include  $GLOBALS['view_folder'].'supplier/__header.php'; ?>
<?php  include  $GLOBALS['view_folder'].'supplier/__lefter.php'; ?>

<style>        

    td.highlight {
        background-color: whitesmoke !important;
    }
		.market-title{
			background: #ffffff;
			padding:25px;
		}
		.market-title h2{
			margin: 0;
		}
		.market-title .title-filter{
			display: flex;
			align-items: center;
			justify-content: flex-start;
		}

		.market-title .title-filter input[type='date']{
			margin-right: 25px;
		}
		.market-title .title-filter h4,.market-title .title-filter select{
			margin-right: 15px;
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
		.modal-dialog{
			width: 60%;
		}
		.modal-dialog .modal-body{
			padding:20px;
		}
		button.close,button.close:hover{
			color:#000;
		}
		.data-box td{
			text-align: center;
		}
		.glyphicon-align-justify{
			cursor: pointer;
		}
		#vendorcode:disabled{
			background: #eeeeee;
		}
</style>


<!-- main content start -->
		<!--供应商信息-->
  <div class="">

    	<div class="clearing-status">

            <div class="table-detail">
                  <div class="table-body" style="padding:25px;">
                      <h4>联系信息</h4>
                      <table id="myTable" class="display data-box" cellspacing="0">
                      <thead>
                           <tr>
                             <th style="text-align: center;width:120px;"><span>用户</span></th>
                             <th style="text-align: center;width: 100px;"><span>状态</span></th>
                             <th style="text-align: center;width: 120px;"><span>联系人</span></th>
                             <th style="text-align: center;width: 80px;"><span>职位</span></th>
                             <th style="text-align: center;width: 80px;"><span>联系电话</span></th>
                          </tr>
                      </thead>
                      <tbody>
                            <?php
                                if( isset($vendor) && is_array($vendor) ) {
                            ?>
                                <tr>
                                    <td style="text-align: center;width: 120px;"><?php echo $vendor['UserEmail']; ?>  </td>
                                    <td style="text-align: center;width: 80px;"><?php echo ($vendor['UserStatus']==1)?'正常':'注销'; ?></td>
                                    <td style="text-align: center;width: 80px;"><?php echo $vendor['UserContact']; ?></td>
                                    <td style="text-align: center;width: 80px;"><?php echo $vendor['UserPosition'] ?></td>
                                    <td style="text-align: center;width: 80px;"><?php echo $vendor['UserPhone']; ?></td>
                                </tr>
                            <?php

                            }
                            ?>

                      </tbody>

                    </table>
                </div>
            </div>
        </div>

        <!-- clearing end -->


    </div>
		<!--供应商成交统计-->
	<div class="">

		<div class="clearing-status">

			<div class="table-detail">
				<div class="table-body" style="padding:25px;">
					<h4>成交统计</h4>
					<table id="myTable" class="display data-box" cellspacing="0">
						<thead>
						<tr>
							<th style="text-align: center;width:120px;"><span>成交金额</span></th>
							<th style="text-align: center;width: 100px;"><span>折扣金额</span></th>
							<th style="text-align: center;width: 120px;"><span>平均DPE</span></th>
							<th style="text-align: center;width: 80px;"><span>平均折扣率</span></th>
						</tr>
						</thead>
						<tbody>
            <?php
            if( isset($deal) && is_array($deal) ) {
                foreach($deal as $v){
                    ?>
									<tr>
										<td style="text-align: center;width: 120px;"><?php echo $v['TotalAmount']; ?>  </td>
										<td style="text-align: center;width: 80px;"><?php echo $v['TotalDiscount']; ?></td>
										<td style="text-align: center;width: 80px;"><?php echo $v['AvgDpe']; ?></td>
										<td style="text-align: center;width: 80px;"><?php echo $v['AvgDiscount']?>%</td>
									</tr>
                    <?php
                }
            }
            ?>

						</tbody>

					</table>
				</div>
			</div>
		</div>

		<!-- clearing end -->


	</div>
		<!--其它市场列表-->
	<div class="">

		<div class="clearing-status">

			<div class="table-detail">
				<div class="table-body" style="padding:25px;">
					<h4>其它市场信息</h4>
					<table id="myTable" class="display data-box" cellspacing="0">
						<thead>
						<tr>
							<th style="text-align: center;width: 80px;"><span>序号</span></th>
							<th style="text-align: center;width:120px;"><span>市场编号</span></th>
							<th style="text-align: center;width: 100px;"><span>DIVISION</span></th>
							<th style="text-align: center;width: 120px;"><span>货币名称</span></th>
							<th style="text-align: center;width: 80px;"><span>货币标识</span></th>
						</tr>
						</thead>
						<tbody>
            <?php
            if( isset($market) && is_array($market) ) {
                foreach($market as $index=>$v){
                    ?>
									<tr>
										<td style="text-align: center;width: 80px;"><?php echo $index+1 ?></td>
										<td style="text-align: center;width: 120px;"><?php echo $v['CashpoolCode']; ?>  </td>
										<td style="text-align: center;width: 80px;"><?php echo $v['CompanyDivision'];?></td>
										<td style="text-align: center;width: 80px;"><?php echo $v['CurrencyName'];?></td>
										<td style="text-align: center;width: 80px;"><?php echo $v['CurrencySign']?></td>
									</tr>
                    <?php
                }
            }
            ?>

						</tbody>

					</table>
				</div>
			</div>
		</div>

		<!-- clearing end -->


	</div>
    <!--  main content end -->



<script type="text/javascript">

    var selectedCount = 0;
    var table ;

    $(document).ready(function(){
    	var colIdx;
        var lastIdx = null;	  

    	  //<!-- 配置 datatable 开始-->
	     table = $('.data-box').DataTable({
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


    $('#filter-date').change(function(){
        $.select_vendor();
		});

    $('#select-all').click(function(){
        $.select_vendor();
		});

		$('.cleartype').change(function(){
				$.select_vendor();
		});

    $('#vendorcode').change(function(){
        $.select_vendor();
		});

    $.select_vendor = function(){
        var code = $('input[name="cashpoolcode"]').val();
        var id = $('input[name="cashpoolid"]').val();
        var filter_date = $('#filter-date').val();
        var select_vendor = $('#vendorcode').find("option:selected").val();
        var cleartype = $('.cleartype').find("option:selected").val();
        var url = '/supplier/market/detail/'+code+'/'+id+'/'+filter_date;
        if($('#select-all').is(':checked')){
            url+='/all';
        }else{
            url+='/'+select_vendor;
				}
				url+='/'+cleartype;

        window.location.href = url;
		}

		$('.invoice1').click(function(){

        $('#myModal').modal({
            keyboard:false
        });
				$('#myTable3').css('display','none');
				$('.loading').css('display','block');
        var code = $('input[name="cashpoolcode"]').val();
		    $.get('/supplier/market/invoice/'+code+'/'+$(this).data('qid'),function(data){
		        var tbody = "";
		        for(var i=0;i<data.length;i++){
		            var IsAwarded = "清算";
		            if(data[i].IsAwarded=="0") IsAwarded="未清算"
		            tbody +='<tr>';
                tbody+='<td>'+data[i].supplier+'</td>'+
                    '<td>'+data[i].vendorcode+'</td>'+
                    '<td>'+data[i].InvoiceNo+'</td>'+
                    '<td>'+data[i].InvoiceDate+'</td>'+
                    '<td>'+data[i].EstPaydate+'</td>'+
                    '<td>'+data[i].InvoiceAmount*1+'</td>'+
                    '<td>'+IsAwarded+'</td>';
                tbody +='</tr>';
						}
						$('#myTable3 tbody').html(tbody);
						$('#myTable3').css('display','table');
            $('.loading').css('display','none');
				});
		});

    $('.invoice2').click(function(){
        $('#invoiceModal').modal({
            keyboard:false
        });
        $('#myTable4').css('display','none');
        $('.loading').css('display','block');
        var codeid = $('input[name="cashpoolid"]').val();
        var select_vendor = $('#vendorcode').find("option:selected").val();
        if($('#select-all').is(':checked')){
            select_vendor = "null";
        }
        var filter_date = $('#filter-date').val();
        $.get('/supplier/market/invoice_rs/'+codeid+'/'+select_vendor+'/'+filter_date,function(data){
            var tbody = "";
            for(var i=0;i<data.length;i++){
                var IsAwarded = "清算";
                if(data[i].IsAwarded=="0") IsAwarded="未清算"
                tbody +='<tr>';
                tbody+='<td>'+data[i].supplier+'</td>'+
                    '<td>'+data[i].vendorcode+'</td>'+
                    '<td>'+data[i].InvoiceNo+'</td>'+
                    '<td>'+data[i].InvoiceDate+'</td>'+
                    '<td>'+data[i].EstPaydate+'</td>'+
                    '<td>'+data[i].InvoiceAmount*1+'</td>'
                tbody +='</tr>';
            }
            $('#myTable4 tbody').html(tbody);
            $('#myTable4').css('display','table');
            $('.loading').css('display','none');
        });
		});

   });

</script>




<?php  include  $GLOBALS['view_folder'].'supplier/__footer.php'; ?>