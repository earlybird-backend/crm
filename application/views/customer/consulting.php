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
    #myTable .split1 {

        border-bottom:1px solid #b1b1b1;
    }
    #myTable .split2:before
    {
        content:" / ";
    }
    #myTable th {
        font-size: 12px;
        text-align: center;
    }
</style>

<div class="clearing-status">
        <div class="grapha-header">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="pull-left"><i class="fa fa-navicon" aria-hidden="true"></i>咨询记录</h5>
                </div>
                <div class="col-md-6">
										<div style="text-align: right">
											<select name="activatestatus">
												<option value="0">新申请</option>
												<option value="1">已线下沟通</option>
												<option value="2">已经邀请注册</option>
												<option value="3">已经注册</option>
											</select>
											<button class="btn btn-primary">处理</button>
										</div>
                </div>
            </div>
        </div>
        <ul class="nav nav-tabs nav-justified" role="tablist">
            <li class="<?php echo $notRegister;?>" role="presentation">
                <a href="/customer/consulting/notRegister" class="nav-link" aria-controls="active" role="tab" data-toggle="tab">未注册</a>
            </li>
            <li class="<?php echo $alreadyRegister;?>" role="presentation">
                <a href="/customer/consulting/alreadyRegister" class="nav-link" aria-controls="inactive" role="tab" data-toggle="tab">已经注册</a>
            </li>
        </ul>
				<div class="graph-body">
                  <div class="table-body" style="padding:25px;">  		
                      <table id="myTable" class="display" cellspacing="0">
                      <thead>
                           <tr>
                                <th style="text-align: center;"><span></span></th>
                                <th style="text-align: center;width:70px;"><span>状态</span></th>
                                <th style="text-align: center;width: 210px;">
                                    <div style="width: 200px;margin-left: 10px;">
                                        <div style="margin: 0 auto;text-align: left">
                                            <span>&sdot;公司名称<br>&sdot;名 / 姓/ 所属角色</span>
                                         </div>
                                     </div>
                                </th>
                                <th style="text-align: center;width: 160px;">
                                    <div style="width: 150px;margin-left: 10px;">
                                        <div style="margin: 0 auto;text-align: left">
                                            <span>&sdot;电子邮箱<br>&sdot;联系电话</span>
                                        </div>
                                     </div>
                                </th>
                                <th style="text-align: center;width:160px;">
                                    <div style="width: 150px;margin-left: 10px;">
                                        <div style="margin: 0 auto;text-align: left">
                                            <span>&sdot;所属区域<br>&sdot;提交时间</span>
                                        </div>
                                     </div>
                                </th>
                               <th style="text-align: center;"><span>意向</span></th>
                               <th>&nbsp;</th>
                                <!--<th style="text-align: center;"><span>备注</span></th>-->
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
														<td>
                                                            <div style="width: 200px;margin-left: 10px;">
                                                                <div style="margin: 0 auto;text-align: left">
                                                                    <span class="split1"><?php echo $item['CompanyName'] ?></span>
                                                                    <br>
                                                                    <span class="split1"><?php echo $item['FirstName'] ?></span>
                                                                    <span class="split2"></span>
                                                                    <span class="split1"><?php echo $item['LastName'] ?></span>
                                                                    <span class="split2"></span>
                                                                    <span class="split1"><?php echo $item['RoleName'] ?></span>
                                                                 </div>
                                                            </div>
                                                        </td>
														<td>
                                                            <div style="width: 150px;margin-left: 10px;">
                                                                <div style="margin: 0 auto;text-align: left">
                                                            <span class="split1"><?php echo $item['ContactEmail'] ?></span>
                                                            <br>
                                                            <span class="split1"><?php echo $item['ContactPhone'] ?></span>
                                                                    </div>
                                                                </div>
                                                        </td>
                                                        <td>
                                                            <div style="width: 150px;margin-left: 10px;">
                                                                <div style="margin: 0 auto;text-align: left">
                                                            <span class="split1"><?php echo $item['RegionName'] ?></span>
                                                            <br>
                                                            <span class="split1"><?php echo $item['CreateTime'] ?></span>
                                                                    </div>
                                                                </div>
                                                        </td>
                                                        <td><?php echo $item['InterestName'] ?></td>
														<!--<td><?php echo $item['RequestComment'] ?></td>-->
                                                        <td><button type="button" onclick="location.href='/customer/consulting/consultingDetail/<?php echo $item['Id'] ?>';" class="btn btn-info">更多...</button></td>
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