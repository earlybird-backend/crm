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
                    <h5 class="pull-left"><i class="fa fa-navicon" aria-hidden="true"></i>用户状态</h5>
                </div>

            </div>
        </div>
				<div class="graph-body">
                  <div class="table-body" style="padding:25px;">  		
                      <table id="myTable" class="display" cellspacing="0">
                      <thead>
                           <tr>
                                <th style="text-align: center;width:30px;"><span>状态</span></th>
                                <th style="text-align: center;text-align: left;padding-left: 10px;">
                                    <span>用户(称呼/名字/姓氏/角色/职位)</span>
                                </th>
                                <th style="text-align: center;width: 250px"><span>邮箱/联系电话</span></th>
                                <th style="text-align: center;width: 150px;"><span>创建时间</span></th>
                               <th style="width: 80px">&nbsp;</th>
                            </tr>
                      </thead>
                      <tbody>
                            <?php foreach($rs as $item): ?>
                            <tr>
                                <td><input type="checkbox" checked="<?php echo intval($item['UserStatus'])==1?"true":"false" ?>" /></td>
                                <td style="text-align: left;padding-left: 10px;">
                                    <span class="split1"><?php echo $item['UserName'] ?></span>
                                    <span class="split2"></span>
                                    <span class="split1"><?php echo $item['FirstName'] ?></span>
                                    <span class="split2"></span>
                                    <span class="split1"><?php echo $item['LastName'] ?></span>
                                    <span class="split2"></span>
                                    <span class="split1"><?php echo $item['UserRole'] ?></span>
                                    <span class="split2"></span>
                                    <span class="split1"><?php echo $item['UserPosition'] ?></span>
                                </td>
                                <td>
                                    <span class="split1"><?php echo $item['UserEmail'] ?></span>
                                    <span class="split2"></span>
                                    <span class="split1"><?php echo $item['UserContact'] ?></span>
                                </td>
                                <td><?php echo $item['CreateTime'] ?></td>
                                <td><button type="button" onclick="location.href='/customer/user/userListDetail/<?php echo $item['Uid'] ?>';" class="btn btn-info">更多...</button></td>
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