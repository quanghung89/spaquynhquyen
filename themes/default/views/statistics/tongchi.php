<style>
    @media (max-width: 1199px) {
        .main-menu.user {padding: 0 25px !important;}
    }
    label.act-ajax {margin-left: 20px;}
    a.saveCPCD, a.saveCPPS {display: none!important;}
    a.minusCPCD, a.minusCPPS {font-size: 120%;line-height: 34px;}
    #form_cpcd, #form_cpps {margin-top: 20px;}
    .filter-date {margin-top: 25px;}
    @media (max-width: 600px) {
        .filter-date span {display: block;width: 100%;margin-bottom: 10px;}
    }
</style>

<script>
    $(document).ready(function () {
        var danhmucCPCD = '<?php
            $type = $this->config->config["danhmucodinh"];
            echo preg_replace("/[\r\n]*/", "", form_dropdown('sma_pay_danhmuc_index[]', $type, (isset($_POST['type']) ? $_POST['type'] : [0]), 'class="form-control select" id="month1" placeholder="' . lang("Loại") . " " . lang() . '" style="width:100%"'));
            ?>';
        var addRowCPCD = '<div class="container cpcd">\n' +
            '                        <div class="col-sm-11">' +
            '                                <input type="hidden" class="form-control" name="sma_pay_type[]" value="0">\n' +
            '                           <div class="form-group col-sm-3 col-md-3 col-lg-3">' +
            '                               <label for="">Loại:</label>' +
            danhmucCPCD +
            '                              </div>' +
            '                            <div class="form-group col-sm-3 col-md-3 col-lg-3">\n' +
            '                                <label for="">Số tiền:</label>\n' +
            '                                <input type="text" name="sotien[]" class="form-control"  placeholder="Nhập số tiền">\n' +
            '                            </div>\n' +
            '                            <div class="form-group col-sm-3 col-md-3 col-lg-3">\n' +
            '                                <label >Ngày nộp:</label>\n' +
            '                                <input type="text" name="ngaynop[]" class="form-control date"  placeholder="Nhập ngày">\n' +
            '                            </div>\n' +
            '                            <div class="form-group col-sm-3 col-md-3 col-lg-3">\n' +
            '                                <label >Nội dung:</label>\n' +
            '                                <input type="text" name="noidung[]" class="form-control"  placeholder="Nội dung">\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                        <div class="col-sm-1 text-center" style="height: 64px; padding: 25px 15px">\n' +
            '                            <a href="#" class="minusCPCD">\n' +
            '                                <i class="fa fa-minus-square " aria-hidden="true"></i>\n' +
            '                            </a>\n' +
            '                            <a href="javascript: void(0);" class="saveCPCD" rel="0">\n' +
            '                                <i class="fa fa-floppy-o" aria-hidden="true"></i>\n' +
            '                            </a>\n' +
            '                        </div>\n' +
            '                    </div>';
        $('#addChiPhiCoDinh').on("click", function (e) {
            $('#chiphicodinh').append(addRowCPCD);
            return false;
        });
        $(document).on("click", '.minusCPCD', function (e) {
            if (window.confirm('Bạn có chắc chắn muốn xóa!')) {
                $(this).closest(".cpcd").remove();

                var f = $(this).attr('rel');

                if (f != 'test' && f != null) {
                    $.ajax({
                        url: "statistics/del_pay",
                        type: 'POST',
                        data: {
                            "pay_id": f,
                        },
                        success: function (data) {
                            // do something
                            //alert('Xóa thành công!');
                            location.reload();
                        },
                        error: function (data) {
                            // do something
                            alert('Error!');
                        }
                    });
                }
                return false;
            } else {
                return false;
            }
        });

        $(document).on("click", '.saveCPCD', function (e) {
            //console.log('save');
            //return false;

            var f = $(this).attr('rel');
            var pay_type = $(this).parents('.cpcd').find('input[name="sma_pay_type"]').val(),
                pay_danhmuc_index = $(this).parents('.cpcd').find('select[name="sma_pay_danhmuc_index"]').val(),
                sotien = $(this).parents('.cpcd').find('input[name="sotien"]').val(),
                ngaynop = $(this).parents('.cpcd').find('input[name="ngaynop"]').val(),
                noidung = $(this).parents('.cpcd').find('input[name="noidung"]').val();

            if (f != 'test') {
                $.ajax({
                    url: "statistics/save_pay",
                    type: 'POST',
                    data: {
                        "pay_id": f,
                        "pay_type": pay_type,
                        "pay_danhmuc_index": pay_danhmuc_index,
                        "sotien": sotien,
                        "ngaynop": ngaynop,
                        "noidung": noidung
                    },
                    success: function (data) {
                        // do something
                        alert('Lưu thành công!');
                        location.reload();
                    },
                    error: function (data) {
                        // do something
                        alert('Error!');
                    }
                });
            }
        });

        $('#save-cpcd').on('click', function () {
            pay_data = $("#form_cpcd").serialize();
            $.ajax({
                url: "statistics/save_pay",
                type: 'POST',
                data: pay_data,
                success: function (response) {
                    // do something
                    alert('Lưu thành công!');
                    location.reload();
                },
                error: function (response) {
                    // do something
                    alert('Error!');
                }
            });
        });


        var addRowCPPS = '<div class="container cpps">\n' +
            '                            <div class="col-sm-11">\n' +
            '                                <input type="hidden" class="form-control" name="sma_pay_type[]" value="1">\n' +
            '                                <input type="hidden" class="form-control" name="sma_pay_danhmuc_index[]" value="0">\n' +
            '                                <div class="form-group col-sm-4 col-md-4 col-lg-4">\n' +
            '                                    <label for="">Số tiền:</label>\n' +
            '                                    <input type="text" name="sotien[]" class="form-control sotien"  placeholder="Nhập số tiền">\n' +
            '                                </div>\n' +
            '                                <div class="form-group col-sm-4 col-md-4 col-lg-4">\n' +
            '                                    <label >Ngày nộp:</label>\n' +
            '                                    <input type="text" name="ngaynop[]" class="form-control date"  placeholder="Nhập ngày">\n' +
            '                                </div>\n' +
            '                                <div class="form-group col-sm-4 col-md-4 col-lg-4">\n' +
            '                                    <label >Nội dung:</label>\n' +
            '                                    <input type="text" name="noidung[]" class="form-control"  placeholder="Nội dung">\n' +
            '                                </div>\n' +
            '                            </div>\n' +
            '                            <div class="col-sm-1 text-center" style="height: 64px; padding: 25px 15px">\n' +
            '                                <a href="#" class="minusCPPS">\n' +
            '                                    <i class="fa fa-minus-square " aria-hidden="true"></i>\n' +
            '                                </a>\n' +
            '                                <a href="javascript: void(0);" class="saveCPPS" rel="0">\n' +
            '                                    <i class="fa fa-floppy-o" aria-hidden="true"></i>\n' +
            '                                </a>\n' +
            '                            </div>\n' +
            '                        </div>';

        $('#addChiPhiPhatSinh').on("click", function (e) {
            $('#chiphiphatsinh').append(addRowCPPS);
            return false;
        });
        $(document).on("click", '.minusCPPS', function (e) {
            if (window.confirm('Bạn có chắc chắn muốn xóa!')) {
                $(this).closest(".cpps").remove();

                var f = $(this).attr('rel');

                if (f != 'test' && f != null) {
                    $.ajax({
                        url: "statistics/del_pay",
                        type: 'POST',
                        data: {
                            "pay_id": f,
                        },
                        success: function (data) {
                            // do something
                            //alert('Xóa thành công!');
                            location.reload();
                        },
                        error: function (data) {
                            // do something
                            alert('Error!');
                        }
                    });
                }
                return false;
            }
            else {
                return false;
            }
        });

        $(document).on("click", '.saveCPPS', function (e) {
            // console.log('save');
            // return false;

            var f = $(this).attr('rel');
            var pay_type = $(this).parents('.cpps').find('input[name="sma_pay_type"]').val(),
                pay_danhmuc_index = $(this).parents('.cpps').find('select[name="sma_pay_danhmuc_index"]').val(),
                sotien = $(this).parents('.cpps').find('input[name="sotien"]').val(),
                ngaynop = $(this).parents('.cpps').find('input[name="ngaynop"]').val(),
                noidung = $(this).parents('.cpps').find('input[name="noidung"]').val();

            if (f != 'test') {
                $.ajax({
                    url: "statistics/save_pay",
                    type: 'POST',
                    data: {
                        "pay_id": f,
                        "pay_type": pay_type,
                        "pay_danhmuc_index": 0,
                        "sotien": sotien,
                        "ngaynop": ngaynop,
                        "noidung": noidung
                    },
                    success: function (data) {
                        // do something
                        alert('Lưu thành công!');
                    },
                    error: function (data) {
                        // do something
                        alert('Error!');
                    }
                });
            }
            location.reload();
        });

        $('#save-cpps').on('click', function () {
            pay_data = $("#form_cpps").serialize();
            $.ajax({
                url: "statistics/save_pay",
                type: 'POST',
                data: pay_data,
                success: function (response) {
                    // do something
                    alert('Lưu thành công!');
                    location.reload();
                },
                error: function (response) {
                    // do something
                    alert('Error!');
                }
            });
        });
    })
</script>

<?php //var_dump($lists); ?>
<div class="main-menu col-xs-12 user sales">
    <div class="title-menu con-xs-12">
        <span>TỔNG CHI</span>
    </div>

    <div class="row text-center filter-date">
        <div class="col-xs-12">
            <form method="post" action="<?php echo base_url(); ?>statistics/tongchi" class="filter-form">
                <span>Từ ngày: <input type="text" name="date_from" class="input input-sm date" value="<?php echo $date_from; ?>"></span>
                <span>Đến ngày: <input type="text" name="date_to" class="input input-sm date" value="<?php echo $date_to; ?>"></span>
                <input type="submit" class="btn btn-sm" value="Lọc">
            </form>
        </div>
    </div>

    <div class="row" style="margin-top: 30px">
        <div class="container">
            <div class="row">
                <label>
                    <i class="fa fa-hand-o-right"></i>
                    <span>CHI PHÍ CỐ ĐỊNH THÁNG</span>
                </label>
                <label class="act-ajax">
                    <button type="button" id="save-cpcd" class="save-cpcd btn btn-primary white">Lưu tất cả</button>
                </label>
            </div>

            <div class="row">
                <form id="form_cpcd" method="post">
                    <div id="chiphicodinh">
                        <?php if (!empty($lists)) { ?>
                            <?php foreach ($lists as $list) { ?>
                                <?php if ($list['sma_pay_type'] == 0) { ?>
                                    <div class="container cpcd">
                                        <div class="col-sm-11">
                                            <input type="hidden" class="form-control" name="sma_pay_type_edit[]" value="0">
                                            <input type="hidden" class="form-control" name="sma_pay_id_edit[]" value="<?php echo $list['sma_pay_id']; ?>">
                                            <div class="form-group col-sm-3 col-md-3 col-lg-3">
                                                <label for="">Loại:</label>
                                                <?php
                                                $type = $this->config->config["danhmucodinh"];
                                                echo form_dropdown('sma_pay_danhmuc_index_edit[]', $type, (isset($list['sma_pay_danhmuc_index']) ? $list['sma_pay_danhmuc_index'] : [0]), 'class="form-control select" id="month11" placeholder="' . lang("Loại") . " " . lang() . '" style="width:100%"');

                                                ?>
                                            </div>
                                            <div class="form-group col-sm-3 col-md-3 col-lg-3">
                                                <label for="">Số tiền:</label>
                                                <input type="text" name="sotien_edit[]" class="form-control" value="<?php echo number_format($list['sma_pay_pay']); ?>">
                                            </div>
                                            <div class="form-group col-sm-3 col-md-3 col-lg-3">
                                                <label>Ngày nộp:</label>
                                                <input type="text" name="ngaynop_edit[]" class="form-control date" value="<?php echo date('d/m/Y', $list['sma_pay_createtime']); ?>">
                                            </div>
                                            <div class="form-group col-sm-3 col-md-3 col-lg-3">
                                                <label>Nội dung:</label>
                                                <input type="text" name="noidung_edit[]" class="form-control" value="<?php echo $list['sma_pay_note']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-1 text-center" style="height: 64px; padding: 25px 15px">
                                            <a href="javascript: void(0);" class="minusCPCD" rel="<?php echo $list['sma_pay_id']; ?>">
                                                <i class="fa fa-minus-square " aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="saveCPCD" rel="<?php echo $list['sma_pay_id']; ?>">
                                                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="container cpcd">
                                <div class="col-sm-11">
                                    <input type="hidden" class="form-control" name="sma_pay_type[]" value="0">
                                    <div class="form-group col-sm-3 col-md-3 col-lg-3">
                                        <label for="">Loại:</label>
                                        <?php
                                        $type = $this->config->config["danhmucodinh"];
                                        echo form_dropdown('sma_pay_danhmuc_index[]', $type, (isset($list['sma_pay_danhmuc_index']) ? $list['sma_pay_danhmuc_index'] : [0]), 'class="form-control select" id="month1" placeholder="' . lang("Loại") . " " . lang() . '" style="width:100%"');

                                        ?>
                                    </div>
                                    <div class="form-group col-sm-3 col-md-3 col-lg-3">
                                        <label for="">Số tiền:</label>
                                        <input type="text" name="sotien[]" class="form-control" placeholder="Nhập số tiền">
                                    </div>
                                    <div class="form-group col-sm-3 col-md-3 col-lg-3">
                                        <label>Ngày nộp:</label>
                                        <input type="text" name="ngaynop[]" class="form-control date" placeholder="Nhập ngày">
                                    </div>
                                    <div class="form-group col-sm-3 col-md-3 col-lg-3">
                                        <label>Nội dung:</label>
                                        <input type="text" name="noidung[]" class="form-control" placeholder="Nội dung">
                                    </div>
                                </div>
                                <div class="col-sm-1 text-center" style="height: 64px; padding: 25px 15px">
                                    <a href="javascript: void(0);" class="minusCPCD">
                                        <i class="fa fa-minus-square " aria-hidden="true"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="saveCPCD" rel="0">
                                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="clearfix" style="clear: both;"></div>
                    <div class="container text-center">
                        <a href="#" id="addChiPhiCoDinh" style="color: #c9902c">
                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                            Thêm
                        </a>

                    </div>
                    <div class="row" style="border-bottom: 1px solid #c9902c">
                        <div class="col-md-3" style="float: right;">
                            <label> Tổng:
                                <strong class="danger"><?php echo number_format($total['total_cpcd']); ?></strong></label>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 30px">
        <div class="container">
            <div class="row">
                <label>
                    <i class="fa fa-hand-o-right"></i>
                    <span>CHI PHÍ PHÁT SINH</span>
                </label>
                <label class="act-ajax">
                    <button type="button" id="save-cpps" class="save-cpps btn btn-primary white">Lưu tất cả</button>
                </label>
            </div>

            <div class="row">
                <form id="form_cpps" method="post">
                    <div id="chiphiphatsinh">
                        <?php if (!empty($lists)) { ?>
                            <?php foreach ($lists as $list) { ?>
                                <?php if ($list['sma_pay_type'] == 1) { ?>
                                    <div class="container cpps">
                                        <div class="col-sm-11">
                                            <input type="hidden" class="form-control" name="sma_pay_type_edit[]" value="1">
                                            <input type="hidden" class="form-control" name="sma_pay_id_edit[]" value="<?php echo $list['sma_pay_id']; ?>">
                                            <div class="form-group col-sm-4 col-md-4 col-lg-4">
                                                <label for="">Số tiền:</label>
                                                <input type="text" name="sotien_edit[]" class="form-control" value="<?php echo number_format($list['sma_pay_pay']); ?>">
                                            </div>
                                            <div class="form-group col-sm-4 col-md-4 col-lg-4">
                                                <label>Ngày nộp:</label>
                                                <input type="text" name="ngaynop_edit[]" class="form-control date" value="<?php echo date('d/m/Y', $list['sma_pay_createtime']); ?>">
                                            </div>
                                            <div class="form-group col-sm-4 col-md-4 col-lg-4">
                                                <label>Nội dung:</label>
                                                <input type="text" name="noidung_edit[]" class="form-control" value="<?php echo $list['sma_pay_note']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-1 text-center" style="height: 64px; padding: 25px 15px">
                                            <a href="javascript: void(0);" class="minusCPPS" rel="<?php echo $list['sma_pay_id']; ?>">
                                                <i class="fa fa-minus-square " aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript: void(0);" class="saveCPPS" rel="<?php echo $list['sma_pay_id']; ?>">
                                                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="container cpps">
                                <div class="col-sm-11">
                                    <input type="hidden" class="form-control" name="sma_pay_type[]" value="1">
                                    <div class="form-group col-sm-4 col-md-4 col-lg-4">
                                        <label for="">Số tiền:</label>
                                        <input type="text" name="sotien[]" class="form-control" placeholder="Nhập số tiền">
                                    </div>
                                    <div class="form-group col-sm-4 col-md-4 col-lg-4">
                                        <label>Ngày nộp:</label>
                                        <input type="text" name="ngaynop[]" class="form-control date" placeholder="Nhập ngày">
                                    </div>
                                    <div class="form-group col-sm-4 col-md-4 col-lg-4">
                                        <label>Nội dung:</label>
                                        <input type="text" name="noidung[]" class="form-control" placeholder="Nội dung">
                                    </div>
                                </div>
                                <div class="col-sm-1 text-center" style="height: 64px; padding: 25px 15px">
                                    <a href="javascript: void(0);" class="minusCPPS">
                                        <i class="fa fa-minus-square " aria-hidden="true"></i>
                                    </a>
                                    <a href="#" class="saveCPPS" rel="0">
                                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="clearfix" style="clear: both;"></div>
                    <div class="container text-center">
                        <a href="#" style="color: #c9902c" id="addChiPhiPhatSinh">
                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                            Thêm
                        </a>
                    </div>
                </form>

            </div>
            <div class="row" style="border-bottom: 1px solid #c9902c">
                <div class="col-md-3" style="float: right;">
                    <label> Tổng:
                        <strong class="danger"><?php echo number_format($total['total_cpps']); ?></strong></label>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 30px;margin-bottom: 200px;">
        <div class="container">
            <div class="text-center">
                <label> Tổng: <strong class="danger"><?php echo number_format($total['total']); ?></strong></label>
            </div>
        </div>
    </div>
</div>