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
<div class="content-strip">
	<div class="clearing-status">
			<div class="grapha-header">
				<div class="row">
					<div class="col-md-6">
						<h3 class="pull-left">行业分类</h3><span class="glyphicon glyphicon-plus add-icon"></span>
					</div>
				</div>
			</div>
			<div class="graph-body">
				<div class="table-body" style="padding:25px;">
					<table id="myTable" class="display" cellspacing="0">
						<thead>
						<tr>
							<th style="text-align: center;width:80px;"><span>行业ID</span></th>
							<th style="text-align: center;width:140px;"><span>行业名称</span></th>
							<th style="text-align: center;"><span>备注</span></th>
							<th style="text-align: center;width:40px;">&nbsp;</th>
						</tr>
						</thead>
						<tbody>
							<?php foreach($data as $key=>$item): ?>
							<tr id="<?php echo $item['id'] ?>" data-name="<?php echo $item['name'] ?>" data-comment="">
								<td><?php echo $key+1 ?></td>
								<td><?php echo $item['name'] ?></td>
								<td><?php echo $item['Comment'] ?></td>
								<td><span class="glyphicon glyphicon-pencil edit-icon"></span></td>
							</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
</div>

	<div class="modal fade" tabindex="-1" id="modal-box" role="dialog">
		<div class="modal-dialog modal-md" style="width: 600px;">
			<div class="modal-content">
				<form action="#" id="form" class="form-horizontal" >
					<div class="modal-body">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<div class="list-form title">行业分类</div>
						<section class="popup-input" id="section_pop2">
							<div class="slimScrollDiv" style="position: relative; overflow: auto; width: auto; height: 300px;">
								<div class="table-wrap-sec main-wtd">
									<div class="row">
										<div class="col-sm-3">
											<label>行业名称</label>
										</div>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="name" required />
										</div>
									</div>
									<div class="row">
										<div class="col-sm-3">
											<label>备注</label>
										</div>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="comment" required />
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>
					<div class="file-sec-wrap-info">
						<button type="submit" class="btn-rectangular">确定</button>
					</div>
				</form>
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

      $('.add-icon').click(function(){
				$('#form')[0].reset();
				$('#modal-box').data('flag','add');
      	$('#modal-box').modal({
            keyboard:false
        });
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