<style type="text/css">
   #UsrTable{
   width: 100% !important;
   }

   #UsrTable tbody tr:first-child{
    display: none;
   }

    #UsrTable > tbody > tr > td ul a.po{
        display: none;
    }

    #UsrTable > tbody > tr:last-child > td ul a.po{
        display: block;
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
           'sAjaxSource': '<?= site_url('auth/getPay/?userid=')?>' + id,
           'fnServerData': function (sSource, aoData, fnCallback) {
               aoData.push({
                   "name": "<?= $this->security->get_csrf_token_name() ?>",
                   "value": "<?= $this->security->get_csrf_hash() ?>"
               });
               $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
           },
           "aoColumns": [{"mRender": text_center}, {"mRender": currencyFormat}, null, {"mRender": text_center},{"mRender": text_center}, {"bSortable": false}]
           
       } ;
       var oTable = $('#UsrTable').dataTable(initParams).fnSetFilteringDelay().dtFilter([
          
       ], "footer");
   
       function search(){
           var v = $('#start_date1').val();
           var d = $('#end_date1').val();
           if(!v){
               v='';
           }
   
           var id = <?php echo $id ?>
   
           
   
   
           oTable.fnDestroy();
           initParams.sAjaxSource = '<?= site_url('auth/getPay') ?>/?start_date='+v+'&userid='+ id+'&end_date='+ d;
           oTable = $('#UsrTable').dataTable(initParams);
           // $('#action-form').attr('action',href+'/'+v)
       }
   
   
   
       $('#start_date1,#end_date1').change(function(){
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
            <span>Lịch sử tăng lương</span>
         </div>
         <div id="restTable" class="hidden">123</div>
         <div class="product_actions col-xs-12" style="padding: 0px; margin-bottom: 15px; display: inline-block; width: 100%">
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10" style="padding:0px;">
               <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0px;">
                  <div class="input-group " >
                     <div class="input-group-addon" style="padding: 2px 11px;">
                        <a  ><i class="" >Ngày bắt đầu</i></a>
                     </div>
                      <input type="text" name="start_date" class="form-control date" id="start_date1"/>
                  </div>
               </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0px;">
                  <div class="input-group " >
                     <div class="input-group-addon" style="padding: 2px 11px;">
                        <a  ><i class="" >Ngày kết thúc</i></a>
                     </div>
                     <input type="text" name="end_date" class="form-control date" id="end_date1"/>
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
                           <th class="col-xs-1"><?php echo lang('Ngày bắt đầu'); ?></th>
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