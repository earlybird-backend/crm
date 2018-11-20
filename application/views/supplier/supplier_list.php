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

            <div class="graph-body">	
                  <div class="table-body" style="padding:25px;">
											<input type="hidden" name="code" value="<?php echo $code ?>" />
											<input type="text" name="search" value="<?php echo $key ?>" placeholder="DIVISION/USERID" /> <span style="cursor: pointer" class="glyphicon glyphicon-search search"></span>
                      <table id="myTable" class="display" cellspacing="0">
                      <thead>
                           <tr>
														 		<th style="text-align: center;width:140px;"><span>ID</span></th>
                                <th style="text-align: center;width:140px;"><span>供应商名</span></th>
                                <th style="text-align: center;width:140px;"><span>Division</span></th>
														 		<th style="text-align: center;"><span>负责人</span></th>
                                <th style="text-align: center;"><span>详情</span></th>
                            </tr>
                      </thead>
                      
                      <tbody>   
                            <?php
                            if( isset($suppliers) && is_array($suppliers) ) {
                                $num = 0;
                                foreach($suppliers as $v){
                                    $num++;                                                              
                                
                            ?>
                                <tr id="<?php echo $v["Id"]; ?>">
																	<td style="text-align: center;width: 30px;"><?php echo $num; ?></td>
                                    <td style="text-align: center;width: 100px;"><?php echo $v['CompanyName']; ?></td>
                                    <td style="text-align: center;width: 100px;"><?php echo $v['VendorDivision']; ?></td>
																		<td style="text-align: center;width: 100px;"><?php echo $v['RelevancyEmail']; ?></td>
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
			  $('.search').click(function(){
					var code = $('input[name="code"]').val();
					var key = $('input[name="search"]').val();
            location.href = "<?php echo base_url($link."/supplier_list?code=");?>"+code+"&key="+key;
				});
         $('.v-detail').click(function(){
						var v_id = $(this).closest('tr').attr('id');
             var code = $('input[name="code"]').val();
             location.href = "<?php echo base_url($link."/detail_new?id=");?>"+v_id+"&code="+code;
				 });
      });

      
      
     

</script>



<?php  include  $GLOBALS['view_folder'].'supplier/__footer.php'; ?>