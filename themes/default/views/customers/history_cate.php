<style type="text/css">
    @media (min-width: 992px){
        .modal-lg {
            width: 1200px;
        }
    }
       
        
</style>
<?php
    $v = '&customers='.$id_customer;
?>
<script>
    $(document).ready(function () {
        var initParams = {            
            "aaSorting": [[1, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('customers/getHistoryBook?v=1' . $v) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTableC.fnSettings();
                // nRow.id = aData[0];
                // nRow.className = "customers_link";
                //if(aData[7] > aData[9]){ nRow.className = "product_link warning"; } else { nRow.className = "product_link"; }
                return nRow;
            },

            "aoColumns": [
            {
                "bSortable": false,
                "mRender": text_center
            }, {
                "bSortable": false,
                "mRender": text_center
            },
            
            {
                "bSortable": false,
                "mRender": text_center
            },{
                "bSortable": false,
                "mRender": text_center
            },null,null,null,
           
            {
                "bSortable": false,
                "mRender": currencyFormat,
            },{
                "bSortable": false,
                "mRender": text_center
            }],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var row_total = 0, tax = 0, gtotal = 0;

                for (var i = 0; i < aaData.length; i++) {
                
                    gtotal += parseFloat(aaData[aiDisplay[i]][7]);
                }

                var nCells = nRow.getElementsByTagName('th');
             
                nCells[7].innerHTML = currencyFormat(formatMoney(gtotal));
            }
        } ;

        var oTableC = $('#ListbookData').dataTable(initParams).dtFilter([            
            {column_number: 1, filter_default_label: "[<?=lang('Dịch vụ');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('Dịch vụ thêm');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('Chi nhánh');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('Nhân viên làm');?>]", filter_type: "text", data: []},
            
        ], "footer");

        function search(){
            var v = <?php echo $id_customer ?>;

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


            oTableC.fnDestroy();
            initParams.sAjaxSource = '<?= site_url('customers/getHistoryBook') ?>/?customers='+v+'&user_id='+b+'&start_date='+c+'&end_date='+d;
            oTableC = $('#ListbookData').dataTable(initParams).dtFilter([            
            {column_number: 1, filter_default_label: "[<?=lang('Dịch vụ');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('Dịch vụ thêm');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('Chi nhánh');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('Nhân viên làm');?>]", filter_type: "text", data: []},
            
        ], "footer");
            // $('#action-form').attr('action',href+'/'+v)
        }



        $('#warehouse,#user,#start_date,#end_date').change(function(){
            search();
        });

        $('#btnRestTableC').click(function(){
            oTableC.fnDraw();
            // $('#ListbookData').data.reload();
        })
                
          
    });
</script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel">Lịch sử sử dụng dịch vụ khách hàng</h4>
        </div>
      
        <div class="modal-body">           
            <a class="btn btn-primary hidden" id="btnRestTableC">12345</a>
                <!-- <p class="introtext"><?= lang('list_results'); ?></p> -->
               <!--  <div class="col-lg-12" style="padding:0px;margin-bottom: 15px;">
                    <a style="border-radius: 7px !important" class="btn pull-right btn-warning" data-toggle="modal" data-target="#myModal" href="<?php echo site_url('customers/add') ?>">Thêm khách hàng</a>
                </div> -->
                <?php if($Owner || $Admin){ ?>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-left: 0px;">
                    <?php
                    
                        $wh1['']    = "";
                        $wh1['all']    = "Tất cả";
                        foreach ($users as $user) {

                            $wh1[$user['id']] = $user['first_name'].' '.$user['last_name'];
                        }


                        echo form_dropdown('user', $wh1, (isset($_POST['user']) ? $_POST['user'] : '' ) , 'class="form-control select" id="user" placeholder="' . lang("Nhân viên làm") . " " . lang() . '" style="width:100%"');
                    
                     ?>
                </div>
                <?php } ?>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="form-group">
                        <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control date" placeholder="Từ ngày" id="start_date"'); ?>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="form-group">
                        <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control date" placeholder="Đến ngày" id="end_date"'); ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="ListbookData" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-condensed table-hover table-striped">
                        <thead>
                        <tr class="primary">
                            <th>STT</th>                         
                            <th>Dịch vụ</th>
                            <th>Dịch vụ thêm</th>
                            <th>Chi nhánh</th>
                            <th>Nhân viên làm</th>
                            <th>Nhân viên theo dõi</th>
                            <th>Thời gian làm</th>                            
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="99" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th></th>                           
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>

        </div>
       <!--  <div class="modal-footer">
            <span class="btn btn-primary">Đóng</span>
        </div> -->
    </div>
  
</div>
<?= $modal_js ?>

