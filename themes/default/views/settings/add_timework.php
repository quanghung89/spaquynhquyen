<style type="text/css">
  .ui-timepicker-standard{
    z-index: 9999 !important;
  }

  .error{
    border: 1px solid red !important;
  }

</style>
<script type="text/javascript">
   $("#add-form-timework").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            e.stopPropagation();
            e.stopImmediatePropagation();
            var this1 = $(this);
            var url = $(this).attr('action'); // the script where you handle the form input.
            var formData = new FormData(this);
            $.ajax({
                   type: "POST",
                   url: url,
                   dataType:"JSON",
                   async: false,
                   cache: false,
                   contentType: false,
                   processData: false,
                   data: formData, // serializes the form's elements.                   
                   success: function(data)
                   {
                       $(this1).parents('.modal.fade').modal('hide');
                       bootbox.alert(data.msg);
                       setTimeout(function(){ $('.bootbox.modal.fade.bootbox-alert').modal('hide')  }, 3000);
                   }
                 });
            
            if($('#CategoryTable').length != 0){
                $('#btnRestTable').click()
            }
            
            return false; // avoid to execute the actual submit of the form.
        });
</script>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Thêm giờ làm'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form','id'=>'add-form-timework');
        echo form_open_multipart("system_settings/add_timework", $attrib); ?>
        <div class="modal-body" style="display: inline-block;">
            <p><?= lang('enter_info'); ?></p>
            <div class="col-md-6 col-xs-12 col-sm-12">
              <div class="form-group">
                  <?php echo lang('Mã', 'code'); ?>
                  <div class="controls">
                      <?php echo form_input($code); ?>
                  </div>
              </div>

              <div class="form-group">
                  <?php echo lang('Giờ bắt đầu', 'startime'); ?>
                  <div class="controls">
                      <?php echo form_input('startime', (isset($_POST['startime']) ? $_POST['startime'] : ""), 'class="form-control input-tip timepicker" onkeypress="return isNumber_time(event,this)"  id="startime" required="required"'); ?>
                  </div>
              </div>
             
            </div>

            <div class="col-md-6 col-xs-12 col-sm-12">
               <div class="form-group">
                  <?php echo lang('Tên', 'name'); ?>
                  <div class="controls">
                      <?php echo form_input($name); ?>
                  </div>
              </div>
              

              <div class="form-group">
                  <?php echo lang('Giờ kết thúc', 'endtime'); ?>
                  <div class="controls">
                      <?php echo form_input('endtime', (isset($_POST['endtime']) ? $_POST['endtime'] : ""), 'class="form-control input-tip timepicker"  onkeypress="return isNumber_time(event,this)" id="endtime" required="required"'); ?>
                  </div>
              </div>
            </div>
            <!-- <div class="form-group">
                <?php echo lang('Số tiền', 'price'); ?>
                <div class="controls">
                    <?php echo form_input($price); ?>
                </div>
            </div> -->

            <!-- <div class="form-group">
                <?= lang("category_image", "image") ?>
                <input id="image" type="file" name="userfile" data-show-upload="false" data-show-preview="false"
                       class="form-control file">
            </div> -->

        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_category', lang('Thêm'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>
<script type="text/javascript">

   


    function isNumber_time(evt,num) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57) &&charCode!=186) {

            return false;
        }

        if(num.value.length == 0 && (charCode==48 || charCode==49 || charCode==50))
        {
            return true;
        }
        else if(num.value.length == 1&&num.value==2&&(charCode>=48 && charCode<=51))
        {
            return true;
        }
        else if(num.value.length == 1&&num.value<2&&(charCode>=48 && charCode<=57))
        {
            return true;
        }
        else if(num.value.length == 2 && (charCode>=48 && charCode<=53))
        {
            num.value=num.value+":";
            return true;
        }
        else if(num.value.length == 3 && (charCode>=48 && charCode<=53))
        {
            return true;
        }
        else if(num.value.length == 4 && (charCode>=48 && charCode<=57))
        {
            return true;
        }
        else
        {
            return false;
        }

        return true;
    }
   

   
    


  $(document).ready(function(){
    
    $('.timepicker').keyup(function(){
        if($('#startime').val() != '' &&  $('#endtime').val() !=''){          
          var starttime = ($('#startime').val()).split(":");
          var endtime = ($('#endtime').val()).split(":");

          if(starttime[0] >= endtime[0]){
            $('#endtime').addClass('error');            
            if(starttime[1] < endtime[1]){
              $('#endtime').removeClass('error');
            }else{
              $('#endtime').addClass('error');
            }
          }else{
            $('#endtime').removeClass('error');
            
          }
        }

        

        if($('.error').length > 0){
          $('input[name="add_category"]').addClass('disabled');
        }else{
          $('input[name="add_category"]').removeClass('disabled');
        }
    })
  })
</script>

