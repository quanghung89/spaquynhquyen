<script>
    $(document).ready(function () {
        'use strict';
        var initParams = {
            "aaSorting": [[2, "asc"], [3, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('auth/getUsers') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, null, null, null, null, {"mRender": user_status}, {"bSortable": false}]
            
        } ;
//        var oTable = $('#UsrTable').dataTable(initParams).fnSetFilteringDelay().dtFilter([
//            {column_number: 1, filter_default_label: "[Tên]", filter_type: "text", data: []},
//            {column_number: 2, filter_default_label: "[Khách]", filter_type: "text", data: []},
//            {column_number: 3, filter_default_label: "[Dịch vụ]", filter_type: "text", data: []},
//
//            {column_number: 4, filter_default_label: "[Thời gian]", filter_type: "text", data: []},
//            {
//                column_number: 5, select_type: 'select2',
//                select_type_options: {
//                    placeholder: '<?//=lang('status');?>//',
//                    width: '100%',
//                    minimumResultsForSearch: -1,
//                    allowClear: true
//                },
//                data: [{value: '1', label: '<?//=lang('active');?>//'}, {value: '0', label: '<?//=lang('inactive');?>//'}]
//            }
//        ], "footer");

        function search(){
            var v = $('#warehouse').val();
            if(!v){
                v='';
            }

            var b = $('#group').val();

            if(!b){
                b='';
            }

            var c = $('#status').val();

            if(!c){
                c='';
            }

            


//            oTable.fnDestroy();
//            initParams.sAjaxSource = '<?//= site_url('auth/getUsers') ?>///?warehouse_id='+v+'&group_id='+b+'&status='+c;
//            oTable = $('#UsrTable').dataTable(initParams);
            // $('#action-form').attr('action',href+'/'+v)
        }



        $('#warehouse,#group,#status').change(function(){
            search();
        });

        $('#restTable').click(function(){
             oTablePu.fnDraw();
        })
    });
</script>
<style>.table td:nth-child(6) {
        text-align: right;
        width: 10%;
    }

    .table td:nth-child(8) {
        text-align: center;
    }</style>
<?php if ($Owner) {
    
} ?>
<div class="box">
    <!-- <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('users'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip"
                                                                                  data-placement="left"
                                                                                  title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?= site_url('auth/create_user'); ?>"><i
                                    class="fa fa-plus-circle"></i> <?= lang("add_user"); ?></a></li>
                        <li><a href="#" id="excel" data-action="export_excel"><i
                                    class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
                        <li><a href="#" id="pdf" data-action="export_pdf"><i
                                    class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
                        <li class="divider"></li>
                        <li><a href="#" class="bpo" title="<b><?= $this->lang->line("delete_users") ?></b>"
                               data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>"
                               data-html="true" data-placement="left"><i
                                    class="fa fa-trash-o"></i> <?= lang('delete_users') ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div> -->
    <div class="title-menu con-xs-12" style="margin: 45px 0px;">
         <span>CHECK LỊCH NHÂN VIÊN</span>
    </div>

    <?php echo form_open('auth/check_user', 'id="action-form"'); ?>
    <div id="restTable" class="hidden">123</div>

    <div class="product_actions col-xs-12" style="padding: 0px; margin-bottom: 15px;">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">

            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control date" placeholder="Từ ngày" id="start_date"'); ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
                <div class="form-group">
                    <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control date" placeholder="Đến ngày" id="end_date"'); ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3" style="padding-left: 0px;">
                <?php echo form_submit('submit_form', 'Lọc', 'class="btn-warning default  btn"'); ?>
            </div>

       </div>
    </div> 
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <!-- <p class="introtext"><?= lang('list_results'); ?></p> -->

                <div class="table-responsive">
                    <table  cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th  style="min-width:30px; width: 30px; text-align: center;">
                                STT
                            </th>
                            <th  style="min-width:30px; width: 30px !important; text-align: center;">
                                Status
                            </th>
                            <th >Username</th>
                            <th class="col-xs-2">Khách</th>
                            <th class="col-xs-2">Dịch vụ</th>
                            <th class="col-xs-2">Thời gian</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(isset($users)) {
                            $count = 1;
                            foreach ($users as $user){ ?>
                                <tr class="active">
                                    <th style="min-width:30px; width: 30px; text-align: center;">
                                        <?= $count ?>
                                    </th>
                                    <th style="text-align: center">
                                        <?php
                                            if ($user -> isOff == true) { ?>
                                                <div class="radio">
                                                    <input type="radio" class="skip"  value="option2" name="radioSingle1<?= $count ?>" checked="" >
                                                    <label></label>
                                                </div>
                                            <?php } else if ($user -> isWorking == true && $user -> isNowdate == true) {  ?>
                                                <div class="radio radio-danger">
                                                    <input type="radio" class="skip"  value="option2" name="radioSingle1<?= $count ?>" checked="" >
                                                    <label></label>
                                                </div>
                                           <?php } else { ?>
                                                <div class="radio radio-success">
                                                    <input type="radio" class="skip"  value="option2" name="radioSingle1<?= $count ?>" checked="" >
                                                    <label></label>
                                                </div>
                                            <?php }
                                        ?>
                                    </th>
                                    <th>
                                        <?=
                                        $user->avatar ? '<img alt="" style="max-width:50px;margin-right: 10px;" src="' . base_url() . 'assets/uploads/avatars/thumbs/' . $user->avatar . '" class="avatar">' :
                                            '<img alt="" style="max-width:50px;margin-right: 10px;" src="' . base_url() . 'assets/images/' . $user->gender . '.png" class="avatar">';
                                        ?>
                                        <?= $user->username?>
                                    </th>
                                    <th>
                                        <?php

                                            if(isset($user->customerName)) { ?>
                                                <label>><?=$user->customerName?></label>
                                            <?php }
                                        ?>
                                    </th>
                                    <th>
                                        <?php
                                        if(isset($user->serviceName)) { ?>
                                            <label><?=$user->serviceName?></label>
                                        <?php }
                                        ?>
                                    </th>
                                    <th>
                                        <?php
                                        if(isset($user->dateWorkStart)) { ?>
                                            <label><?=$user->dateWorkStart?></label>
                                        <?php }
                                        ?>
                                    </th>
                                </tr>
                            <?php
                                $count ++; }
                        }

                        ?>
                        </tbody>

                        <!-- <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="width:100px;"></th>
                            <th style="width:85px;"><?= lang("actions"); ?></th>
                        </tr>
                        </tfoot> -->
                    </table>
                </div>

            </div>

        </div>
    </div>
</div>
<?= form_close() ?>
<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>

    <script language="javascript">
        $(document).ready(function () {
            $('#set_admin').click(function () {
                $('#usr-form-btn').trigger('click');
            });

        });
    </script>

<?php } ?>