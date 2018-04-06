<style type="text/css">
   #imaginary_container{
   margin-top:7%; /* Don't copy this */
   }
   .stylish-input-group .input-group-addon{
   background: ddd !important; 
   }
   .stylish-input-group .form-control{
   border-right:0; 
   box-shadow:0 0 0; 
   border-color:#ccc;
   }
   .stylish-input-group button{
   border:0;
   background:transparent;
   }
</style>

<div class="main-menu col-xs-12 customers" style="position: relative;">
   <div class="main-header col-xs-12" style="border-bottom: 1px solid #dea43e; padding-bottom: 5%;">
      <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'search-customer-form');
         echo form_open_multipart("customers/search", $attrib); ?>
      <div class="title-menu con-xs-12">
         <span>Tìm kiếm khách hàng</span>
      </div>
      <div class="col-xs-5" style="margin-left: 30%">
         <div id="imaginary_container">
            <div class="input-group stylish-input-group">
               <input type="text" name="phonenumber" class="form-control" placeholder="Nhập số điện thoại" style="    border-radius: 5px 0px 0px 5px !important;">
               <span class="input-group-addon" style="border-radius: 0px 5px 5px 0px;">
               <button type="submit" style="    padding: 3px 11px;">
               <span class="fa fa-search" style="font-size: 19px;"></span>
               </button>  
               </span>
            </div>
         </div>
      </div>
      <?php echo form_close(); ?>
   </div>
   <div class="main-footer col-xs-12" style="padding-top: 3%;">
      <div class="title-menu con-xs-12">
         <span>Kết quả</span>
      </div>
      <div class="content" style="margin-top: 3%;">
      </div>
   </div>
</div>


<script type="text/javascript">
       $("#search-customer-form").submit(function(e) {
           var this1 = $(this);
           var url = $(this).attr('action'); // the script where you handle the form input.
           var formData = new FormData(this);
             e.preventDefault(); // avoid to execute the actual submit of the form.
            e.stopPropagation();
            e.stopImmediatePropagation();
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
                       var html = '';
                       if(data.success == false){
                           bootbox.alert(data.msg);
                           setTimeout(function(){ $('.bootbox.modal.bootbox-alert').modal('hide');  }, 3000);
                       }else{
                           if(!data.obj.service){
                               data.obj.service = '';
                           }
                           if(!data.obj.note){
                               data.obj.note = '';
                           }
                           html +='<div class="table-responsive">';
                           html += '<table id="CategoryTable" class="table table-bordered table-hover table-striped">',
                           html += '<thead><tr><th>Tên</th><th>Số điện thoại</th><th>Lịch sử</th><th>Ghi chú</th> </tr></thead>'
                           html += '<tbody>'
                           html += '<td>'+data.obj.name+'</td>'
                           html += '<td>'+data.obj.phone+'</td>'
                           html += '<td>'+data.obj.service+'</td>'
                           html += '<td>'+data.obj.note+'</td>'
                           html +='</tbody>'
                           html += '</table>'
                           html += '</div>'
                           
          
                       }
                       $('.content').empty().html(html);
                  }
                });
          
           return false;
       });
   
       
</script>