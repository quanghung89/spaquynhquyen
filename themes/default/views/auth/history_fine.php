<style type="text/css">
   #UsrTable{
   width: 100% !important;
   }
</style>
<script>
   var id = <?php echo $id ?>;
   $(document).ready(function () {
       'use strict';
       var initParams = {
           "aaSorting": [[2, "asc"], [3, "asc"]],
           "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
           "iDisplayLength": <?= $Settings->rows_per_page ?>,
           'bProcessing': true, 'bServerSide': true,
           'sAjaxSource': '<?= site_url('auth/getFines/?userid=')?>' + id,
           'fnServerData': function (sSource, aoData, fnCallback) {
               aoData.push({
                   "name": "<?= $this->security->get_csrf_token_name() ?>",
                   "value": "<?= $this->security->get_csrf_hash() ?>"
               });
               $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
           },
           "aoColumns": [{"mRender": text_center}, {"mRender": currencyFormat}, null, {"mRender": text_center},{"mRender": text_center}, null, {"bSortable": false}]
           
       } ;
       var oTable = $('#UsrTable').dataTable(initParams).fnSetFilteringDelay().dtFilter([
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
   
       function search(){
           var v = $('#month').val();
           if(!v){
               v='';
           }
   
           var id = <?php echo $id ?>
   
           
   
   
           oTable.fnDestroy();
           initParams.sAjaxSource = '<?= site_url('auth/getFines') ?>/?month='+v+'&userid='+ id;
           oTable = $('#UsrTable').dataTable(initParams);
           // $('#action-form').attr('action',href+'/'+v)
       }
   
   
   
       $('#month').change(function(){
           search();
       });
   
       $('#restTable').click(function(){
            oTablePu.fnDraw();
       })
   });
</script>
<div class="modal-dialog modal-lg">
   <div class="modal-content">
      <!-- <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
         </button>
         <h4 class="modal-title" id="myModalLabel"><?php echo lang('Cập nhật'); ?></h4>
         </div> -->
      <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'update-bouns-form');
         echo form_open_multipart("auth/update_fines/".$id, $attrib); ?>
      <div class="modal-body">
         <!-- <div class="form-group">
            <label class="control-label"
                   for="customer_group"><?php echo $this->lang->line("default_customer_group"); ?></label>
            
            <div class="controls"> <?php
               foreach ($customer_groups as $customer_group) {
                   $cgs[$customer_group->id] = $customer_group->name;
               }
               echo form_dropdown('customer_group', $cgs, $this->Settings->customer_group, 'class="form-control tip select" id="customer_group" style="width:100%;"');
               ?>
            </div>
            </div> -->
         <div class="title-menu con-xs-12" style="margin: 45px 0px;">
            <span>Lịch sử tiền phạt</span>
         </div>
         <div id="restTable" class="hidden">123</div>
         <div class="product_actions col-xs-12" style="padding: 0px; margin-bottom: 15px; display: inline-block; width: 100%">
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10" style="padding:0px;">
               <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0px;">
                  <div class="input-group " >
                     <div class="input-group-addon" style="padding: 2px 11px;">
                        <a  ><i class="fa fa-calendar" ></i></a>
                     </div>
                     <?php
                        for ($i=1; $i <=12 ; $i++) { 
                            $month[$i] = 'Tháng ' + $i; 
                        }
                        
                        echo form_dropdown('month', $month, (isset($_POST['month']) ? $_POST['month'] : '' ) , 'class="form-control select" multiple id="month" placeholder="' . lang("Tháng") . " " . lang() . '" style="width:100%"');
                        
                        ?>
                  </div>
               </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0px;">
                  <div class="input-group " >
                     <div class="input-group-addon" style="padding: 2px 11px;">
                        <a  ><i class="" >Năm</i></a>
                     </div>
                     <input type="text" name="year" class="form-control" id="year"/>
                  </div>

               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-lg-12">
               <!-- <p class="introtext"><?= lang('list_results'); ?></p> -->
               <div class="table-responsive">
                  <table id="UsrTable" cellpadding="0" cellspacing="0" border="0"
                     class="table table-bordered table-hover table-striped">
                     <thead>
                        <tr>
                           <th class="col-xs-1"><?php echo lang('STT'); ?></th>
                           <th class="col-xs-2"><?php echo lang('Số tiền'); ?></th>
                           <th class="col-xs-2">Ghi chú</th>
                           <th class="col-xs-1"><?php echo lang('Tháng'); ?></th>
                           <th class="col-xs-2"><?php echo lang('Năm'); ?></th>
                           <th class="col-xs-2"><?php echo lang('Người tạo'); ?></th>
                           <th class="col-xs-2"><?php echo lang('actions'); ?></th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td colspan="99" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
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
      <!-- <div class="modal-footer " style=" display: inline-block; width: 100%;">
         <?php echo form_submit('add', lang('Thêm'), 'class="btn btn-warning"'); ?>
      </div> -->
   </div>
   <?php echo form_close(); ?>
</div>
<script type="text/javascript">
   $(document).ready(function(){
       $("#month").select2({
           tokenSeparators: [","],
       
   });
   })
   
</script>