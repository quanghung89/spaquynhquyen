<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    $(document).ready(function () {
        'use strict';
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawMultSeries);

        function drawMultSeries() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Nhân viên');

            //1  lay ra danh sach cac cong viec
            //2 lay ra danh sach nhan vien so luong cong viec tuong tung
            <?php
                for($i=0;$i<count($categories);$i++){
                    $category = $categories[$i];
            ?>
                data.addColumn('number', '<?=$category->name?>' );
            <?php  }
            ?>

            <?php
                if (isset($nangsuat)){ ?>
                        var nangsuat = <?php echo json_encode($nangsuat, JSON_PRETTY_PRINT) ?>;

                        data.addRows(nangsuat);
                    <?php }
            ?>


            var options = {
                title: 'Năng suất nhân viên',
                hAxis: {
                    title: 'Nhân viên',

                },
                vAxis: {
                    title: 'Khách'
                },
                bar : {
                    groupWidth: "20%"
                }
            };

            var chart = new google.visualization.ColumnChart(
                document.getElementById('chart_div'));

            chart.draw(data, options);
        }
    });
</script>
<style>.table td:nth-child(6) {
        text-align: right;
        width: 10%;
    }

    .table td:nth-child(8) {
        text-align: center;
    }</style>
<?php if ($Owner) {
    
} ?>
<div class="box">

    <div class="title-menu con-xs-12" style="margin: 45px 0px;">
         <span>NĂNG SUẤT NHÂN VIÊN</span>
    </div>

<?php echo form_open('auth/check_user', 'id="action-form"'); ?>
    <div id="restTable" class="hidden">123</div>

    <div class="product_actions col-xs-12" style="padding: 0px; margin-bottom: 15px;">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-12">

            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <div class="form-group">
                    <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control date" placeholder="Từ ngày" id="start_date"'); ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <div class="form-group">
                    <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control date" placeholder="Đến ngày" id="end_date"'); ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <div class="form-group">
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
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3" style="padding-left: 0px;">
                <?php echo form_submit('submit_form', 'Lọc', 'class="btn-warning default  btn"'); ?>
            </div>

       </div>
    </div> 
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <div id="chart_div"></div>

            </div>

        </div>
    </div>
</div>
<?= form_close() ?>
