<?php  include  $GLOBALS['view_folder'].'supplier/__header.php'; ?>


<style>        

    td.highlight {
        background-color: whitesmoke !important;
    }
		.supplier-title{
			background: #ffffff;
			padding:25px;
		}
		.supplier-title h3{
			margin: 0 20px 0 0;
		}
		.supplier-title{
			display: flex;
			align-items: center;
			justify-content: flex-start;
		}

		.supplier-title .title-filter input[type='date']{
			margin-right: 25px;
		}
		.supplier-title .title-filter h4,.market-title .title-filter select{
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

		.data-box td{
			text-align: center;
		}

		.useremail.clicked{
			background-color: #1484da !important;
			color:#fff;
		}

</style>

<div class="clearing-status supplier-title">
	<h3>类别</h3>
	<div class="title-filter">
			<select id="vendor" name="vendor">
				<option>供应商</option>
				<option>DIVISION</option>
				<option>USERID</option>
			</select>
	</div>
</div>
<!-- main content start -->
	<div class="" style="display: none;">
		<div class="clearing-status">
			<div class="table-detail">
				<div class="table-body" style="padding:25px;">
					<h1>数据版</h1>
				</div>
			</div>
		</div>

		<!-- clearing end -->


	</div>
	<div class="">
    	<div class="clearing-status">
            <div class="table-detail">
								<div class="table-body" style="padding:25px;">
										<h4>联系人信息</h4>
									<table id="myTable" class="display data-box" cellspacing="0">
										<thead>
										<tr>
											<th style="text-align: center;width: 80px;"><span>用户</span></th>
											<th style="text-align: center;width:120px;"><span>状态</span></th>
											<th style="text-align: center;width: 100px;"><span>联系人</span></th>
											<th style="text-align: center;width: 120px;"><span>职位</span></th>
											<th style="text-align: center;width: 120px;"><span>联系电话</span></th>
										</tr>
										</thead>
										<tbody>
										<?php if(isset($info) && is_array($info)){
											    foreach($info as $item){?>
										<tr class="<?php echo ($item['UserStatus']==-1)?'':'useremail' ?>" data-vid="<?php echo $vendorid; ?>" data-email='<?php echo $item['UserEmail'] ?>'>
											<td style="text-align: center;cursor: pointer;"><?php echo $item['UserEmail'] ?></td>
											<td style="text-align: center;cursor: pointer;"><?php echo ($item['UserStatus']==-1)?'注销':'正常' ?></td>
											<td style="text-align: center;cursor: pointer;"><?php echo $item['UserContact'] ?></td>
											<td style="text-align: center;cursor: pointer;"><?php echo $item['UserPosition'] ?></td>
											<td style="text-align: center;cursor: pointer;"><?php echo $item['UserPhone'] ?></td>
										</tr>
										<?php } } ?>
										</tbody>
									</table>
								</div>
            </div>
        </div>

        <!-- clearing end -->


    </div>
<!--市场-->
	<div class="market-list" style="display: none;">

		<div class="clearing-status">

			<div class="table-detail">
				<div class="table-body" style="padding:25px;">
					<div class="sub-title">
						<h4>市场</h4>
					</div>
					<div class="loading" style="display: none;text-align: center;"><img style="width:200px;" src="/assets/images/logo_pre.gif" /></div>
					<table id="myTable2" class="display data-box" cellspacing="0">
						<thead>
						<tr>
							<th style="text-align: center;width: 80px;"><span>买家市场编号</span></th>
							<th style="text-align: center;width:120px;"><span>买家名称</span></th>
							<th style="text-align: center;width: 100px;"><span>供应商名称</span></th>
							<th style="text-align: center;width: 120px;"><span>供应商编号</span></th>
						</tr>
						</thead>
						<tbody>
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

			var table2 = $('#myTable2').DataTable({
          bStateSave:true,
          bFiltered:false,
          info:true,
          ordering:false,
          searching:false,
          bLengthChange: true,
          paging:true,
      });
    	$('.useremail').click(function(){
          $('.useremail').removeClass('clicked');
          $(this).addClass('clicked');
    	    $('.market-list').show();
    	    $('#myTable2').hide();
    	    $('.loading').show();
    	    var email = encodeURI($(this).data('email')) ;
    	    var vid = encodeURI($(this).data('vid')) ;
    	    $.post('/supplier/supplier/markets',
							{
							    email:email,
									vid:vid
							}
							,function(data){
    	          var tbody = "";
    	          for(var i=0;i<data.length;i++){
    	          	tbody = "<tr>";
    	          	tbody +="<td>"+data[i].CashpoolCode+"</td>"
													+"<td>"+data[i].CompanyDivision+"</td>"
													+"<td>"+data[i].Supplier+"</td>"
													+"<td>"+data[i].Vendorcode+"</td>";
    	          	tbody += "</tr>";
								}
								$('#myTable2 tbody').html(tbody);
								table2.destroy();
								table2 = $('#myTable2').DataTable({
										stateSave:true,
										bFiltered:false,
										info:false,
										ordering:false,
										searching:false,
										bLengthChange: false,
										paging:false,
								});
								$('#myTable2').show();
								$('.loading').hide();
					});
			});

   });

</script>




<?php  include  $GLOBALS['view_folder'].'supplier/__footer.php'; ?>