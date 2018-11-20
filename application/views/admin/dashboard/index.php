<div id="content-wrapper">
    <ul class="breadcrumb breadcrumb-page">
        <div class="breadcrumb-label text-light-gray">You are here: </div>
        <li><a href="<?php echo base_url(); ?>">Home</a></li>
        <li class="active"><a href="<?php echo base_url('admin_bak/dashboard'); ?>">Dashboard</a></li>
    </ul>
    <div class="page-header">

        <div class="row">

            <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-dashboard page-header-icon"></i>&nbsp;&nbsp;Dashboard</h1>


        </div>
    </div> 

    <div class="stat-panel">
        <div class="stat-row">

            <div class="stat-cell col-sm-4 padding-sm-hr bordered no-border-r valign-top">

                <ul class="list-group no-margin">

                    <li class="list-group-item no-border-hr padding-xs-hr no-bg no-border-radius">
                        Total Customer :  <?php
                     $cusersql = "SELECT count(UserId) from site_users where Role='customer'";
                    $cuserstatus = $this->db->query($cusersql);
                    $cuserdata = $cuserstatus->result_array();                   
                    if ($cuserdata) {
                        foreach ($cuserdata as $p => $s) {
                              echo $ctotaluser = $s['count(UserId)'];
                        }
                    }
                    ?> 
                    </li> 
					 <li class="list-group-item no-border-hr padding-xs-hr no-bg no-border-radius">
                        Total Supplier :  <?php
                     $susersql = "SELECT count(UserId) from site_users where Role='customer'";
                    $suserstatus = $this->db->query($susersql);
                    $suserdata = $suserstatus->result_array();                   
                    if ($suserdata) {
                        foreach ($suserdata as $p => $s) {
                              echo $stotaluser = $s['count(UserId)'];
                        }
                    }
                    ?> 
                    </li> 
                    

                    
                   
                   
                </ul>
            </div>

        </div>
    </div>
</div>

