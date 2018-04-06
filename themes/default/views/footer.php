<div class="clearfix"></div>
<?= '</div></div></div></div></div>'; ?>
<div class="clearfix"></div>
<!-- <footer ><a href="#" id="toTop" class="blue"
   style="position: fixed; bottom: 30px; right: 30px; font-size: 30px; display: none;"><i
    class="fa fa-chevron-circle-up"></i></a>
   
   <p style="text-align:center;">&copy; <?= date('Y') . " " . $Settings->company_name; ?> ( www.google.com
   ) <?php if ($_SERVER["REMOTE_ADDR"] == '127.0.0.1') {
      echo ' - Page rendered in <strong>{elapsed_time}</strong> seconds';
      } ?>
   </p>
   </footer> -->
<?= '</div>'; ?>
<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div class="modal fade in" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true"></div>
<style type="text/css">
   .modal-logo-product-notes{
   position: absolute;
   top: -25px;
   right: -17px;
   }
   .alert_product {
   padding: 2px 0px;
   font-size: 15px;
   }
   #moreAlertQuantityProduct{
   color:blue; 
   text-decoration: underline;
   }
   #moreAlertQuantityProduct:hover {
   cursor: pointer;
   }
</style>

<div class="modal  fade in" id="myModalNoteProduct" tabindex="-1" role="dialog" aria-labelledby="myModalNoteProductLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
   <div class="modal-dialog ">
      <!-- Modal content-->
   </div>
</div>

<div class="modal  fade in" id="myModalNoteBook" tabindex="-1" role="dialog" aria-labelledby="myModalNoteBookLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
   <div class="modal-dialog ">
      <!-- Modal content-->
   </div>
</div>

<div class="modal  fade in" id="myModalNoteBookOutTime" tabindex="-1" role="dialog" aria-labelledby="myModalNoteBookOutTime" aria-hidden="true" data-backdrop="static" data-keyboard="false">
   <div class="modal-dialog ">
      <!-- Modal content-->
   </div>
</div>

<div id="modal-loading" style="display: none;">
   <div class="blackbg"></div>
   <div class="loader"></div>
</div>
<style type="text/css">
  a {
     color: #2a6496; 
  }

  a:hover, a:focus {
    color: #2a6496;
  }
</style>
<div id="ajaxCall"><i class="fa fa-spinner fa-pulse"></i></div>
<?php unset($Settings->setting_id, $Settings->smtp_user, $Settings->smtp_pass, $Settings->smtp_port, $Settings->update, $Settings->reg_ver, $Settings->allow_reg, $Settings->default_email, $Settings->mmode, $Settings->timezone, $Settings->restrict_calendar, $Settings->restrict_user, $Settings->auto_reg, $Settings->reg_notification, $Settings->protocol, $Settings->mailpath, $Settings->smtp_crypto, $Settings->corn, $Settings->customer_group, $Settings->envato_username, $Settings->purchase_code); ?>
<script type="text/javascript">
   var dt_lang = <?=$dt_lang?>, dp_lang = <?=$dp_lang?>, site = <?=json_encode(array('base_url' => base_url(), 'settings' => $Settings, 'dateFormats' => $dateFormats))?>;
   var lang = {paid: '<?=lang('paid');?>', pending: '<?=lang('pending');?>', completed: '<?=lang('completed');?>', ordered: '<?=lang('ordered');?>', received: '<?=lang('received');?>', partial: '<?=lang('partial');?>', sent: '<?=lang('sent');?>', r_u_sure: '<?=lang('r_u_sure');?>', due: '<?=lang('due');?>', transferring: '<?=lang('transferring');?>', active: '<?=lang('active');?>', inactive: '<?=lang('inactive');?>', unexpected_value: '<?=lang('unexpected_value');?>', select_above: '<?=lang('select_above');?>'};
</script>
<?php
   $s2_lang_file = read_file('./assets/config_dumps/s2_lang.js');
   foreach (lang('select2_lang') as $s2_key => $s2_line) {
       $s2_data[$s2_key] = str_replace(array('{', '}'), array('"+', '+"'), $s2_line);
   }
   $s2_file_date = $this->parser->parse_string($s2_lang_file, $s2_data, true);
   ?>
<script type="text/javascript" src="<?= $assets ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.dtFilter.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/select2.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.calculator.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/core.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/perfect-scrollbar.min.js"></script>

<?= ($m == 'purchases' && ($v == 'add' || $v == 'edit' || $v == 'purchase_by_csv')) ? '<script type="text/javascript" src="' . $assets . 'js/purchases.js"></script>' : ''; ?>
<?= ($m == 'transfers' && ($v == 'add' || $v == 'edit')) ? '<script type="text/javascript" src="' . $assets . 'js/transfers.js"></script>' : ''; ?>
<?= ($m == 'sales' && ($v == 'add' || $v == 'edit')) ? '<script type="text/javascript" src="' . $assets . 'js/sales.js"></script>' : ''; ?>
<?= ($m == 'quotes' && ($v == 'add' || $v == 'edit')) ? '<script type="text/javascript" src="' . $assets . 'js/quotes.js"></script>' : ''; ?>
<script type="text/javascript" charset="UTF-8">var r_u_sure = "<?=lang('r_u_sure')?>";
   <?=$s2_file_date?>
   $.extend(true, $.fn.dataTable.defaults, {"oLanguage":<?=$dt_lang?>});
   $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
   $(window).load(function () {
       $('.mm_<?=$m?>').addClass('active');
       $('.mm_<?=$m?>').find("ul").first().slideToggle();
       $('#<?=$m?>_<?=$v?>').addClass('active');
       $('.mm_<?=$m?> a .chevron').removeClass("closed").addClass("opened");
   });
   
   
   // Xử lý thông báo hàng hóa sắp hết ...
   $(document).ready(function(){

    <?php if($customerSale && $m != 'customers' && $v != 'list_book'){ ?>
      function LoadOutTimeBook(){
        $.ajax({
                 type: "POST",
                 url: "Welcome/getOutTimeBook",
                 dataType: "JSON",
                 
                 data:{
                  'user_id' : "<?php echo $customerSale[0]['id']; ?>",                
                 } ,

                 success: function(data){
                  console.log(data)
                  if(data){
                       var html = "";
                       var html1 = "";
                       html1 +='<form action="<?php echo site_url('customers/change_customer_book') ?>" id="action-form-notiBook" method="post" accept-charset="utf-8"><div class="modal-content bgog borad" style="padding: 10px; border-radius: 10px; position: relative;"><div class="borad" style=" border: 1px solid #fff;   padding: 30px;"><div class="title-menu con-xs-12" style="margin: 10px 0px;"><span style="border-bottom: 1px solid #000">Đã quá giờ</span></div><div class="modal-logo-product-notes"><img style="width: 63px" src="<?php echo base_url("assets/uploads/WarningSign1.png") ?>"></div><div class="totalproductalert text-center bold" style="margin-top: 20px;font-size:20px; color:red"></div><div id="contentNoteBookOut" style="text-align: center; margin-top: 10px;"><div>Đã quá giờ</div></div><div class="text-right"></div><div class="text-center"><a href="customers/list_book">Nhấp vào đây để lựa chọn hoàn thành</a></div><div class="moodal-footer text-center" style="margin-top:10px;"><span class="btn btn-warning btn-closeBookOut"  style="margin-left: 15px;background: #1d1c1c; color:#dea43e;border-radius: 5px !important">Đóng</span></div></div></div></form>';
                       $.each(data,function(key,val){ 
                           html += '<div class="alert_product" id="alert_product_'+ key +'">Bạn đã làm quá giờ cho khách '+ val.sma_books_customername  +', yêu cầu hoàn thành!!!</div>'
                       });
                       $('#myModalNoteBookOutTime .modal-dialog ').empty().html(html1);
                       $('#contentNoteBookOut').empty().html(html);
                       $('#myModalNoteBookOutTime').modal('show');
                       var totallenght = $('#contentNoteBookOut .alert_product').length;
                       var total = data.count - totallenght;
                       // $('.totalproductalert').empty().html('Có '+data.count+' sản phẩm sắp hoặc đã hết hàng.<br> Yêu cầu nhập thêm');
                       // $('#moreAlertQuantityProduct').html('Xem thêm ...('+ total +')');
                 }
                }
           });
      }

      LoadOutTimeBook();
    <?php } ?>

   <?php if($Owner || $Admin || $user_warehouse_id){ ?>

      function loadAlertProduct(){
           $.ajax({
                 type: "POST",
                 url: "Welcome/getProductNoti",
                 dataType: "JSON",
                 <?php if($user_warehouse_id){ ?>
                 data:{
                  'warehouse_id' : "<?php echo $user_warehouse_id ?>",
                 },
                 <?php } ?>
                 success: function(data){
                   if(data.count > 0){
                       var html = "";
                       var html1 = "";
                       html1 +='<div class="modal-content bgog borad" style="padding: 10px; border-radius: 10px; position: relative;"><div class="borad" style=" border: 1px solid #fff;   padding: 30px;"><div class="title-menu con-xs-12" style="margin: 10px 0px;"><span style="border-bottom: 1px solid #000">Thông báo</span></div><div class="modal-logo-product-notes"><img style="width: 63px" src="<?php echo base_url("assets/uploads/WarningSign1.png") ?>"></div><div class="totalproductalert text-center bold" style="margin-top: 20px;font-size:20px; color:red"></div><div id="contentNoteProduct" style="text-align: center; margin-top: 10px;"><div>Mặt hàng abc đã hết</div></div><div class="text-right"><span id="moreAlertQuantityProduct" >Xem thêm...</span></div><div class="moodal-footer text-center"><a href="<?php echo site_url('products/add_receipt') ?>" style="background: #1d1c1c; color:#dea43e;border-radius: 5px !important; " class="btn borad" target="_blank">Nhập hàng</a><button type="button" class="close1  btn " style="margin-left: 15px;background: #1d1c1c; color:#dea43e;border-radius: 5px !important">Dismiss</button></div></div></div>';
                       $.each(data.products,function(key,val){ 
                           html += '<div class="alert_product" id="alert_product_'+ key +'">Mặt hàng <span class="bold" style="color:#a54141">' + val.prname + '(' + val.code + ')</span> thuộc kho <b>' + val.whname + '</b> số lượng còn <b>' + formatMoney(val.whpquantity) + '</b>.</div>'
                       });
                       $('#myModalNoteProduct .modal-dialog ').empty().html(html1);
                       $('#contentNoteProduct').empty().html(html);
                       $('#myModalNoteProduct').modal('show');
                       var totallenght = $('#contentNoteProduct .alert_product').length;
                       var total = data.count - totallenght;
                       $('.totalproductalert').empty().html('Có '+data.count+' sản phẩm sắp hoặc đã hết hàng.<br> Yêu cầu nhập thêm');
                       $('#moreAlertQuantityProduct').html('Xem thêm ...('+ total +')');
                     }
                 }
           });
      }



      <?php if($m != 'customers' && $v != 'book' && $userss->warehouse_id && ( $userss->group_id == 1 || $userss->group_id == 2 ) ){ ?>
          function loadAlertBookCancel(){
           $.ajax({
                 type: "POST",
                 url: "Welcome/getBookNotiCancel",
                 dataType: "JSON",
                 
                 data:{
                  'warehouse_id' : "<?php echo $userss->warehouse_id; ?>",                
                 } ,

                 success: function(data){
                  if(data){
                       var html = "";
                       var html1 = "";
                       html1 +='<form action="<?php echo site_url('customers/change_customer_book') ?>" id="action-form-notiBook" method="post" accept-charset="utf-8"><div class="modal-content bgog borad" style="padding: 10px; border-radius: 10px; position: relative;"><div class="borad" style=" border: 1px solid #fff;   padding: 30px;"><div class="title-menu con-xs-12" style="margin: 10px 0px;"><span style="border-bottom: 1px solid #000">Thông báo</span></div><div class="modal-logo-product-notes"><img style="width: 63px" src="<?php echo base_url("assets/uploads/WarningSign1.png") ?>"></div><div class="totalproductalert text-center bold" style="margin-top: 20px;font-size:20px; color:red"></div><div id="contentNoteBook" style="text-align: center; margin-top: 10px;"><div>Thông báo</div></div><div class="text-right"></div><div class="moodal-footer text-center" style="margin-top:10px;"><span class="btn btn-warning btn-closeBook1"  style="margin-left: 15px;background: #1d1c1c; color:#dea43e;border-radius: 5px !important">Đóng</span></div></div></div></form>';
                       $.each(data,function(key,val){ 
                        
                           html += '<div class="alert_product" id="alert_product_'+ key +'">Nhân viên '+ val.last_name +' đã từ chối nhận book lịch của khách hàng '+ val.sma_books_customername + '(' + val.sma_books_starttime + ')<br><a target="_blank" href="customers/book/'+ val.sma_history_book_bookid +'/change">Bấm vào đây để đổi nhân viên</a></div>'
                       });
                       $('#myModalNoteBook .modal-dialog ').empty().html(html1);
                       $('#contentNoteBook').empty().html(html);
                       $('#myModalNoteBook').modal('show');
                       var totallenght = $('#contentNoteBook .alert_product').length;
                       var total = data.count - totallenght;
                       // $('.totalproductalert').empty().html('Có '+data.count+' sản phẩm sắp hoặc đã hết hàng.<br> Yêu cầu nhập thêm');
                       // $('#moreAlertQuantityProduct').html('Xem thêm ...('+ total +')');
                 }
                }
           });


      }



      loadAlertBookCancel();
      
      <?php } ?>


      function loadAlertBook(){
           $.ajax({
                 type: "POST",
                 url: "Welcome/getBookNoti",
                 dataType: "JSON",
                 
                 data:{
                  'user_id' : <?php echo $this->session->userdata('user_id'); ?>,                
                 } ,

                 success: function(data){
                  if(data){
                       var html = "";
                       var html1 = "";
                       html1 +='<form action="<?php echo site_url('customers/change_customer_book') ?>" id="action-form-notiBook" method="post" accept-charset="utf-8"><div class="modal-content bgog borad" style="padding: 10px; border-radius: 10px; position: relative;"><div class="borad" style=" border: 1px solid #fff;   padding: 30px;"><div class="title-menu con-xs-12" style="margin: 10px 0px;"><span style="border-bottom: 1px solid #000">Thông báo</span></div><div class="modal-logo-product-notes"><img style="width: 63px" src="<?php echo base_url("assets/uploads/WarningSign1.png") ?>"></div><div class="totalproductalert text-center bold" style="margin-top: 20px;font-size:20px; color:red"></div><div id="contentNoteBook" style="text-align: center; margin-top: 10px;"><div>Thông báo</div></div><div class="text-right"></div><div class="moodal-footer text-center" style="margin-top:10px;"><span id="acppect"  style="background: #1d1c1c; color:#dea43e;border-radius: 5px !important; " class="btn borad" ">Nhận lịch</span><button type="button" class="closebook1  btn " style="margin-left: 15px;background: #1d1c1c; color:#dea43e;border-radius: 5px !important">Bỏ qua</button><span class="btn btn-warning btn-closeBook"  style="margin-left: 15px;background: #1d1c1c; color:#dea43e;border-radius: 5px !important">Đóng</span></div></div></div></form>';
                       $.each(data,function(key,val){ 
                           html += '<div class="alert_product" id="alert_product_'+ key +'"><input type="checkbox" class="checkbox_id_noti" name="id_noti[]" style="zoom: 1.5;" value="'+ val.sma_notice_id +'"><span style="position: relative; top: -5px;">'+ val.sma_notice_descriptionname +'</span></div>'
                       });
                       $('#myModalNoteBook .modal-dialog ').empty().html(html1);
                       $('#contentNoteBook').empty().html(html);
                       $('#myModalNoteBook').modal('show');
                       var totallenght = $('#contentNoteBook .alert_product').length;
                       var total = data.count - totallenght;
                       // $('.totalproductalert').empty().html('Có '+data.count+' sản phẩm sắp hoặc đã hết hàng.<br> Yêu cầu nhập thêm');
                       // $('#moreAlertQuantityProduct').html('Xem thêm ...('+ total +')');
                 }
                 }
           });
      }
      

      loadAlertBook();
      loadAlertProduct();

      var totallenght = 0;
      

      $(document).on('click','#acppect',function(){
        if($('input[name="id_noti[]"]:checked').length > 0){
          $.ajax({
                 type: "POST",
                 url: "Welcome/readBookNoti",
                 dataType: "JSON",
                 
                data: $("#action-form-notiBook").serialize(),// serializes the form's elements.
                success: function(data){
                  $.each(data,function(k,v){
                    $('.checkbox_id_noti[value="'+ v +'"]').parents('.alert_product').remove();
                  })

                  if($('.alert_product').length <= 0){
                    $('#myModalNoteBook').modal('hide');
                  }
                }
                 
          });

        }else{
          alert('Xin chọn lịch book!!!');
        }
      })


      $(document).on('click','.closebook1',function(){
        if($('input[name="id_noti[]"]:checked').length > 0){
          $.ajax({
                 type: "POST",
                 url: "Welcome/readBookNotiCancel",
                 dataType: "JSON",
                 
                data: $("#action-form-notiBook").serialize(),// serializes the form's elements.
                success: function(data){
                  $.each(data,function(k,v){
                    $('.checkbox_id_noti[value="'+ v +'"]').parents('.alert_product').remove();
                  })

                  if($('.alert_product').length <= 0){
                    $('#myModalNoteBook').modal('hide');
                  }
                }
                 
          });

        }else{
          alert('Xin chọn lịch book!!!');
        }
      })

      $(document).on('click','#moreAlertQuantityProduct',function(){

           $.ajax({
                 type: "POST",
                 url: "Welcome/getProductNoti",
                 dataType: "JSON",
                 <?php if($user_warehouse_id){ ?>
                 data:{
                  'warehouse_id' : <?php echo $user_warehouse_id ?>,
                 },
                 <?php } ?>
                 success: function(data){
                   var html = "";
                   var i = 0;
                   var count = (data.count*1);
                       $.each(data.allproducts,function(key,val){
                            
                           
                               if(key > 2){
                                   html += '<div class="alert_product" id="alert_product_'+ key +'">Mặt hàng <span class="bold" style="color:#a54141">' + val.prname + '(' + val.code + ')</span> thuộc kho <b>' + val.whname + '</b> số lượng còn <b>' + formatMoney(val.whpquantity) + '</b>.</div>';
   
                                   i++;
   
                               }
                       });
                  totallenght = $('#contentNoteProduct .alert_product').length + i ;

                  if(count >= totallenght && html!=''){
                       $('#contentNoteProduct').append(html);
                       var total = (data.count*1) - totallenght;
                        
                       $('#moreAlertQuantityProduct').html('Xem thêm ...('+ total +')');
                   }else{
                        alert('Không còn dữ liệu để xuất');
                       
                   }
                 }
           });
      })
       
   
   <?php } ?>
    

    $(document).on('click','.btn-closeBook',function(){
          $('#myModalNoteBook').modal('toggle');
          setTimeout(function(){ loadAlertBook(); }, 30000);
    })

    $(document).on('click','.btn-closeBook1',function(){
        $('#myModalNoteBook').modal('toggle');
    })

     $(document).on('click','.close1',function(){
         $('#myModalNoteProduct').modal('toggle');
         setTimeout(function(){ loadAlertProduct(); }, <?php echo ($Settings->time_notice*1000*60) ?>);
     })

     $(document).on('click','.btn-closeBookOut',function(){
          $('#myModalNoteBookOutTime').modal('toggle');
          setTimeout(function(){ LoadOutTimeBook(); }, 30000);
    })

      
   });
   
</script>
</body>
</html>