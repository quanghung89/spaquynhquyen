<script>
    $(document).ready(function () {
        var initParams = {
            "aaSorting": [[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('purchases/getPurchases' . ($warehouse_id ? '/' . $warehouse_id : '')) ?>',
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
            }, {"mRender": fld}, null,null,null, {"mRender": currencyFormat}, {"bSortable": false}],
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTablePu.fnSettings();
                nRow.id = aData[0];
                nRow.className = "purchase_link";
                return nRow;
            },
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                
                // var total = 0, paid = 0, balance = 0;
                // for (var i = 0; i < aaData.length; i++) {
                //     total += parseFloat(aaData[aiDisplay[i]][3]);
                //     // paid += parseFloat(aaData[aiDisplay[i]][6]);
                //     // balance += parseFloat(aaData[aiDisplay[i]][7]);
                // }
                // var nCells = nRow.getElementsByTagName('th');
                // nCells[3].innerHTML = currencyFormat(total);
                // // nCells[6].innerHTML = currencyFormat(paid);
                // // nCells[7].innerHTML = currencyFormat(balance);
            }
        } ;
        var oTablePu = $('#POData').dataTable(initParams).fnSetFilteringDelay().dtFilter([
            // {column_number: 1, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
            // {column_number: 2, filter_default_label: "[<?=lang('ref_no');?>]", filter_type: "text", data: []},
           
        ], "footer");

        function search(){
            var v = $('#warehouse').val();
            if(!v){
                v='';
            }

            var b = $('#user').val();

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


            oTablePu.fnDestroy();
            initParams.sAjaxSource = '<?= site_url('purchases/getPurchases') ?>/?warehouse_id='+v+'&user_id='+b+'&start_date='+c+'&end_date='+d;
            oTablePu = $('#POData').dataTable(initParams);
            // $('#action-form').attr('action',href+'/'+v)
        }



        $('#warehouse,#user,#start_date,#end_date').change(function(){
            search();
        });

        $('#restTable').click(function(){
             oTablePu.fnDraw();
        })

        <?php if($this->session->userdata('remove_pols')) { ?>
        if (localStorage.getItem('poitems')) {
            localStorage.removeItem('poitems');
        }
        if (localStorage.getItem('podiscount')) {
            localStorage.removeItem('podiscount');
        }
        if (localStorage.getItem('potax2')) {
            localStorage.removeItem('potax2');
        }
        if (localStorage.getItem('poshipping')) {
            localStorage.removeItem('poshipping');
        }
        if (localStorage.getItem('poref')) {
            localStorage.removeItem('poref');
        }
        if (localStorage.getItem('powarehouse')) {
            localStorage.removeItem('powarehouse');
        }
        if (localStorage.getItem('ponote')) {
            localStorage.removeItem('ponote');
        }
        if (localStorage.getItem('posupplier')) {
            localStorage.removeItem('posupplier');
        }
        if (localStorage.getItem('pocurrency')) {
            localStorage.removeItem('pocurrency');
        }
        if (localStorage.getItem('poextras')) {
            localStorage.removeItem('poextras');
        }
        if (localStorage.getItem('podate')) {
            localStorage.removeItem('podate');
        }
        if (localStorage.getItem('postatus')) {
            localStorage.removeItem('postatus');
        }
        <?php $this->sma->unset_data('remove_pols'); } ?>
    });

</script>


<div class="box">
   <!--  <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-star"></i><?= lang('purchases') . ' (' . ($warehouse_id ? $warehouse->name : lang('all_warehouses')) . ')'; ?>
        </h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?= site_url('purchases/add') ?>"><i class="fa fa-plus-circle"></i> <?= lang('add_purchase') ?></a></li>
                        <li><a href="#" id="excel" data-action="export_excel"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
                        <li><a href="#" id="pdf" data-action="export_pdf"><i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
                        <li class="divider"></li>
                        <li><a href="#" class="bpo" title="<b><?= $this->lang->line("delete_purchases") ?></b>" data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>" data-html="true" data-placement="left"><i class="fa fa-trash-o"></i> <?= lang('delete_purchases') ?></a></li>
                    </ul>
                </li>
                <?php if (!empty($warehouses)) { ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?= lang("warehouses") ?>"></i></a>
                        <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?= site_url('purchases') ?>"><i class="fa fa-building-o"></i> <?= lang('all_warehouses') ?></a></li>
                            <li class="divider"></li>
                            <?php
                            foreach ($warehouses as $warehouse) {
                                echo '<li ' . ($warehouse_id && $warehouse_id == $warehouse->id ? 'class="active"' : '') . '><a href="' . site_url('purchases/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div> -->
     <div class="title-menu con-xs-12" style="margin: 45px 0px;">
         <span>Danh sách nhập hàng</span>
    </div>
    <div id="restTable" class="hidden">123</div>
    <?php  echo form_open('purchases/purchase_actions', 'id="action-form"'); ?>

    <div class="product_actions col-xs-12" style="padding: 0px; margin-bottom: 15px;">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2" style="padding-left: 0px;">
               <?php
                $cat = array(
                    '' => '',
                    'delete' => 'Xóa',
                    'export_excel' => 'Excel',
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

                        echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : '' ) , 'class="form-control select" id="warehouse" placeholder="' . lang("select") . " " . lang("kho") . '" style="width:100%"');
                     }?>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2" style="padding-left: 0px;">
                    <?php
                    
                        $wh1['']    = "";
                        $wh1['all']    = "Tất cả";
                        foreach ($users as $user) {

                            $wh1[$user['id']] = $user['first_name'].' '.$user['last_name'];
                        }


                        echo form_dropdown('user', $wh1, (isset($_POST['user']) ? $_POST['user'] : '' ) , 'class="form-control select" id="user" placeholder="' . lang("Người tạo") . " " . lang() . '" style="width:100%"');
                    
                     ?>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2">
                    <div class="form-group">
                        <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control date" placeholder="Từ ngày" id="start_date"'); ?>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-2">
                    <div class="form-group">
                        <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control date" placeholder="Đến ngày" id="end_date"'); ?>
                    </div>
                </div>
            
              
            
       </div>
       <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 text-right">
          
           <a href="<?php echo site_url('products/add_receipt') ?>" class="btn btn-warning default "><?= lang('Nhập hàng') ?></a>


       </div> 

    </div> 
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <!-- <p class="introtext"><?= lang('list_results'); ?></p> -->

                <div class="table-responsive">
                    <table id="POData" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th><?php echo $this->lang->line("date"); ?></th>
                            <th><?php echo $this->lang->line("ref_no"); ?></th>
                            <th>Kho</th>
                            <th>Người tạo</th>
                            <!-- <th><?php echo $this->lang->line("supplier"); ?></th>
                            <th><?php echo $this->lang->line("purchase_status"); ?></th> -->
                            <th><?php echo $this->lang->line("grand_total"); ?></th>
                           <!--  <th><?php echo $this->lang->line("paid"); ?></th>
                            <th><?php echo $this->lang->line("balance"); ?></th>
                            <th><?php echo $this->lang->line("payment_status"); ?></th> -->
                            <th style="width:100px;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="99" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                        </tr>
                        </tbody>
                        <!-- <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                           <th></th> -->
                            <!-- <th></th>
                            <th><?php echo $this->lang->line("grand_total"); ?></th>
                            <th><?php echo $this->lang->line("paid"); ?></th>
                            <th><?php echo $this->lang->line("balance"); ?></th>
                            <th></th>
                            <th style="width:100px; text-align: center;"><?php echo $this->lang->line("actions"); ?></th>
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
<?php } ?>