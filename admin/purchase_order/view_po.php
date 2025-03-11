<?php
$qry = $conn->query("SELECT p.*,s.name as supplier FROM purchase_order_list p inner join supplier_list s on p.supplier_id = s.id  where p.id = '{$_GET['id']}'");
if ($qry->num_rows > 0) {
    foreach ($qry->fetch_array() as $k => $v) {
        $$k = $v;
    }
}
$unit_qry = $conn->query("SELECT * FROM `units` order by `name` asc");
$unit_arr = array();
while ($row = $unit_qry->fetch_assoc()) {
    $unit_arr[$row['id']] = $row;
}
$has_receiving = $conn->query("SELECT COUNT(*) as count FROM receiving_list WHERE purchase_id = '{$id}' AND from_order = 1")->fetch_assoc()['count'] > 0;
?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title">Purchase Order Details - <?php echo $po_code ?></h4>
    </div>
    <?php if (isset($_GET['action'])): ?>
        <div class="px-5">
            <div class="alert alert-info d-flex justify-content-between align-items-center">
                <span>Your order has been recorded. You can go back to the list</span>
                <div class="">
                    <button class="btn btn-flat btn-success" type="button" id="print">Print</button>
                    <a class="btn btn-flat btn-dark" href="<?php echo base_url . '/admin?page=purchase_order' ?>">Back To
                        List</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="card-body" id="print_out">
        <div class="container-fluid">
            <fieldset style="border: 2px solid #dee2e6; border-radius: 8px;" class="p-4 pt-2 mb-2">
                <div class="row">
                    <div class="col-sm-2  col-6">
                        <label class="control-label text-info">P.O. Code</label>
                        <div><?php echo isset($po_code) ? $po_code : '' ?></div>
                    </div>
                    <div class="col-sm-2 col-6">
                        <div class="form-group">
                            <label for="supplier_id" class="control-label text-info">Supplier</label>
                            <div><?php echo isset($supplier) ? $supplier : '' ?></div>
                        </div>
                    </div>
                    <?php if ($status > 0): ?>
                        <div class="col-sm-4 col-12 border bg-light d-flex align-items-center justify-content-center">
                            <span class="h4 <?php echo ($status == 2) ? 'text-success' : 'text-danger' ?>">
                                <?php echo ($status == 2) ? "RECEIVED" : "PARTIALLY RECEIVED" ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    <div class="col-sm-3 col-12">
                        <div class="form-group  d-flex align-items-center justify-content-center">
                            <fieldset>
                                <legend class="text-info">Remarks</legend>
                                <?php echo isset($remarks) ? $remarks : '' ?>
                            </fieldset>

                        </div>
                    </div>

                </div>
            </fieldset>
            <h4 class="text-info">Orders</h4>
            <table class="table table-striped table-bordered" id="list">
                <colgroup>
                    <col width="10%">
                    <col width="10%">
                    <col width="30%">
                    <col width="25%">
                    <col width="25%">
                </colgroup>
                <thead>
                    <tr class="text-light bg-navy">
                        <th class="text-center py-1 px-2">Qty</th>
                        <th class="text-center py-1 px-2">Unit</th>
                        <th class="text-center py-1 px-2">Item</th>
                        <th class="text-center py-1 px-2">Cost</th>
                        <th class="text-center py-1 px-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    $qry = $conn->query("SELECT p.*,i.name,i.description FROM `po_items` p inner join item_list i on p.item_id = i.id where p.po_id = '{$id}'");
                    while ($row = $qry->fetch_assoc()):
                        $total += $row['total']
                            ?>
                        <tr>
                            <td class="py-1 px-2 text-center"><?php echo number_format($row['quantity'], 2) ?></td>
                            <td class="py-1 px-2 text-center"> <?php echo $unit_arr[$row['unit']]['name']; ?></td>
                            <td class="py-1 px-2">
                                <?php echo $row['name'] ?> <br>
                                <?php echo $row['description'] ?>
                            </td>
                            <td class="py-1 px-2 text-right"><?php echo number_format($row['price']) ?></td>
                            <td class="py-1 px-2 text-right"><?php echo number_format($row['total']) ?></td>
                        </tr>

                    <?php endwhile; ?>

                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Sub Total</th>
                        <th class="text-right py-1 px-2 sub-total"><?php echo number_format($total, 2) ?></th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Discount
                            <?php echo isset($discount_perc) ? $discount_perc : 0 ?>%
                        </th>
                        <th class="text-right py-1 px-2 discount">
                            <?php echo isset($discount) ? number_format($discount, 2) : 0 ?>
                        </th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Tax <?php echo isset($tax_perc) ? $tax_perc : 0 ?>%
                        </th>
                        <th class="text-right py-1 px-2 tax"><?php echo isset($tax) ? number_format($tax, 2) : 0 ?></th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Total</th>
                        <th class="text-right py-1 px-2 grand-total">
                            <?php echo isset($amount) ? number_format($amount, 2) : 0 ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
            <?php
            // (isset($status) && $status !== null && $status != 2) &&
            if ($has_receiving):
                ?>
                <div class="mt-4">
                    <h4 class="text-info">Related Receiving Records</h4>
                    <table class="table table-striped table-bordered">
                        <colgroup>
                            <col width="10%">
                            <col width="10%">
                            <col width="30%">
                            <col width="25%">
                            <col width="25%">
                        </colgroup>
                        <thead>
                            <tr class="text-light bg-navy">
                                <th class="text-center py-1 px-2">Qty</th>
                                <th class="text-center py-1 px-2">Unit</th>
                                <th class="text-center py-1 px-2">Item</th>
                                <th class="text-center py-1 px-2">Cost</th>
                                <th class="text-center py-1 px-2">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            $receiving_qry = $conn->query("
                                SELECT r.* FROM receiving_list r 
                                WHERE r.purchase_id = '{$id}' AND r.from_order = 1 
                                ORDER BY r.date_created DESC
                            ");
                            while ($row = $receiving_qry->fetch_assoc()):
                                if (!empty($row['stock_ids'])) {
                                    $items_qry = $conn->query("
                                        SELECT s.*, i.name, i.description 
                                        FROM stock_list s 
                                        INNER JOIN item_list i ON s.item_id = i.id 
                                        WHERE s.id IN ({$row['stock_ids']})
                                    ");
                                    while ($item = $items_qry->fetch_assoc()):
                                        $total += $item['total'];
                                        ?>
                                        <tr>
                                            <td class="py-1 px-2 text-center"><?php echo number_format($item['quantity'], 2) ?></td>
                                            <td class="py-1 px-2 text-center"><?php echo $unit_arr[$item['unit']]['name'] ?></td>
                                            <td class="py-1 px-2">
                                                <?php echo $item['name'] ?> <br>
                                                <?php echo $item['description'] ?>
                                            </td>
                                            <td class="py-1 px-2 text-right"><?php echo number_format($item['price']) ?></td>
                                            <td class="py-1 px-2 text-right"><?php echo number_format($item['total']) ?></td>
                                        </tr>
                                        <?php
                                    endwhile;
                                }
                            endwhile;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-right py-1 px-2" colspan="4">Sub Total</th>
                                <th class="text-right py-1 px-2 sub-total"><?php echo number_format($total, 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-footer py-1 text-center">
        <button class="btn btn-flat btn-success" type="button" id="print">Print</button>
        <?php if ($isAdmin && $status != 2): ?>
            <a class="btn btn-flat btn-primary"
                href="<?php echo base_url . '/admin?page=purchase_order/manage_po&id=' . (isset($id) ? $id : '') ?>">Edit</a>
        <?php endif; ?>
        <a class="btn btn-flat btn-dark" href="<?php echo base_url . '/admin?page=purchase_order' ?>">Back To List</a>
    </div>
</div>
<table id="clone_list" class="d-none">
    <tr>
        <td class="py-1 px-2 text-center">
            <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
        </td>
        <td class="py-1 px-2 text-center qty">
            <span class="visible"></span>
            <input type="hidden" name="item_id[]">
            <input type="hidden" name="unit[]">
            <input type="hidden" name="qty[]">
            <input type="hidden" name="price[]">
            <input type="hidden" name="total[]">
        </td>
        <td class="py-1 px-2 text-center unit">

        </td>
        <td class="py-1 px-2 item">
        </td>
        <td class="py-1 px-2 text-right cost">
        </td>
        <td class="py-1 px-2 text-right total">
        </td>
    </tr>
</table>
<script>

    $(function () {
        $('#print').click(function () {
            start_loader()
            var _el = $('<div>')
            var _head = $('head').clone()
            _head.find('title').text("Purchase Order Details - Print View")
            var p = $('#print_out').clone()
            p.find('tr.text-light').removeClass("text-light bg-navy")
            _el.append(_head)
            _el.append('<div class="d-flex justify-content-center">' +
                '<div class="col-1 text-right">' +
                '<img src="<?php echo validate_image($_settings->info('logo')) ?>" width="65px" height="65px" />' +
                '</div>' +
                '<div class="col-10">' +
                '<h4 class="text-center"><?php echo $_settings->info('name') ?></h4>' +
                '<h4 class="text-center">Purchase Order</h4>' +
                '</div>' +
                '<div class="col-1 text-right">' +
                '</div>' +
                '</div><hr/>')
            _el.append(p.html())
            var nw = window.open("", "", "width=1200,height=900,left=250,location=no,titlebar=yes")
            nw.document.write(_el.html())
            nw.document.close()
            setTimeout(() => {
                nw.print()
                setTimeout(() => {
                    nw.close()
                    end_loader()
                }, 200);
            }, 500);
        })
    })
</script>