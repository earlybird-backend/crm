<?php  include  $GLOBALS['view_folder'].'manager/__header.php'; ?>
<?php  include  $GLOBALS['view_folder'].'manager/__lefter.php'; ?>

<style>
	.glyphicon-plus{
		font-size: 24px;
		margin-top: 20px;
		margin-left: 15px;
		cursor: pointer;
	}
	.edit-icon{
		cursor: pointer;
	}
	td{
		text-align: center;
	}
	.main-wtd .row{
		margin-bottom: 15px;
	}
</style>
<?php
	/*
	 * 市场开价日期的状态标识
	 */
	$status = array(
		0 => "待开始",
		1 => "进行中",
		2 => "已结束"
	)

?>
<div class="content-strip">
	<div class="clearing-status">
			<div class="grapha-header" style="padding:5px!important;">
				<div class="row" style="height:48px;">
					<div class="col-md-5">
						<h3 class="pull-left">市场开价日期</h3>
					</div>
					<div class="col-md-7" style="line-height:65px;font-size:16px;color: crimson;">
						<label style="font-weight: bold"> 控制日期： </label>
						往前一日<span class="glyphicon glyphicon-triangle-right"></span>
						计算当天<span class="glyphicon glyphicon-triangle-right"></span>
						早付日计算<span class="glyphicon glyphicon-triangle-right"></span>
					</div>
				</div>
			</div>
			<div class="graph-body">
				<div class="table-body" style="padding:25px;">
					<table id="myTable" class="display" cellspacing="0">
						<thead>
						<tr>
							<th style="text-align: center;width:80px;"><span>开价日期</span></th>
							<th style="text-align: center;width:140px;"><span>服务状态</span></th>
							<th style="text-align: center;"><span>服务开始时间</span></th>
							<th style="text-align: center;"><span>服务结束时间</span></th>
						</tr>
						</thead>
						<tbody>
							<?php foreach($data as $key=>$item): ?>
							<tr  data-comment="">
								<td><?php echo $item["ServiceDate"]; ?></td>
								<td><?php echo $status[$item['ServiceStatus']]; ?></td>
								<td><?php echo $item['StartTime'] ?></td>
								<td><?php echo $item['EndTime'] ?></td>
							</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){

      var table = $('#myTable').DataTable({
          bStateSave:true,
          bFiltered:false,
          info:true,
          ordering:false,
          searching:false,
          bLengthChange: true,
          paging:true,
      });


      $('#form').submit(function(){
          var flag = $('#modal-box').data('flag');
          var name = $('input[name="name"]').val();
          var comment = $('input[name="comment"]').val();

          var data = {
              name:name,
							comment:comment
					};
          if(flag=='edit'){
              data.id = $('#modal-box').data('id');
					}
          $('.warp').show();
          $.post('/manager/home/save',data,function(result){
							if(flag=='add'){
                  var tr_html = '<tr id="'+result.data.id+'">' +
                      '<td>'+($('#myTable tbody tr').length*1+1)+'</td>'+
                      '<td>'+result.data.name+'</td>'+
											'<td>'+result.data.Comment+'</td>'+
                      '<td><span class="glyphicon glyphicon-pencil edit-icon"></span></td>'+
                      '</tr>';
                  $('#myTable tbody').append($(tr_html));

							}else{
							    $('#'+result.data.id).data('name',result.data.name);
							    $('#'+result.data.id).data('comment',result.data.Comment);
							    var tds = $('#'+result.data.id+' td');
							    var index = $(tds[0]).html();
                  var tr_html = '<td>'+index+'</td>'+
                      '<td>'+result.data.name+'</td>'+
											'<td>'+result.data.Comment+'</td>'+
                      '<td><span class="glyphicon glyphicon-pencil edit-icon"></span></td>';
                  $('#'+result.data.id).html(tr_html);
							}
              $('.warp').hide();
              $('#modal-box').modal('hide');
              $('#form')[0].reset();

						});
          return false;
			});

			$('body').on('click','.edit-icon',function(){
			    var id = $(this).closest('tr').attr('id');
			    $('.warp').show();
			    $.get('/manager/home/getitem/'+id,function(result){
			        $('input[name="name"]').val(result.name);
			        $('input[name="comment"]').val(result.Comment);
              $('#modal-box').data('flag','edit');
              $('#modal-box').data('id',id);
              $('#modal-box').modal({
                  keyboard:false
              });
              $('.warp').hide();
					});

      });


	})

</script>




<?php  include  $GLOBALS['view_folder'].'manager/__footer.php'; ?>