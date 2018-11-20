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
						<h3 class="pull-left">咨询页字段</h3></span>
					</div>
				</div>
			</div>
			<div class="graph-body">
				<div class="table-body" style="padding:25px;">
					<table id="myTable" class="display" cellspacing="0">
						<thead>
						<tr>
							<th style="text-align: center;width:80px;"><span>ID</span></th>
							<th style="text-align: center;width:80px;"><span>名称</span></th>
							<th style="text-align: center;width:40px;">&nbsp;</th>
						</tr>
						</thead>

						<tbody>
							<tr>
								<td>1</td>
								<td>角色</td>
								<td><span data-field="role" class="glyphicon glyphicon-pencil edit-icon"></span></td>
							</tr>
							<tr>
								<td>2</td>
								<td>区域</td>
								<td><span data-field="region" class="glyphicon glyphicon-pencil edit-icon"></span></td>
							</tr>
							<tr>
								<td>3</td>
								<td>兴趣</td>
								<td><span data-field="interest" class="glyphicon glyphicon-pencil edit-icon"></span></td>
							</tr>
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
						<div class="list-form title">咨询页字段</div>
						<section class="popup-input" id="section_pop2">
							<div class="slimScrollDiv" style="position: relative; overflow: auto; width: auto; height: 300px;">
								<div class="table-wrap-sec main-wtd">
									<div class="row">
										<div class="col-sm-3">
											<label>字段名称</label>
										</div>
										<div class="col-sm-6">
											<input type="email" class="form-control" name="email-username" required />
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



      $('body').on('click','.edit-icon',function(){
          var field = $(this).data('field');
          window.location.href = '/manager/consulting/editfield?field='+field;
      });

	})
</script>




<?php  include  $GLOBALS['view_folder'].'manager/__footer.php'; ?>