<h1 class="">Welcome to <?php echo $_settings->info('name') ?></h1>
<hr>
<div class="row">
    <div class="col-12 col-sm-4">
        <div class="card shadow">
            <div class="card-header bg-lightblue">
                <h3 class="card-title text-white">
                    <i class="fas fa-th-list mr-2"></i>
                    Purchases
                </h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div>Pending Orders</div>
                            <div>
                                <span class="badge badge-warning badge-pill mr-2">
                                    <?php
                                    $pending_query = $conn->query("SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total FROM `purchase_order_list` WHERE status = 0");
                                    $pending = $pending_query->fetch_assoc();
                                    echo $pending['count'];
                                    ?>
                                </span>
                                <span class="badge badge-info">
                                    £<?php echo number_format($pending['total'], 2); ?>
                                </span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div>Received Orders</div>
                            <div>
                                <span class="badge badge-success badge-pill mr-2">
                                    <?php
                                    $received_query = $conn->query("SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total FROM `purchase_order_list` WHERE status = 2");
                                    $received = $received_query->fetch_assoc();
                                    echo $received['count'];
                                    ?>
                                </span>
                                <span class="badge badge-info">
                                    £<?php echo number_format($received['total'], 2); ?>
                                </span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div>Back Orders</div>
                            <div>
                                <span class="badge badge-success badge-pill mr-2">
                                    <?php
                                    $backorder_query = $conn->query("SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total FROM `back_order_list`");
                                    $backorder = $backorder_query->fetch_assoc();
                                    echo $backorder['count'];
                                    ?>
                                </span>
                                <span class="badge badge-info">
                                    £<?php echo number_format($backorder['total'], 2); ?>
                                </span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="card-footer text-center">
                <a href="<?php echo base_url ?>admin/?page=purchase_order" class="btn btn-info btn-sm">
                    <i class="fas fa-eye mr-1"></i>View All Purchases
                </a>
            </div>
        </div>
        <div class="card shadow">
            <div class="card-header bg-teal">
                <h3 class="card-title text-white">
                    <i class="fas fa-th-list mr-2"></i>
                    Sales
                </h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div>Sales</div>
                            <div>
                                <span class="badge badge-warning badge-pill mr-2">
                                    <?php
                                    $sales_query = $conn->query("SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total FROM `sales_list`");
                                    $sales = $sales_query->fetch_assoc();
                                    echo $sales['count'];
                                    ?>
                                </span>
                                <span class="badge badge-info">
                                    £<?php echo number_format($sales['total'], 2); ?>
                                </span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div>Returns</div>
                            <div>
                                <span class="badge badge-success badge-pill mr-2">
                                    <?php
                                    $returns_query = $conn->query("SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total FROM `return_list`");
                                    $returns = $returns_query->fetch_assoc();
                                    echo $returns['count'];
                                    ?>
                                </span>
                                <span class="badge badge-info">
                                    £<?php echo number_format($returns['total'], 2); ?>
                                </span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div>Gifts</div>
                            <div>
                                <span class="badge badge-success badge-pill mr-2">
                                    <?php
                                    $gifts_query = $conn->query("SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total FROM `back_order_list`");
                                    $gifts = $gifts_query->fetch_assoc();
                                    echo $gifts['count'];
                                    ?>
                                </span>
                                <span class="badge badge-info">
                                    £<?php echo number_format($gifts['total'], 2); ?>
                                </span>
                            </div>
                        </div>
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
    <div class="col-sm-8 col-12">
        <div class="card shadow">
            <div class="card-header bg-primary">
                <h3 class="card-title text-white">
                    <i class="fas fa-chart-line mr-2"></i>
                    Monthly Sales Overview
                </h3>
            </div>
            <div class="card-body">
                <div id="salesChart" style="width: 100%; height: 420px;"></div>
            </div>
        </div>
    </div>
</div>
<script>
    // <!-- Common Chart Functions -->

    function createMonthlyChart(elementId, dataUrl) {
        fetch(dataUrl)
            .then(response => response.json())
            .then(data => {
                const options = {
                    width: document.getElementById(elementId).offsetWidth,
                    height: 420,
                    title: "Monthly Overview",
                    series: [
                        {},
                        {
                            label: "Sales",
                            stroke: "rgba(0,123,255,1)",
                            fill: "rgba(0,123,255,0.1)",
                            points: {
                                show: true
                            }
                        },
                        {
                            label: "Purchases",
                            stroke: "rgba(220,53,69,1)",
                            fill: "rgba(220,53,69,0.1)",
                            points: {
                                show: true
                            }
                        }
                    ],
                    scales: {
                        x: {
                            time: false
                        }
                    },
                    axes: [
                        {
                            label: "Month",
                            values: (self, splits) => splits.map(i => data.labels[i])
                        },
                        {
                            label: "Amount",
                            values: (self, splits) => splits.map(v => '₱' + v.toFixed(2))
                        }
                    ]
                };

                new uPlot(options, [
                    Array.from({ length: data.sales.length }, (_, i) => i),
                    data.sales,
                    data.purchases
                ], document.getElementById(elementId));
            });
    }

    // Handle window resize for responsive charts
    window.addEventListener('resize', function () {
        const charts = document.querySelectorAll('[id$="Chart"]');
        charts.forEach(chart => {
            if (chart.__uplot) {
                chart.__uplot.setSize({
                    width: chart.offsetWidth,
                    height: chart.__uplot.height
                });
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        createMonthlyChart('salesChart', 'sales/get_monthly_sales.php');
    });
</script>