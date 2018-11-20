<?php  include  $GLOBALS['view_folder'].'manager/__header.php'; ?>
<?php  include  $GLOBALS['view_folder'].'manager/__lefter.php'; ?>

<style>
	.glyphicon-plus{
		font-size: 24px;
		margin-top: 20px;
		margin-left: 15px;
		cursor: pointer;
	}
	.edit-icon,.remove-icon{
		cursor: pointer;
	}
	td{
		text-align: center;
	}
	.main-wtd .row{
		margin-bottom: 15px;
	}
	.edit-icon{
		margin-left: 5px;
	}
</style>
<div class="content-strip">
	<div class="clearing-status">
			<div class="grapha-header">
				<div class="row">
					<div class="col-md-6">
						<h3 class="pull-left">字段：</h3>
						<strong style="color:red;"><?php echo $filed_title ?></strong>
						<span class="glyphicon glyphicon-plus add-icon"></span>
						<input type="hidden" name="field" value="<?php echo $field ?>" />
					</div>
				</div>
			</div>
			<div class="graph-body">
				<div class="table-body" style="padding:25px;">
					<table id="myTable" class="display" cellspacing="0">
						<thead>
						<tr>
							<th style="text-align: center;width:80px;"><span>ID</span></th>
							<th style="text-align: center;width:80px;"><span>中文</span></th>
							<th style="text-align: center;width:80px;"><span>繁体</span></th>
							<th style="text-align: center;width:80px;"><span>英文</span></th>
							<th style="text-align: center;width:40px;">&nbsp;</th>
						</tr>
						</thead>

						<tbody>
            <?php foreach($data as $key=>$item): ?>
							<tr id="<?php echo $item['Id'] ?>">
								<td><?php echo $key+1 ?></td>
								<td><?php echo $item['cnname'] ?></td>
								<td><?php echo $item['twname'] ?></td>
								<td><?php echo $item['enname'] ?></td>
								<td><span class="glyphicon glyphicon-remove remove-icon"></span><span class="glyphicon glyphicon-pencil edit-icon"></span></td>
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
						<div class="list-form title">编辑字段</div>
						<section class="popup-input" id="section_pop2">
							<div class="slimScrollDiv" style="position: relative; overflow: auto; width: auto; height: 300px;">
								<div class="table-wrap-sec main-wtd">
									<div class="row">
										<div class="col-sm-3">
											<label>中文</label>
										</div>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="cnname" required />
										</div>
									</div>
									<div class="row">
										<div class="col-sm-3">
											<label>繁体</label>
										</div>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="twname" required />
										</div>
									</div>
									<div class="row">
										<div class="col-sm-3">
											<label>英文</label>
										</div>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="enname" required />
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
          var cnname = $('input[name="cnname"]').val();
          var enname = $('input[name="enname"]').val();
          var twname = $('input[name="twname"]').val();
          var field = $('input[name="field"]').val();

          var data = {
              cnname:cnname,
              enname:enname,
              twname:twname,
							field:field
          };
          if(flag=='edit'){
              data.id = $('#modal-box').data('id');
          }
          $('.warp').show();
          $.post('/manager/consulting/savefield',data,function(result){
              if(flag=='add'){
                  var tr_html = '<tr id="'+result.data.Id+'">' +
                      '<td>'+($('#myTable tbody tr').length*1+1)+'</td>'+
                      '<td>'+result.data.cnname+'</td>'+
                      '<td>'+result.data.twname+'</td>'+
                      '<td>'+result.data.enname+'</td>'+
                      '<td><span class="glyphicon glyphicon-remove remove-icon"></span><span class="glyphicon glyphicon-pencil edit-icon"></span></td>'+
                      '</tr>';
                  $('#myTable tbody').append($(tr_html));

              }else{
                  var tds = $('#'+result.data.Id+' td');
                  var index = $(tds[0]).html();
                  var tr_html = '<td>'+index+'</td>'+
                      '<td>'+result.data.cnname+'</td>'+
                      '<td>'+result.data.twname+'</td>'+
                      '<td>'+result.data.enname+'</td>'+
                      '<td><span class="glyphicon glyphicon-remove remove-icon"></span><span class="glyphicon glyphicon-pencil edit-icon"></span></td>';
                  $('#'+result.data.Id).html(tr_html);
              }
              $('.warp').hide();
              $('#modal-box').modal('hide');
              $('#form')[0].reset();

          });
          return false;
      });

      $('body').on('click','.edit-icon',function(){
          var id = $(this).closest('tr').attr('id');
          var field = $('input[name="field"]').val();
          $('.warp').show();
          $.get('/manager/consulting/getfielditem/'+id+'/'+field,function(result){
              $('input[name="cnname"]').val(result.cnname);
              $('input[name="twname"]').val(result.twname);
              $('input[name="enname"]').val(result.enname);
              $('#modal-box').data('flag','edit');
              $('#modal-box').data('id',id);
              $('#modal-box').modal({
                  keyboard:false
              });
              $('.warp').hide();
          });

      });
      $('body').on('click','.remove-icon',function(){
          if(window.confirm("确认要删除吗？")){
              var tr = $(this).closest('tr');
              var id = $(this).closest('tr').attr('id');
              var field = $('input[name="field"]').val();
              var data = {
                  id:id,
									field:field
							};
              $('.warp').show();
              $.post('/manager/consulting/delfield',data,function(result){
                  tr.remove();
                  $('.warp').hide();
							});
					}
			});

	})
</script>




<?php  include  $GLOBALS['view_folder'].'manager/__footer.php'; ?>