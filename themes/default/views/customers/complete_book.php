<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel">Thanh toán</h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open("customers/complete_book/" . $inv->sma_books_id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="row">

                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <?php echo lang('Tên khách hàng', 'customer_name'); ?>
                        <div class="controls">
                            <?php echo form_input('customer_name',($inv->sma_books_customername) ? $inv->sma_books_customername : '', 'class="form-control" id="customer_name" readonly required="required"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo lang('Dịch vụ', 'categoryparentname'); ?>
                        <div class="controls">
                            <?php echo form_input('categoryparentname', ($inv->sma_books_categoryparentname) ? $inv->sma_books_categoryparentname : '', 'class="form-control" id="categoryparentname" readonly required="required"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo lang('Dịch vụ thêm', 'categorychildname'); ?>
                        <div class="controls">
                            <?php echo form_input('categorychildname', ($inv->sma_books_categorychildname) ? $inv->sma_books_categoryparentname : '', 'class="form-control" id="categorychildname" readonly'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo lang('Số tiền thanh toán dự kiến', 'price_timeline'); ?>
                        <div class="controls">
                            <?php echo form_input('price_timeline', ($inv->sma_books_price) ? $this->sma->formatMoney($inv->sma_books_price) : 0, 'class="form-control" readonly id="price_timeline"'); ?>
                        </div>
                    </div>
                   

                    <div class="form-group">
                        <?php echo lang('Số tiền thanh toán thực tế', 'sma_books_price'); ?>
                        <div class="controls">
                            <input type="text" id="price" name="price" class="form-control formatMoney" required="required"/>
                            <?php /* echo form_input('email', '', 'class="form-control" id="email" required="required"'); */ ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                        <?php echo lang('Thời gian bắt đầu làm', 'starttime'); ?>
                        <div class="controls">
                            <?php echo form_input('starttime', ($inv->sma_books_starttime) ? $this->sma->ihrld($inv->sma_books_starttime) : 0, 'class="form-control" readonly id="starttime"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo lang('Thời gian kết thúc dự kiến', 'endtime'); ?>
                        <div class="controls">
                            <?php echo form_input('endtime', ($inv->sma_books_endtime) ? $this->sma->ihrld($inv->sma_books_endtime) : 0, 'class="form-control" readonly id="endtime"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo lang('Thời gian kết thúc thực tế', 'endtime1'); ?>
                        <div class="controls">
                            <?php echo form_input('endtime1', ($inv->sma_books_endtime1) ? $this->sma->ihrld($inv->sma_books_endtime1) : '', 'class="form-control datetime" required id="endtime1"'); ?>
                        </div>
                    </div>

                   
                    <div class="clearfix"></div>


                </div>
            </div>


        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_user', lang('Thanh toán'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>

