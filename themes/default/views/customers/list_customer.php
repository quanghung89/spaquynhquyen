<style type="text/css">
    #CusData th {
        width: auto !important;
        padding: 5px 10px;
    }

    #CusData th.width10{
        width: 10% !important;
    }
</style>

<?php
$v = "";
if ($this->input->post('submit_form')) {    
    if ($this->input->post('customer_groups_id')) {
        $v .= "&cgroups=" . $this->input->post('customer_groups_id');
    }   
}
?>


<script>
    $(document).ready(function () {
        function history(x){
           
            return '<div class="text-center"><a data-target="#myModal" data-toggle="modal" href="customers/history_cate/'+ x +'">'+ 'Nhấp vào xem lịch sử' +'</a></div>';
           
        }

        var oTableC = $('#CusData').dataTable({            
            "aaSorting": [[1, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('customers/getCustomers?v=1' . $v) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTableC.fnSettings();
                nRow.id = aData[0];
                nRow.className = "customers_link";
                //if(aData[7] > aData[9]){ nRow.className = "product_link warning"; } else { nRow.className = "product_link"; }
                return nRow;
            },

            "aoColumns": [
            {
                "bSortable": false,
                "mRender": text_center
            },{
                "bSortable": false,
                "mRender": checkbox
            } ,
            {
                "bSortable": false,
                "mRender": img_hl
            }, null,
            
            null,
            {
                "bSortable": false,
                "mRender": history
            },
            {
                "bSortable": false,
                "mRender": currencyFormat
            }, null, {"bSortable": false}]
        }).dtFilter([            
            {column_number: 3, filter_default_label: "[<?=lang('Tên');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('Số điện thoại');?>]", filter_type: "text", data: []},
            
        ], "footer");



        $('#btnRestTableC').click(function(){
            oTableC.fnDraw();
            // $('#CusData').data.reload();
        })
                
          
    });
</script>

<div class="box" style="border:0px;">
    <div class="title-menu con-xs-12" style="margin: 45px 0px;">
         <span>Quản lý hoạt động khách hàng</span>
    </div>
    <?php  echo form_open('customers/list_customer', 'id="action-form"'); ?>
    <div class="customer_actions col-xs-12" style="padding: 0px; margin-bottom: 15px;">
       <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
           <span>TÙY CHỌN:</span>
           <?php foreach ($customer_groups as $key => $value) { ?>
                <?php if($value->id != 1){ ?>
                    <span style="margin-left: 5px;"><input type="radio" id="<?php echo $value->id ?>" name="customer_groups_id" value="<?php echo $value->id ?>">
                    <label style="font-weight: normal;" for="<?php echo $value->id ?>" ><?php echo $value->name ?></label></span>
                <?php } ?>            
           <?php } ?>
       </div> 
       <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 text-right">
           <a href="#" id="excel" class="btn btn-warning br7c000" data-action="export_excel"><?= lang('export_to_excel') ?></a>
           <?php echo form_submit('submit_form', $this->lang->line("Apply"), 'class="btn-warning btn br7c000"'); ?>
       </div> 
    </div>
    <?= form_close() ?>
    <!-- <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('customers'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip"
                                                                                  data-placement="left"
                                                                                  title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?= site_url('customers/add'); ?>" data-toggle="modal" data-target="#myModal"
                               id="add"><i class="fa fa-plus-circle"></i> <?= lang("add_customer"); ?></a></li>
                        <li><a href="<?= site_url('customers/import_csv'); ?>" data-toggle="modal"
                               data-target="#myModal"><i class="fa fa-plus-circle"></i> <?= lang("import_by_csv"); ?>
                            </a></li>
                        <?php if ($Owner) { ?>
                            <li><a href="#" id="excel" data-action="export_excel"><i
                                        class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
                            <li><a href="#" id="pdf" data-action="export_pdf"><i
                                        class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
                            <li class="divider"></li>
                            <li><a href="#" class="bpo" title="<b><?= $this->lang->line("delete_customers") ?></b>"
                                   data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>"
                                   data-html="true" data-placement="left"><i
                                        class="fa fa-trash-o"></i> <?= lang('delete_customers') ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div> -->

    <?php if ($Owner) {
        echo form_open('customers/customer_actions', 'id="action-form"');
    } ?>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <a class="btn btn-primary hidden" id="btnRestTableC">12345</a>
                <!-- <p class="introtext"><?= lang('list_results'); ?></p> -->
               <!--  <div class="col-lg-12" style="padding:0px;margin-bottom: 15px;">
                    <a style="border-radius: 7px !important" class="btn pull-right btn-warning" data-toggle="modal" data-target="#myModal" href="<?php echo site_url('customers/add') ?>">Thêm khách hàng</a>
                </div> -->
                <div class="table-responsive">
                    <table id="CusData" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-condensed table-hover table-striped">
                        <thead>
                        <tr class="primary">
                            <th>STT</th>

                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
                            </th>
                            <th class="width10"><?= lang("Hình"); ?></th>
                            <th><?= lang("name"); ?></th>
                            <th><?= lang("Số điện thoại"); ?></th>
                            <th><?= lang("Lịch sử"); ?></th>
                            <th><?= lang("Tổng tích lũy"); ?></th>
                            <th><?= lang("Ghi chú"); ?></th>                            
                            <th style="width:85px;"><?= lang("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="5" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th></th>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th>Hình</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="width:85px;" class="text-center"><?= lang("actions"); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>
<?php } ?>
<?php if ($action && $action == 'add') {
    echo '<script>$(document).ready(function(){$("#add").trigger("click");});</script>';
}
?>
	

