<?php
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT p.*,s.name as supplier FROM purchase_order_list p inner join supplier_list s on p.supplier_id = s.id  where p.id = '{$_GET['id']}'");
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

    /* New styles for card and table separation */
    .card {
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        border: none;
    }

    .card-body {
        padding: 1.5rem;
    }

    #list {
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        overflow: hidden;
        border: none;
    }

    #list thead tr {
        border-radius: 5px 5px 0 0;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, .02);
    }

    .alert {
        border: none;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    hr {
        margin: 2rem 0;
        border-color: rgba(0, 0, 0, 0.05);
    }

    .form-control,
    .custom-select {
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }

    .form-control:focus,
    .custom-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
    }

    /* Add this to your CSS */
    .currency-input::-webkit-outer-spin-button,
    .currency-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .currency-input {
        -moz-appearance: textfield;
    }
</style>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title">
            <?php echo isset($id) ? "Purchase Order Details - " . $po_code : 'Create New Purchase Order' ?>
        </h4>
    </div>
    <div class="card-body">
        <form action="" id="po-form">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
            <div class="container-fluid">
                <div class="row">
                    <?php if (isset($po_code)): ?>
                        <div class="col-md-3">
                            <label class="control-label text-info">P.O. Code</label>
                            <input type="text" class="form-control form-control-sm rounded-0"
                                value="<?php echo isset($po_code) ? $po_code : '' ?>" readonly>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-3 <?php echo isset($supplier_id) ? 'readonly' : '' ?>">
                        <div class="form-group">
                            <label for="supplier_id" class="control-label text-info">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="custom-select select2">
                                <option <?php echo !isset($supplier_id) ? 'selected' : '' ?> disabled></option>
                                <?php
                                $supplier = $conn->query("SELECT * FROM `supplier_list` where status = 1 order by `name` asc");
                                while ($row = $supplier->fetch_assoc()):
                                    ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($supplier_id) && $supplier_id == $row['id'] ? "selected" : "" ?>><?php echo $row['name'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                </div>
                <div id="purchase-form-section" style="display: none;">
                    <div
                        class="mx-auto  col-sm-6 col-12 alert  d-flex justify-content-between align-items-center mb-0 pb-0">
                        <span>You can <button class="btn btn-link btn-sm " type="button" id="new_item_btn">
                                <i class="fa fa-plus"></i> Add New Product
                            </button> or select existing product from below</span>

                    </div>
                    <fieldset
                        style="border: 2px solid #dee2e6; border-radius: 8px; padding: 20px; margin-bottom: 10px;">
                        <legend style="width: auto; padding: 0 10px; margin-bottom: 0;">Purchase Form</legend>

                        <div class="row justify-content-center align-items-end">
                            <?php
                            $item_arr = array();
                            $cost_arr = array();
                            $item = $conn->query("SELECT * FROM `item_list` where status = 1 order by `name` asc");
                            while ($row = $item->fetch_assoc()):
                                $item_arr[$row['supplier_id']][$row['id']] = $row;
                                $cost_arr[$row['id']] = $row['buy_price'];
                            endwhile;
                            ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="item_id" class="control-label">Item</label>
                                    <select id="item_id" class="custom-select ">
                                        <option disabled selected></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="unit" class="control-label">Unit</label>
                                    <input type="hidden" class="form-control rounded-0" id="item_name">

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
                                    <label for="qty" class="control-label">Quantity</label>
                                    <input type="number" step="any" class="form-control rounded-0" id="qty">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="price" class="control-label">Price</label>
                                    <input type="number" step="any" class="form-control rounded-0" id="buy_price">
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="form-group">
                                    <button type="button" class="btn btn-flat btn-sm btn-primary" id="add_to_list">Add
                                        to List</button>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset style="border: 2px solid #dee2e6; border-radius: 8px;">
                        <legend style="width: auto; padding: 0; margin-bottom: 0;">Add Your Note...</legend>
                        <div class="mx-auto  col-12">
                            <div class="form-group">
                                <textarea name="remarks" id="remarks" rows="2"
                                    class="form-control border: 1px solid  rounded-1"><?php echo isset($remarks) ? $remarks : '' ?></textarea>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <hr class="p-1 m-2">
                <table class="table table-striped table-bordered text-center" id="list">
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
                            $qry = $conn->query("SELECT p.*,i.name,i.description FROM `po_items` p inner join item_list i on p.item_id = i.id where p.po_id = '{$id}'");
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
                                        <?php echo $unit_arr[$row['unit']]['name']; ?>
                                    </td>
                                    <td class="py-1 px-2 item">
                                        <?php echo $row['name'] . '<br/>' . $row['description']; ?>
                                    </td>
                                    <td class="py-1 px-2 text-right cost">
                                        <?php echo number_format($row['price']); ?>
                                    </td>
                                    <td class="py-1 px-2 text-right total">
                                        <?php echo number_format($row['total']); ?>
                                    </td>
                                </tr>
                                <?php
                            endwhile;
                        endif;
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-right py-1 px-2" colspan="5">Sub Total</th>
                            <th class="text-right py-1 px-2 sub-total">0</th>
                        </tr>
                        <tr>
                            <th class="text-right py-1 px-2" colspan="5">Discount <input style="width:40px !important"
                                    name="discount_perc" class='' type="number" min="0" max="100"
                                    value="<?php echo isset($discount_perc) ? $discount_perc : 0 ?>">%
                                <input type="hidden" name="discount"
                                    value="<?php echo isset($discount) ? $discount : 0 ?>">
                            </th>
                            <th class="text-right py-1 px-2 discount">
                                <?php echo isset($discount) ? number_format($discount) : 0 ?>
                            </th>
                        </tr>
                        <tr>
                            <th class="text-right py-1 px-2" colspan="5">Tax <input style="width:40px !important"
                                    name="tax_perc" class='' type="number" min="0" max="100"
                                    value="<?php echo isset($tax_perc) ? $tax_perc : 0 ?>">%
                                <input type="hidden" name="tax" value="<?php echo isset($discount) ? $discount : 0 ?>">
                            </th>
                            <th class="text-right py-1 px-2 tax"><?php echo isset($tax) ? number_format($tax) : 0 ?>
                            </th>
                        </tr>
                        <tr>
                            <th class="text-right py-1 px-2" colspan="5">Total
                                <input type="hidden" name="amount"
                                    value="<?php echo isset($discount) ? $discount : 0 ?>">
                            </th>
                            <th class="text-right py-1 px-2 grand-total">0</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
    </div>
    </form>
</div>
<div class="card-footer py-1 text-right">
    <button class="btn btn-flat btn-primary" type="submit" form="po-form">Save</button>
    <a class="btn btn-flat btn-dark" href="<?php echo base_url . '/admin?page=purchase_order' ?>">Cancel</a>
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
    var units = $.parseJSON('<?php echo json_encode($unit_arr) ?>')
    var po_code = '<?php echo isset($po_code) ? $po_code : false ?>'

    $(function () {
        $('.select2').select2({
            placeholder: "Please select here",
            width: 'resolve',
        })
        $('#item_id').select2({
            placeholder: "Please select supplier first",
            width: 'resolve',
        })

        $(function () {
            // Check if supplier is already selected on page load
            if ($('#supplier_id').val()) {
                $('#purchase-form-section').show();
            }

            // Show/hide purchase form section when supplier changes
            $('#supplier_id').change(function () {
                if ($(this).val()) {
                    $('#purchase-form-section').slideDown();
                } else {
                    $('#purchase-form-section').slideUp();
                }
            });
        });

        $('#supplier_id').change(function () {
            var supplier_id = $(this).val()
            // Check if select2 is already initialized before destroying
            if ($.fn.select2 && $('#item_id').hasClass('select2-hidden-accessible')) {
                $('#item_id').select2('destroy');
            }
            if (!!items[supplier_id]) {
                $('#item_id').html('')
                // Add "Select Product" as first option
                var defaultOpt = $('<option>')
                defaultOpt.attr('value', '')
                defaultOpt.attr('disabled', 'disabled')
                defaultOpt.attr('selected', 'selected')
                defaultOpt.text('Select Product')
                $('#item_id').append(defaultOpt)

                var list_item = new Promise(resolve => {
                    Object.keys(items[supplier_id]).map(function (k) {
                        var row = items[supplier_id][k]
                        var opt = $('<option>')
                        opt.attr('value', row.id)
                        opt.text(row.name)
                        $('#item_id').append(opt)
                    })
                    resolve()
                })
                list_item.then(function () {
                    $('#item_id').select2({
                        placeholder: "Select Product",
                        width: 'resolve',
                    })
                })
            }
        })

        $('#item_id').change(function () {
            var item_id = $(this).val();
            var item = items[$('#supplier_id').val()][item_id];

            if (item) {
                $('#unit').val(item.unit || '');
                $('#buy_price').val(item.buy_price || 0);
            } else {
                $('#unit').val('');
                $('#buy_price').val(0);
            }
            $('#qty').val('');
        });

        $('#add_to_list').click(function () {
            var supplier = $('#supplier_id').val()
            var item = $('#item_id').val()
            var qty = $('#qty').val() > 0 ? $('#qty').val() : 0;
            var unit = $('#unit').val()
            var price = $('#buy_price').val()
            // var price = costs[item] || 0
            var total = parseFloat(qty) * parseFloat(price)
            console.log(items[supplier][item])
            var item_name = items[supplier][item].name || 'N/A';
            var item_description = items[supplier][item].description || 'N/A';
            var tr = $('#clone_list tr').clone()
            if (item == '' || qty == '' || unit == '') {
                alert_toast('Form Item textfields are required.', 'warning');
                return false;
            }
            if ($('table#list tbody').find('tr[data-id="' + item + '"]').length > 0) {
                alert_toast('Item is already exists on the list.', 'error');
                return false;
            }
            var unit_name = units[unit] ? units[unit].name : unit;
            tr.find('[name="item_id[]"]').val(item)
            tr.find('[name="unit[]"]').val(unit)
            tr.find('[name="qty[]"]').val(qty)
            tr.find('[name="price[]"]').val(price)
            tr.find('[name="total[]"]').val(total)
            tr.attr('data-id', item)
            tr.find('.qty .visible').text(qty)
            tr.find('.unit').text(unit_name)
            tr.find('.item').html(item_name + '<br/>' + item_description)
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
            $('#supplier_id').attr('readonly', 'readonly')
        })
        $('#po-form').submit(function (e) {

            e.preventDefault();
            var _this = $(this)
            $('.err-msg').remove();
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_po",
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
                    // Add tag condition here
                    var tag = po_code ? "" : "&action=new";
                    if (resp.status == 'success') {
                        location.replace(_base_url_ + "admin/?page=purchase_order/view_po&id=" + resp.id + tag);
                        // console.log(_base_url_ + "admin/?page=purchase_order/view_po&id=" + resp.id + tag);
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
            $('#supplier_id').trigger('change')
            $('#supplier_id').attr('readonly', 'readonly')
            $('table#list tbody tr .rem_row').click(function () {
                rem($(this))
            })
        }
    })
    function rem(_this) {
        _this.closest('tr').remove()
        calc()
        if ($('table#list tbody tr').length <= 0)
            $('#supplier_id').removeAttr('readonly')

    }
    function calc() {
        var sub_total = 0;
        var grand_total = 0;
        var discount = 0;
        var tax = 0;
        $('table#list tbody input[name="total[]"]').each(function () {
            sub_total += parseFloat($(this).val())

        })
        $('table#list tfoot .sub-total').text(parseFloat(sub_total).toLocaleString('en-US', { style: 'decimal', maximumFractionDigit: 2 }))
        var discount = sub_total * (parseFloat($('[name="discount_perc"]').val()) / 100)
        sub_total = sub_total - discount;
        var tax = sub_total * (parseFloat($('[name="tax_perc"]').val()) / 100)
        grand_total = sub_total + tax
        $('.discount').text(parseFloat(discount).toLocaleString('en-US', { style: 'decimal', maximumFractionDigit: 2 }))
        $('[name="discount"]').val(parseFloat(discount))
        $('.tax').text(parseFloat(tax).toLocaleString('en-US', { style: 'decimal', maximumFractionDigit: 2 }))
        $('[name="tax"]').val(parseFloat(tax))
        $('table#list tfoot .grand-total').text(parseFloat(grand_total).toLocaleString('en-US', { style: 'decimal', maximumFractionDigit: 2 }))
        $('[name="amount"]').val(parseFloat(grand_total))

    }
</script>
<!-- Add this before the closing body tag -->
<div class="modal fade" id="newProductModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="item-form">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6 col-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="form-group">
                                <label for="unit" class="control-label">Unit</label>
                                <select name="unit" id="unit" class="form-control select2" required>
                                    <option value="" disabled selected>Select Unit</option>
                                    <?php foreach ($unit_arr as $unit): ?>
                                        <option value="<?php echo $unit['id']; ?>"><?php echo $unit['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="form-group">
                                <label for="buy_price" class="control-label">Buy Price</label>
                                <input type="number" step="0.01" min="0" class="form-control rounded-0 currency-input"
                                    id="buy_price" name="buy_price">
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="form-group">
                                <label for="sell_price" class="control-label">Sell Price</label>
                                <input type="number" step="0.01" min="0" class="form-control rounded-0 currency-input"
                                    id="sell_price" name="sell_price">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description" class="control-label">Description</label>
                                <textarea name="description" id="description" cols="30" rows="2" class="form-control"
                                    required></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="supplier_id" id="modal_supplier_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        // Add button next to item selection
        // $('#item_id').after('<button class="btn btn-primary btn-sm ml-2" type="button" id="new_item_btn"><i class="fa fa-plus"></i> New Item</button>');

        // New Item Button Click
        $('#new_item_btn').click(function () {
            var supplier_id = $('#supplier_id').val();
            if (!supplier_id) {
                alert_toast('Please select a supplier first', 'warning');
                return;
            }
            $('#modal_supplier_id').val(supplier_id);
            $('#newProductModal').modal('show');
        });

        // Item Form Submit
        $('#item-form').submit(function (e) {
            e.preventDefault();
            $('.err-msg').remove();

            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_item",
                data: $(this).serialize(),
                method: 'POST',
                dataType: 'json',
                error: err => {
                    console.log("error: ", err);
                    alert_toast("An error occurred", 'error');
                    end_loader();
                },
                success: function (resp) {
                    if (typeof resp == 'object' && resp.resp_status == 'success') {
                        // Get current supplier_id
                        var supplier_id = $('#supplier_id').val();

                        // Initialize the supplier object if it doesn't exist
                        if (!items[supplier_id]) {
                            items[supplier_id] = {};
                        }

                        // Add the new item directly from the response
                        // resp should contain all fields from item_list table
                        items[supplier_id][resp.id] = resp;

                        // Update select options
                        var opt = $('<option>');
                        opt.attr('value', resp.id);
                        opt.text(resp.name);
                        $('#item_id').append(opt);

                        // Reset form and close modal
                        $('#item-form')[0].reset();
                        $('#newProductModal').modal('hide');
                        $('.modal-backdrop').remove();

                        // Show success message
                        alert_toast("Item successfully saved", 'success');
                        end_loader();

                        // Debug log
                        // console.log("Updated items:", items);
                    } else if (resp.status == 'failed' && !!resp.msg) {
                        var el = $('<div>');
                        el.addClass("alert alert-danger err-msg").text(resp.msg);
                        $('#item-form').prepend(el);
                        el.show('slow');
                        end_loader();
                        // console.log("elseif", resp);
                    } else {
                        alert_toast("Product saved but cant add to the list", 'error');
                        end_loader();
                        // console.log("final else", resp);
                    }
                }
            });
        });
    });
</script>