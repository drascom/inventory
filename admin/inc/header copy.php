<?php
require_once('sess_auth.php');
?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>
    <?php echo $_settings->info('title') != false ? $_settings->info('title') . ' | ' : '' ?><?php echo $_settings->info('name') ?>
  </title>
  <link rel="icon" href="<?php echo validate_image($_settings->info('logo')) ?>" />
  <!-- Google Font: Source Sans Pro -->
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback"> -->
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet"
    href="<?php echo base_url ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url ?>dist/css/adminlte.css">
  <link rel="stylesheet" href="<?php echo base_url ?>dist/css/custom.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/summernote/summernote-bs4.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- uPlot -->
  <link rel="stylesheet" href="<?php echo base_url ?>plugins/uplot/uPlot.min.css">

  <style type="text/css">
    /* Chart.js */
    @keyframes chartjs-render-animation {
      from {
        opacity: .99
      }

      to {
        opacity: 1
      }
    }

    .chartjs-render-monitor {
      animation: chartjs-render-animation 1ms
    }

    .chartjs-size-monitor,
    .chartjs-size-monitor-expand,
    .chartjs-size-monitor-shrink {
      position: absolute;
      direction: ltr;
      left: 0;
      top: 0;
      right: 0;
      bottom: 0;
      overflow: hidden;
      pointer-events: none;
      visibility: hidden;
      z-index: -1
    }

    .chartjs-size-monitor-expand>div {
      position: absolute;
      width: 1000000px;
      height: 1000000px;
      left: 0;
      top: 0
    }

    .chartjs-size-monitor-shrink>div {
      position: absolute;
      width: 200%;
      height: 200%;
      left: 0;
      top: 0
    }
  </style>

  <!-- jQuery -->
  <script src="<?php echo base_url ?>plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="<?php echo base_url ?>plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="<?php echo base_url ?>plugins/sweetalert2/sweetalert2.min.js"></script>
  <!-- Toastr -->
  <script src="<?php echo base_url ?>plugins/toastr/toastr.min.js"></script>
  <!-- uPlot -->
  <script src="<?php echo base_url ?>plugins/uplot/uPlot.iife.min.js"></script>

  <script>
    var _base_url_ = '<?php echo base_url ?>';
  </script>
  <script src="<?php echo base_url ?>dist/js/script.js"></script>

  <!-- Common Chart Functions -->
  <script>
    function createMonthlyChart(elementId, endpoint) {
      fetch(_base_url_ + endpoint)
        .then(response => response.json())
        .then(data => {
          const options = {
            title: "Monthly Overview",
            width: document.getElementById(elementId).offsetWidth,
            height: 400,
            series: [
              {},
              {
                label: "Total",
                stroke: "rgba(0,123,255,1)",
                fill: "rgba(0,123,255,0.1)",
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
            Array.from({ length: data.values.length }, (_, i) => i),
            data.values
          ], document.getElementById(elementId));
        })
        .catch(error => console.error('Error loading chart:', error));
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
  </script>
</head>