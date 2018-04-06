<style type="text/css" media="screen">
    

    .btn-warning.default{
        background: #efeaea;
        border-color: #ddd;
        color: #000;
    }
   
    .modal-dialog.modal-sm{
        width: 30%;
    }
</style>
<script>
    var oTablePro;
    $(document).ready(function () {
           var initParams = {"aaSorting": [[2, "asc"], [3, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('products/getProducts') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTablePro.fnSettings();
                // nRow.id = aData[0];
                // nRow.className = "product_link";
                //if(aData[7] > aData[9]){ nRow.className = "product_link warning"; } else { nRow.className = "product_link"; }
                return nRow;
            },
            "aoColumns": [
                {"bSortable": false, "mRender": checkbox}, {
                    "bSortable": false,
                    "mRender": img_hl
                }, null, null, null, {"mRender": currencyFormat},{"mRender": text_center},null,null, {"bSortable": false}
            ]} 


            oTablePro = $('#PRData').dataTable(initParams).fnSetFilteringDelay().dtFilter([], "footer");
        
        var href = $('#action-form').attr('action');
        function search(){
            var v = $('#warehouse').val();
            if(!v){
                v='';
            }
            var b = $('#disable').val();
            if(!b){
                b='';
            }
            oTablePro.fnDestroy();
            initParams.sAjaxSource = '<?= site_url('products/getProducts') ?>/?warehouse_id='+v+'&disable='+b;
            oTablePro = $('#PRData').dataTable(initParams);
            $('#action-form').attr('action',href+'/'+v)
        }



        $('#warehouse,#disable').change(function(){
            search();
        });

        $('#restTable').click(function(){
             oTablePro.fnDraw();
        })
           
        
    });
</script>

<div class="box">
   <!--  <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-barcode"></i><?= lang('products') . ' (' . ($warehouse_id ? $warehouse->name : lang('all_warehouses')) . ')'; ?>
        </h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?= site_url('products/add') ?>"><i class="fa fa-plus-circle"></i> <?= lang('add_product') ?></a></li>
                        <li><a href="#" id="barcodeProducts" data-action="barcodes"><i class="fa fa-print"></i> <?= lang('print_barcodes') ?></a></li>
                        <li><a href="#" id="labelProducts" data-action="labels"><i class="fa fa-print"></i> <?= lang('print_labels') ?></a></li>
                        <li><a href="#" id="sync_quantity" data-action="sync_quantity"><i class="fa fa-arrows-v"></i> <?= lang('sync_quantity') ?></a></li>
                        <li><a href="#" id="excel" data-action="export_excel"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
                        <li><a href="#" id="pdf" data-action="export_pdf"><i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
                        <li class="divider"></li>
                        <li><a href="#" class="bpo" title="<b><?= $this->lang->line("delete_products") ?></b>"
                               data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>" data-html="true" data-placement="left"><i class="fa fa-trash-o"></i> <?= lang('delete_products') ?></a></li>
                    </ul>
                </li>
                <?php if (!empty($warehouses)) { ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?= lang("warehouses") ?>"></i></a>
                        <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?= site_url('products') ?>"><i class="fa fa-building-o"></i> <?= lang('all_warehouses') ?></a></li>
                            <li class="divider"></li>
                            <?php
                            foreach ($warehouses as $warehouse) {
                                echo '<li><a href="' . site_url('products/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div> -->

    <div class="title-menu con-xs-12" style="margin: 45px 0px;">
         <span>Quản lý kho</span>
    </div>
    <div id="restTable" class="hidden">123</div>
    <?php  echo form_open('products/product_actions', 'id="action-form"'); ?>

    <div class="product_actions col-xs-12" style="padding: 0px; margin-bottom: 15px;">
       <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3" style="padding-left: 0px;">
               <?php
                $cat = array(
                    '' => '',
                    'delete' => 'Xóa',
                    'export_excel' => 'Excel',
                );
                
                echo form_dropdown('category', $cat, (isset($_POST['category']) ? $_POST['category'] : ($product ? $product->category_id : '')), 'class="form-control select" id="category" placeholder="' . lang("") . " " . lang("Tác vụ") . '" required="required" style="width:100%"')
                ?>
                </div>
                
               
                
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3" style="padding-left: 0px;">
                    <?php echo form_submit('submit_form', $this->lang->line("Apply"), 'class="btn-warning default  btn"'); ?>
                </div>
                 <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3" style="padding-left: 0px;">
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

                 <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3" style="padding-left: 0px;">
                    <?php
                    
                        $wh1['']    = "";
                        $wh1['all'] = "Tất cả";
                        $wh1['0']   = "Hiện thông báo";
                        $wh1['1']   = "Không hiện thông báo";

                        

                        echo form_dropdown('disable', $wh1, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ($product ? $product->category_id : '') ) , 'class="form-control select" id="disable" placeholder="' . lang("Trạng thái") . " " . lang() . '" style="width:100%"');
                    
                     ?>
                </div>
            
              
            
       </div> 
       <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 text-right">
           <a href="<?php echo site_url('products/add') ?>" class="btn btn-warning default "><?= lang('Thêm sản phẩm') ?></a>
           <a href="<?php echo site_url('products/add_receipt') ?>" class="btn btn-warning default "><?= lang('Nhập hàng') ?></a>
           <a href="<?php echo site_url('products/add_issue') ?>" class="btn btn-warning default "><?= lang('Xuất hàng') ?></a>
           

       </div> 
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <!-- <p class="introtext"><?= lang('list_results'); ?></p> -->

                <div class="table-responsive">
                    <table id="PRData" class="table table-bordered table-condensed table-hover table-striped">
                        <thead>
                        <tr class="primary">
                            <th class="width3" style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkth " type="checkbox" name="check"/>
                            </th>
                            <th class="width9" style="min-width:40px; width: 40px; text-align: center;"><?php echo $this->lang->line("image"); ?></th>                           
                            <th><?= lang("product_name") ?></th>
                            <th class="width15"><?= lang("product_code") ?></th>
                            <th class="width7"><?= lang("Tình trạng") ?></th>
                            <th class="width15"><?= lang("Giá") ?></th> 
                            <th class="width7"><?= lang("product_unit") ?></th>
                            <th><?= lang("Ngày") ?></th>
                            <th class="width7"><?= lang("Thông báo hết hàng") ?></th>
                            <th class="width9" style="min-width:65px;max-width: 80px; text-align:center;"><?= lang("actions") ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="99" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                        </tr>
                        </tbody>

                      <!--   <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th style="min-width:40px; width: 40px; text-align: center;"><?php echo $this->lang->line("image"); ?></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="width:65px; text-align:center;"><?= lang("actions") ?></th>
                        </tr>
                        </tfoot> -->
                    </table>
                </div>
            </div>
        </div>
    </div>
                <?= form_close() ?>

</div>
<?php if ($Owner) {
    echo form_open('products/product_actions', 'id="action-form"');
} ?>
<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>
<?php } ?>
<script type="text/javascript">
    
    $(document).ready(function(){

        $(document).on('submit','#action-form',function(e){
            url = $(this).attr('action');
            if($('#category').val() == 'delete'){
                 $.ajax({
                   type: "POST",
                   url: url,
                   data: $("#action-form").serialize(), // serializes the form's elements.
                   success: function(data)
                   {
                        bootbox.alert(data);
                        $('#restTable').click();
                   }
                 });

                e.preventDefault(); 
            }
        })


        $(document).on('click','.clickdisable',function(e){
            url = $(this).attr('href');
            if(url){
                $.ajax({
                   type: "POST",
                   url: url,
                   dataType:"JSON",
                   
                   success: function(data)
                   {
                        alert(data.msg);
                        $('#restTable').click();
                   }
                 });
            
            }
            e.preventDefault();
        })

    })

    
</script>

