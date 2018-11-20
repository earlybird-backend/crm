<?php  include  $GLOBALS['view_folder'].'supplier/__header.php'; ?>


<style>        
        
    td.highlight {
        background-color: whitesmoke !important;
    }
   .grapha-header h5{
       font-size:15px;
   }
</style>

<div class="clearing-status">
        <div class="grapha-header">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="pull-left"><i class="fa fa-navicon" aria-hidden="true"></i> Market List</h5>
                </div>
                <div class="col-md-6">
									<!--<h5 class="pull-right"><a href="#" data-toggle="modal" data-target="#supplier_add"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Supplier</a></h5>
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#supplier_add" ><i class="fa fa-plus" aria-hidden="true"></i> 新增供应商</a></h4>-->
                </div>
            </div>
        </div>
            <div class="graph-body">	
                  <div class="table-body" style="padding:25px;">  		
                      <table id="myTable" class="display" cellspacing="0">
                      <thead>
                           <tr>
                                <th style="text-align: center;width:140px;"><span>编号</span></th>
                                <th style="text-align: center;width:140px;"><span>创建时间</span></th>
														 		<th style="text-align: center;"><span>买家名称</span></th>
														 		<th style="text-align: center;"><span>DIVISION</span></th>
                                <th style="text-align: center;"><span>币别</span></th>
                                <th style="text-align: center;"><span>币别符号</span></th>
                                <th style="text-align: center;"><span>供应商数</span></th>
                                <th style="text-align: center;"><span></span></th>
                            </tr>
                      </thead>
                      
                      <tbody>   
                            <?php
                            if( isset($markets) && is_array($markets) ) {
                                foreach($markets as $v){
                            ?>
                                <tr id="<?php echo $v["cashpoolcode"]; ?>">

                                    <td style="text-align: center;width: 100px;"><?php echo $v['cashpoolcode']; ?></td>
                                    <td style="text-align: center;width: 100px;"><?php echo $v['createtime']; ?></td>
																		<td style="text-align: center;width: 100px;"><?php echo $v['buyer']; ?></td>
																		<td style="text-align: center;width: 100px;"><?php echo $v['division']; ?></td>
																		<td style="text-align: center;width: 100px;"><?php echo $v['currency']; ?></td>
																		<td style="text-align: center;width: 100px;"><?php echo $v['currencysign']; ?></td>
																		<td style="text-align: center;width: 100px;"><?php echo $v['SupplierCount']; ?></td>
                                    <td style="text-align: center;width: 100px;"><button class="v-detail">详情</button></td>
                                </tr>
                            <?php
                                }
                            }
                            ?>
                                            
                      </tbody>                                     
                </table>                                                    
            </div>
        </div>
</div>


<script type="text/javascript">
var selectedCount = 0;

    $(document).ready(function(){

        var lastIdx = null;	  

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
           } );

         $('.v-detail').click(function(){
						var v_id = $(this).closest('tr').attr('id');
             location.href = "<?php echo base_url($link."/supplier_list?code=");?>"+v_id;
				 });

         $('.history').click(function(){
             var v_id = $(this).closest('tr').attr('id');
             location.href = "<?php echo base_url($link."/history");?>?vid="+v_id;
				 });
      });
      
      
      function show_detail(item){

        $('#'+item).toggle();
      }


      
      
     

</script>



<?php  include  $GLOBALS['view_folder'].'supplier/__footer.php'; ?>