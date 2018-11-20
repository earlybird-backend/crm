<?php  include  $GLOBALS['view_folder'].'customer/__header.php'; ?>


<style>        
        
    td.highlight {
        background-color: whitesmoke !important;
    }
    .vt-stat-main{margin-bottom: 20px}
    .vt-stat-main >div >a{display:block;text-decoration:none;background: #fff;text-align: center;line-height: 100px;font-size: 15px;
        box-shadow: 0 0 4px 2px rgba(0,0,0, 0.10);color: #666;transition: all .2s}
    .vt-stat-main >div >a:hover{background: #1484da;color: #fff}
</style>

    <div>
        <div class="row vt-stat-main">
            <div class="col-md-3"><a href="">全部供应商: <?php echo $panel['TotalVendor'] ?></a></div>
            <div class="col-md-3"><a href="">注册供应商: <?php echo $panel['TotalRegistered'] ?></a></div>
            <div class="col-md-3"><a href="">开价供应商: <?php echo $panel['TotalBid'] ?></a></div>
            <div class="col-md-3"><a href="">清算供应商: <?php echo $panel['TotalAward'] ?></a></div>

        </div>
    </div>
<div class="clearing-status">
    <div class="graph-body">
        <div class="table-body" style="padding:25px;">
            <table id="myTable" class="display" cellspacing="0">
                <thead>
                <tr>
                    <th style="text-align: center;"><span>序号</span></th>
                    <th style="text-align: center;width:140px;"><span>供应商</span></th>
                    <th style="text-align: center;width:140px;"><span>VENDORCODE</span></th>
                    <th style="text-align: center;"><span>电话</span></th>
                    <th style="text-align: center;"><span>邮箱</span></th>
                    <th style="text-align: center;"><span>是否注册</span></th>
                    <th style="text-align: center;"><span>最后一次开价</span></th>
                    <th style="text-align: center;"><span>最后一次清算</span></th>
                </tr>
                </thead>

                <tbody>
								<?php foreach($vendors as $key=>$item): ?>
                <tr>
                    <td style="text-align: center;width: 50px;"><?php echo $key+1 ?></td>
                    <td style="text-align: center;width: 100px;"><?php echo $item['Supplier'] ?></td>
                    <td style="text-align: center;width: 100px;"><?php echo $item['Vendorcode'] ?></td>
                    <td style="text-align: center;width: 100px;"><?php echo isset($item['UserPhone'])?$item['UserPhone']:'-' ?></td>
                    <td style="text-align: center;width: 120px;"><?php echo isset($item['UserEmail'])?$item['UserEmail']:'-' ?></td>
                    <td style="text-align: center;"><?php echo ($item['IsRegistered']==1)?"是":"否" ?></td>
                    <td style="text-align: center;"><?php echo $item['LastBidDate'] ?></td>
                    <td style="text-align: center;"><?php echo $item['LastAwardDate'] ?></td>
                </tr>
								<?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
				

	          

</div>
    <script type="text/javascript">
        var selectedCount = 0;

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
                    link = "<?php echo base_url($link) . "/";?>" + $(this).attr("name") + "/market_id=" + $(this).parent().parent().attr("id");
                    location.href = link;
                });
        })


        function show_detail(item){

            $('#'+item).toggle();
        }




    </script>



<?php  include  $GLOBALS['view_folder'].'customer/__footer.php'; ?>