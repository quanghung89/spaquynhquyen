<div class="main-menu col-xs-12 user">
    <div class="title-menu con-xs-12">
        <span>Kho hàng</span>
    </div>
    <div class="col-xs-12" style="margin-top: 70px;">
            <?php if($Owner || $Admin){?>
            <div class="col-xs-4" style="height: 230px">
                <a href="<?php echo site_url('purchases') ?>">
                    <div class="col-xs-12 text-center bgog borad" style=" padding: 5px;height: 100%">
                        <div class="col-xs-12 borad" style="border:1px solid #000; padding:30px 5px;height: 98%">
                            <div class="col-xs-12"><img class="img-menu" src="<?php echo site_url() . 'assets/uploads/menu/packaging-into-a-box.png'  ?>"></div>
                            <div class="col-xs-12" style=" margin-top: 8px; font-weight: bold; color: #000;">NHẬP HÀNG</div>
                        </div>
                    </div>
                </a>
            </div>


            <div class="col-xs-4" style="height: 230px">
                <a href="<?php echo site_url('quotes') ?>">
                    <div class="col-xs-12 text-center bgog borad" style=" padding: 5px;height: 100%">
                        <div class="col-xs-12 borad" style="border:1px solid #000; padding:30px 5px;height: 98%">
                            <div class="col-xs-12"><img class="img-menu" src="<?php echo site_url() . 'assets/uploads/menu/o-into-a-box.png'  ?>"></div>
                            <div class="col-xs-12 mid" style=" margin-top: 8px; font-weight: bold; color: #000;">XUẤT/BÁN HÀNG</div>
                        </div>
                    </div>
                </a>
            </div>

            
            

            <div class="col-xs-4" style="height: 230px">
                <a href="<?php echo site_url('products') ?>">
                    <div class="col-xs-12 text-center bgog borad" style=" padding: 5px;height: 100%">
                       <div class="col-xs-12 borad" style="border:1px solid #000; padding:30px 5px;height: 98%">
                            <div class="col-xs-12"><img class="img-menu" src="<?php echo site_url() . 'assets/uploads/menu/view-symbol-on-delivery-opened-box.png'  ?>"></div>
                            <div class="col-xs-12" style=" margin-top: 8px; font-weight: bold; color: #000;">QUẢN LÝ KHO HÀNG</div>
                        </div>
                    </div>
                </a>
            </div>
            <?php }else{ ?>
            <div class="col-xs-6">
                <?php if($Owner || $Admin){ ?>
                    <a href="<?php echo site_url('purchases') ?>">
                <?php }else{ ?>
                    <a href="<?php echo site_url('products/add_receipt') ?>">
                <?php } ?>
                
                    <div class="col-lg-5 col-xs-12 col-md-6 col-sm-6 text-center bgog borad" style="float: right; padding: 5px;">
                        <div class="col-xs-12 borad" style="border:1px solid #000; padding:30px 5px;">
                            <div class="col-xs-12"><img class="img-menu" src="<?php echo site_url() . 'assets/uploads/menu/packaging-into-a-box.png'  ?>"></div>
                            <div class="col-xs-12" style=" margin-top: 8px; font-weight: bold; color: #000;">NHẬP HÀNG</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xs-6">
                <a href="<?php echo site_url('products') ?>">
                    <div class="col-lg-5 col-xs-12 col-md-6 col-sm-6 text-center bgog borad" style="float: left; padding: 5px;">
                        <div class="col-xs-12 borad" style="border:1px solid #000; padding:30px 5px;">
                            <div class="col-xs-12"><img class="img-menu" src="<?php echo site_url() . 'assets/uploads/menu/view-symbol-on-delivery-opened-box.png'  ?>"></div>
                            <div class="col-xs-12" style=" margin-top: 8px; font-weight: bold; color: #000;">QUẢN LÝ KHO HÀNG</div>
                        </div>
                    </div>
                </a>
            </div>
            <?php } ?>
            
            
        
        <div class="col-xs-6"></div>
    </div>
</div>