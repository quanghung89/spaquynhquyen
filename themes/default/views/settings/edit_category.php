<script type="text/javascript">
    $("#edit-form-category").submit(function(e) {
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
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_category'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id'=> 'edit-form-category');
        echo form_open_multipart("system_settings/edit_category/", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('update_info'); ?></p>

            <div class="form-group">
                <?php echo lang('category_code', 'code'); ?>
                <div class="controls">
                    <?php echo form_input($code); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo lang('category_name', 'name'); ?>
                <div class="controls">
                    <?php echo form_input($name); ?>
                </div>
            </div>
            <?php if($inv->id_parent){ ?>
             <div class="form-group">
                <?php echo lang('Danh mục cha', 'category_parent'); ?>
                <div class="controls">
                   <?php
                     $cate[''] = '';
                     foreach ($categories as $category) {
                         if($category->id_parent){
                           $cate[$category->id] = ' - '.$category->name.'('. $category->name_parent .')';
                         }else{
                           $cate[$category->id] = $category->name;
                         }
                     }
                     echo form_dropdown('category_parent', $cate, (isset($_POST['category_parent']) ? $_POST['category_parent'] : $inv->id_parent), 'id="category_parent" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("danh mục cha") . '" required="required" style="width:100%;" ');
                     ?>
                </div>
            </div>
            <?php } ?>

            <div class="form-group">
                <?php echo lang('Nhân viên theo dõi', 'assign'); ?>
                <div class="controls">
                   <?php
                     foreach ($staffs as $staff) {
                          $staf[$staff['id']] = $staff['last_name'];
                     }
                     
                     echo form_dropdown('assign[]', $staf, ($inv->staffs ? $inv->staffs : ''), 'id="assign" multiple class="form-control" data-placeholder="' . lang("select") . ' ' . lang("Nhân viên theo dõi") . '"  style="width:100%;" ');
                     ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo lang('Số tiền', 'price'); ?>
                <div class="controls">
                    <?php echo form_input('price', (isset($_POST['price']) ? $_POST['price'] : ($inv) ? $this->sma->formatMoney($inv->price) : '' ) , 'class="form-control input-tip formatMoney" required id="price"'); ?>
                </div>
            </div>

             <div class="form-group">
                <?php echo lang('Thời gian làm(Phút)', 'category_time'); ?>
                <div class="controls">
                    <?php echo form_input('category_time', (isset($_POST['category_time']) ? $_POST['category_time'] : ($inv) ? $inv->time : '' ), 'class="form-control input-tip" required onkeydown="validateNumber(event);" id="category_time"'); ?>
                </div>
            </div>
           <!--  <div class="form-group">
                <?= lang("category_image", "image") ?>
                <input id="image" type="file" name="userfile" data-show-upload="false" data-show-preview="false"
                       class="form-control file">
            </div> -->
            <?php echo form_hidden('id', $id); ?>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_category', lang('edit_category'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>

<script type="text/javascript">
  $(document).ready(function(){
      $("#assign").select2({
           tokenSeparators: [","],       
      });
   })
</script>