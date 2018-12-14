<?php  include  $GLOBALS['view_folder'].'customer/__header.php'; ?>
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
                    <h5 class="pull-left"><i class="fa fa-navicon" aria-hidden="true"></i> Buyers List</h5>
                </div>
                <div class="col-md-6">
                    <h5 class="pull-right"><a href="<?php echo base_url($link. "/market");?>" ><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Buyer</a></h5>
                </div>
            </div>
        </div>
            <div class="graph-body">	
                  <div class="table-body" style="padding:25px;">  		
                      <table id="myTable" class="display" cellspacing="0">
                      <thead>
                           <tr>
                                <th style="text-align: center;"><span>#ID</span></th>
                                <th style="text-align: center;width:140px;"><span>集团名称</span></th>
                                <th style="text-align: center;width:140px;"><span>行业</span></th>
                                <th style="text-align: center;"><span>国家</span></th>
                                <th style="text-align: center;"><span>类型</span></th>
                                <th style="text-align: center;width:140px;"><span>网站</span></th>
                                <th style="text-align: center;"><span>市场个数</span></th>
                                <th style="text-align: center;"></th>
                            </tr>
                      </thead>
                      
                      <tbody>   
                            <?php
                            if( isset($rs) && is_array($rs) ) {
                                $num = 0;
                                foreach($rs as $v) {
                                    $num++;                                                              
                                    $info = '';
                                if($v['UserName']!="") {
                                    $info = $v['UserName'];
				}
				if($v['UserEmail'] != "") {
                                  //  $info .='（'.$v['UserEmail'].'）';
				}
                            ?>
                                <tr id="<?php echo $v["Id"]; ?>">
                                    <td style="text-align: center;width: 50px;"><?php echo $num; ?></td>                                    
                                    <td style="text-align: center;width: 100px;"><?php echo $v['CompanyName']; ?></td>
                                    <td style="text-align: center;width: 100px;"><?php echo $v['Industry']; ?></td>
                                    <td style="text-align: center;width: 100px;"><?php echo $v['Country']; ?></td>
                                    <td style="text-align: center;width: 120px;"><?php echo $v['Ctype'] ?></td>
                                    <td style="text-align: center;width: 120px;"><?php echo $v['CompanyWebsite'] ?></td>
                                    <td style="text-align: center;width: 120px;"><?php echo $v['NUM'] ?></td>
                                    <td style="text-align: center;width: 120px;">
     <a href='/customer/customer/editmarket/<?php echo $v['Id']?>' >管理</a></td>
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
           link = "<?php echo base_url($link) . "/";?>" + $(this).attr("name") + "?cashpoolCode=" + $(this).parent().parent().attr("id");
           location.href = link;
       });
    })


</script>



<?php  include  $GLOBALS['view_folder'].'customer/__footer.php'; ?>
