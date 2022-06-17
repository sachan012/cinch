  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
      <div class="card card-default">
        <div class="card-header">
          <div class="d-inline-block">
              <h3 class="card-title"> <i class="fa fa-plus"></i>
             <?= trans('add_new_user') ?> </h3>
          </div>
          <div class="d-inline-block float-right">
            <a href="<?= base_url('admin/users'); ?>" class="btn btn-success"><i class="fa fa-list"></i>  <?= trans('users_list') ?></a>
          </div>
        </div>
        <div class="card-body">
   
           <!-- For Messages -->
            <?php $this->load->view('admin/includes/_messages.php') ?>

            <?php echo form_open(base_url('admin/users/add'), 'class="form-horizontal"');  ?> 
             
              <div class="form-group">
                <label for="firstname" class="col-md-2 control-label"><?= trans('firstname') ?></label>
                <div class="col-md-12">
                  <input type="text" name="firstname" class="form-control" placeholder="Enter First Name" value="<?php echo set_value('firstname'); ?>" autocomplete="off">
                  <?php echo form_error('firstname', '<div class="error text-danger">', '</div>'); ?>
                </div>
                
              </div>
              
              <div class="form-group">
                <label for="lastname" class="col-md-2 control-label"><?= trans('lastname') ?></label>

                <div class="col-md-12">
                  <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Enter Last Name" value="<?php echo set_value('lastname');?>" autocomplete="off">
                  <?php echo form_error('lastname', '<div class="error text-danger">', '</div>'); ?>
                </div>
                
              </div>

              

              <div class="form-group">
                <label for="email" class="col-md-2 control-label"><?= trans('email') ?></label>

                <div class="col-md-12">
                  <input type="email" name="email" class="form-control" id="email" placeholder="Enter Valid Email Id" value="<?php echo set_value('email');?>" autocomplete="off">
                  <?php echo form_error('email', '<div class="error text-danger">', '</div>'); ?>
                </div>
                
              </div>
            

              <div class="form-group">
                <label for="username" class="col-md-2 control-label"><?= trans('username') ?></label>

                <div class="col-md-12">
                  <input type="text" name="username" class="form-control" id="username" placeholder="Enter username" value="<?php echo set_value('username');?>" autocomplete="off">
                   <?php echo form_error('username', '<div class="error text-danger">', '</div>'); ?>
                </div>
               
              </div>


              <div class="form-group">
                <label for="password" class="col-md-2 control-label"><?= trans('password') ?></label>

                <div class="col-md-12">
                  <input type="text" name="password" class="form-control" id="password" placeholder="Enter Password" value="<?php echo set_value('password');?>" autocomplete="off">
                   <?php echo form_error('password', '<div class="error text-danger">', '</div>'); ?>  
                </div>                          
              </div>

              <div class="form-group">
                <label for="station" class="col-md-2 control-label"><?= ucfirst('assign station') ?></label>                

                <div class="col-md-12">
                  <!-- <input type="text" name="assigned_station" class="form-control" id="assigned_station" placeholder="Enter Assign Station value" value="<?php echo set_value('assigned_station');?>"> -->
                  <select class="form-control" name="assigned_station" class="form-control">
                    <option value="">Select Station from the list</option>
                      <?php foreach($stations as $st){ ?>
                        <option value="<?php echo trim($st['station_code']);?>" <?php if(set_value('assigned_station')==trim($st['station_code'])){echo "selected";}?>><?php echo $st['station_name'].'('.trim($st['station_code']).')';?></option>
                      <?php } ?>                      
                    </select>                  
                  <?php echo form_error('assigned_station', '<div class="error text-danger">', '</div>'); ?>
                </div>
                
              </div>
              
              <div class="form-group">
                <div class="col-md-12">
                  <input type="submit" name="submit" value="<?= ('Create Account') ?>" class="btn btn-primary pull-right">
                </div>
              </div>
            <?php echo form_close( ); ?>
        </div>
          <!-- /.box-body -->
      </div>
    </section> 
  </div>