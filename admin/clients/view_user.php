<?php
if (!isset($_GET['id'])) {
    echo '<script>alert("Client ID is required."); window.location.href = "' . base_url . 'admin/?page=clients";</script>';
    exit;
}
$id = $_GET['id'];

$qry = $conn->query("SELECT * FROM client_list where id = '{$id}'");
if ($qry->num_rows <= 0) {
    echo '<script>alert("Client not found."); window.location.href = "' . base_url . 'admin/?page=clients";</script>';
    exit;
}
foreach ($qry->fetch_array() as $k => $v) {
    $$k = $v;
}
?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title">Client Details - <?php echo $name ?></h4>
    </div>
    <div class="card-body" id="print_out">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-info">Member Since</label>
                        <div><?php echo isset($date_created) ? date("d-m-Y", strtotime($date_created)) : '' ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="control-label text-info">Name</label>
                        <div><?php echo isset($name) ? $name : '' ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-info">Email</label>
                        <div><?php echo isset($email) ? $email : '' ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label text-info">Contact</label>
                        <div><?php echo isset($contact) ? $contact : '' ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label text-info">Address</label>
                        <div><?php echo isset($address) ? $address : '' ?></div>
                    </div>
                </div>
            </div>
            <hr>
            <h4 class="text-info">Purchase History</h4>
            <table class="table table-striped table-bordered" id="list">
                <colgroup>
                    <col width="15%">
                    <col width="20%">
                    <col width="25%">
                    <col width="15%">
                    <col width="10%">
                    <col width="15%">
                </colgroup>
                <thead>
                    <tr class="text-light bg-navy">
                        <th class="text-center py-1 px-2">Date</th>
                        <th class="text-center py-1 px-2">Sales Code</th>
                        <th class="text-center py-1 px-2">Item</th>
                        <th class="text-center py-1 px-2">Price</th>
                        <th class="text-center py-1 px-2">Qty</th>
                        <th class="text-center py-1 px-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    $qry = $conn->query("SELECT s.date_created, s.sales_code, 
                                              sl.quantity, sl.price, sl.total,
                                              i.name, i.description 
                                       FROM sales_list s 
                                       INNER JOIN stock_list sl ON FIND_IN_SET(sl.id, s.stock_ids)
                                       INNER JOIN item_list i ON sl.item_id = i.id 
                                       WHERE s.client_id = '{$id}'
                                       ORDER BY s.date_created DESC");
                    while ($row = $qry->fetch_assoc()):
                        $total += $row['total'];
                        ?>
                        <tr>
                            <td class="py-1 px-2 text-center"><?php echo date("Y-m-d", strtotime($row['date_created'])) ?>
                            </td>
                            <td class="py-1 px-2 text-center"><?php echo $row['sales_code'] ?></td>
                            <td class="py-1 px-2">
                                <?php echo $row['name'] ?><br>
                                <small><i><?php echo $row['description'] ?></i></small>
                            </td>
                            <td class="py-1 px-2 text-right"><?php echo number_format($row['price'], 2) ?></td>
                            <td class="py-1 px-2 text-center"><?php echo $row['quantity'] ?></td>
                            <td class="py-1 px-2 text-right"><?php echo number_format($row['total'], 2) ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if ($qry->num_rows <= 0): ?>
                        <tr>
                            <td class="py-1 px-2 text-center" colspan="6">No purchase history found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="5">Total Purchases</th>
                        <th class="text-right py-1 px-2 grand-total"><?php echo number_format($total, 2) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="card-footer py-1 text-center">
        <button class="btn btn-flat btn-success" type="button" id="print">Print</button>
        <a class="btn btn-flat btn-primary"
            href="<?php echo base_url . '/admin?page=clients/manage_user&id=' . (isset($id) ? $id : '') ?>">Edit</a>
        <a class="btn btn-flat btn-dark" href="<?php echo base_url . '/admin?page=clients' ?>">Back To List</a>
    </div>
</div>
<script>
    $(function () {
        $('#print').click(function () {
            start_loader();
            var _el = $('<div>');
            var _head = $('head').clone();
            _head.find('title').text("Client Details - Print View");
            var p = $('#print_out').clone();
            p.find('tr.text-light').removeClass("text-light bg-navy");
            _el.append(_head);
            _el.append('<div class="d-flex justify-content-center">' +
                '<div class="col-1 text-right">' +
                '<img src="<?php echo validate_image($_settings->info('logo')) ?>" width="65px" height="65px" />' +
                '</div>' +
                '<div class="col-10">' +
                '<h4 class="text-center"><?php echo $_settings->info('name') ?></h4>' +
                '<h4 class="text-center">Client Details</h4>' +
                '</div>' +
                '<div class="col-1 text-right">' +
                '</div>' +
                '</div><hr/>');
            _el.append(p.html());
            var nw = window.open("", "", "width=1200,height=900,left=250,location=no,titlebar=yes");
            nw.document.write(_el.html());
            nw.document.close();
            setTimeout(() => {
                nw.print();
                setTimeout(() => {
                    nw.close();
                    end_loader();
                }, 200);
            }, 500);
        });
    });
</script>