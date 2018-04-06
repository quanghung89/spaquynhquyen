<style type="text/css">
    .form-horizontal .form-group{
        margin-right: 0px;
    }
</style>
<div class="box">
  <!--  <div class="box-header">
      <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('create_user'); ?></h2>
   </div> -->
   <div class="title-menu con-xs-12" style="margin: 45px 0px;">
         <span><?= lang('create_user'); ?></span>
    </div>
   <div class="box-content">
      <div class="row">
         <div class="col-lg-12">
            <!-- <p class="introtext"><?php echo lang('create_user'); ?></p> -->
            <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
               echo form_open("auth/create_user", $attrib);
               ?>
            <div class="row">
               <div class="col-md-12">
                  <div class="col-md-6">
                     <!-- <div class="form-group">
                        <?php echo lang('first_name', 'first_name'); ?>
                        <div class="controls">
                            <?php echo form_input('first_name', '', 'class="form-control" id="first_name" required="required" pattern=".{3,10}"'); ?>
                        </div>
                        </div> -->
                     <div class="form-group">
                        <?php echo lang('full_name', 'last_name'); ?>
                        <div class="controls">
                           <?php echo form_input('last_name', '', 'class="form-control" id="last_name" required="required"'); ?>
                        </div>
                     </div>

                     <div class="form-group">
                        <?php echo lang('Lương (Tháng)', 'pay'); ?>
                        <div class="controls">
                           <?php echo form_input('pay', '', 'class="form-control formatMoney" id="pay" required="required"'); ?>
                        </div>
                     </div>
                     <!--  <div class="form-group">
                        <?= lang('gender', 'gender'); ?>
                        <?php
                           $ge[''] = array('male' => lang('male'), 'female' => lang('female'));
                           echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : ''), 'class="tip form-control" id="gender" data-placeholder="' . lang("select") . ' ' . lang("gender") . '" required="required"');
                           ?>
                        </div>
                        -->
                     <!--  <div class="form-group">
                        <?php echo lang('company', 'company'); ?>
                        <div class="controls">
                            <?php echo form_input('company', '', 'class="form-control" id="company" required="required"'); ?>
                        </div>
                        </div>
                        -->
                  
                     <div class="form-group">
                        <?= lang("Cơ sở", "basis"); ?>
                        <?php
                           $wh[''] = '';
                           foreach ($warehouses as $warehouse) {
                               $wh[$warehouse->id] = $warehouse->name;
                           }
                           echo form_dropdown('basis', $wh, (isset($_POST['basis']) ? $_POST['basis'] : ''), 'id="basis" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("cơ sở") . '" required="required" style="width:100%;" ');
                           ?>
                     </div>
                      <div class="form-group">
                        <?php echo lang('email', 'email'); ?>
                        <div class="controls">
                           <input type="email" id="email" name="email" class="form-control"
                              required="required"/>
                           <?php /* echo form_input('email', '', 'class="form-control" id="email" required="required"'); */ ?>
                           <div style="color: red; font-size: 11px; margin-top: 5px;">* Dùng để đăng nhặp phần mềm</div>
                        </div>
                     </div>
                    
                     <!-- <div class="form-group">
                        <?php echo lang('password', 'password'); ?>
                        <div class="controls">
                            <?php echo form_password('password', '', 'class="form-control tip" id="password" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"'); ?>
                            <span class="help-block"><?= lang('pasword_hint') ?></span>
                        </div>
                        </div>
                        
                        <div class="form-group">
                        <?php echo lang('confirm_password', 'confirm_password'); ?>
                        <div class="controls">
                            <?php echo form_password('confirm_password', '', 'class="form-control" id="confirm_password" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bv-identical="true" data-bv-identical-field="password" data-bv-identical-message="' . lang('pw_not_came') . '"'); ?>
                        </div>
                        </div> -->
                  </div>
                  <div class="col-md-6">
                         <div class="form-group">
                        <?php echo lang('phone', 'phone'); ?>
                        <div class="controls">
                           <?php echo form_input('phone', '', 'class="form-control" id="phone" required="required"'); ?>
                        </div>
                     </div>
                     <div class="form-group">
                        <?php echo lang('Ngày vào làm', 'date_start'); ?>
                        <div class="controls">
                           <?php echo form_input('date_start', '', 'class="form-control date" id="date_start" required="required"'); ?>
                        </div>
                     </div>
                      <div class="form-group">
                        <?php echo lang('username', 'username'); ?>
                        <div class="controls">
                           <input type="text" id="username" name="username" class="form-control"
                              required="required" pattern=".{4,20}"/>
                        </div>
                     </div>
                     

                     <div class="form-group">
                        <?= lang("Quyền hạn", "group"); ?>
                        <?php
                           $gp[""] = "";
                           foreach ($groups as $group) {
                               if ($group['name'] != 'customer' && $group['name'] != 'supplier') {
                                   $gp[$group['id']] = $group['description'];
                               }
                           }
                           echo form_dropdown('group', $gp, (isset($_POST['group']) ? $_POST['group'] : ''), 'id="group" data-placeholder="' . lang("select") . ' ' . lang("group") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                           ?>
                     </div>

                     <div class="form-group no " style="display: none">
                           <?= lang("Chọn cơ sở", "warehouse"); ?>
                           <?php
                              $wh[''] = '';
                              $wh['all'] = 'Tất cả';
                              foreach ($warehouses as $warehouse) {
                                  $wh[$warehouse->id] = $warehouse->name;
                              }
                              echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ''), 'id="warehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("cơ sở") . '" required="required" style="width:100%;" ');
                              ?>
                        </div>
                  </div>
                  <div class="col-md-12 ">
                     <div class="form-group hidden">
                        <?= lang('status', 'status'); ?>
                        <?php
                           $opt = array(1 => lang('active'), 0 => lang('inactive'));
                           echo form_dropdown('status', $opt, (isset($_POST['status']) ? $_POST['status'] : ''), 'id="status" data-placeholder="' . lang("select") . ' ' . lang("status") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                           ?>
                     </div>
                     
                     <div class="clearfix"></div>
                     <div class="no" style="display: none;">
                        <!-- <div class="form-group">
                           <?= lang("biller", "biller"); ?>
                           <?php
                              $bl[""] = "";
                              foreach ($billers as $biller) {
                                  $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                              }
                              echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $Settings->default_biller), 'id="biller" data-placeholder="' . lang("select") . ' ' . lang("biller") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                              ?>
                           </div> -->
                        
                     </div>
                     <!-- checked="checked" -->
                     <div class="row hidden">
                        <div class="col-md-8"><label class="checkbox" for="notify"><input type="checkbox"
                           name="notify"
                           value="1" id="notify"
                           /> <?= lang('notify_user_by_email') ?>
                           </label>
                        </div>
                        <div class="clearfix"></div>
                     </div>
                  </div>
               </div>
               <div class="col-md-12"><?php echo form_submit('add_user', lang('add_user'), 'class="btn btn-primary"'); ?></div>
            </div>
            <?php echo form_close(); ?>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript" charset="utf-8">
   $(document).ready(function () {
        var url = "<?php echo site_url('Auth/check_group') ?>";

       $('#group').change(function (event) {
           var group = $(this).val();
          
           if(group){  
                $.ajax({
                       type: "POST",
                       url: url,
                       dataType:"JSON",
                       data: {
                         'id_g' : group,
                       }, // serializes the form's elements.
                    
                       success: function(data)
                       {
                           if(data.flag == 1 || group == 1 || group == 2){
                             $('.no').slideDown();                             
                           }else{
                              $('.no').slideUp();   
                              $("#warehouse").select2("val", ""); //set the value
                           }
                       }
                     });
               }
       });
   });
</script>