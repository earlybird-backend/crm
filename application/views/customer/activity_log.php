<?php  include  $GLOBALS['view_folder'].'customer/__header.php'; ?>


	<style>

		td.highlight {
			background-color: whitesmoke !important;
		}

		tr.invalid td{
			color:gray;
		}

		.table-body {
			padding:25px;
		}

		.grapha p{
			font-size:15px;
			font-weight:bold;
		}
		.grapha .input-group{
			margin-top:8px;
		}

	</style>

	<div class="half-strip">
		<div class="clearing-status">
			<div class="today-main">
				<div class="today-wrap">
					<div class="today-heading">
						<div class="pull-left"><i class="fa fa-filter"></i> Filters</div>
					</div>
				</div>
				<div class="grapha-bar">
					<div class="row" style="padding-left:10px;margin-top:10px;">
						<div class="grapha grapha-green">
							<p>Activity Date</p>

							<div class="input-group">
								<span class="input-group-addon" style="width:60px;">From</span>
								<input type="date" class="form-control" id="startdate" value="<?php echo $start ?>" />
								<input type="hidden" class="form-control" id="id" value="<?php echo $id ?>" />
							</div>
							<div class="input-group">
								<span class="input-group-addon" style="width:60px;">To</span>
								<input type="date"  class="form-control" id="enddate" value="<?php echo $end ?>" />
							</div>

						</div>
					</div>

					<div class="row" style="padding-left:10px;margin-top:10px;">
						<div class="grapha grapha-green">
							<p>Select User Role </p>
							<label class="input-group">
								<input type="checkbox" class="toggle" name="role" <?php echo (strpos($role,'customer')!==false)?'checked':'' ?> value="customer"> Customers
							</label>
							<label class="input-group">
								<input type="checkbox" class="toggle" name="role" <?php echo (strpos($role,'supplier')!==false)?'checked':'' ?> value="supplier"> Suppliers
							</label>
						</div>
					</div>

					<div class="row" style="padding-left:10px;margin-top:10px;">
						<div class="grapha grapha-green">
							<p>Activity Level</p>
							<label class="input-group">
								<input type="checkbox" class="toggle" name="level" <?php echo (strpos($level,'customer_open')!==false)?'checked':'' ?> value="customer_open"> Customer Open Market
							</label>
							<label class="input-group">
								<input type="checkbox" class="toggle" name="level" <?php echo (strpos($level,'customer_close')!==false)?'checked':'' ?> value="customer_close"> Customer Close Market
							</label>
							<label class="input-group">
								<input type="checkbox" class="toggle" name="level" <?php echo (strpos($level,'customer_cash')!==false)?'checked':'' ?> value="customer_cash"> Customer Modify Available Cash
							</label>
							<label class="input-group">
								<input type="checkbox" class="toggle" name="level" <?php echo (strpos($level,'supplier_join')!==false)?'checked':'' ?> value="supplier_join"> Supplier Join Market
							</label>
							<label class="input-group">
								<input type="checkbox" class="toggle" name="level" <?php echo (strpos($level,'supplier_offer')!==false)?'checked':'' ?> value="supplier_offer"> Supplier Offer Value
							</label>
							<label class="input-group">
								<input type="checkbox" class="toggle" name="level" <?php echo (strpos($level,'supplier_close')!==false)?'checked':'' ?> value="supplier_close"> Supplier Close Market
							</label>
						</div>
					</div>

					<div class="row">
						<input type="button" class="btn btn-success pull-right" value="Go" id="btn_filter" style="margin-right:40px;margin-bottom:10px;"/>
					</div>
				</div>
			</div>

		</div>
	</div>


	<!-- main content start -->
	<div class="full-strip" style="width:74%;">

		<!-- Activity List start  -->
		<div class="clearing-status">
			<div class="table-detail">
				<div class="table-body">
					<table id="myTable" class="display" cellspacing="0">
						<thead>
						<tr>
							<th style="text-align: center;width: 50px;"><span>#ID</span></th>
							<th style="text-align: center;width: 140px;"><span>日志时间</span></th>
							<th style="text-align: center;width: 100px;"><span>用户角色</span></th>
							<th style="text-align: center;width: 100px;"><span>用户名</span></th>
							<th style="text-align: center;width: 100px;"><span>操作类型</span></th>
							<th style="text-align: center;width: 260px;"><span>操作内容</span></th>
						</tr>
						</thead>

						<tbody>
            <?php if( isset($logs) && is_array($logs) ) {

                foreach($logs as $v){

                    ?>
									<tr id="<?php echo 'tr_'.$v['id']; ?>">
										<td style="text-align: center;width: 50px;"><?php echo $v['ID']; ?></td>
										<td style="text-align: center;width: 100px;"><?php echo $v['LogTime']; ?>  </td>
										<td style="text-align: center;width: 100px;"><?php echo $v['UserRole']; ?></td>
										<td style="text-align: center;width: 100px;"><?php echo $v['UserName']; ?></td>
										<td style="text-align: center;width: 100px;"><?php echo $v['MethodType']; ?></td>
										<td style="text-align: center;width: 60px;"><?php echo $v['LogContent']; ?></td>
									</tr>
                    <?php
                }
            }
            ?>

						</tbody>
						<tfoot>
						<tr>
							<th style="text-align: center;width: 50px;"><span>#ID</span></th>
							<th style="text-align: center;width: 140px;"><span>日志时间</span></th>
							<th style="text-align: center;width: 100px;"><span>用户角色</span></th>
							<th style="text-align: center;width: 100px;"><span>用户名</span></th>
							<th style="text-align: center;width: 100px;"><span>操作类型</span></th>
							<th style="text-align: center;width: 260px;"><span>操作内容</span></th>
						</tr>
						</tfoot>
					</table>
				</div>


				<!-- Activity List end -->

			</div>
		</div>
	</div>
	<!--  main content end -->



	<script type="text/javascript">
      var selectedCount = 0;

      $(document).ready(function(){
          var colIdx;
          var lastIdx = null;

          //<!-- 配置 datatable 开始-->
          var table = $('#myTable').DataTable({
              bStateSave:true,
              bFiltered:false,
              info:true,
              ordering:false,
              searching:false,
              bLengthChange: true,
              paging:true,
          });


          //<!-- 配置 datatable 结束-->

          $('#myTable tbody')
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

              } )
              .on( 'click', 'tr', function () {
                  if($(this).hasClass('eligible')){
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
              } );

          $('#btn_filter').click(function(){
              var url = '/customer/home/activity?';
              var id = $('#id').val();
              var start = $('#startdate').val();
              var end = $('#enddate').val();
              var role = "";
              var level = "";
              $.each($('input[name=role]:checked'),function(){
                  role += $(this).val() +',';
              });
              role = role.substring(0,role.length-1);

              $.each($('input[name=level]:checked'),function(){
                  level += $(this).val() +',';
              });
              level = level.substring(0,level.length-1);
              url += "id="+id+"&start="+start+"&end="+end+"&role="+role+"&level="+level;
              window.location.href = url;
          });

      })


	</script>



<?php  include  $GLOBALS['view_folder'].'customer/__footer.php'; ?>