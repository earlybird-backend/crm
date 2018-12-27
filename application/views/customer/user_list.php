<?php  include  $GLOBALS['view_folder'].'customer/__header.php'; ?>


<style>        
        
    td.highlight {
        background-color: whitesmoke !important;
    }
    .pageshow
    {
        display: block; !important;
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
                    <h5 class="pull-left"><i class="fa fa-navicon" aria-hidden="true"></i>用户列表</h5>
                </div>

            </div>
        </div>
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="<?php echo $buyer;?>" role="presentation">
            <a href="/customer/user/userBuyerList" class="nav-link" aria-controls="active" role="tab" data-toggle="tab">买家</a>
        </li>
        <li class="<?php echo $vendor;?>" role="presentation">
            <a href="/customer/user/userVendorList" class="nav-link" aria-controls="inactive" role="tab" data-toggle="tab">供应商</a>
        </li>
    </ul>
				<div class="graph-body">
                  <div class="table-body" style="padding:25px;">  		
                      <table id="myTable" class="display" cellspacing="0">
                      <thead>
                           <tr>
                                <th><span>姓名</span></th>
                                <th>
                                    <span>职位</span>
                                </th>
                                <th><span>移动电话 / 座机电话</span></th>
                                <th ><span>电子邮件</span></th>
                               <th ><span>国家</span></th>
                               <th><span>市场统计数</span></th>
                               <th><span>注册时间</span></th>
                               <th>&nbsp;</th>
                            </tr>
                      </thead>
                      <tbody>
                            <?php foreach($rs as $item): ?>
                            <tr>
                                <td><?php echo $item['FirstName'].' '.$item['LastName'] ?></td>
                                <td>
                                    <?php echo $item['Position'] ?>
                                </td>
                                <td>
                                    <span class="split1"><?php echo $item['Telephone'] ?></span>
                                    <span class="split2"></span>
                                    <span class="split1"><?php echo $item['Cellphone'] ?></span>
                                </td>
                                <td><?php echo $item['EmailAddress'] ?></td>
                                <td><?php echo $item['CountryName'] ?></td>
                                <td><?php echo array_find($rsMarketCount,$item['UserId'],'UserId','num'); ?></td>
                                <td><?php echo $item['RegisterDate'] ?></td>
                                <td><button type="button" onclick="location.href='/customer/user/userListDetail/<?php echo $UserRole;?>/<?php echo $item['UserId'] ?>';" class="btn btn-info">更多...</button></td>
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