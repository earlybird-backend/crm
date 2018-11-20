<?php  include  $GLOBALS['view_folder'].'supplier/__header.php'; ?>

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

		.market_name,.plan{
			color:#1484da;
			cursor: pointer;
			text-decoration: underline;
		}

	</style>


	<!-- main content start -->
	<div class="center-strip">

		<ul class="nav nav-tabs nav-justified" role="tablist">

			<li class="active" role="presentation" >
				<a href="#active" class="nav-link" aria-controls="active" role="tab"  data-toggle="tab">活跃市场</a>
			</li>
			<li role="presentation">
				<a href="#inactive" class="nav-link" aria-controls="inactive" role="tab"  data-toggle="tab">非活跃市场</a>
			</li>
		</ul>


		<!-- Market List start  -->
		<div class="clearing-status">
			<div class="table-detail">
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade in active" id="active">
						<div class="table-body">

							<table id="activeTable" class="display" cellspacing="0">
								<thead>
								<tr>
									<th style="text-align: center;width: 140px;"><span>市场</span></th>
									<th style="text-align: center;width: 100px;"><span>市场状态</span></th>
									<th style="text-align: center;width: 100px;"><span>资金计划数</span></th>
									<th style="text-align: center;width: 140px;"><span>资金计算创建时间</span></th>
									<th style="text-align: center;width: 120px;"><span>下一付款日</span></th>
									<th style="text-align: center;width: 120px;"><span>可用资金</span></th>
									<th style="text-align: center;width: 120px;"><span>发票明细</span></th>
								</tr>
								</thead>

								<tbody>
                <?php
                if( isset($active) && is_array($active) ) {

                    foreach($active as $v){
                        ?>
											<tr id="<?php echo $v['market_id']; ?>"
													data-nextpay="<?php echo $v['next_paydate']; ?>" data-code="<?php echo $v['cashpoolcode'] ?>">
												<td class="market_name" style="text-align: center;width: 140px;"><?php echo $v['market_name']; ?> <br> (<?php echo $v['currency_name']; ?>)</td>
												<td style="text-align: center;width: 100px;"><?php echo $v['market_status']; ?></td>
												<td class="plan" style="text-align: center;width: 100px;"><?php echo $v['cash_schedule']; ?></td>
												<td style="text-align: center;width: 140px;"><?php echo $v['create_time']; ?></td>
												<td style="text-align: center;width: 100px;"><?php echo $v['next_paydate']; ?></td>
												<td style="text-align: center;"> <span title="<?php echo $v["cash_plandate"] ; ?> 初始资金 <?php echo $v['currency_sign'].round($v['cash_amount'],2); ?>">
													<?php echo $v['currency_sign'].round($v['cash_available'],2); ?>
													</span>
												</td>
												<td class="detail-btn" style="text-align: center;"><button>查看</button></td>
											</tr>
                        <?php
                    }
                }
                ?>

								</tbody>
								<tfoot>
								<tr>
									<th style="text-align: center;width: 100px;"><span>市场</span></th>
									<th style="text-align: center;width: 100px;"><span>市场状态</span></th>
									<th style="text-align: center;width: 100px;"><span>资金计划数</span></th>
									<th style="text-align: center;width: 140px;"><span>资金计算创建时间</span></th>
									<th style="text-align: center;width: 120px;"><span>下一付款日</span></th>
									<th style="text-align: center;width: 120px;"><span>现金池</span></th>
									<th style="text-align: center;width: 120px;"><span>发票明细</span></th>
								</tr>
								</tfoot>
							</table>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="inactive">
						<div class="table-body" >
							<table id="inactiveTable" class="display" cellspacing="0">
								<thead>
								<tr>
									<th style="text-align: center;width: 140px;"><span>市场</span></th>
									<th style="text-align: center;width: 100px;"><span>市场状态</span></th>
									<th style="text-align: center;width: 100px;"><span>资金计划数</span></th>
									<th style="text-align: center;width: 140px;"><span>资金计算创建时间</span></th>
									<th style="text-align: center;width: 120px;"><span>下一付款日</span></th>
									<th style="text-align: center;width: 120px;"><span>现金池</span></th>
									<th style="text-align: center;width: 120px;"><span>发票明细</span></th>
								</tr>
								</thead>

								<tbody>
                <?php if( isset($inactive) && is_array($inactive) ) {

                    foreach($inactive as $v){
                        ?>
											<tr id="<?php echo $v['market_id']; ?>"
													data-nextpay="<?php echo $v['next_paydate']; ?>" data-code="<?php echo $v['cashpoolcode'] ?>">
												<td class="market_name" style="text-align: center;width: 140px;"><?php echo $v['market_name']; ?> <br> (<?php echo $v['currency_name']; ?>)</td>
												<td style="text-align: center;width: 100px;"><?php echo $v['market_status']; ?></td>
												<td class="plan" style="text-align: center;width: 100px;"><?php echo $v['cash_schedule']; ?></td>
												<td style="text-align: center;width: 140px;"><?php echo $v['create_time']; ?></td>
												<td style="text-align: center;width: 100px;"><?php echo $v['next_paydate']; ?></td>
												<td style="text-align: center;"> <span title="<?php echo $v["cash_plandate"] ; ?> 初始资金 <?php echo $v['currency_sign'].round($v['cash_amount'],2); ?>">
													<?php echo $v['currency_sign'].round($v['cash_available'],2); ?>
													</span>
												</td>
												<td class="detail-btn" style="text-align: center;"><button>查看</button></td>
											</tr>
                        <?php
                    }
                }
                ?>

								</tbody>
								<tfoot>
								<tr>
									<th style="text-align: center;width: 140px;"><span>市场</span></th>
									<th style="text-align: center;width: 100px;"><span>市场状态</span></th>
									<th style="text-align: center;width: 100px;"><span>资金计划数</span></th>
									<th style="text-align: center;width: 140px;"><span>资金计算创建时间</span></th>
									<th style="text-align: center;width: 120px;"><span>下一付款日</span></th>
									<th style="text-align: center;width: 120px;"><span>现金池</span></th>
									<th style="text-align: center;width: 120px;"><span>发票明细</span></th>
								</tr>
								</tfoot>
							</table>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!-- Market List end -->


	</div>
	<!--  main content end -->




	<script type="text/javascript">
      var selectedCount = 0;

      $(document).ready(function(){
          var colIdx;
          var lastIdx = null;

          //<!-- 配置 datatable 开始-->
          var table = $('#activeTable').DataTable({
              bStateSave:true,
              bFiltered:false,
              info:true,
              ordering:false,
              searching:false,
              bLengthChange: true,
              paging:true,
          });

          var un_table = $('#inactiveTable').DataTable({
              bStateSave:true,
              bFiltered:false,
              info:true,
              ordering:false,
              searching:false,
              bLengthChange: true,
              paging:true,
          });



          //<!-- 配置 datatable 结束-->

          $('#activeTable tbody')
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

              } );

          $('.market_name').click(function(){
              var id = $(this).closest('tr').attr('id');
              location.href = "<?php echo base_url($link."/detail");?>/"+id;
					});

          $('.plan').click(function(){
              var code = $(this).closest('tr').data('code');
              location.href = "<?php echo base_url($link."/plan");?>/"+code;
					});

          $('.detail-btn').click(function(){
              var next = $(this).closest('tr').data('nextpay');
              var code = $(this).closest('tr').data('code');
              location.href = "<?php echo base_url($link."/invoice_detail");?>?nextpay="+next+"&code="+code;
					});

          $('#inactiveTable tbody')
              .on( 'mouseover', 'td', function () {

                  if(un_table.cell(this).index() != undefined ){
                      colIdx = un_table.cell(this).index().column;
                      if ( colIdx !== lastIdx && colIdx > 0 ) {
                          $(un_table.cells().nodes() ).removeClass( 'highlight' );
                          $(un_table.column( colIdx ).nodes() ).addClass( 'highlight' );
                      }
                  }
              } )
              .on( 'mouseleave', function () {

                  $(un_table.cells().nodes() ).removeClass( 'highlight' );

              });
      })


	</script>




<?php  include  $GLOBALS['view_folder'].'supplier/__footer.php'; ?>