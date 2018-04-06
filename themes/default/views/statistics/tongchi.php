<script>
    $(document).ready(function () {
        var danhmucCPCD = '<?php
        $type = $this->config->config["danhmucodinh"];
        echo preg_replace("/[\r\n]*/","",form_dropdown('sma_pay_danhmuc_index', $type, (isset($_POST['type']) ? $_POST['type'] : [0] ) , 'class="form-control select" id="month1" placeholder="' . lang("Loại") . " " . lang() . '" style="width:100%"'));
        ?>';
        var addRowCPCD = '<div class="container cpcd">\n' +
            '                        <div class="col-lg-11">' +
            '                           <div class="form-group col-md-3 col-lg-3">'+
            '                               <label for="">Loại:</label>' +
                                            danhmucCPCD+
            '                              </div>'+
            '                            <div class="form-group col-md-3 col-lg-3">\n' +
            '                                <label for="">Số tiền:</label>\n' +
            '                                <input type="text" class="form-control"  placeholder="Nhập số tiền">\n' +
            '                            </div>\n' +
            '                            <div class="form-group col-md-3 col-lg-3">\n' +
            '                                <label >Ngày nộp:</label>\n' +
            '                                <input type="date" class="form-control"  placeholder="Nhập ngày">\n' +
            '                            </div>\n' +
            '                            <div class="form-group col-md-3 col-lg-3">\n' +
            '                                <label >Nội dung:</label>\n' +
            '                                <input type="text" class="form-control"  placeholder="Nội dung">\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                        <div class="col-lg-1 text-center" style="height: 64px; padding: 25px">\n' +
            '                            <a href="#" class="minusCPCD">\n' +
            '                                <i class="fa fa-minus-square " aria-hidden="true"></i>\n' +
            '                            </a>\n' +
            '                            <a href="#" class="saveCPCD">\n' +
            '                                <i class="fa fa-floppy-o" aria-hidden="true"></i>\n' +
            '                            </a>\n' +
            '                        </div>\n' +
            '                    </div>';
        $('#addChiPhiCoDinh').on("click", function (e) {
            $('#chiphicodinh').append(addRowCPCD);
            return false;
        });
        $(document).on("click", '.minusCPCD', function (e) {
            $(this).closest(".cpcd").remove();
            return false;
        });

        $(document).on("click", '.saveCPCD', function (e) {
            console.log('save');
            return false;
        });


        var addRowCPPS = '<div class="container cpps">\n' +
            '                            <div class="col-lg-11">\n' +
            '                                <div class="form-group col-md-4 col-lg-4">\n' +
            '                                    <label for="">Số tiền:</label>\n' +
            '                                    <input type="text" class="form-control"  placeholder="Nhập số tiền">\n' +
            '                                </div>\n' +
            '                                <div class="form-group col-md-4 col-lg-4">\n' +
            '                                    <label >Ngày nộp:</label>\n' +
            '                                    <input type="date" class="form-control"  placeholder="Nhập ngày">\n' +
            '                                </div>\n' +
            '                                <div class="form-group col-md-4 col-lg-4">\n' +
            '                                    <label >Nội dung:</label>\n' +
            '                                    <input type="text" class="form-control"  placeholder="Nội dung">\n' +
            '                                </div>\n' +
            '                            </div>\n' +
            '                            <div class="col-lg-1 text-center visible-lg-inline" style="height: 64px; padding: 25px">\n' +
            '                                <a href="#" class="minusCPPS">\n' +
            '                                    <i class="fa fa-minus-square " aria-hidden="true"></i>\n' +
            '                                </a>\n' +
            '                                <a href="#" class="saveCPPS">\n' +
            '                                    <i class="fa fa-floppy-o" aria-hidden="true"></i>\n' +
            '                                </a>\n' +
            '                            </div>\n' +
            '                        </div>';

        $('#addChiPhiPhatSinh').on("click", function (e) {
            $('#chiphiphatsinh').append(addRowCPPS);
            return false;
        });
        $(document).on("click", '.minusCPPS', function (e) {
            $(this).closest(".cpps").remove();
            return false;
        });

        $(document).on("click", '.saveCPPS', function (e) {
            console.log('save');
            return false;
        });
    })



</script>
<div class="main-menu col-xs-12 user sales" >
    <div class="title-menu con-xs-12">
        <span>TỔNG CHI</span>
    </div>
    <div class="row" style="margin-top: 30px">
        <div class="container">
            <div class="row">
                <label>
                    <i class="fa fa-hand-o-right"></i>
                    <span>CHI PHÍ CỐ ĐỊNH THÁNG</span>
                </label>
            </div>

            <div class="row">
                <form>
                    <div id="chiphicodinh">
                        <div class="container cpcd">
                            <div class="col-lg-11">
                                <div class="form-group col-md-3 col-lg-3">
                                    <label for="">Loại:</label>
                                    <?php
                                    $type = $this->config->config["danhmucodinh"];
                                    echo form_dropdown('sma_pay_danhmuc_index', $type, (isset($_POST['type']) ? $_POST['type'] : [0] ) , 'class="form-control select" id="month1" placeholder="' . lang("Loại") . " " . lang() . '" style="width:100%"');

                                    ?>
                                </div>
                                <div class="form-group col-md-3 col-lg-3">
                                    <label for="">Số tiền:</label>
                                    <input type="text" class="form-control"  placeholder="Nhập số tiền">
                                </div>
                                <div class="form-group col-md-3 col-lg-3">
                                    <label >Ngày nộp:</label>
                                    <input type="date" class="form-control"  placeholder="Nhập ngày">
                                </div>
                                <div class="form-group col-md-3 col-lg-3">
                                    <label >Nội dung:</label>
                                    <input type="text" class="form-control"  placeholder="Nội dung">
                                </div>
                            </div>
                            <div class="col-lg-1 text-center" style="height: 64px; padding: 25px">
                                <a href="#" class="minusCPCD">
                                    <i class="fa fa-minus-square " aria-hidden="true"></i>
                                </a>
                                <a href="#" class="saveCPCD">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="container text-center">
                        <a href="#" id="addChiPhiCoDinh" style="color: #c9902c">
                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                            Thêm
                        </a>

                    </div>
                    <div class="row" style="border-bottom: 1px solid #c9902c">
                        <div class="col-md-3" style="float: right;">
                            <label> Tổng: </label>
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
            </div>

            <div class="row">
                <form>
                    <div id="chiphiphatsinh">
                        <div class="container cpps">
                            <div class="col-lg-11">
                                <div class="form-group col-md-4 col-lg-4">
                                    <label for="">Số tiền:</label>
                                    <input type="text" class="form-control"  placeholder="Nhập số tiền">
                                </div>
                                <div class="form-group col-md-4 col-lg-4">
                                    <label >Ngày nộp:</label>
                                    <input type="date" class="form-control"  placeholder="Nhập ngày">
                                </div>
                                <div class="form-group col-md-4 col-lg-4">
                                    <label >Nội dung:</label>
                                    <input type="text" class="form-control"  placeholder="Nội dung">
                                </div>
                            </div>
                            <div class="col-lg-1 text-center visible-lg-inline" style="height: 64px; padding: 25px">
                                <a href="#" class="minusCPPS">
                                    <i class="fa fa-minus-square " aria-hidden="true"></i>
                                </a>
                                <a href="#" class="saveCPPS">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
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
                    <label> Tổng: </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 30px">
        <div class="container">
            <div class="text-center" >
                <label> TỔNG: 1111111</label>
            </div>
        </div>
    </div>
</div>