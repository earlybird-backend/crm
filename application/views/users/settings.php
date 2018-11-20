<?php
if (is_array($userdata) && sizeof($userdata) > 0) {
    foreach ($userdata as $u => $d) {
        ?>
 
        <div class="setting-wrap">
            <div class="container">
                <div id="message"></div>
                <div class="row display-md-table">
                    <div class="col-md-4 personalinfo-head display-md-table-cell">
                        <div class="text-personal-info">
                            <h3>Personal Setting</h3>
                            <span></span>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                        </div>
                    </div>
                    <div class="col-md-8 account-information display-md-table-cell">
                        <div class="setting-row">
                            <div class="form-label">
                                Username
                            </div>
                            <div class="setting-space edited">
                                <p class="hide-username"> <?php echo $d['Username']; ?></p> 
                                <div id="usermessage"></div>
                            </div>
                            <div class="btn-wrap-setting edited">
                                <a href="JavaScript:void(0)" class="btn-edit pull-right">
                                    <i class="fa fa-pencil"></i>Edit
                                </a>
                            </div>
                            <div class="setting-space editable">
                                <input type="text" name="username" class="form-control" placeholder="Enter Username" value="<?php echo $d['Username']; ?>">

                            </div>
                            <div class="btn-wrap-setting vr-bottom editable">
                                <a href="JavaScript:void(0)" class="btn btn-border btn-rounded btn-cancel pull-right">Cancel</a>
                                <a href="JavaScript:void(0)" onclick="edituser(<?php echo $d['UserId']; ?>);" class="btn btn-border btn-rounded btn-save pull-right">Save</a>
                            </div>
                        </div>
                        <div class="setting-row pwd">
                            <div class="form-label">
                                Password
                            </div>
                             
                            <div class="setting-space edited">
                                <p>.....................</p>                              
                            </div>
                            <div class="btn-wrap-setting edited">
                                <a href="JavaScript:void(0)" class="btn-edit pull-right">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                            </div>

                            <div class="setting-space editable">
                                <input type="Password" name="oldpassword" class="form-control" placeholder="Current Password">
                                <div id="messagepwd"></div>
                                <input type="Password" name="password" class="form-control" placeholder="New Password"> 
                                <div id="messagenpwd"></div>
                                <input type="Password" name="passconf" class="form-control" placeholder="Confirm Password">
                                <div id="messagecpwd"></div>
                            </div>
                            <div class="btn-wrap-setting vr-bottom editable">
                                <a href="JavaScript:void(0)" class="btn btn-border btn-rounded btn-cancel pull-right">Cancel</a>
                                <a href="JavaScript:void(0)" onclick="editpassword(<?php echo $d['UserId']; ?>);" class="btn btn-border btn-rounded btn-save pull-right">Save</a>
                            </div>
                        </div>
                        <div class="setting-row">
                            <div class="form-label">
                                Phone Number
                            </div>
                            <div class="setting-space edited">
                                <p class="hide-phone"><?php echo $d['Phone']; ?></p>
                                <div id="message1"></div>
                                
                            </div>
                            <div class="btn-wrap-setting edited">
                                <a href="JavaScript:void(0)" class="btn-edit pull-right">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                            </div>

                            <div class="setting-space editable">
                                <input type="text" name="phonenumber" class="form-control" placeholder="Enter Number" value="<?php echo $d['Phone']; ?>">
                            </div>
                            <div class="btn-wrap-setting vr-bottom editable">
                                <a href="JavaScript:void(0)" class="btn btn-border btn-rounded btn-cancel pull-right">Cancel</a>
                                <a href="JavaScript:void(0)"  onclick="editphone(<?php echo $d['UserId']; ?>);" class="btn btn-border btn-rounded btn-save pull-right">Save</a>
                            </div>
                        </div>
                        <div class="setting-row">
                            <div class="form-label">
                                Email Address
                            </div>
                            <div class="setting-space edited">
                                <p class="hide-email"><?php echo $d['EmailAddress']; ?></p>
                                 <div id="message2"></div>
                            </div>
                            <div class="btn-wrap-setting edited">
                                <a href="JavaScript:void(0)" class="btn-edit pull-right">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                            </div>
                            <div class="setting-space editable">
                                <input type="text" name="EmailAddress" class="form-control" placeholder="Enter Email" value="<?php echo $d['EmailAddress']; ?>">
                            </div>
                            <div class="btn-wrap-setting vr-bottom editable">
                                <a href="JavaScript:void(0)" class="btn btn-border btn-rounded btn-cancel pull-right">Cancel</a>
                                <a href="JavaScript:void(0)" onclick="editemail(<?php echo $d['UserId']; ?>);" class="btn btn-border btn-rounded btn-save pull-right">Save</a>
                            </div>

                        </div>
                        <div class="setting-row">
                             <?php                            
                     $questiononesql = "SELECT * from security_questions_one where QuestionOneId='" . $d['QuestionOne'] . "'";
                    $questiononestatus = $this->db->query($questiononesql);
                    $questiononedata = $questiononestatus->result_array();                   
                    if ($questiononedata) {
                        foreach ($questiononedata as $p => $s) {
                            $questiononename = $s['QuestionOne'];
                        }
                    }
                    ?>
                             <div id="ques-ans">
                            <div class="form-label">                              
                                <p><?php  if ($questiononedata) { echo $questiononename; } ?></p>
                               
                            </div>
                            
                            <div class="setting-space edited">
                                <p><?php echo $d['QuestionOneAns']; ?></p>                                
                            </div>
                             </div>
                            <div id="message3"></div>
                            
                            <div class="btn-wrap-setting edited">
                                <a href="JavaScript:void(0)" class="btn-edit pull-right">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                            </div>
                            <div class="setting-space editable">   
                                 <?php echo $questionone; ?> 
                                <input type="text" name="Answer1" class="form-control" placeholder="Answer" value="<?php echo $d['QuestionOneAns']; ?>">
                            </div>
                            <div class="btn-wrap-setting vr-bottom editable">
                                <a href="#" class="btn btn-border btn-rounded btn-cancel pull-right">Cancel</a>
                                <a href="#" onclick="editanswer1(<?php echo $d['UserId']; ?>);" class="btn btn-border btn-rounded btn-save pull-right">Save</a>
                            </div>
                        </div>
                        <div class="setting-row">
                             <?php
                             
                     $questiontwosql = "SELECT * from security_questions_two where QuestionTwoId='".$d['QuestionTwo']."'";
                    $questiontwostatus = $this->db->query($questiontwosql);
                    $questiontwodata = $questiontwostatus->result_array();                   
                    if ($questiontwodata) {
                        foreach ($questiontwodata as $p => $s) {
                               $question2 = $s['QuestionTwo'];
                        }
                    }
                    ?>
                             <div id="ques-anes"> 
                            <div class="form-label">                              
                                 <p><?php if ($questiontwodata) { echo $question2; } ?></p>
                            </div>
                            <div class="setting-space edited">
                                <p><?php echo $d['QuestionTwoAns']; ?></p>                                
                            </div> 
                             </div>
                             <div id="message4"></div>
                            <div class="btn-wrap-setting edited">
                                <a href="JavaScript:void(0)" class="btn-edit pull-right">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                            </div>
                            <div class="setting-space editable"> 
                                 <?php echo $questiontwo; ?>
                                <input type="text" name="Answer2" class="form-control" placeholder="Answer" value="<?php echo $d['QuestionTwoAns']; ?>">
                            </div>
                            <div class="btn-wrap-setting vr-bottom editable">
                                <a href="JavaScript:void(0)" class="btn btn-border btn-rounded btn-cancel pull-right">Cancel</a>
                                <a href="JavaScript:void(0)" onclick="editanswer2(<?php echo $d['UserId']; ?>);" class="btn btn-border btn-rounded btn-save pull-right">Save</a>
                            </div>
                        </div>    

                        <div class="setting-row">
                            <div class="form-label">
                                Timeline Background
                            </div>
                            <div class="setting-space rest-wd">
                                <span data-setting="1" class="img-timline-select 
                                <?php
                                if ($d['Timelineimg'] == '1.jpg') {
                                    echo 'selected';
                                }
                                ?> " style="background-image: url(<?php echo base_url(); ?>assets/img/1.jpg);">

                                </span>

                                <span data-setting="2" class="img-timline-select <?php
                                if ($d['Timelineimg'] == '2.jpg') {
                                    echo 'selected';
                                }
                                ?>" style="background-image: url(<?php echo base_url(); ?>assets/img/2.jpg);">

                                </span>

                                <span data-setting="3" class="img-timline-select <?php
                                if ($d['Timelineimg'] == '3.jpg') {
                                    echo 'selected';
                                }
                                ?>" style="background-image: url(<?php echo base_url(); ?>assets/img/3.jpg);">

                                </span>
                             <div id="message-img"></div>                                
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>