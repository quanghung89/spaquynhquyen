<style type="text/css">
    #BookData th {
        width: auto !important;
        padding: 5px 10px;
    }

    #BookData th.width10{
        width: 10% !important;
    }

    .table-responsive{
        width: 150%;
    }
</style>




<script>
    $(document).ready(function () {
        function ifld(x){

            var m = new Date(x*1000)
            var year = m.getFullYear();
            var month = m.getMonth() + 1;
            if(month < 10){
                month = '0'+month;
            }
            var day = m.getDay();
            if(day < 10){
                day = '0'+day;
            }

            min =  m.getMinutes();
            if(min < 10){
                min = '0' + min;
            }

            var time = (m.getHours() + 1) + ':' + min ;

            if (m != null) {
               
                if (site.dateFormats.js_sdate == 'dd-mm-yyyy')
                    return day + "-" + month + "-" + year + " " + time;
                else if (site.dateFormats.js_sdate === 'dd/mm/yyyy')
                    return day + "/" + month + "/" + year + " " + time;
                else if (site.dateFormats.js_sdate == 'dd.mm.yyyy')
                    return day + "." + month + "." + year + " " + time;
                else if (site.dateFormats.js_sdate == 'mm/dd/yyyy')
                    return month + "/" + day + "/" + year + " " + time;
                else if (site.dateFormats.js_sdate == 'mm-dd-yyyy')
                    return month + "-" + day + "-" + year + " " + time;
                else if (site.dateFormats.js_sdate == 'mm.dd.yyyy')
                    return month + "." + day + "." + year + " " + time;
                else
                    return x;
            } else {
                return '';
            }
        }
        var initParams = {            
            "aaSorting": [[1, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('customers/getBooks') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },'fnRowCallback': function (nRow, aData, iDisplayIndex) {
             
                var oSettings = oTableC.fnSettings();
                console.log()

                nRow.id = aData[0];
                // nRow.className = "customers_link";
                if(aData[10] > aData[9]){ nRow.className = "book_link danger"; } else { nRow.className = "book_link"; }
                return nRow;
            },

            "aoColumns": [
            {
                "bSortable": false,
                "mRender": text_center
            },{
                "bSortable": false,
                "mRender": checkbox
            },null, null,null,null,
            null, null,null, {"bSortable": false, "mRender": ifld},{"bSortable": false, "mRender": ifld},null,null,
            {"bSortable": false, "mRender": currencyFormat}, {
                "bSortable": false,
                "mRender": text_center
            }, {"bSortable": false}]
        }


        var oTableC = $('#BookData').dataTable(initParams).dtFilter([            
            // {column_number: 3, filter_default_label: "[<?=lang('Tên');?>]", filter_type: "text", data: []},
            // {column_number: 4, filter_default_label: "[<?=lang('Số điện thoại');?>]", filter_type: "text", data: []},
            // {column_number: 5, filter_default_label: "[<?=lang('Lịch sử');?>]", filter_type: "text", data: []},
            
        ], "footer");

        function search(){
            var v = $('#warehouse').val();
            if(!v){
                v='';
            }


            var b = $('#disable').val();
            if(!b){
                b='';
            }

            var c = $('#start_date').val();

            if(!c){
                c='';
            }

            var d = $('#end_date').val();

            if(!d){
                d='';
            }
            oTableC.fnDestroy();
            initParams.sAjaxSource = '<?= site_url('customers/getBooks') ?>/?warehouse_id='+v+'&disable='+b+'&start_date='+c+'&end_date='+d;
            oTableC = $('#BookData').dataTable(initParams);
        }



        $('#warehouse,#disable,#start_date,#end_date').change(function(){
            search();
        });

        $('#btnRestTableC').click(function(){
            oTableC.fnDraw();
            // $('#BookData').data.reload();
        })
                
          
    });
</script>

<div class="box" style="border:0px;">
    <div class="title-menu con-xs-12" style="margin: 45px 0px;">
         <span>Danh sách book lịch</span>
    </div>
    <?php  echo form_open('customers/list_customer', 'id="action-form"'); ?>
    <div class="product_actions col-xs-12" style="padding: 0px; margin-bottom: 15px;">
       <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2" style="padding-left: 0px;">
               <?php
                $cat = array(
                    '' => '',
                    'delete' => 'Xóa',
                );
                
                echo form_dropdown('category', $cat, (isset($_POST['category']) ? $_POST['category'] : ($product ? $product->category_id : '')), 'class="form-control select" id="category" placeholder="' . lang("") . " " . lang("Tác vụ") . '" required="required" style="width:100%"')
                ?>
                </div>
                
               
                
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2" style="padding-left: 0px;">
                    <?php echo form_submit('submit_form', $this->lang->line("Apply"), 'class="btn-warning default  btn"'); ?>
                </div>


                 <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2" style="padding-left: 0px;">
                    <?php
                    if($Owner || $Admin){

                        $wh[''] = "";
                        $wh['all'] = "Tất cả";

                        foreach ($warehouses as $whv) {
                            $wh[$whv->id] = $whv->name;
                        }

                        echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ($product ? $product->category_id : '') ) , 'class="form-control select" id="warehouse" placeholder="' . lang("select") . " " . lang("kho") . '" style="width:100%"');
                    
                     }else{
                        
                        $wh[''] = "";
                        $wh['all'] = "Tất cả";

                        foreach ($warehouse as $whv) {
                            $wh[$whv->id] = $whv->name;
                        }

                        echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ($product ? $product->category_id : '') ) , 'class="form-control select" id="warehouse" placeholder="' . lang("select") . " " . lang("kho") . '" style="width:100%"');
                     }?>
                </div>

                 <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2" style="padding-left: 0px;">
                    <?php
                    
                        $wh1['']    = "";
                        $wh1['all'] = "Tất cả";
                        $wh1['0']   = "Chưa hoàn thành";
                        $wh1['2']   = "Hủy";                        
                        $wh1['1']   = "Hoàn thành";

                        

                        echo form_dropdown('disable', $wh1, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ($product ? $product->category_id : '') ) , 'class="form-control select" id="disable" placeholder="' . lang("Trạng thái",'',[]) . " " . lang('','',[]) . '" style="width:100%"');
                    
                     ?>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2" style="padding-left: 0px;">
                    <div class="form-group">
                        <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control date" placeholder="Từ ngày" id="start_date"'); ?>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2" style="padding-left: 0px;">
                    <div class="form-group">
                        <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control date" placeholder="Đến ngày" id="end_date"'); ?>
                    </div>
                </div>
            
              
            
       </div> 
       <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 text-right">
           
           <a href="<?php echo site_url('customers/book') ?>" class="btn btn-warning default "><?= lang('Thêm book lịch') ?></a>
           

       </div> 
    </div>
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

    
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12" style="overflow: scroll;">
                <a class="btn btn-primary hidden" id="btnRestTableC">12345</a>
                <!-- <p class="introtext"><?= lang('list_results'); ?></p> -->
               <!--  <div class="col-lg-12" style="padding:0px;margin-bottom: 15px;">
                    <a style="border-radius: 7px !important" class="btn pull-right btn-warning" data-toggle="modal" data-target="#myModal" href="<?php echo site_url('customers/add') ?>">Thêm khách hàng</a>
                </div> -->
                <div class="table-responsive">
                    <table id="BookData" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-condensed table-hover table-striped">
                        <thead>
                        <tr class="primary">
                            <th>STT</th>

                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
                            </th>
                            <th class="width10"><?= lang("Tên khách hàng"); ?></th>
                            <th><?= lang("Số điện thoại"); ?></th>
                            <th><?= lang("Lịch sử"); ?></th>
                            <th><?= lang("Dịch vụ"); ?></th>
                            <th>Dịch vụ thêm</th>
                            <th><?= lang("Cơ sở"); ?></th>                            
                            <th><?= lang("Giờ làm"); ?></th>
                            <th><?= lang("Giờ kết thúc dự kiến"); ?></th>                            
                            <th><?= lang("Giờ kết thúc thực tế"); ?></th>                            
                            <th><?= lang("Nhân viên"); ?></th>                            
                            <th><?= lang("Nhân viên theo dõi"); ?></th>
                            <th><?= lang("Số tiền"); ?></th>
                            <th><?= lang("Trạng thái"); ?></th>                            
                            <th style="width:85px;"><?= lang("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="99" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>
                        </tbody>
                        <!-- <tfoot class="dtFilter">
                        <tr class="active">
                            <th></th>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="width:85px;" class="text-center"><?= lang("actions"); ?></th>
                        </tr>
                        </tfoot> -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= form_close() ?>

<?php if ($action && $action == 'add') {
    echo '<script>$(document).ready(function(){$("#add").trigger("click");});</script>';
}
?>
	

