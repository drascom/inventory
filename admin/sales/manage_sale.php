<?php
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM sales_list where id = '{$_GET['id']}'");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_array() as $k => $v) {
            $$k = $v;
        }
    }
}
$unit_qry = $conn->query("SELECT * FROM `units` order by `name` asc");
$unit_arr = array();
while ($row = $unit_qry->fetch_assoc()) {
    $unit_arr[$row['id']] = $row;
}
?>
<style>
    select[readonly].select2-hidden-accessible+.select2-container {
        pointer-events: none;
        touch-action: none;
        background: #eee;
        box-shadow: none;
    }

    select[readonly].select2-hidden-accessible+.select2-container .select2-selection {
        background: #eee;
        box-shadow: none;
    }
</style>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title"><?php echo isset($id) ? "Sale Details - " . $sales_code : 'Create New Sale Record' ?>
        </h4>
    </div>
    <div class="card-body">
        <form action="" id="sale-form">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
            <div class="container-fluid">
                <div class="row">
                    <?php if (isset($po_code)): ?>
                        <div class="col-sm-3 col-12">
                            <label class="control-label text-info">Sale Code</label>
                            <input type="text" class="form-control form-control-sm rounded-0"
                                value="<?php echo isset($sales_code) ? $sales_code : '' ?>" readonly>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-3 <?php echo isset($client_id) ? 'readonly' : '' ?>">
                        <div class="form-group">
                            <label for="client_id" class="control-label text-info">Client</label>
                            <select name="client_id" id="client_id" class="custom-select select2">
                                <option <?php echo !isset($client_id) ? 'selected' : '' ?> disabled></option>
                                <?php
                                $client = $conn->query("SELECT * FROM `client_list` where status = 1 order by `name` asc");
                                while ($row = $client->fetch_assoc()):
                                    ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($client_id) && $client_id == $row['id'] ? "selected" : "" ?>><?php echo $row['name'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div id="sales-form-section" style="display: none;">
                    <fieldset>
                        <legend class="text-info">Item Form</legend>
                        <div class="row justify-content-center align-items-end">
                            <?php
                            $item_arr = array();
                            $cost_arr = array();
                            $item = $conn->query("SELECT * FROM `item_list` where status = 1 order by `name` asc");
                            while ($row = $item->fetch_assoc()):
                                $item_arr[$row['id']] = $row;
                                $cost_arr[$row['id']] = $row['sell_price'];
                            endwhile;
                            ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="item_id" class="control-label">Item</label>
                                    <select id="item_id" class="custom-select select2">
                                        <option disabled selected></option>
                                        <?php foreach ($item_arr as $k => $v): ?>
                                            <option value="<?php echo $k ?>"> <?php echo $v['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="unit" class="control-label">Unit</label>
                                    <select id="unit" class="custom-select select2">
                                        <option value="" disabled selected>Select Unit</option>
                                        <?php foreach ($unit_arr as $unit): ?>
                                            <option value="<?php echo $unit['id']; ?>"><?php echo $unit['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="qty" class="control-label">Qty</label>
                                    <input type="number" step="any" class="form-control rounded-0" id="qty">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="price" class="control-label">Price</label>
                                    <input type="number" step="any" class="form-control rounded-0" id="price">
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="form-group">
                                    <button type="button" class="btn btn-flat btn-sm btn-primary" id="add_to_list">Add
                                        to
                                        List</button>
                                </div>
                            </div>
                    </fieldset>
                </div>
                <hr>
                <table class="table table-striped table-bordered" id="list">
                    <colgroup>
                        <col width="5%">
                        <col width="10%">
                        <col width="10%">
                        <col width="25%">
                        <col width="25%">
                        <col width="25%">
                    </colgroup>
                    <thead>
                        <tr class="text-light bg-navy">
                            <th class="text-center py-1 px-2"></th>
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
                        if (isset($id)):
                            $qry = $conn->query("SELECT s.*,i.name,i.description FROM `stock_list` s inner join item_list i on s.item_id = i.id where s.id in ({$stock_ids})");
                            while ($row = $qry->fetch_assoc()):
                                $total += $row['total']
                                    ?>
                                <tr>
                                    <td class="py-1 px-2 text-center">
                                        <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i
                                                class="fa fa-times"></i></button>
                                    </td>
                                    <td class="py-1 px-2 text-center qty">
                                        <span class="visible"><?php echo number_format($row['quantity']); ?></span>
                                        <input type="hidden" name="item_id[]" value="<?php echo $row['item_id']; ?>">
                                        <input type="hidden" name="unit[]" value="<?php echo $row['unit']; ?>">
                                        <input type="hidden" name="qty[]" value="<?php echo $row['quantity']; ?>">
                                        <input type="hidden" name="price[]" value="<?php echo $row['price']; ?>">
                                        <input type="hidden" name="total[]" value="<?php echo $row['total']; ?>">
                                    </td>
                                    <td class="py-1 px-2 text-center unit">
                                        <?php echo $row['unit']; ?>
                                    </td>
                                    <td class="py-1 px-2 item">
                                        <?php echo $row['name']; ?> <br>
                                        <?php echo $row['description']; ?>
                                    </td>
                                    <td class="py-1 px-2 text-right cost">
                                        <?php echo number_format($row['price']); ?>
                                    </td>
                                    <td class="py-1 px-2 text-right total">
                                        <?php echo number_format($row['total']); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-right py-1 px-2" colspan="5">Total
                                <input type="hidden" name="amount"
                                    value="<?php echo isset($discount) ? $discount : 0 ?>">
                            </th>
                            <th class="text-right py-1 px-2 grand-total">0</th>
                        </tr>
                    </tfoot>
                </table>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="remarks" class="text-info control-label">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3"
                                class="form-control rounded-0"><?php echo isset($remarks) ? $remarks : '' ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer py-1 text-center">
        <button class="btn btn-flat btn-primary" type="submit" form="sale-form">Save</button>
        <a class="btn btn-flat btn-dark" href="<?php echo base_url . '/admin?page=sale' ?>">Cancel</a>
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
    var items = $.parseJSON('<?php echo json_encode($item_arr) ?>')
    var costs = $.parseJSON('<?php echo json_encode($cost_arr) ?>')
    var units = $.parseJSON('<?php echo json_encode($unit_arr) ?>');

    $(function () {
        $('.select2').select2({
            placeholder: "Please select here",
            width: 'resolve',
        })

        // Check if client is already selected on page load
        if ($('#client_id').val()) {
            $('#sales-form-section').show();
        }

        // Show/hide sales form section when client changes
        $('#client_id').change(function () {
            if ($(this).val()) {
                $('#sales-form-section').slideDown();
            } else {
                $('#sales-form-section').slideUp();
            }
        });

        $('#item_id').change(function () {
            var item_id = $(this).val();
            var item = items[item_id];

            if (item) {
                // Check stock level
                $.ajax({
                    url: _base_url_ + 'admin/sales/check_stock.php',
                    method: 'POST',
                    data: { item_id: item_id },
                    dataType: 'json',
                    success: function (resp) {
                        if (resp.available <= 0) {
                            // Reset item selection
                            $('#item_id').val('').trigger('change');
                            $('#unit').val('').trigger('change');
                            $('#price').val(0);
                            $('#qty').val('');

                            // Show alert modal
                            $('#stockAlertModal').modal('show');
                        } else {
                            // Continue with existing item selection logic
                            var unit_id = Object.keys(units).find(key => units[key].name === item.unit);
                            $('#unit').val(unit_id).trigger('change');
                            $('#price').val(item.sell_price || costs[item_id] || 0);
                            $('#qty').val('');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error checking stock:', error);
                        alert_toast('Error checking stock level', 'error');
                    }
                });
            } else {
                $('#unit').val('').trigger('change');
                $('#price').val(0);
                $('#qty').val('');
            }
        });

        $('#add_to_list').click(function () {
            var item = $('#item_id').val();
            var qty = $('#qty').val() > 0 ? $('#qty').val() : 0;
            var unit_id = $('#unit').val();
            var unit_name = units[unit_id].name;
            var price = $('#price').val() > 0 ? $('#price').val() : (costs[item] || 0);
            var total = parseFloat(qty) * parseFloat(price);

            if (item == '' || qty == '' || unit_id == '') {
                alert_toast('Form Item textfields are required.', 'warning');
                return false;
            }
            if ($('table#list tbody').find('tr[data-id="' + item + '"]').length > 0) {
                alert_toast('Item is already exists on the list.', 'error');
                return false;
            }
            var tr = $('#clone_list tr').clone()
            tr.find('[name="item_id[]"]').val(item)
            tr.find('[name="unit[]"]').val(unit_id)
            tr.find('[name="qty[]"]').val(qty)
            tr.find('[name="price[]"]').val(price)
            tr.find('[name="total[]"]').val(total)
            tr.attr('data-id', item)
            tr.find('.qty .visible').text(qty)
            tr.find('.unit').text(unit_name)
            tr.find('.item').html(items[item].name || 'N/A' + '<br/>' + (items[item].description || 'N/A'))
            tr.find('.cost').text(parseFloat(price).toLocaleString('en-US'))
            tr.find('.total').text(parseFloat(total).toLocaleString('en-US'))
            $('table#list tbody').append(tr)
            calc()
            $('#item_id').val('').trigger('change')
            $('#qty').val('')
            $('#unit').val('')
            tr.find('.rem_row').click(function () {
                rem($(this))
            })

            $('[name="discount_perc"],[name="tax_perc"]').on('input', function () {
                calc()
            })
            $('#client_id').attr('readonly', 'readonly')
        })
        $('#sale-form').submit(function (e) {
            e.preventDefault();
            var _this = $(this)
            $('.err-msg').remove();
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_sale",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error: err => {
                    console.log(err)
                    alert_toast("An error occured", 'error');
                    end_loader();
                },
                success: function (resp) {
                    if (resp.status == 'success') {
                        location.replace(_base_url_ + "admin/?page=sales/view_sale&id=" + resp.id);
                    } else if (resp.status == 'failed' && !!resp.msg) {
                        var el = $('<div>')
                        el.addClass("alert alert-danger err-msg").text(resp.msg)
                        _this.prepend(el)
                        el.show('slow')
                        end_loader()
                    } else {
                        alert_toast("An error occured", 'error');
                        end_loader();
                        console.log(resp)
                    }
                    $('html,body').animate({ scrollTop: 0 }, 'fast')
                }
            })
        })

        if ('<?php echo isset($id) && $id > 0 ?>' == 1) {
            calc()
            $('#client_id').trigger('change')
            $('#client_id').attr('readonly', 'readonly')
            $('table#list tbody tr .rem_row').click(function () {
                rem($(this))
            })
        }
    })
    function rem(_this) {
        _this.closest('tr').remove()
        calc()
        if ($('table#list tbody tr').length <= 0)
            $('#client_id').removeAttr('readonly')

    }
    function calc() {
        var grand_total = 0;
        $('table#list tbody input[name="total[]"]').each(function () {
            grand_total += parseFloat($(this).val())

        })

        $('table#list tfoot .grand-total').text(parseFloat(grand_total).toLocaleString('en-US', { style: 'decimal', maximumFractionDigit: 2 }))
        $('[name="amount"]').val(parseFloat(grand_total))

    }
</script>
<!-- Add this modal to your HTML -->
<div class="modal fade" id="stockAlertModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">Stock Alert</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You cannot sell this product. Stock level is 0</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>