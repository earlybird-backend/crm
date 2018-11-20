<div class="login-page" style="background: url(<?php echo base_url(); ?>assets/img/login_bg.jpg)">

		<div class="container">

			<div class="page-title-sec">

				<h1>Create your account</h1>

			</div>

		    <div class="row">

			    <div class="col-md-6 col-md-offset-3">

			      <div class="panel panel-login">

			        <div class="panel-body">

			          <div class="row">

			            <div class="col-lg-12">

			              
						  <?php echo form_open('customer/index',array('name' => 'customer', 'id' => 'customer'));?>
							<input type="hidden" name="Role" value="customer" />
			                  <div class="form-group row">

			                  	<div class="col-sm-12">

			                  		<input type="text" name="EmailAddress" id="EmailAddress"  class="form-control" placeholder="EmailAddress" value="<?php echo set_value('EmailAddress'); ?>" required>
									<?php echo form_error('EmailAddress', '<p class="error">'); ?>	
			                  	</div>

			                  </div>

			                  <div class="form-group row">

			                  	<div class="cols col-sm-6">

			                  		<input type="Password" name="Password" id="Password"  class="form-control" placeholder="Password" value="<?php echo set_value('Password'); ?>" required>
									<?php echo form_error('Password', '<p class="error">'); ?>
			                  	</div>

			                  	<div class="cols col-sm-6">

			                  		<input type="Password" name="ConfirmPassword" id="ConfirmPassword"  class="form-control" placeholder="Confirm Password" value="<?php echo set_value('ConfirmPassword'); ?>" required>
									<?php echo form_error('ConfirmPassword', '<p class="error">'); ?>
			                  	</div>

			                  </div>
							  
							  
							  <div class="form-group row">

			                  	<div class="col-sm-12">

			                  		<input type="text" name="CompanyName" id="CompanyName"  class="form-control" placeholder="CompanyName" value="<?php echo set_value('CompanyName'); ?>" required>
									<?php echo form_error('CompanyName', '<p class="error">'); ?>	
			                  	</div>

			                  </div>

			                   <div class="form-group row">

			                  	<div class="cols col-sm-6">

			                  		<select class="form-control" name="Industry" id="Industry" required>
										<option value="">Select Industry</option>
										<option value="industry1">Industry1</option>
										<option value="industry2">Industry2</option>
										<option value="other">Other</option>
									</select>
									

			                  	</div>

			                  	<div class="cols col-sm-6">

			                  		<input type="text" name="IndustryRemark" id="IndustryRemark" tabindex="1" class="form-control" placeholder="Remark" value="">

			                  	</div>

			                  </div>

			                  <div class="form-group row">

			                  	<div class="col-sm-12">

			                  		 <?php echo form_dropdown('Region', $datacounty, set_value('Region'), 'id="Country" class="form-control input-lg" onchange="setAutocomplete();"'); ?>
                                        <?php echo form_error('Region', '<p class="error">'); ?>

			                  	</div>

			                  </div>
							  
							  
							   <div class="form-group row">

			                  	<div class="col-sm-12">

			                  		<input type="text" name="ContactName" id="ContactName"  class="form-control" placeholder="ContactName" value="<?php echo set_value('ContactName'); ?>" required>
										 <?php echo form_error('ContactName', '<p class="error">'); ?>
			                  	</div>

			                  </div>
							  
							   <div class="form-group row">

			                  	<div class="col-sm-12">

			                  		<input type="text" name="Position" id="Position"  class="form-control" placeholder="Position" value="<?php echo set_value('Position'); ?>" required>
									<?php echo form_error('Position', '<p class="error">'); ?>
			                  	</div>

			                  </div>
							  
							   <div class="form-group row">

			                  	<div class="col-sm-12">

			                  		<input type="text" name="Telephone" id="Telephone"  class="form-control" placeholder="Telephone" value="<?php echo set_value('Telephone'); ?>" required>
									<?php echo form_error('Telephone', '<p class="error">'); ?>
			                  	</div>

			                  </div>
							  
							   <div class="form-group row">

			                  	<div class="col-sm-12">

			                  		<input type="text" name="Cellphone" id="Cellphone"  class="form-control" placeholder="Cellphone" value="<?php echo set_value('Telephone'); ?>" required>
									<?php echo form_error('Cellphone', '<p class="error">'); ?>
			                  	</div>

			                  </div>
							  
							  <div class="form-group">
                                    <?php echo $captchaImage; ?>

                                    Confirm security code<span class="error">*</span>
                                    <input type="text" class="form-control input-lg" name="captcha" id="captcha" />
                                    <?php echo form_error('captcha', '<p class="error">'); //echo $this->session->userdata('captchaword');   ?> 
                                </div>

			                  <div class="form-group row">

			                  	<div class="col-sm-12 text-center">

			                  		<input class="btn btn-login" type="submit" value="submit" />

			                  	</div>

			                  </div>

			              <?php echo form_close(); ?>

			            </div>

			          </div>

			        </div>

			      </div>

			    </div>

		  </div>

		</div>

	</div>