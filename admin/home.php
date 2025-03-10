<h1 class="">Welcome to <?php echo $_settings->info('name') ?></h1>
<hr>
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="card shadow">
            <div class="card-header bg-info">
                <h3 class="card-title text-white">
                    <i class="fas fa-th-list mr-2"></i>
                    Purchases
                </h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Pending Orders
                        <span class="badge badge-warning badge-pill">
                            <?php
                            echo $conn->query("SELECT * FROM `purchase_order_list` WHERE status = 0")->num_rows;
                            ?>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Received Orders
                        <span class="badge badge-success badge-pill">
                            <?php
                            echo $conn->query("SELECT * FROM `purchase_order_list` WHERE status = 1")->num_rows;
                            ?>
                        </span>
                    </li>
                </ul>
            </div>
            <div class="card-footer text-center">
                <a href="<?php echo base_url ?>admin/?page=purchase_order" class="btn btn-info btn-sm">
                    <i class="fas fa-eye mr-1"></i>View All Purchases
                </a>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-boxes"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Receiving Records</span>
                <span class="info-box-number text-right">
                    <?php
                    echo $conn->query("SELECT * FROM `receiving_list`")->num_rows;
                    ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-exchange-alt"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">BO Records</span>
                <span class="info-box-number text-right">
                    <?php
                    echo $conn->query("SELECT * FROM `back_order_list`")->num_rows;
                    ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-undo"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Return Records</span>
                <span class="info-box-number text-right">
                    <?php
                    echo $conn->query("SELECT * FROM `return_list`")->num_rows;
                    ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-file-invoice-dollar"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Sales Records</span>
                <span class="info-box-number text-right">
                    <?php
                    echo $conn->query("SELECT * FROM `sales_list`")->num_rows;
                    ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-navy elevation-1"><i class="fas fa-truck-loading"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Suppliers</span>
                <span class="info-box-number text-right">
                    <?php
                    echo $conn->query("SELECT * FROM `supplier_list` where `status` = 1")->num_rows;
                    ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-lightblue elevation-1"><i class="fas fa-th-list"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Items</span>
                <span class="info-box-number text-right">
                    <?php
                    echo $conn->query("SELECT * FROM `item_list` where `status` = 1")->num_rows;
                    ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <?php if ($_settings->userdata('type') == 1): ?>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box bg-light shadow">
                <span class="info-box-icon bg-teal elevation-1"><i class="fas fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Users</span>
                    <span class="info-box-number text-right">
                        <?php
                        echo $conn->query("SELECT * FROM `users` where id != 1 ")->num_rows;
                        ?>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
    <?php endif; ?>
</div>