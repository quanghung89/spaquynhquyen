<style type="text/css">
    #CusData thead th {
        padding: 10px 0px;
        background: #fff;
        color: #6b6868;
        border: 1px solid #6b6868;
    }

    #Timeout {
        width: 100% !important;
    }

    #CusData tbody tr td {
        border: 1px solid #6b6868;
    }

    #Timeout > tbody > tr > td button {
        display: none;
    }

    #Timeout > tbody > tr:last-child > td button {
        display: block;
    }


</style>
<div class="row">

    <div class="col-sm-2">
        <div class="row">
            <div class="col-sm-12 text-center" style="padding-right: 0px;padding-left: 20px;">
                <div style="max-width:200px; margin: 0 auto;">
                    <?=
                    $user->avatar ? '<img alt="" src="' . base_url() . 'assets/uploads/avatars/thumbs/' . $user->avatar . '" class="avatar">' :
                        '<img alt="" src="' . base_url() . 'assets/images/' . $user->gender . '.png" class="avatar">';
                    ?>
                </div>
                <h4><?= lang('login_email'); ?></h4>

                <p><i class="fa fa-envelope"></i> <?= $user->email; ?></p>
            </div>
        </div>
    </div>

    <div class="col-sm-10 " style="border-left: 1px solid #ccc;">

        <ul id="myTab" class="nav nav-tabs">
            <li><a href="#detail" class="tab-grey"><?= lang('Tổng quan') ?></a></li>
            <li class=""><a href="#edit" class="tab-grey"><?= lang('edit') ?></a></li>
            <li class=""><a href="#cpassword" class="tab-grey"><?= lang('change_password') ?></a></li>
            <li class=""><a href="#avatar" class="tab-grey"><?= lang('avatar') ?></a></li>
            <li class=""><a href="#time_work" class="tab-grey"><?= lang('Thời gian làm việc') ?></a></li>
            <li class=""><a href="#time_out" class="tab-grey"><?= lang('Ngày nghỉ') ?></a></li>
            <li class=""><a href="#pay" class="tab-grey"><?= lang('Lương') ?></a></li>
        </ul>

        <div class="tab-content" style="min-height: 627px">
            <div id="detail" class="tab-pane fade in">
                <div class="box">
                    <!--   <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-key nb"></i><?= lang('change_password'); ?></h2>
                    </div> -->
                    <div class="title-menu con-xs-12" style="margin: 45px 0px;">
                        <span><?= lang('Tổng quan'); ?></span>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-6">
                                    <div class="col-lg-12 clear-padding" style="padding: 7px 0px;">
                                        <div class="col-lg-5 bold">Họ tên:</div>
                                        <div class="col-lg-7"><?php echo $user->last_name ?></div>
                                    </div>
                                    <div class="col-lg-12 clear-padding" style="padding: 7px 0px;">
                                        <div class="col-lg-5 bold">Số điện thoại:</div>
                                        <div class="col-lg-7"><?php echo $user->phone ?></div>
                                    </div>

                                    <div class="col-lg-12 clear-padding" style="padding: 7px 0px;">
                                        <div class="col-lg-5 bold">Quyền hạn:</div>
                                        <div class="col-lg-7"><?php echo $user->group_name ?></div>
                                    </div>
                                    <?php if ($user->warehouse_name) { ?>
                                        <div class="col-lg-12 clear-padding" style="padding: 7px 0px;">
                                            <div class="col-lg-5 bold">Quyền hạn(cơ sở):</div>
                                            <div class="col-lg-7"><?php echo $user->warehouse_name ?></div>
                                        </div>
                                    <?php } ?>

                                    <div class="col-lg-12 clear-padding" style="padding: 7px 0px;">
                                        <div class="col-lg-5 bold">Cơ sở hoạt động:</div>
                                        <div class="col-lg-7"><?php echo $user->CompanyName ?></div>
                                    </div>


                                    <div class="col-lg-12 clear-padding" style="padding: 7px 0px;">
                                        <div class="col-lg-5 bold">Trạng thái hoạt động:</div>
                                        <div class="col-lg-7"
                                             style="font-size:15px; color: #94630c"><?php echo $details['status']; ?></div>
                                    </div>
                                </div>

                                <div class="col-lg-6">

                                    <div class="col-lg-12 clear-padding" style="padding: 7px 0px;">
                                        <div class="col-lg-5 bold">Số ngày làm(*):</div>
                                        <div class="col-lg-7"><?php echo $details['totalDayWo'] ?>.</div>
                                    </div>

                                    <div class="col-lg-12 clear-padding" style="padding: 7px 0px;">
                                        <div class="col-lg-5 bold">Số ngày nghỉ(*):</div>
                                        <div class="col-lg-7"><?php echo $details['totalDayOu'] ?>.</div>
                                    </div>

                                    <div class="col-lg-12 clear-padding" style="padding: 7px 0px;">
                                        <div class="col-lg-5 bold">Tổng số ngày làm(*):</div>
                                        <div class="col-lg-7"><?php echo $details['totalDayWo'] - $details['totalDayOu'] ?>
                                            .
                                        </div>
                                    </div>

                                    <div class="col-lg-12 clear-padding" style="padding: 7px 0px;">
                                        <div class="col-lg-5 bold">Tiền lương(*):</div>
                                        <div class="col-lg-7"><?php echo $this->sma->formatMoney($details['totalPay'] - $details['totalPayOut']) ?>
                                            VNĐ.
                                        </div>
                                    </div>

                                    <div class="col-lg-12 clear-padding" style="padding: 7px 0px;">
                                        <div class="col-lg-5 bold">Tiền thưởng(Tháng):</div>
                                        <div class="col-lg-7"><?php echo $this->sma->formatMoney($details['totalBoun']) ?>
                                            VNĐ.
                                        </div>
                                    </div>

                                    <div class="col-lg-12 clear-padding" style="padding: 7px 0px;">
                                        <div class="col-lg-5 bold">Tiền phạt(Tháng):</div>
                                        <div class="col-lg-7"><?php echo $this->sma->formatMoney($details['totalFine']) ?>
                                            VNĐ.
                                        </div>
                                    </div>

                                    <div class="col-lg-12 clear-padding" style="padding: 7px 0px;">
                                        <div class="col-lg-5 bold">Chi phí ăn(Tháng):</div>
                                        <div class="col-lg-7"><?php echo $this->sma->formatMoney($details['totalOtex']) ?>
                                            VNĐ.
                                        </div>
                                    </div>

                                    <div class="col-lg-12 clear-padding" style="padding: 7px 0px;">
                                        <div class="col-lg-5 bold">Tổng thành tiền(*):</div>
                                        <div class="col-lg-7"><?php echo $this->sma->formatMoney($details['totalAll']) ?>
                                            VNĐ.
                                        </div>
                                    </div>

                                    <div class="col-lg-12 ">
                                        <label style="color: red;font-size: 12px">(*): Cho đến thời điểm hiện tại trong
                                            tháng.</label></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div id="edit" class="tab-pane fade ">

                <div class="box">
                    <!-- <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-edit nb"></i><?= lang('edit_profile'); ?></h2>
                    </div> -->
                    <div class="title-menu con-xs-12" style="margin: 45px 0px;">
                        <span><?= lang('edit_profile'); ?></span>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">

                                <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                                echo form_open('auth/edit_user/' . $user->id, $attrib);
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-5">
                                            <div class="form-group hidden">
                                                <?php echo lang('first_name', 'first_name'); ?>
                                                <div class="controls">
                                                    <?php echo form_input('first_name', $user->first_name, 'class="form-control" id="first_name" required="required"'); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <?php echo lang('Họ và tên', 'last_name'); ?>

                                                <div class="controls">
                                                    <?php echo form_input('last_name', $user->last_name, 'class="form-control" id="last_name" required="required"'); ?>
                                                </div>
                                            </div>
                                            <!--   <?php if (!$this->ion_auth->in_group('customer', $id) && !$this->ion_auth->in_group('supplier', $id)) { ?>
                                                <div class="form-group">
                                                    <?php echo lang('company', 'company'); ?>
                                                    <div class="controls">
                                                        <?php echo form_input('company', $user->company, 'class="form-control" id="company" required="required"'); ?>
                                                    </div>
                                                </div>
                                            <?php } else {
                                                echo form_hidden('company', $user->company);
                                            } ?> -->

                                            <div class="form-group">
                                                <?= lang("Cơ sở", "basis"); ?>
                                                <?php
                                                $wh[''] = '';
                                                $wh['all'] = 'Tất cả';
                                                foreach ($warehouses as $warehouse) {
                                                    $wh[$warehouse->id] = $warehouse->name;
                                                }
                                                echo form_dropdown('basis', $wh, (isset($_POST['basis']) ? $_POST['basis'] : $user->company_id), 'id="basis" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("cơ sở") . '" required="required" style="width:100%;" ');
                                                ?>
                                            </div>

                                            <div class="form-group">

                                                <?php echo lang('phone', 'phone'); ?>
                                                <div class="controls">
                                                    <input type="tel" name="phone" class="form-control" id="phone"
                                                           required="required" value="<?= $user->phone ?>"/>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <?= lang('gender', 'gender'); ?>
                                                <div class="controls">  <?php
                                                    $ge[''] = array('male' => lang('male'), 'female' => lang('female'));
                                                    echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : $user->gender), 'class="tip form-control" id="gender" required="required"');
                                                    ?>
                                                </div>
                                            </div>
                                            <?php if (($Owner || $Admin) && $id != $this->session->userdata('user_id')) { ?>
                                                <div class="form-group hidden">
                                                    <?= lang('award_points', 'award_points'); ?>
                                                    <?= form_input('award_points', set_value('award_points', $user->award_points), 'class="form-control tip" id="award_points"  required="required"'); ?>
                                                </div>
                                            <?php } ?>

                                            <?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
                                                <div class="form-group">
                                                    <?php echo lang('username', 'username'); ?>
                                                    <input type="text" name="username" class="form-control"
                                                           id="username" value="<?= $user->username ?>"
                                                           required="required"/>
                                                </div>
                                                <div class="form-group">
                                                    <?php echo lang('email', 'email'); ?>

                                                    <input type="email" name="email" class="form-control" id="email"
                                                           value="<?= $user->email ?>" required="required"/>
                                                </div>

                                            <?php } ?>

                                        </div>
                                        <div class="col-md-5 col-md-offset-1">
                                            <?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
                                                <div class="row">
                                                    <div class="panel panel-warning">
                                                        <div
                                                                class="panel-heading"><?= lang('if_you_need_to_rest_password_for_user') ?></div>
                                                        <div class="panel-body" style="padding: 5px;">
                                                            <div class="col-md-12">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <?php echo lang('password', 'password'); ?>
                                                                        <?php echo form_input($password); ?>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <?php echo lang('confirm_password', 'password_confirm'); ?>
                                                                        <?php echo form_input($password_confirm); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if (!$this->ion_auth->in_group('customer', $id) && !$this->ion_auth->in_group('supplier', $id)) { ?>
                                                    <div class="row">
                                                        <div class="panel panel-warning">
                                                            <div class="panel-heading"><?= lang('user_options') ?></div>
                                                            <div class="panel-body" style="padding: 5px;">
                                                                <div class="col-md-12">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <?= lang('status', 'status'); ?>
                                                                            <?php
                                                                            $opt = array(1 => lang('active'), 0 => lang('inactive'));
                                                                            echo form_dropdown('status', $opt, (isset($_POST['status']) ? $_POST['status'] : $user->active), 'id="status" required="required" class="form-control input-tip select" style="width:100%;"');
                                                                            ?>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <?= lang("Quyền hạn", "group"); ?>
                                                                            <?php
                                                                            $gp[""] = "";
                                                                            foreach ($groups as $group) {
                                                                                if ($group['name'] != 'customer' && $group['name'] != 'supplier') {
                                                                                    $gp[$group['id']] = $group['name'];
                                                                                }
                                                                            }
                                                                            echo form_dropdown('group', $gp, (isset($_POST['group']) ? $_POST['group'] : $user->group_id), 'id="group" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("group") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                                                            ?>
                                                                        </div>
                                                                        <div class="clearfix"></div>
                                                                        <div class="no">
                                                                            <div class="form-group hidden">
                                                                                <?= lang("biller", "biller"); ?>
                                                                                <?php
                                                                                $bl[""] = "";
                                                                                foreach ($billers as $biller) {
                                                                                    $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                                                                                }
                                                                                echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $user->biller_id), 'id="biller" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("biller") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                                                                ?>
                                                                            </div>

                                                                            <div class="form-group no"
                                                                                 style="display: none">
                                                                                <?= lang("Chọn cơ sở", "warehouse"); ?>
                                                                                <?php
                                                                                $wh[''] = '';
                                                                                foreach ($warehouses as $warehouse) {
                                                                                    $wh[$warehouse->id] = $warehouse->name;
                                                                                }
                                                                                echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $user->warehouse_id), 'id="warehouse" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("Cơ sở") . '" required="required" style="width:100%;" ');
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php } ?>
                                            <?php } ?>
                                            <?php echo form_hidden('id', $id); ?>
                                            <?php echo form_hidden($csrf); ?>
                                        </div>
                                    </div>
                                </div>
                                <p><?php echo form_submit('update', lang('update'), 'class="btn btn-warning"'); ?></p>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="cpassword" class="tab-pane fade">
                <div class="box">
                    <!--   <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-key nb"></i><?= lang('change_password'); ?></h2>
                    </div> -->
                    <div class="title-menu con-xs-12" style="margin: 45px 0px;">
                        <span><?= lang('change_password'); ?></span>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php echo form_open("auth/change_password", 'id="change-password-form"'); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?php echo lang('old_password', 'curr_password'); ?> <br/>
                                                <?php echo form_password('old_password', '', 'class="form-control" id="curr_password" required="required" pattern="[A-Za-z0-9]{7,}"'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label
                                                        for="new_password"><?php echo sprintf(lang('new_password'), $min_password_length); ?></label>
                                                <br/>
                                                <?php echo form_password('new_password', '', 'class="form-control" id="new_password" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"'); ?>
                                                <span class="help-block"><?= lang('pasword_hint') ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?php echo lang('confirm_password', 'new_password_confirm'); ?> <br/>
                                                <?php echo form_password('new_password_confirm', '', 'class="form-control" id="new_password_confirm" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bv-identical="true" data-bv-identical-field="new_password" data-bv-identical-message="' . lang('pw_not_same') . '"'); ?>

                                            </div>
                                            <?php echo form_input($user_id); ?>

                                        </div>
                                        <div class="col-md-12  pull-right text-right"><?php echo form_submit('change_password', lang('change_password'), 'class="btn btn-warning"'); ?></div>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="avatar" class="tab-pane fade">
                <div class="box">
                    <!-- <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-file-picture-o nb"></i><?= lang('change_avatar'); ?></h2>
                    </div> -->
                    <div class="title-menu con-xs-12" style="margin: 45px 0px;">
                        <span><?= lang('change_avatar'); ?></span>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-md-12">
                                    <div style="position: relative;">
                                        <?php if ($user->avatar) { ?>
                                            <img alt=""
                                                 src="<?= base_url() ?>assets/uploads/avatars/<?= $user->avatar ?>"
                                                 class="profile-image img-thumbnail">
                                            <a href="#" class="btn btn-danger btn-xs po"
                                               style="position: absolute; top: 0;" title="<?= lang('delete_avatar') ?>"
                                               data-content="<p><?= lang('r_u_sure') ?></p><a class='btn btn-block btn-danger po-delete' href='<?= site_url('auth/delete_avatar/' . $id . '/' . $user->avatar) ?>'> <?= lang('i_m_sure') ?></a> <button class='btn btn-block po-close'> <?= lang('no') ?></button>"
                                               data-html="true" rel="popover"><i class="fa fa-trash-o"></i></a><br>
                                            <br><?php } ?>
                                    </div>
                                    <?php echo form_open_multipart("auth/update_avatar"); ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?= lang("change_avatar", "change_avatar"); ?>
                                            <input type="file" name="avatar" id="product_image" required="required"
                                                   data-show-upload="false" data-show-preview="false" accept="image/*"
                                                   class="form-control file"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?php echo form_hidden('id', $id); ?>
                                            <?php echo form_hidden($csrf); ?>

                                            <?php echo form_submit('update_avatar', lang('update_avatar'), 'class="btn btn-warning" style="    margin-top: 30px;"'); ?>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="time_work" class="tab-pane fade">
                <div class="box">
                    <!-- <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-file-picture-o nb"></i><?= lang('change_avatar'); ?></h2>
                    </div> -->
                    <div class="title-menu con-xs-12" style="margin: 45px 0px;">
                        <span><?= lang('Thời gian làm việc'); ?></span>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                            echo form_open('auth/time_work/' . $user->id, $attrib); ?>
                            <div class="col-md-3 hidden-xs hidden-sm">


                            </div>
                            <div class="col-md-6 col-xs-12 text-center">
                                <div class="col-md-2 col-xs-2" style="margin-top: 7px; font-weight: 600">DẠNG:</div>

                                <?php if ($usertimeworks) { ?>
                                    <div class="col-md-5 col-xs-5"><input
                                                type="radio" <?php echo ($usertimeworks[0]['sma_usertimework_duty'] == 'full') ? 'checked="checked"' : '' ?>
                                                name="duty" id="full" value="full"><label
                                                style="margin-left: 7px;font-weight: 300" for="full">Full time</label>
                                    </div>
                                    <!--  <div class="col-md-5 col-xs-5"><input  type="radio" <?php echo ($usertimeworks[0]['sma_usertimework_duty'] == 'part') ? 'checked="checked"' : '' ?>  name="duty" id="part" value="part"><label style="margin-left: 7px;font-weight: 300" for="part">Part time</label></div> -->
                                <?php } else { ?>
                                    <div class="col-md-5 col-xs-5"><input type="radio" name="duty" id="full"
                                                                          value="full"><label
                                                style="margin-left: 7px;font-weight: 300" for="full">Full time</label>
                                    </div>
                                    <!--      <div class="col-md-5 col-xs-5"><input  type="radio"   name="duty" id="part" value="part"><label style="margin-left: 7px;font-weight: 300" for="part">Part time</label></div> -->
                                <?php } ?>
                                <div class="table-responsive col-md-12" style="margin-top: 20px;">
                                    <table id="CusData" cellpadding="0" cellspacing="0" border="0"
                                           class="table table-bordered table-condensed table-hover table-striped">
                                        <thead>
                                        <tr class="primary">
                                            <th><?= lang(""); ?></th>
                                            <?php foreach ($timeworks as $key => $value): ?>
                                                <th><?= $value->sma_timework_name ?></th>
                                            <?php endforeach ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if ($usertimeworksB) { ?>
                                            <tr>
                                                <td>Thứ hai</td>
                                                <?php foreach ($usertimeworksB as $key => $value): ?>
                                                    <td>
                                                        <input type="<?php echo $usertimeworks[0]['sma_usertimework_duty'] == 'full' ? 'checkbox' : 'radio' ?>"
                                                               name="monday[]" <?php echo ($value->monday->sma_usertimework_timeworkid == $value->id) ? 'checked="checked"' : '' ?>
                                                               value="monday_<?php echo $value->id ?>"></td>
                                                <?php endforeach ?>
                                            </tr>

                                            <tr>
                                                <td>Thứ ba</td>
                                                <?php foreach ($usertimeworksB as $key => $value): ?>
                                                    <td>
                                                        <input type="<?php echo $usertimeworks[0]['sma_usertimework_duty'] == 'full' ? 'checkbox' : 'radio' ?>"
                                                               name="tuesday[]" <?php echo ($value->tuesday->sma_usertimework_timeworkid == $value->id) ? 'checked="checked"' : '' ?>
                                                               value="tuesday_<?php echo $value->id ?>"></td>
                                                <?php endforeach ?>
                                            </tr>

                                            <tr>
                                                <td>Thứ tư</td>
                                                <?php foreach ($usertimeworksB as $key => $value): ?>
                                                    <td>
                                                        <input type="<?php echo $usertimeworks[0]['sma_usertimework_duty'] == 'full' ? 'checkbox' : 'radio' ?>"
                                                               name="wednesday[]" <?php echo ($value->wednesday->sma_usertimework_timeworkid == $value->id) ? 'checked="checked"' : '' ?>
                                                               value="wednesday_<?php echo $value->id ?>"></td>
                                                <?php endforeach ?>

                                            </tr>

                                            <tr>
                                                <td>Thứ năm</td>
                                                <?php foreach ($usertimeworksB as $key => $value): ?>
                                                    <td>
                                                        <input type="<?php echo $usertimeworks[0]['sma_usertimework_duty'] == 'full' ? 'checkbox' : 'radio' ?>"
                                                               name="thursday[]" <?php echo ($value->thursday->sma_usertimework_timeworkid == $value->id) ? 'checked="checked"' : '' ?>
                                                               value="thursday_<?php echo $value->id ?>"></td>
                                                <?php endforeach ?>
                                            </tr>

                                            <tr>
                                                <td>Thứ sáu</td>
                                                <?php foreach ($usertimeworksB as $key => $value): ?>
                                                    <td>
                                                        <input type="<?php echo $usertimeworks[0]['sma_usertimework_duty'] == 'full' ? 'checkbox' : 'radio' ?>"
                                                               name="friday[]" <?php echo ($value->friday->sma_usertimework_timeworkid == $value->id) ? 'checked="checked"' : '' ?>
                                                               value="friday_<?php echo $value->id ?>"></td>
                                                <?php endforeach ?>
                                            </tr>

                                            <tr>
                                                <td>Thứ bảy</td>
                                                <?php foreach ($usertimeworksB as $key => $value): ?>
                                                    <td>
                                                        <input type="<?php echo $usertimeworks[0]['sma_usertimework_duty'] == 'full' ? 'checkbox' : 'radio' ?>"
                                                               name="saturday[]" <?php echo ($value->saturday->sma_usertimework_timeworkid == $value->id) ? 'checked="checked"' : '' ?>
                                                               value="saturday_<?php echo $value->id ?>"></td>
                                                <?php endforeach ?>
                                            </tr>

                                            <tr>
                                                <td>Chủ nhật</td>
                                                <?php foreach ($usertimeworksB as $key => $value): ?>
                                                    <td>
                                                        <input type="<?php echo $usertimeworks[0]['sma_usertimework_duty'] == 'full' ? 'checkbox' : 'radio' ?>"
                                                               name="sunday[]" <?php echo ($value->sunday->sma_usertimework_timeworkid == $value->id) ? 'checked="checked"' : '' ?>
                                                               value="sunday_<?php echo $value->id ?>"></td>
                                                <?php endforeach ?>

                                            </tr>
                                        <?php } else { ?>
                                            <tr>
                                                <td>Thứ hai</td>
                                                <?php foreach ($timeworks as $key => $value): ?>
                                                    <td><input type="radio" name="monday[]"
                                                               value="monday_<?php echo $value->id ?>"></td>
                                                <?php endforeach ?>
                                            </tr>

                                            <tr>
                                                <td>Thứ ba</td>
                                                <?php foreach ($timeworks as $key => $value): ?>
                                                    <td><input type="radio" name="tuesday[]"
                                                               value="tuesday_<?php echo $value->id ?>"></td>
                                                <?php endforeach ?>
                                            </tr>

                                            <tr>
                                                <td>Thứ tư</td>
                                                <?php foreach ($timeworks as $key => $value): ?>
                                                    <td><input type="radio" name="wednesday[]"
                                                               value="wednesday_<?php echo $value->id ?>"></td>
                                                <?php endforeach ?>
                                            </tr>

                                            <tr>
                                                <td>Thứ năm</td>
                                                <?php foreach ($timeworks as $key => $value): ?>
                                                    <td><input type="radio" name="thursday[]"
                                                               value="thursday_<?php echo $value->id ?>"></td>
                                                <?php endforeach ?>
                                            </tr>

                                            <tr>
                                                <td>Thứ sáu</td>
                                                <?php foreach ($timeworks as $key => $value): ?>
                                                    <td><input type="radio" name="friday[]"
                                                               value="friday_<?php echo $value->id ?>"></td>
                                                <?php endforeach ?>
                                            </tr>

                                            <tr>
                                                <td>Thứ bảy</td>
                                                <?php foreach ($timeworks as $key => $value): ?>
                                                    <td><input type="radio" name="saturday[]"
                                                               value="saturday_<?php echo $value->id ?>"></td>
                                                <?php endforeach ?>
                                            </tr>

                                            <tr>
                                                <td>Chủ nhật</td>
                                                <?php foreach ($timeworks as $key => $value): ?>
                                                    <td><input type="radio" name="sunday[]"
                                                               value="sunday_<?php echo $value->id ?>"></td>
                                                <?php endforeach ?>
                                            </tr>
                                        <?php } ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="col-md-3 hidden-xs hidden-sm"></div>
                            <div class="col-md-12 col-xs-12 text-center"
                                 style="margin-top: 30px;"><?php echo form_submit('update_time_work', lang('update'), 'class="btn btn-warning"'); ?></div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>


            <?php if ($timeouts) { ?>
                <script>
                    var id = <?php echo $id ?>;
                    $(document).ready(function () {
                        'use strict';
                        var initParams = {
                            "aaSorting": [[2, "asc"], [3, "asc"]],
                            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                            "iDisplayLength": <?= $Settings->rows_per_page ?>,
                            'bProcessing': true, 'bServerSide': true,
                            'sAjaxSource': '<?= site_url('auth/getTimeout/?userid=')?>' + id,
                            'fnServerData': function (sSource, aoData, fnCallback) {
                                aoData.push({
                                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                                    "value": "<?= $this->security->get_csrf_hash() ?>"
                                });
                                $.ajax({
                                    'dataType': 'json',
                                    'type': 'POST',
                                    'url': sSource,
                                    'data': aoData,
                                    'success': fnCallback
                                });
                            },
                            "aoColumns": [{"mRender": text_center}, null, null, {"mRender": text_center}, {"mRender": text_center}, {"bSortable": false}]

                        };
                        var oTable = $('#Timeout').dataTable(initParams).fnSetFilteringDelay().dtFilter([
                            // {column_number: 1, filter_default_label: "[<?=lang('last_name');?>]", filter_type: "text", data: []},
                            // {column_number: 2, filter_default_label: "[<?=lang('email_address');?>]", filter_type: "text", data: []},
                            // {column_number: 3, filter_default_label: "[<?=lang('cơ sở');?>]", filter_type: "text", data: []},

                            // {column_number: 4, filter_default_label: "[<?=lang('group');?>]", filter_type: "text", data: []},
                            // {
                            //     column_number: 5, select_type: 'select2',
                            //     select_type_options: {
                            //         placeholder: '<?=lang('status');?>',
                            //         width: '100%',
                            //         minimumResultsForSearch: -1,
                            //         allowClear: true
                            //     },
                            //     data: [{value: '1', label: '<?=lang('active');?>'}, {value: '0', label: '<?=lang('inactive');?>'}]
                            // }
                        ], "footer");

                        function search() {
                            var v = $('#month1').val();
                            var y = $('#year1').val();
                            if (!v) {
                                v = '';
                            }

                            var id = <?php echo $id ?>




                                oTable.fnDestroy();
                            initParams.sAjaxSource = '<?= site_url('auth/getTimeout') ?>/?month=' + v + '&userid=' + id + '&year=' + y;
                            oTable = $('#Timeout').dataTable(initParams);
                            // $('#action-form').attr('action',href+'/'+v)
                        }


                        $('#month1').change(function () {
                            search();
                        });

                        $('#year1').keyup(function () {
                            search();
                        })

                        $('#restTable').click(function () {
                            oTablePu.fnDraw();
                        })
                    });
                </script>
            <?php } ?>
            <div id="time_out" class="tab-pane fade">
                <div class="box">
                    <!-- <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-file-picture-o nb"></i><?= lang('change_avatar'); ?></h2>
                    </div> -->
                    <div class="title-menu con-xs-12" style="margin: 45px 0px;">
                        <span><?= lang('Khai báo nghỉ phép'); ?></span>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'form-time-out');
                            echo form_open('auth/time_out/' . $user->id, $attrib); ?>
                            <div class="col-md-2 hidden-xs hidden-sm">


                            </div>

                            <div class="col-md-12 col-xs-12">
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                    <label>Từ ngày</label>
                                    <div class="form-group" style="margin: 0px;">
                                        <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control date" placeholder="Từ ngày" id="start_date"'); ?>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                    <label>Đến hết ngày</label>
                                    <div class="form-group" style="margin: 0px;">
                                        <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control date" readonly  placeholder="Đến hết ngày" id="end_date"'); ?>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                    <label>Loại</label>
                                    <div class="form-group" style="margin: 0px;">
                                        <?php
                                        $type[0] = 'Nghỉ phép';
                                        $type[1] = 'Nghỉ không phép';
                                        $type[2] = 'Nghỉ nửa ngày có phép';
                                        $type[3] = 'Nghỉ nửa ngày không phép';
                                        echo form_dropdown('type', $type, (isset($_POST['type']) ? $_POST['type'] : [0]), 'class="form-control select" id="month1" placeholder="' . lang("Loại") . " " . lang() . '" style="width:100%"');

                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 hidden-xs hidden-sm"></div>
                            <div class="col-md-12 col-xs-12 text-center"
                                 style="margin-top: 30px;"><?php echo form_submit('update_time_out', lang('update'), 'class="btn btn-warning" '); ?></div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                    <?php if ($timeouts) { ?>
                        <div class="title-menu con-xs-12"
                             style="margin: 45px 0px;  border-top: 1px dashed #ccc; padding-top: 15px;">
                            <span>Lịch sử nghỉ phép</span>
                        </div>
                        <div id="restTable" class="hidden">123</div>
                        <div class="product_actions col-xs-12"
                             style="margin-bottom: 15px; display: inline-block; width: 100%;">
                            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10" style="padding:0px;">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0px;">
                                    <div class="input-group ">
                                        <div class="input-group-addon" style="padding: 2px 11px;">
                                            <a><i class="">Tháng</i></a>
                                        </div>
                                        <?php
                                        for ($i = 1; $i <= 12; $i++) {
                                            $month[$i] = 'Tháng ' + $i;
                                        }

                                        echo form_dropdown('month', $month, (isset($_POST['month']) ? $_POST['month'] : ''), 'class="form-control select" multiple id="month1" placeholder="' . lang("Tháng") . " " . lang() . '" style="width:100%"');

                                        ?>
                                    </div>

                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0px;">
                                    <div class="input-group ">
                                        <div class="input-group-addon" style="padding: 2px 11px;">
                                            <a><i class="">Năm</i></a>
                                        </div>
                                        <input type="text" name="year" class="form-control" id="year1"/>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="table-responsive col-xs-12">
                            <table id="Timeout" cellpadding="0" cellspacing="0" border="0"
                                   class="table table-bordered table-condensed table-hover table-striped">
                                <thead>
                                <tr class="primary">
                                    <th>STT</th>
                                    <th><?= lang("Từ ngày"); ?></th>
                                    <th><?= lang("Đến hết ngày ngày"); ?></th>
                                    <th><?= lang("Số ngày nghỉ"); ?></th>
                                    <th><?= lang("Loại"); ?></th>
                                    <?php if ($Owner || $Admin) { ?>
                                        <th style="width:85px;"><?= lang("actions"); ?></th>
                                    <?php } ?>
                                </tr>
                                </thead>

                                <tbody>
                                <tr>
                                    <td colspan="99"
                                        class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div id="pay" class="tab-pane fade">
                <div class="box">
                    <!-- <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-file-picture-o nb"></i><?= lang('change_avatar'); ?></h2>
                    </div> -->
                    <div class="title-menu con-xs-12" style="margin: 45px 0px;">
                        <span><?= lang('Lương'); ?></span>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                            echo form_open('auth/pay/' . $user->id, $attrib); ?>


                            <div class="col-md-12 col-xs-12">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">
                                    <?php $pay = 0; ?>
                                    <?php foreach ($pays as $key => $value) {
                                        if ($value['sma_pay_startdate'] <= strtotime(date('Y-m-d'))) {
                                            $pay = $value['sma_pay_pay'];
                                        }
                                    } ?>
                                    <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12" style="margin: 0px;">
                                        <label>Lương tháng</label>
                                        <?php echo form_input('pay', (isset($_POST['pay']) ? $_POST['pay'] : $pays ? $this->sma->formatMoney($pay) : ''), 'class="form-control formatMoney" readonly placeholder="Số tiền" id="pay"'); ?>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12" style="margin-top: 30px;">
                                        <a href="<?php echo site_url('auth/update_pay') . '/' . $user->id ?>"
                                           data-toggle="modal" data-target="#myModal" class="btn btn-warning">Tăng
                                            lương</a>

                                        <a href="<?php echo site_url('auth/history_pay') . '/' . $user->id ?>"
                                           data-toggle="modal" data-target="#myModal" class="btn btn-warning">Lịch sử
                                            tăng lương</a>


                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">

                                    <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12" style="margin: 0px;">
                                        <label>Thưởng (Tháng)</label>
                                        <?php echo form_input('bouns', (isset($details['totalBoun']) ? $this->sma->formatMoney($details['totalBoun']) : 0), 'class="form-control formatMoney" readonly placeholder="Số tiền" id="bouns"'); ?>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12" style="margin-top: 30px;">
                                        <a href="<?php echo site_url('auth/update_bouns') . '/' . $user->id ?>"
                                           data-toggle="modal" data-target="#myModal" class="btn btn-warning">Thưởng</a>

                                        <a href="<?php echo site_url('auth/history_bouns') . '/' . $user->id ?>"
                                           data-toggle="modal" data-target="#myModal" class="btn btn-warning">Lịch sử
                                            thưởng</a>


                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">

                                    <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12" style="margin: 0px;">
                                        <label>Phạt (Tháng)</label>
                                        <?php echo form_input('fine', (isset($details['totalFine']) ? $this->sma->formatMoney($details['totalFine']) : 0), 'class="form-control formatMoney" readonly placeholder="Số tiền" id="fine"'); ?>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12" style="margin-top: 30px;">
                                        <a href="<?php echo site_url('auth/update_fine') . '/' . $user->id ?>"
                                           data-toggle="modal" data-target="#myModal" class="btn btn-warning">Phạt</a>

                                        <a href="<?php echo site_url('auth/history_fine') . '/' . $user->id ?>"
                                           data-toggle="modal" data-target="#myModal" class="btn btn-warning">Lịch sử
                                            phạt</a>


                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">

                                    <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12" style="margin: 0px;">
                                        <label>Chi phí ăn trưa (Tháng)</label>
                                        <?php echo form_input('ot_ex', (isset($details['totalOtex']) ? $this->sma->formatMoney($details['totalOtex']) : 0), 'class="form-control formatMoney" readonly placeholder="Chi phí ăn trưa" id="ot_ex"'); ?>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12" style="margin-top: 30px;">
                                        <?php if (!$details['totalOtex']) { ?>
                                            <a href="<?php echo site_url('auth/update_ot_ex') . '/' . $user->id ?>"
                                               data-toggle="modal" data-target="#myModal" class="btn btn-warning">Cập
                                                nhật</a>
                                        <?php } ?>

                                        <a href="<?php echo site_url('auth/history_ot_ex') . '/' . $user->id ?>"
                                           data-toggle="modal" data-target="#myModal" class="btn btn-warning">Lịch sử
                                            cập nhật</a>


                                    </div>
                                </div>
                            </div>


                            <!-- <div class="col-md-12 col-xs-12 text-center" style="margin-top: 30px;"><?php echo form_submit('update_time_out', lang('update'), 'class="btn btn-warning" '); ?></div> -->
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
                <!-- <?php if ($pays && $Admin || $pays && $Owner) { ?>
                    <div class="table-responsive col-xs-12">
                    <table id="Timeout" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-condensed table-hover table-striped">
                        <thead>
                        <tr class="primary">
                            <th>STT</th>
                            <th><?= lang("Lương"); ?></th>
                            <th><?= lang("Ghi chú"); ?></th>
                            <th><?= lang("Ngày thay đổi"); ?></th>
                            <?php if ($Owner || $Admin) { ?>
                                <th style="width:85px;"><?= lang("actions"); ?></th>
                            <?php } ?>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                    foreach ($pays as $key => $value) { ?>
                                <tr class="text-center">
                                    <td ><?php echo $i ?></td>
                                    <td><?php echo $this->sma->formatMoney($value['sma_pay_pay']) ?>
                                    </td>
                                    <td><?php echo $value->sma_pay_note ?>
                                    </td>
                                    <td><?php echo $this->sma->ihrsd($value['sma_pay_createtime']) ?>
                                    </td>
                                    <?php if ($Owner || $Admin) { ?>
                                        <td style="width:85px;">
                                            <a href="#" class="tip bpo"
                                                 title="<b><?= $this->lang->line("Xóa lương") ?></b>"
                                                 data-content="<div style='width:220px;'><p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' href='<?php echo site_url("auth/delete_pay/" . $value["sma_timeout_id"]) ?>'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button></div>"
                                                 data-html="true" data-placement="left"><i class="fa fa-trash-o"></i> </a>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++;
                    } ?>
                        </tbody>
                        
                    </table>
                </div>
                <?php } ?> -->
            </div>
        </div>
    </div>
    <script>

        // function check_errors(a){

        //     if($('#'+a+' .error').length == 0){
        //         $('#'+a).find('input[type="submit"]').removeClass('disabled');
        //     }else{
        //         $('#'+a).find('input[type="submit"]').addClass('disabled');
        //     }
        // }

        $(document).ready(function () {
            <?php if($userTime){ ?>

            var time = <?php echo json_encode($userTime) ?>;
            var fromDateUser = new Date(time[0], time[1] - 1, time[2]);
            // var toDateUser = new Date(time[0], time[1], 0);

            $("#start_date.date").datetimepicker({
                format: site.dateFormats.js_sdate,
                fontAwesome: true,
                language: 'sma',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                maxView: 2,
                minView: 2,
                forceParse: 1,
                startDate: fromDateUser,
                // endDate : toDateUser,
            }).datetimepicker();
            <?php } ?>
            $('#change-password-form').bootstrapValidator({
                message: 'Please enter/select a value',
                submitButtons: 'input[type="submit"]'
            });


            // if($('#full')[0].checked == true){
            //      $.each($('#CusData input[type="radio"]'),function(k,v){
            //          $(v).attr('type','checkbox');
            //      })
            // }else{
            //   $.each($('#CusData input[type="checkbox"]'),function(k,v){
            //          $(v).attr('type','radio');
            //      })
            // }

            // Kiểm tra ngày bắt đầu nghỉ //
            $('#time_out .date').change(function () {

                var a = hrsd($('#start_date').val());
                var b = hrsd($('#end_date').val());


                if (a) {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url('auth/getLastDateEndTimeout') ?>",
                        data: {
                            'id': <?php echo $user->id ?>,
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            var a1 = a.split("-");
                            var a2 = Date.parse(new Date(a1[0], a1[1] - 1, a1[2]));
                            if (data.sma_timeout_enddate) {
                                var c = hrsd(data.sma_timeout_enddate);
                                var c1 = c.split("-");
                                var c2 = Date.parse(new Date(c1[0], c1[1] - 1, c1[2]));
                            } else {
                                var c2 = 0;
                            }
                            if (a2 > c2) {
                                $('#end_date').removeAttr('readonly');
                                if (a && b) {
                                    b1 = b.split("-");
                                    b2 = Date.parse(new Date(b1[0], b1[1] - 1, b1[2]));
                                    if (a2 > b2) {
                                        $('#end_date').addClass('error');
                                    } else {
                                        $('#end_date').removeClass('error');
                                    }
                                }
                            } else {
                                $('#end_date').attr('readonly', 'readonly');
                                $('#end_date').val('');
                                $('#form-time-out input[type="submit"]').addClass('disabled');
                            }

                            check_errors('time_out');

                        }
                    });

                    var dateToday = a.split('-');
                    var fromDate = new Date(dateToday[0], dateToday[1] - 1, 1);

                    var toDate = new Date(dateToday[0], dateToday[1], 0);

                    $("#end_date.date").datetimepicker({
                        format: site.dateFormats.js_sdate,
                        fontAwesome: true,
                        language: 'sma',
                        weekStart: 1,
                        todayBtn: 1,
                        autoclose: 1,
                        todayHighlight: 1,
                        startView: 2,
                        maxView: 2,
                        minView: 2,
                        forceParse: 1,
                        startDate: fromDate,
                        endDate: toDate,
                    }).datetimepicker("setEndDate", toDate).datetimepicker("setStartDate", fromDate);


                }


                //     // var dateToday = new Date();

                //     // var fromDate = new Date(dateToday.getFullYear(), dateToday.getMonth(), 1);

                //     // //Last day on month
                //     // var toDate = new Date(dateToday.getFullYear(), dateToday.getMonth() + 1, 0);


            });


            $('#full').on('ifChecked', function (event) {

                $.each($('#CusData input[type="radio"]'), function (k, v) {
                    $(v).attr('type', 'checkbox');
                    $(v).iCheck('check');
                })
            });

            $('#full').on('ifUnchecked', function (event) {
                $.each($('#CusData input[type="checkbox"]'), function (k, v) {
                    $(v).attr('type', 'radio');
                    $(v).iCheck('uncheck');
                })
            });


        });
    </script>
    <?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function () {
            var url = "<?php echo site_url('Auth/check_group') ?>";

            function getWar() {
                var group = $('#group').val();

                if (group) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "JSON",
                        data: {
                            'id_g': group,
                            'id_user': $('#user_id').val(),
                        }, // serializes the form's elements.

                        success: function (data) {
                            if (data.flag == 1 || group == 1 || group == 2) {
                                $('.no').slideDown();
                            } else {
                                $('.no').slideUp();
                                $("#warehouse").select2("val", data.user.warehouse_id); //set the value
                            }
                        }
                    });
                }
            }


            getWar();
            $('#group').change(function (event) {
                getWar();
            });
            // var group = <?=$user->group_id?>;
            // if (group == 1 || group == 2) {
            //     $('.no').slideUp();
            //     $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'biller');
            //     $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'warehouse');
            // } else {
            //     $('.no').slideDown();
            //     $('form[data-toggle="validator"]').bootstrapValidator('addField', 'biller');
            //     $('form[data-toggle="validator"]').bootstrapValidator('addField', 'warehouse');
            // }
        });


    </script>
<?php } ?>