<?php  include  $GLOBALS['view_folder'].'customer/__header.php'; ?>
<?php

$allocateStatus = array(
	0 => "资金未到账",
	1 => "资金使用中",
	2 => "资金已过期"
);

?>

<style>        
        
    td.highlight {
        background-color: whitesmoke !important;
    }
    td{
			text-align: center;
		}
</style>
<div class="center-strip">
	<ul class="nav nav-tabs nav-justified" role="tablist">

		<li class="active" role="presentation" >
			<a href="#active" class="nav-link" aria-controls="active" role="tab"  data-toggle="tab">未实施计划</a>
		</li>
		<li role="presentation">
			<a href="#inactive" class="nav-link" aria-controls="inactive" role="tab"  data-toggle="tab">历史资金计划</a>
		</li>
	</ul>
	<div class="clearing-status">
		<div class="graph-body">
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane fade in active" id="active">
					<div class="table-body" style="padding:25px;">
						<table id="myTable" class="display plan-list" cellspacing="0">
							<thead>
							<tr>
								<th style="text-align: center;width: 100px;"><span>&nbsp;</span></th>
								<th style="text-align: center;width:140px;"><span>金额</span></th>
								<th style="text-align: center;width:140px;"><span>可付日期</span></th>
								<th style="text-align: center;width:140px;"><span>状态</span></th>
							</tr>
							</thead>

							<tbody>
              <?php foreach($active as $key=>$item): ?>
								<tr>
									<td style="text-align: center;width: 50px;">计划<?php echo $key+1 ?></td>
									<td style="text-align: center;width: 100px;"><?php echo $item['AllocateAmount'] ?></td>
									<td style="text-align: center;width: 100px;"><?php echo $item['AllocateDate'] ?></td>
									<td style="text-align: center;width: 100px;"><?php echo $allocateStatus[$item['AllocateStatus']] ?></td>
								</tr>
              <?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade " id="inactive">
					<div class="table-body" style="padding:25px;">
						<table id="myTable2" class="display plan-list" cellspacing="0">
							<thead>
							<tr>
								<th style="text-align: center;width: 100px;"><span>&nbsp;</span></th>
								<th style="text-align: center;width:140px;"><span>金额</span></th>
								<th style="text-align: center;width:140px;"><span>可付款日期</span></th>
								<th style="text-align: center;width:140px;"><span>实际使用金额</span></th>
								<th style="text-align: center;width:140px;"><span>资金年化回报率</span></th>
								<th style="text-align: center;width:140px;"><span>资金平均折扣率</span></th>
							</tr>
							</thead>

							<tbody>
              <?php foreach($inactive as $key=>$item): ?>
								<tr>
									<td style="text-align: center;width: 50px;">计划<?php echo $key+1 ?></td>
									<td style="text-align: center;width: 100px;"><?php echo $item['AllocateAmount'] ?></td>
									<td style="text-align: center;width: 100px;"><?php echo $item['AllocateDate'] ?></td>
									<td style="text-align: center;"><?php echo isset($item['UsageAmount']) ? $item['UsageAmount'] : '-' ?></td>
									<td style="text-align: center;"><?php echo isset($item['AvgAPR'])?$item['AvgAPR'].'%':'-' ?></td>
									<td style="text-align: center;"><?php echo isset($item['AvgDiscount'])?$item['AvgDiscount'].'%':'-' ?></td>
								</tr>
              <?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

    <script type="text/javascript">
        var selectedCount = 0;

        $(document).ready(function(){

            var lastIdx = null;
            var link = "";

            var table = $('.plan-list').DataTable({
                bStateSave:true,
                bFiltered:false,
                info:true,
                ordering:false,
                searching:false,
                bLengthChange: true,
                paging:true,
            });

            $('#myTable tbody')
                .on( 'mouseover', 'td', function () {

                    if (table.cell(this).index() === undefined || table.cell(this).index() === null)
                        return;

                    var colIdx = table.cell(this).index().column;

                    if ( colIdx !== lastIdx && colIdx > 0 ) {
                        $( table.cells().nodes() ).removeClass( 'highlight' );
                        $( table.column( colIdx ).nodes() ).addClass( 'highlight' );
                    }
                } )
                .on( 'mouseleave', function () {
                    $(table.cells().nodes() ).removeClass( 'highlight' );
                } )
        })
    </script>



<?php  include  $GLOBALS['view_folder'].'customer/__footer.php'; ?>