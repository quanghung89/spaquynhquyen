<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <!-- <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Cập nhật'); ?></h4>
        </div> -->
        <div class="title-menu con-xs-12" style="margin: 45px 0px;">
            <span><?php echo ($inv) ? 'Sửa tiền thưởng' : 'Thêm tiền thưởng'?></span>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'update-bouns-form');
        echo form_open_multipart("auth/update_bouns/".$id, $attrib); ?>
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

            <div class="row">
                <div class="col-xs-12 col-md-6 col-sm-12 col-lg-6">
                    <div class="form-group">
                        <?= lang("Tiền thưởng", "bouns"); ?>
                        <input type="text" name="bouns" class="form-control formatMoney" value="<?php echo ($inv) ? $this->sma->formatMoney($inv->sma_bouns_bouns) : 0?>" required id="bouns"/>
                        
                    </div>
                </div>

                <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                    <div class="form-group">
                        <?= lang('Ghi chú', 'note'); ?>
                        <textarea name="note" id="note" class="pa form-control kb-text note"><?php echo ($inv->sma_bouns_note) ? $inv->sma_bouns_note : '' ?></textarea>
                    </div>
                </div>
                


                
                <!-- <input type="hidden" name="img" value="<?php echo $customer->image ?>"> -->
                <input type="hidden" name="idbouns" value="<?php echo $inv->sma_bouns_id ?>">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                
            </div>


        </div>
        <div class="modal-footer">
            <?php if($inv){ ?>
            <?php echo form_submit('add', lang('Sửa'), 'class="btn btn-warning"'); ?>
            <?php }else{ ?>
            <?php echo form_submit('add', lang('Thêm'), 'class="btn btn-warning"'); ?>
            <?php } ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>


