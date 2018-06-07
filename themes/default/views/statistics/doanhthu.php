<style type="text/css">
    .subtitle {font-weight: bold;}
    .subtitle span.fa {color: #f0ad4e;margin-right: 5px;}
    .p-x-150 {padding: 0 150px;}
    .p-x-250 {padding: 0 300px;}
    .m-y-30 {margin-top: 30px;margin-bottom: 30px;}
</style>

<script type="text/javascript">
    $(document).ready(function () {

    });
</script>

<?php //var_dump($warehouses);die; ?>

<div class="col-xs-12 user sales">
    <div class="title-menu con-xs-12" style="margin: 45px 0px;">
        <span>LÃI</span>
    </div>

    <div class="row text-center filter-date">
        <div class="col-xs-12">
            <form method="post" action="<?php echo base_url(); ?>statistics/doanhthu" class="filter-form">
                <span>Từ ngày: <input type="text" name="date_from" class="input input-sm date" value="<?php echo $date_from; ?>"></span>
                <span>Đến ngày: <input type="text" name="date_to" class="input input-sm date" value="<?php echo $date_to; ?>"></span>
                <input type="submit" class="btn btn-sm btn-warning" value="Lọc">
            </form>
        </div>
    </div>

    <div class="box-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="subtitle">
                    <span class="fa fa-eye"></span><span>TỔNG CHI: <strong class="text-danger"><?php echo number_format($total['total_cpcd'] + $total['total_luong'] + $total['total_cpps'] + $total['total_cpvl']); ?> VNĐ</strong></span>
                </div>
            </div>
            <div class="col-sm-12 p-x-150 m-y-30">
                <div class="col-xs-12 col-md-3">
                    <label>Chi phí hàng tháng</label>
                    <input type="text" class="month cpcd form-control" value="<?php echo number_format($total['total_cpcd']); ?>">
                </div>
                <div class="col-xs-12 col-md-3">
                    <label>Lương</label>
                    <input type="text" class="luong form-control" value="<?php echo number_format($total['total_luong']); ?>">
                </div>
                <div class="col-xs-12 col-md-3">
                    <label>Chi phí phát sinh</label>
                    <input type="text" class="cpps form-control" value="<?php echo number_format($total['total_cpps']); ?>">
                </div>
                <div class="col-xs-12 col-md-3">
                    <label>Chi phí vật liệu</label>
                    <input type="text" class="cpvl form-control" value="<?php echo number_format($total['total_cpvl']); ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="subtitle">
                    <span class="fa fa-eye"></span><span>TỔNG THU: <strong class="text-success"><?php echo number_format($total['total_dichvu'] + $total['total_sp']); ?> VNĐ</strong></span>
                </div>
            </div>
            <div class="col-sm-12 p-x-250 m-y-30">
                <div class="col-xs-12 col-md-4 left">
                    <label>Lãi từ dịch vụ</label>
                    <input type="text" class="month form-control" value="<?php echo number_format($total['total_dichvu']); ?>">
                </div>
                <div class="col-xs-12 col-md-4 center">
                    <label>Lãi bán sản phẩm</label>
                    <input type="text" class="luong form-control" value="<?php echo number_format($total['total_sp']); ?>">
                </div>
                <div class="col-xs-12 col-md-4 right">
                    <label>Tổng lãi</label>
                    <input type="text" class="cpps form-control" value="<?php echo number_format($total['total_dichvu'] + $total['total_sp']); ?>">
                </div>
            </div>
        </div>
    </div>
</div>