<?php  include  $GLOBALS['view_folder'].'customer/__header.php'; ?>


<style>        
        
    td.highlight {
        background-color: whitesmoke !important;
    }
   .grapha-header h5{
       font-size:15px;
   }
	td{
		text-align: center;
	}
	.btn-primary{
		padding: 2px 5px;
		margin: 5px;
	}
</style>

<div class="clearing-status">
        <div class="grapha-header">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="pull-left"><i class="fa fa-navicon" aria-hidden="true"></i>用户状态</h5>
                </div>

            </div>
        </div>
				<div class="graph-body">
                  <div class="table-body" style="padding:25px;">  		
                      <table id="myTable" class="display" cellspacing="0">
                      <thead>
                           <tr>
                                <th style="text-align: center;"><span>标记</span></th>
                                <th style="text-align: center;width:140px;"><span>用户状态</span></th>
                                <th style="text-align: center;width:140px;"><span>提交时间</span></th>
                                <th style="text-align: center;"><span>名</span></th>
                                <th style="text-align: center;"><span>姓</span></th>
                                <th style="text-align: center;"><span>公司</span></th>
                                <th style="text-align: center;"><span>角色</span></th>
                                <th style="text-align: center;"><span>邮箱</span></th>
                                <th style="text-align: center;"><span>电话</span></th>
                                <th style="text-align: center;"><span>区域</span></th>
                                <th style="text-align: center;"><span>意向</span></th>
                                <th style="text-align: center;"><span>备注</span></th>
                            </tr>
                      </thead>
                      
                      <tbody>
													<?php foreach($rs as $item): ?>
															<?php
																$status = "-";
																switch ($item['ActivateStatus']){
																		case 0:
                                        $status="新申请";
																			break;
                                    case 1:
                                        $status="已线下沟通";
                                        break;
                                    case 2:
                                        $status="已经邀请注册";
                                        break;
                                    case 3:
                                        $status="已经注册";
                                        break;
																}
															?>
													<tr>
														<td><input type="checkbox" value="<?php echo $item['Id'] ?>" /></td>
														<td><?php echo $status ?></td>
														<td><?php echo $item['CreateTime'] ?></td>
														<td><?php echo $item['FirstName'] ?></td>
														<td><?php echo $item['LastName'] ?></td>
														<td><?php echo $item['CompanyName'] ?></td>
														<td><?php echo $item['RoleName'] ?></td>
														<td><?php echo $item['ContactEmail'] ?></td>
														<td><?php echo $item['ContactPhone'] ?></td>
														<td><?php echo $item['RegionName'] ?></td>
														<td><?php echo $item['InterestName'] ?></td>
														<td><?php echo $item['RequestComment'] ?></td>
													</tr>
													<?php endforeach ?>
                      </tbody>                                     
                </table>                                                    
            </div>
        </div>
</div>


<script type="text/javascript">
    $(document).ready(function(){

    var lastIdx = null;	  
    var link = "";

	   var table = $('#myTable').DataTable({
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
           .on('click', 'a', function() {
               link = "<?php echo base_url($link) . "/";?>" + $(this).attr("name") + "?marketid=" + $(this).parent().parent().attr("id");
               location.href = link;
           });

    	  $('.btn-primary').click(function(){
    	      var ids = "";
    	      $('input[type="checkbox"]:checked').each(function(){
                ids += $(this).val()+",";
						});
    	      if(ids.length>0){
    	          ids = ids.substring(0,ids.length-1);
						}else{
    	          alert("请选择记录");
    	          return;
						}
						$.post('/customer/consulting/process',
								{
								    ids:ids,
										status:$('select[name="activatestatus"]').val()
								},
								function(data){
						    	window.location.href = "/customer/consulting";
								}
						);
				});
        })

      
      
     

</script>



<?php  include  $GLOBALS['view_folder'].'customer/__footer.php'; ?>