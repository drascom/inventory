</style>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand">
  <!-- Brand Logo -->
  <a href="<?php echo base_url ?>admin" class="brand-link bg-primary p-0">
    <img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="Store Logo" class=" "
      style="width: -webkit-fill-available;">
  </a>
  <!-- Sidebar -->
  <div
    class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden">
    <!-- Sidebar Menu -->
    <nav class="mt-4">
      <ul
        class="nav nav-pills nav-sidebar flex-column text-md nav-compact nav-flat nav-child-indent nav-collapse-hide-child"
        data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
          <a href="./" class="nav-link nav-home text-lg">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=purchase_order" class="nav-link nav-purchase_order">
            <i class="nav-icon fas fa-th-list"></i>
            <p>Buying Orders</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=receiving" class="nav-link nav-receiving">
            <i class="nav-icon fas fa-boxes"></i>
            <p>Receiving Item</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=return" class="nav-link nav-return">
            <i class="nav-icon fas fa-undo"></i>
            <p>Create Return</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=back_order" class="nav-link nav-back_order">
            <i class="nav-icon fas fa-exchange-alt"></i>
            <p>Back Orders</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=clients" class="nav-link nav-clients">
            <i class="nav-icon fas fa-users"></i>
            <p>Clients</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=sales" class="nav-link nav-sales">
            <i class="nav-icon fas fa-file-invoice-dollar"></i>
            <p>Sales</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=stocks" class="nav-link nav-stocks">
            <i class="nav-icon fas fa-boxes"></i>
            <p>Stocks</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=maintenance/supplier" class="nav-link nav-maintenance_supplier">
            <i class="nav-icon fas fa-truck-loading"></i>
            <p>Suppliers</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?php echo base_url ?>admin/?page=maintenance/item" class="nav-link nav-maintenance_item">
            <i class="nav-icon fas fa-box"></i>
            <p>Products</p>
          </a>
        </li>

        <?php if ($_settings->userdata('type') == 1): ?>
          <li class="nav-header text-lg">Maintenance</li>
          <li class="nav-item">
            <a href="<?php echo base_url ?>admin/?page=user/list" class="nav-link nav-user_list">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>Users</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url ?>admin/?page=maintenance/unit" class="nav-link nav-maintenance_unit">
              <i class="nav-icon fas fa-ruler"></i>
              <p>Units</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url ?>admin/?page=system_info" class="nav-link nav-system_info">
              <i class="nav-icon fas fa-cogs"></i>
              <p>Settings</p>
            </a>
          </li>
        <?php endif; ?>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
</aside>

<script>
  $(document).ready(function () {
    var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
    page = page.replace(/\//gi, '_');

    if ($('.nav-link.nav-' + page).length > 0) {
      $('.nav-link.nav-' + page).addClass('active')

      if ($('.nav-link.nav-' + page).hasClass('tree-item') == true) {
        $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active')
        $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open')
      }

      if ($('.nav-link.nav-' + page).hasClass('nav-is-tree') == true) {
        $('.nav-link.nav-' + page).parent().addClass('menu-open')
      }
    }

    $('.nav-link.active').parents('.nav-item').each(function () {
      $(this).addClass('menu-open')
      $(this).find('> .nav-link').addClass('active')
    });
  });
</script>