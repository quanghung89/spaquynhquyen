<style type="text/css">
    .messge.erros{
        color: red;
        font-size: 12px;
    }

    .error{
        border: 1px solid red !important;
    }


    .disabled{
        pointer-events: none;
    }

    .input-group .select2-search-choice-close{
        display: none;
    }
    .select2-container-multi .select2-choices .select2-search-choice{
            padding: 6px 6px 6px 6px;
    }
</style>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_customer'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
        echo form_open_multipart("customers/add", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

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

            <div class="row">
                <div class="col-md-6">
                   
                    <div class="form-group person">
                        <?= lang("name", "name"); ?>
                        <?php echo form_input('name', '', 'class="form-control tip" id="name" required'); ?>
                        
                    </div>
                   
                   
                    <div class="form-group">
                        <?= lang("email_address", "email_address"); ?>
                        <input type="email" name="email" class="form-control " required id="email_address"/>
                        
                    </div>
                    <div class="form-group">
                        <?= lang("phone", "phone"); ?>
                        <div class="input-group" style="width: 100%">
                            <?php echo form_input('phone', '', 'class="form-control select-tags" id="attributesInput" required'); ?>
                            <div class="input-group-addon hidden" style="padding: 2px 5px;">
                                <a href="#" id="addAttributes">
                                    <i class="fa fa-2x fa-plus-circle" id="addIcon"></i>
                                </a>
                            </div>
                        </div>
                        <div class="messge"></div>
                        <div class="phonenumber hidden"></div>
                    </div>

                    <div class="form-group">
                        <?= lang("address", "address"); ?>
                        <?php echo form_input('address', '', 'class="form-control" id="address"'); ?>
                    </div>
                    

                </div>

                <div class="col-md-6">
                   
                     <div class="form-group all imgcs">
                        <?= lang("Hình ảnh", "image") ?>
                        <input onchange="putImage()" id="image" type="file" name="image" data-show-upload="false" data-show-preview="false" class="form-control file">

                        <img style="max-width: 200px; height: auto ; display: block; margin: 0 auto;margin-top: 20px;" id="target" />
                    </div>
                    

                </div>

                
                
            </div>


        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_customer', lang('add_customer'), 'class="btn btn-warning" disabled'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">

    function showImage(src, target) {
            var fr = new FileReader();
            fr.onload = function(){
              target.src = fr.result;
            }
            if(src.files[0]){
                fr.readAsDataURL(src.files[0]);
            }

        }
        function putImage() {
            var src = document.getElementById("image");
            var target = document.getElementById("target");
            showImage(src, target);
        }

    $(document).on('click','.imgcs .fileinput-remove.fileinput-remove-button',function(){
        $('#target').attr('src','');
    })

    $("#add-customer-form").submit(function(e) {
            var this1 = $(this);
            var url = $(this).attr('action'); // the script where you handle the form input.
            var formData = new FormData(this);
            $.ajax({
                   type: "POST",

                   url: url,
                   dataType:"JSON",
                   data: formData, // serializes the form's elements.
                   async: false,
                   cache: false,
                    contentType: false,
                    processData: false,
                   success: function(data)
                   {
                       $(this1).parents('.modal.fade').modal('hide');
                       bootbox.alert(data.msg);
                       setTimeout(function(){ $('.bootbox.modal.fade.bootbox-alert').modal('hide');  }, 5000);
                   }
                 });
            if($('#CusData').length != 0){
                $('#btnRestTableC').click()
            }
            e.preventDefault(); // avoid to execute the actual submit of the form.
        });



    $(document).ready(function (e) {
        init_img();
        $('#add-customer-form').bootstrapValidator({
            feedbackIcons: {
                valid: 'fa fa-check',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            }, excluded: [':disabled']
        });



        var variants = <?=json_encode($vars);?>;

        $(".select-tags").select2({
            tags: variants,
            tokenSeparators: [","],
            multiple: true
        });

        

        

        $(document).on('keyup','.input-group .select2-input',function(){
            var v = $(this).val();
            var this1 = $(this);
            if(v){
                $.ajax({
                   type: "POST",
                   url: 'customers/check_error',
                   dataType:"JSON",
                   data: {
                        'valphone': v,
                   }, // serializes the form's elements.
                   success: function(data)
                   {
                        if(!data.success){
                           // $(this1).parents('.input-group').next().addClass('erros');
                           // $(this1).parents('.input-group').next().empty().html('* '+data.msg);
                           $(this1).parents('.input-group').nextAll('.phonenumber').empty().html(data.number);
                           
                        }
                        // else{
                        //    $(this1).parents('.input-group').next().removeClass('erros');
                        //    $(this1).parents('.input-group').next().empty().html();
                        //    $(this1).parents('.input-group').nextAll('.phonenumber').empty().html();
                        // }
                   }
                 });
            
            }

            $.each($('.input-group .select2-search-choice'), function( key, value ) {
                if($(value).find('div').html() == $('.phonenumber.hidden').html()){
                    $(value).addClass('error');
                }
            });


            if($('.input-group .select2-search-choice.error').length == 0){
                $('input[name="add_customer"]').removeClass('disabled');

            }else{
                $('input[name="add_customer"]').addClass('disabled');

            }
            // var flag = true;
            // $.each($('.input-group .select2-search-choice'), function( key, value ) {
            //     if($(value).find('div').html() != $('.phonenumber.hidden').html()){
            //         flag = true;
            //     }else{
            //         flag = false;
            //         return true;
            //     }
            // });

            // if (flag == true) {
            //     $(this1).parents('.input-group').next().removeClass('erros');
            //     $(this1).parents('.input-group').next().empty().html();
            //     $(this1).parents('.input-group').nextAll('.phonenumber').empty().html();
            // }
           

        })

        

        $('select.select').select2({minimumResultsForSearch: 6});
        fields = $('.modal-content').find('.form-control');
        $.each(fields, function () {
            var id = $(this).attr('id');
            var iname = $(this).attr('name');
            var iid = '#' + id;
            if (!!$(this).attr('data-bv-notempty') || !!$(this).attr('required')) {
                $("label[for='" + id + "']").append(' *');
                $(document).on('change', iid, function () {
                    $('form[data-toggle="validator"]').bootstrapValidator('revalidateField', iname);
                });
            }
        });
    });
</script>
