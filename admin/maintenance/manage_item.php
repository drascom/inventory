<?php
require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
	$qry = $conn->query("SELECT * from `item_list` where id = '{$_GET['id']}' ");
	if ($qry->num_rows > 0) {
		foreach ($qry->fetch_assoc() as $k => $v) {
			$$k = $v;
		}
	}
}
?>
<div class="container-fluid">
	<div class="alert alert-info supplier-alert" role="alert">
		Please select supplier first
	</div>
	<form action="" id="item-form">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group">
			<label for="supplier_id" class="control-label">Supplier</label>
			<select name="supplier_id" id="supplier_id" class="custom-select select2">
				<option value="" <?php echo !isset($supplier_id) ? 'selected' : '' ?> disabled></option>
				<?php
				$supplier = $conn->query("SELECT * FROM `supplier_list` where status = 1 order by `name` asc");
				while ($row = $supplier->fetch_assoc()):
					?>
					<option value="<?php echo $row['id'] ?>" <?php echo isset($supplier_id) && $supplier_id == $row['id'] ? "selected" : "" ?>><?php echo $row['name'] ?></option>
				<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group">
			<label for="name" class="control-label">Name</label>
			<input type="text" name="name" id="name" class="form-control rounded-0 form-disabled"
				value="<?php echo isset($name) ? $name : ''; ?>" disabled>
		</div>
		<div class="form-group">
			<label for="description" class="control-label">Description</label>
			<textarea name="description" id="description" cols="30" rows="2"
				class="form-control form no-resize form-disabled"
				disabled><?php echo isset($description) ? $description : ''; ?></textarea>
		</div>
		<div class="form-group">
			<label for="buy_price" class="control-label">Buy Price</label>
			<input type="number" name="buy_price" id="buy_price" step="any"
				class="form-control rounded-0 text-end form-disabled"
				value="<?php echo isset($buy_price) ? $buy_price : ''; ?>" required disabled>
		</div>
		<div class="form-group">
			<label for="sell_price" class="control-label">Sell Price</label>
			<input type="number" name="sell_price" id="sell_price" step="any"
				class="form-control rounded-0 text-end form-disabled"
				value="<?php echo isset($sell_price) ? $sell_price : ''; ?>" required disabled>
		</div>
		<div class="form-group">
			<label for="status" class="control-label">Status</label>
			<select name="status" id="status" class="custom-select selevt form-disabled" disabled>
				<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Active</option>
				<option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactive</option>
			</select>
		</div>
	</form>
</div>
<script>
	$(document).ready(function () {
		// Initialize select2
		$('.select2').select2({ placeholder: "Please Select here", width: "relative" })

		// Check if supplier is already selected (edit mode)
		if ($('#supplier_id').val()) {
			enableFormInputs();
			$('.supplier-alert').hide();
		}

		// Handle supplier selection
		$('#supplier_id').change(function () {
			if ($(this).val()) {
				enableFormInputs();
				$('.supplier-alert').slideUp();
			} else {
				disableFormInputs();
				$('.supplier-alert').slideDown();
			}
		});

		// Function to enable form inputs
		function enableFormInputs() {
			$('.form-disabled').prop('disabled', false);
		}

		// Function to disable form inputs
		function disableFormInputs() {
			$('.form-disabled').prop('disabled', true);
		}

		$('#item-form').submit(function (e) {
			e.preventDefault();
			var _this = $(this)
			$('.err-msg').remove();
			start_loader();
			$.ajax({
				url: _base_url_ + "classes/Master.php?f=save_item",
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
					if (typeof resp == 'object' && resp.status == 'success') {
						location.reload();
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
				}
			})
		})
	})
</script>
<style>
	.supplier-alert {
		margin-bottom: 20px;
	}

	</script>