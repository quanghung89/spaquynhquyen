<style type="text/css">
   .messge.erros{
   color: red;
   font-size: 12px;
   }
   .disabled{
   pointer-events: none;
   }
   .borad{
   border-radius:  5px !important;
   }
   .select2-choice.select2-default{
   border-radius: 5px;
   }

   .avatar {
    border: 5px solid #FFF;
    outline: 1px solid #DBDEE0;
    width: 35%;
    margin-right: 8px;
}


   .status_active1{
    color: green;
    padding: 5px 10px;
    
   }

   .status_active5{
    padding: 5px 5px;

   }

   .status_active2{
    color: red;
    padding: 5px 10px;
   


   }

   .status_active4{
    color: blue;
    padding: 5px 10px;


   }

   .status_active3{
    color: gray;
    padding: 5px 10px;


   }

   .status_active{
      font-size: 11px;
      padding: 2px 4px;
      margin-right: 4px;      
      border-radius: 50%;   
   }

   .activeS{
    background: #eae5e5;
    border: 2px solid #e2e0e0;
   }

   .status_active.status_active1a{
      background: green;
   }

   .status_active.status_active2a{
      background: red;
   }

   .status_active.status_active3a{
      background: gray;
   }

   .status_active.status_active4a{
      background: blue;
   }

   .status_active1:hover{
    cursor: pointer;
   }

</style>
<div class="box" style="border: 0px;">
   <div class="title-menu con-xs-12" style="margin: 45px 0px;">
      <span>Book lịch</span>
   </div>
   <div class="box-content">
      <div class="row">
         <?php
            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
            echo form_open_multipart("customers/book/".$id."/".$change, $attrib)
            ?>
         <div class="col-lg-12">
            <?php if($change){
              $readony = 'readonly';
            } ?>
            <!-- <p class="introtext"><?php echo lang('enter_info'); ?></p> -->
            <input type="hidden" name="id_cus" id="id_cus" value="<?php echo  ($inv) ? $inv->sma_books_customerid : '' ?>">
            <div style="width: 50%;margin: 0 auto;">
               <div class="form-group all">
                  <?= lang("Tên khách hàng", "name_customer") ?>
                  <?php if($inv->sma_books_customername){ ?>
                   <?= form_input('name_customer', ($inv->sma_books_customername) ? $inv->sma_books_customername : '' , 'class="form-control borad"  id="name_customer" required placeholder="Tên khách hàng" '); ?> 
                  <?php }else{ ?>
                  <?= form_input('name_customer', ($inv->sma_books_customername) ? $inv->sma_books_customername : '' , 'class="form-control borad" readonly id="name_customer"'. $readonly .' required placeholder="Tên khách hàng" '); ?>
                  <?php } ?>
               </div>
               <div class="form-group all">
                  <?= lang("Số điện thoại", "phone_customer") ?>                        
                  <?= form_input('phone_customer', ($inv->phone) ? $inv->phone : '', 'class="form-control borad" required id="phone_customer" placeholder="Số điện thoại" '); ?> 
               </div>
               <div class="form-group">
                  <?= lang("Dịch vụ", "category_parent"); ?>
                  <?php
                     $cate[''] = '';
                     foreach ($categories as $categorie) {
                         $cate[$categorie->id] = $categorie->name;
                     }
                     echo form_dropdown('category_parent', $cate, ($inv->sma_books_categoryparentid) ? $inv->sma_books_categoryparentid : '' , 'id="category_parent" class="form-control input-tip  borad" data-placeholder="' . lang("select") . ' ' . lang("dịch vụ") . '" required="required" style="width:100%;" ');
                     ?>
               </div>

               <div class="form-group <?php echo ($inv->sma_books_categorychildid) ? '' : 'hidden' ?> ">
                  <?= lang("Dịch vụ thêm", "category_child"); ?>
                  <?php
                     $cate_c[''] = '';
                     
                     
                     echo form_dropdown('category_child', $cate_c, ($inv->sma_books_categorychildid) ? $inv->sma_books_categorychildid : 'hidden', 'id="category_child" class="form-control input-tip borad" data-placeholder="' . lang("select") . ' ' . lang("dịch vụ con") . '"  style="width:100%;" ');
                     ?>
               </div>

               <div class="form-group">
                  <?= lang("Cơ sở", "warehouse_id"); ?>
                  <?php
                     foreach ($warehouses as $warehouse) {
                         $wh[$warehouse->id] = $warehouse->name;
                     }

                    if($change){ 
                    echo form_dropdown('warehouse_id', $wh, ($inv->sma_books_warehouseid) ? $inv->sma_books_warehouseid : '', 'id="warehouse_id" class="form-control input-tip borad" data-placeholder="' . lang("select") . ' ' . lang("cơ sở") . '" required="required" style="width:100%;pointer-events:none;" ');
                  }else{ 
                    echo form_dropdown('warehouse_id', $wh, ($inv->sma_books_warehouseid) ? $inv->sma_books_warehouseid : '', 'id="warehouse_id" class="form-control input-tip borad" data-placeholder="' . lang("select") . ' ' . lang("cơ sở") . '" required="required" style="width:100%;" ');
                  } 
                     
                     ?>
               </div>

               <div class="form-group all">
                  <?= lang("Giờ làm", "time_work_book") ?>  
                  <?php if($change){ ?>
                    <?= form_input('time_work_book', ($inv->sma_books_starttime) ? $this->sma->ihrld($inv->sma_books_starttime) : '', 'class="form-control borad datetime" readonly required id="time_work_book" placeholder="Giờ làm" '); ?> 
                  <?php }else{ ?>
                    <?= form_input('time_work_book', ($inv->sma_books_starttime) ? $this->sma->ihrld($inv->sma_books_starttime) : '', 'class="form-control borad datetime" required id="time_work_book" placeholder="Giờ làm" '); ?> 
                  <?php } ?>
                  
               </div>
               <div class="form-group">
                  <?= lang("Nhân viên", "staff"); ?>
                  <?php if($inv->staffname){
                   echo form_input('staff', ($inv->staffname) ? $inv->staffname : '' , 'class="form-control borad noread"   id="staff" placeholder="Nhân viên" ');
                  }else{ ?>
                  <?= form_input('staff', ($inv->staffname) ? $inv->staffname : '' , 'class="form-control borad" readonly  id="staff" placeholder="Nhân viên" '); ?> 
                  <?php } ?>
                  <input type="hidden" name="staff_id" id="staff_id" value="<?php echo ($inv->sma_books_staffid) ? $inv->sma_books_staffid : '' ?>">
                 <!--  <?php
                     $staff[''] = '';
                     foreach ($staffs as $key => $value) {
                       $staff[$value['id']] = $value['last_name'];
                     }
                     
                     echo form_dropdown('staff', $staff, (isset($_POST['staff']) ? $_POST['staff'] : $user->company_id), 'id="staff" class="form-control input-tip borad" data-placeholder="' . lang("select") . ' ' . lang("nhân viên") . '"  style="width:100%;" ');
                     ?> -->
               </div>
               <div class="form-group all">
                  <?= lang("Ghi chú", "note") ?>                        
                  <textarea id="note" name="note" style="width: 100%; height: 100px"><?php echo ($inv->note) ? $inv->note : '' ?></textarea>
               </div>

               <div class="form-group">
                  <?= lang("Nhân viên theo dõi", "staff_asgin"); ?>
                  <?php
                     foreach ($staffs as $key => $value) {
                       $staff1[$value['id']] = $value['last_name'];
                     }
                     
                     echo form_dropdown('staff_asgin[]', $staff1, ($inv->staffasgin) ? $inv->staffasgin : '', 'id="staff_asgin" multiple class="form-control input-tip borad" data-placeholder="' . lang("select") . ' ' . lang("nhân viên") . '"  style="width:100%;" ');
                     ?>
               </div>
            </div>
         </div>
         <div class="col-lg-12" style="text-align: center;">
            <?php if( $inv->sma_books_status == 0){ ?>
              <?php echo form_submit('add', lang('Lưu'), 'class="btn btn-warning" '); ?>
              <input class="btn btn-warning" type='reset' value='Nhập lại'>
            <?php } ?>            
         </div>
         <?= form_close(); ?>
      </div>
   </div>
</div>

<div class="modal fade in" id="listUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel">Cập nhật</h4>
        </div>
        
        <form action="http://192.168.50.19/spaquynh/auth/update_bouns/2" data-toggle="validator" role="form" id="update-bouns-form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
        <div class="modal-body">
      

            <!-- <div class="form-group">
                <label class="control-label"
                       for="customer_group">Nhóm khách hàng mặc định</label>

                <div class="controls"> <select name="customer_group" class="form-control tip select" id="customer_group" style="width:100%;">
<option value="0"></option>
</select>
                </div>
            </div> -->

            <div class="row">
                <div class="col-xs-12 col-md-6 col-sm-12 col-lg-6">
                    <div class="form-group">
                        <label for="bouns">Tiền thưởng</label>                        <input type="text" name="bouns" class="form-control formatMoney" value="0" required="" id="bouns">
                        
                    </div>
                </div>

                <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                    <div class="form-group">
                        <label for="note">Ghi chú</label>                        <textarea name="note" id="note" class="pa form-control kb-text note"></textarea>
                    </div>
                </div>
                


                
                <!-- <input type="hidden" name="img" value=""> -->
                <input type="hidden" name="idbouns" value="">
                <input type="hidden" name="id" value="2">
                
            </div>


        </div>
        <div class="modal-footer">
                        <input type="submit" name="add" value="Thêm" class="btn btn-warning">
                    </div>
    </form></div>
    </div>


</div>

<?php echo '<script type="text/javascript">' . file_get_contents($this->data['assets'] . 'js/modal.js') . '</script>' ?>
<script type="text/javascript">
 
   $(document).ready(function(){ 



       $("#staff_asgin").select2({
           tokenSeparators: [","],
       
       });

       function getStaffJS() {
           var v = $("#warehouse_id").val();
           var time = $("#time_work_book").val();
          
            var html = '';    
            //Rảnh              
            var option1= '';
            //Bận
            var option2= '';
            //Làm khác buổi
            var option4= '';
            //Nghỉ cả ngày
            var option3= '';
           if(v){
               $.ajax({
                  type: "POST",
   
                  url: 'customers/getStaff',
                  dataType:"JSON",
                  data: {
                   'warehouse_id' : v,
                   'time' : time,
                   <?php if($id){ ?>
                    'id_book' : <?php echo $id ?>,
                  <?php } ?>

                  }, // serializes the form's elements.
                  async: false,
                  cache: false,
                  success: function(data)
                  {
                      if(data){
                          $.each(data,function(k,v){

                            if(!v.avatar){
                              v.avatar = "assets/images/male.png ";
                            }else{
                              v.avatar = "assets/images/"+v.avatar +".png ";                              
                            }
                            
                            if(v.class){
                              v.class = '('+ v.class + ')';
                            }
                            if(v.status_active == 1){
                              option1 += '<div class="status_active1 col-xs-12" value="'+ v.id +'">' + '<img alt="" src="'+ (v.avatar ? v.avatar : 'assets/images/male.png') +'" class="avatar">' +'<span><i class="status_active status_active1a">a(</i>'+ v.last_name + v.class +'</span>' + '</div>';
                            }else if(v.status_active == 2){
                              option2 += '<div class="status_active2 col-xs-12" value="'+ v.id +'">' + '<img alt="" src="'+ (v.avatar ? v.avatar : 'assets/images/male.png') +'" class="avatar">' + '<span><i class="status_active status_active2a">a(</i>'+ v.last_name + v.class +'</span>' + '</div>';
                            }else if(v.status_active == 4){
                              option4 += '<div class="status_active4 col-xs-12" value="'+ v.id +'">' + '<img alt="" src="'+ (v.avatar ? v.avatar : 'assets/images/male.png') +'" class="avatar">' + '<span><i class="status_active status_active4a">a(</i>'+ v.last_name + v.class +'</span>' + '</div>';
                            }else{
                              option3 += '<div class="status_active3 col-xs-12" value="'+ v.id +'">' + '<img alt="" src="'+ (v.avatar ? v.avatar : 'assets/images/male.png') +'" class="avatar">' + '<span><i class="status_active status_active3a">a(</i>'+ v.last_name + v.class +'</span>' + '</div>';
                            } 
                          })
                       
                          html += '<div class="modal-dialog modal-lg">'
                            html += '<div class="modal-content">'
                              html += '<div class="modal-header">'
                                html += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>'
                                html += '<h4 class="modal-title" id="myModalLabel">DANH SÁCH NHÂN VIÊN</h4>'
                              html += '</div>'
                              html += '<div class="modal-body">'
                                html += '<div class="row">'
                                  if(option1 != ""){
                                    html += '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 ">'
                                      html += option1
                                    html += '</div>'
                                  }

                                  if(option2 != ""){
                                    html += '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 ">'
                                      html += option2
                                    html += '</div>'
                                  }

                                  if(option4 != ""){
                                    html += '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 ">'
                                      html += option4
                                    html += '</div>'
                                  }

                                  if(option3 != ""){
                                    html += '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 ">'
                                      html += option3
                                    html += '</div>'
                                  }
                                html += '</div>'
                              html += '</div>'
                              html += '<div class="modal-footer">'
                                html += '<div class="col-lg-12"><span class="btn btn-primary chosenn">Chọn</span></div>'
                              html += '</div>'            
                            html += '</div>'
                          html += '</div>'

                          // html += option1+option2+option4+option3;

                          if(html){                            
                            $('#myModal').empty().html(html);
                          }


                      }


                  },
                  
                });
           }
       }

       $("#warehouse_id,#time_work_book").change(function(){
          $('#staff').val('');
          $('#staff').removeAttr('readonly');
          $('#staff').addClass('noread');
           getStaffJS();
       })


        $(document).on('click','#staff.noread',function(){
           getStaffJS();
          $('#myModal').modal('show');
        })
      
    
        $(document).on('click','.chosenn',function(){
          var html = $('.activeS').text();
          var name = html.split('(');
          var value = $('.activeS').attr('value');
          $('#staff_id').val(value);
          $('#staff').val(name[1]);
          $('#myModal').modal('hide');
        })

       $('#phone_customer').keyup(function(){
           var v = $(this).val();
           if(v){
               $('#name_customer').removeAttr('readonly');
               $.ajax({
                  type: "POST",
   
                  url: 'customers/check_customer',
                  dataType:"JSON",
                  data: {
                   'phone' : v,
                  }, // serializes the form's elements.
                  async: false,
                  cache: false,
                  success: function(data)
                  {
                       
                      if(data){
                       $('#id_cus').val(data.id);
                       $('#name_customer').val(data.name);
                       $('#note').redactor('set', data.note);
                      }else{
                        $('#id_cus').val('');
                        $('#name_customer').val('');
                        $('#note').redactor('set', '');
                      }
                  }
                });
           }else{
               $('#id_cus').val('');
               $('#name_customer').val('');
               $('#note').redactor('set', '');
           }
       });
    

       $("#category_parent").change(function(){
           var v = $(this).val();
           var child = $("#category_child").val();
         
           var html = '';                  
           
           if(v){
               $.ajax({
                  type: "POST",
   
                  url: 'customers/getServiceChild',
                  dataType:"JSON",
                  data: {
                   'id_c' : v,
                  }, // serializes the form's elements.
                  async: false,
                  cache: false,
                  success: function(data)
                  {
                      if(data.status){
                         
                          // var data1 = JSON.stringify(data.item);
                          if(data.item){
                            $("#category_child").select2("val", "");
                            $('#category_child').parents('.form-group').removeClass('hidden');
                            $.each(data.item,function(k,v){
                              var newOption = new Option(v.text, v.id, false, false);
                              $('#category_child').append(newOption);  
                            })
                          }
                                              
                         $("#staff_asgin").select2('val',data.asgin);
                      }else{
                         $('#category_child').parents('.form-group').addClass('hidden');
                         var newOption = new Option('', '', false, false);
                         $('#category_child').empty().html(newOption);
                      }
                  },
                  
                });
           }else{
              $('#category_child').parents('.form-group').addClass('hidden');
              var newOption = new Option('', '', false, false);

              $('#category_child').empty().html(newOption);
           }
       })
       

       $(document).on('click','.status_active1',function(){
          
          if($(this).hasClass('activeS')){
            $('.status_active1').removeClass('activeS');
            $(this).removeClass('activeS');            
          }else{
            $('.status_active1').removeClass('activeS');
            $(this).addClass('activeS');
          }
       })

       $("#category_child").change(function(){
           var v = $(this).val();
           if(v){
               $.ajax({
                  type: "POST",
   
                  url: 'customers/getServiceChild',
                  dataType:"JSON",
                  data: {
                   'id_c' : v,
                  }, // serializes the form's elements.
                  async: false,
                  cache: false,
                  success: function(data)
                  {
                      if(data.status){                         
                        $("#staff_asgin").select2('val',data.asgin);
                      }else{
                        $("#staff_asgin").select2('val','');
                      }
                  },
                  
                });
           }
       })
   })
</script>