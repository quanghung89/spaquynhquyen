<style type="text/css">
    .messge.erros{
        color: red;
        font-size: 12px;
    }
    .disabled{
        pointer-events: none;
    }
</style>
<?php
if (!empty($variants)) {
    foreach ($variants as $variant) {
        $vars[] = addslashes($variant->name);
    }
} else {
    $vars = array();
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        // $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({
        //     placeholder: "<?= lang('select_category_to_load') ?>", data: [
        //         {id: '', text: '<?= lang('select_category_to_load') ?>'}
        //     ]
        // });
       
        $('#code').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
<div class="box" style="border: 0px;">
    <div class="title-menu con-xs-12" style="margin: 45px 0px;">
         <span>Thêm sản phẩm</span>
    </div>
    <div class="box-content">
        <div class="row">
             <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("products/add", $attrib)
                ?>
            <div class="col-lg-12">

                <!-- <p class="introtext"><?php echo lang('enter_info'); ?></p> -->

               

                <div class="col-md-6">
                   
                    <div class="form-group all">
                        <?= lang("product_name", "name") ?>
                        <?= form_input('name', (isset($_POST['name']) ? $_POST['name'] : ($product ? $product->name : '')), 'class="form-control" id="name" required="required"'); ?>
                    </div>

                    <div class="form-group all">
                        <?= lang("product_code", "code") ?>
                        <?= form_input('code', (isset($_POST['code']) ? $_POST['code'] : ($product ? $product->code : '')), 'class="form-control" id="code"  required="required"') ?>
                        <!-- <span class="help-block"><?= lang('you_scan_your_barcode_too') ?></span> -->
                        <div class="messge"></div>
                    </div>

                    
                    
                    
                    <div class="form-group all">
                        <label class="control-label" for="unit"><?= lang("product_unit") ?></label>
                        <?= form_input('unit', (isset($_POST['unit']) ? $_POST['unit'] : ($product ? $product->unit : '')), 'class="form-control tip" id="unit" required="required"') ?>
                    </div>

                  <!--   <div class="form-group standard">
                        <?= lang("product_cost", "cost") ?>
                        <?= form_input('cost', (isset($_POST['cost']) ? $_POST['cost'] : ($product ? $this->sma->formatDecimal($product->cost) : '')), 'class="form-control tip" id="cost" required="required"') ?>
                    </div> -->

                    <div class="form-group all">
                        <?= lang("Giá", "cost") ?>
                        <?= form_input('cost', (isset($_POST['cost']) ? $_POST['cost'] : ($product ? $this->sma->formatDecimal($product->cost) : '')), 'class="form-control formatMoney tip" id="cost" required="required"') ?>
                    </div>

                    
                   

                   <!--  <div class="form-group standard">
                        <?= lang("supplier", "supplier") ?>
                        <button type="button" class="btn btn-primary btn-xs" id="addSupplier"><i class="fa fa-plus"></i>
                        </button>
                        <div class="row" id="supplier-con">
                            <div class="col-md-8 col-sm-8 col-xs-8">
                                <?php
                                echo form_input('supplier', (isset($_POST['supplier']) ? $_POST['supplier'] : ''), 'class="form-control ' . ($product ? '' : 'suppliers') . '" id="' . ($product && ! empty($product->supplier1) ? 'supplier1' : 'supplier') . '" placeholder="' . lang("select") . ' ' . lang("supplier") . '" style="width:100%;"')
                                ?></div>
                            <div
                                class="col-md-4 col-sm-4 col-xs-4"><?= form_input('supplier_price', (isset($_POST['supplier_price']) ? $_POST['supplier_price'] : ""), 'class="form-control tip" id="supplier_price" placeholder="' . lang('supplier_price') . '"') ?></div>
                        </div>
                        <div id="ex-suppliers"></div>
                    </div> -->
                     
                   

                   <!--  <div class="form-group all">
                        <?= lang("product_gallery_images", "images") ?>
                        <input id="images" type="file" name="userfile[]" multiple="true" data-show-upload="false"
                               data-show-preview="false" class="form-control file" accept="image/*">
                    </div>
                    <div id="img-details"></div> -->
                </div>
                <div class="col-md-6">
                    <div class="form-group all imgcs">
                        <?= lang("product_image", "product_image") ?>
                         <input onchange="putImage()" id="product_image" type="file" name="product_image" data-show-upload="false" data-show-preview="false" class="form-control file">
                        <img style="max-width: 200px; height: auto ; display: block; margin: 0 auto;margin-top: 20px;" id="target" src="assets/uploads/<?php echo $product->image ?>" />
                    </div>
                </div>

              
               

            </div>
             <div class="col-lg-12" style="text-align: center;">
                <?php echo form_submit('add_product', lang('add_product'), 'class="btn btn-warning" disabled'); ?>
                <input class="btn btn-warning" type='reset' value='Nhập lại'>
            </div>
             <?= form_close(); ?>
        </div>
    </div>
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
            var src = document.getElementById("product_image");
            var target = document.getElementById("target");
            showImage(src, target);
        }




    $(document).ready(function () {

        

         
        $(document).on('click','.imgcs .fileinput-remove.fileinput-remove-button,input[type="reset"]',function(){
            $('#target').attr('src','');
        })

        $(document).on('keyup','#code',function(){
            var v = $(this).val();
            var this1 = $(this);
            if(v){
                $.ajax({
                   type: "POST",
                   url: 'products/check_error',
                   dataType:"JSON",
                   data: {
                        'valcode': v,
                   }, // serializes the form's elements.
                   success: function(data)
                   {
                        if(!data.success){
                           $(this1).parents('.form-group').find('.messge').addClass('erros');
                           $(this1).parents('.form-group').find('.messge').empty().html('* '+data.msg);
                           
                        }
                        else{
                           $(this1).parents('.form-group').find('.messge').removeClass('erros');
                           $(this1).parents('.form-group').find('.messge').empty().html();
                        }
                   }
                 });
            
            }


            
        })

        $(document).on('keyup','input[type="text"]',function(){

            if($('.erros').length == 0 ){                   
                $('input[name="add_product"]').removeClass('disabled');
                $('input[name="add_product"]').removeAttr('disabled');
            }else{
                $('input[name="add_product"]').addClass('disabled');
                $('input[name="add_product"]').attr('disabled','disabled');

            }
        })
        

        var audio_success = new Audio('<?= $assets ?>sounds/sound2.mp3');
        var audio_error = new Audio('<?= $assets ?>sounds/sound3.mp3');
    });
</script>