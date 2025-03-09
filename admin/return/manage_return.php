<?php
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT r.*,s.name as supplier FROM return_list r inner join supplier_list s on r.supplier_id = s.id  where r.id = '{$_GET['id']}'");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_array() as $k => $v) {
            $$k = $v;
        }
    }
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
        <h3 class="card-title"><?php echo isset($id) ? "Update Return Record" : "Create New Return Record" ?></h3>
    </div>
    <div class="card-body">
        <form action="" id="return-form">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label text-info">Return Code</label>
                        <input type="text" class="form-control form-control-sm"
                            value="<?php echo isset($return_code) ? $return_code : 'R-' . strtotime(date('y-m-d H:i')) ?>"
                            required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supplier_id" class="control-label text-info">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="custom-select select2" required>
                                <option value=""></option>
                                <?php
                                $supplier = $conn->query("SELECT * FROM `supplier_list` order by `name` asc");
                                while ($row = $supplier->fetch_assoc()):
                                    ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($supplier_id) && $supplier_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="po_id" class="control-label text-info">Select Purchase Order</label>
                            <select name="po_id" id="po_id" class="custom-select select2" required>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
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
                                <tr class="text-center">
                                    <th class="text-center"></th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Unit</th>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Cost</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="remarks" class="control-label text-info">Remarks</label>
                        <textarea name="remarks" id="remarks" rows="3"
                            class="form-control"><?php echo isset($remarks) ? $remarks : '' ?></textarea>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer py-1 text-center">
        <button class="btn btn-flat btn-primary" type="submit" form="return-form">Save</button>
        <a class="btn btn-flat btn-dark" href="<?php echo base_url . '/admin?page=return' ?>">Cancel</a>
    </div>
</div>
<script>
    $(function () {
        $('.select2').select2({
            placeholder: "Please select here",
            width: 'resolve',
        })

        // Load POs when supplier is selected
        $('#supplier_id').change(function () {
            var supplier_id = $(this).val();
            if (supplier_id > 0) {
                $.ajax({
                    url: _base_url_ + 'classes/Master.php?f=get_supplier_pos',
                    method: 'POST',
                    data: { supplier_id: supplier_id },
                    dataType: 'json',
                    error: err => {
                        console.log(err)
                        alert_toast("An error occurred.", 'error');
                    },
                    success: function (resp) {
                        if (resp.status == 'success') {
                            $('#po_id').html(resp.html);
                        }
                    }
                })
            }
        })

        // Load PO items when PO is selected
        $('#po_id').change(function () {
            var po_id = $(this).val();
            if (po_id > 0) {
                $.ajax({
                    url: _base_url_ + 'classes/Master.php?f=get_po_items',
                    method: 'POST',
                    data: { po_id: po_id },
                    dataType: 'json',
                    error: err => {
                        console.log(err)
                        alert_toast("An error occurred.", 'error');
                    },
                    success: function (resp) {
                        if (resp.status == 'success') {
                            $('#list tbody').html(resp.html);
                        }
                    }
                })
            }
        })
    })
</script>