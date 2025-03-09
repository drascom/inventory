<?php
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM client_list where id = '{$_GET['id']}'");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_array() as $k => $v) {
            $$k = $v;
        }
    }
}
?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title"><?php echo isset($id) ? "Update Client Details - " . $id : 'Add New Client' ?>
        </h4>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form action="" id="client-form">
                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                <div class="form-group">
                    <label for="name" class="control-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control rounded-0"
                        value="<?php echo isset($name) ? $name : '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="contact" class="control-label">Contact</label>
                    <input type="text" name="contact" id="contact" class="form-control rounded-0"
                        value="<?php echo isset($contact) ? $contact : '' ?>">
                </div>
                <div class="form-group">
                    <label for="address" class="control-label">Address</label>
                    <textarea name="address" id="address" class="form-control rounded-0"
                        required><?php echo isset($address) ? $address : '' ?></textarea>
                </div>
                <div class="form-group">
                    <label for="email" class="control-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control rounded-0"
                        value="<?php echo isset($email) ? $email : '' ?>">
                </div>
                <div class="form-group">
                    <label for="remarks" class="control-label">Remarks</label>
                    <textarea name="remarks" id="remarks"
                        class="form-control rounded-0"><?php echo isset($remarks) ? $remarks : '' ?></textarea>
                </div>
            </form>
        </div>
    </div>
    <div class="card-footer py-1 text-center">
        <button class="btn btn-flat btn-primary" form="client-form">Save</button>
        <a class="btn btn-flat btn-dark" href="<?php echo base_url . '/admin?page=clients' ?>">Cancel</a>
    </div>
</div>
<script>
    $(function () {
        $('#client-form').submit(function (e) {
            e.preventDefault();
            var _this = $(this)
            $('.err-msg').remove();
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_client",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error: err => {
                    console.log(err)
                    alert_toast("An error occurred", 'error');
                    end_loader();
                },
                success: function (resp) {
                    if (typeof resp == 'object' && resp.status == 'success') {
                        location.href = "./?page=clients";
                    } else if (resp.status == 'failed' && !!resp.msg) {
                        var el = $('<div>')
                        el.addClass("alert alert-danger err-msg").text(resp.msg)
                        _this.prepend(el)
                        el.show('slow')
                        $("html, body").scrollTop(0);
                    } else {
                        alert_toast("An error occurred", 'error');
                        console.log(resp)
                    }
                    end_loader()
                }
            })
        })
    })
</script>