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

        };

        var oTable = $('#luongTable1').dataTable(initParams).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('last_name');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('email_address');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('cơ sở');?>]", filter_type: "text", data: []},

            {column_number: 4, filter_default_label: "[<?=lang('group');?>]", filter_type: "text", data: []},
            {
                column_number: 5, select_type: 'select2',
                select_type_options: {
                    placeholder: '<?=lang('status');?>',
                    width: '100%',
                    minimumResultsForSearch: -1,
                    allowClear: true
                },
                data: [{value: '1', label: '<?=lang('active');?>'}, {value: '0', label: '<?=lang('inactive');?>'}]
            }
        ], "footer");
    });
</script>

<?php //var_dump($users);die; ?>

<div class="col-xs-12 user sales">
    <div class="title-menu con-xs-12" style="margin: 45px 0px;">
        <span>LƯƠNG</span>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table id="luongTable" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;"><?php echo lang('code'); ?></th>
                            <th class="col-xs-2" style="width:100px;"><?php echo lang('full_name'); ?></th>
                            <th class="" style="width:100px;">Lương công</th>
                            <th class="col-xs-2" style="width:40px;">Công</th>
                            <th class="col-xs-1" style="width:40px;">Ngày nghỉ</th>
                            <th style="width:40px;">Thưởng</th>
                            <th style="width:40px;">Phạt</th>
                            <th style="width:40px;">Ăn trưa</th>
                            <th style="width:40px;">Được book</th>
                            <th style="width:40px;">Doanh thu</th>
                            <th style="width:40px;">Tổng</th>
                            <th style="width:40px;">Ghi chú</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="hide">
                            <td colspan="99" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>
                        <?php $j = 1;
                        foreach ($users as $user) { ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $user['last_name']; ?></td>
                                <td><?php echo $this->sma->formatMoney($user['detail']['totalPay']); ?></td>
                                <td><?php echo($user['detail']['totalDayWo'] - $user['detail']['totalDayOu']); ?></td>
                                <td><?php echo $this->sma->formatMoney($user['detail']['totalDayOu']); ?></td>
                                <td><?php echo $this->sma->formatMoney($user['detail']['totalBoun']); ?></td>
                                <td><?php echo $this->sma->formatMoney($user['detail']['totalFine']); ?></td>
                                <td><?php echo $this->sma->formatMoney($user['detail']['totalOtex']); ?></td>
                                <td><?php echo $this->sma->formatMoney($user['detail']['book']); ?></td>
                                <td><?php echo $this->sma->formatMoney($user['detail']['doanhthu']); ?></td>
                                <td><?php echo $this->sma->formatMoney($user['detail']['totalAll']); ?></td>
                                <td><?php echo $user['detail']['note']; ?></td>
                            </tr>
                            <?php $j++;
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <form id="tinh_luong" method="post" action="<?php echo base_url(); ?>statistics/luong">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-2">
                    <?php
                    $month = array(
                        '' => '',
                        '01' => 'Tháng 1',
                        '02' => 'Tháng 2',
                        '03' => 'Tháng 3',
                        '04' => 'Tháng 4',
                        '05' => 'Tháng 5',
                        '06' => 'Tháng 6',
                        '07' => 'Tháng 7',
                        '08' => 'Tháng 8',
                        '09' => 'Tháng 9',
                        '10' => 'Tháng 10',
                        '11' => 'Tháng 11',
                        '12' => 'Tháng 12',
                    );

                    echo form_dropdown('month', $month, (isset($_POST['month']) ? $_POST['month'] : date('m')), 'class="form-control select" id="month" placeholder="' . lang("") . " " . lang("Chọn tháng") . '" required="required" style="width:100%"')
                    ?>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="padding-left: 0;">
                    <?php echo form_input('year', (isset($_POST['year']) ? $_POST['year'] : date('Y')), 'class="input form-control" id="year" placeholder="' . lang("") . " " . lang("Năm") . '"'); ?>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="padding-left: 0px;">
                    <?php echo form_submit('submit_form', $this->lang->line("Apply"), 'class="btn-warning default  btn" id="submit_tinhluong"'); ?>
                </div>
            </form>
        </div>
    </div>
</div>
