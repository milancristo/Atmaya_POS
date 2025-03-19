<?php include('db_connect.php'); ?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-12">
				<form action="" id="manage-product">
					<div class="card">
						<div class="card-header">
							Product Form
						</div>
						<div class="card-body">
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label">Category</label>
								<select name="category_id" id="category_id" class="custom-select select2" required>
									<option value=""></option>
									<?php
									$qry = $conn->query("SELECT * FROM categories order by name asc");
									while ($row = $qry->fetch_assoc()):
										$cname[$row['id']] = ucwords($row['name']);
									?>
										<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Name</label>
								<input type="text" class="form-control" name="name" required>
							</div>
							<div class="form-group">
								<label class="control-label">Description</label>
								<textarea name="description" id="description" cols="30" rows="4" class="form-control" required></textarea>
							</div>
							<div class="form-group">
								<label class="control-label">Year</label>
								<input type="number" class="form-control text-right" name="year" required>
							</div>
							<div class="form-group">
								<label class="control-label">Qty</label>
								<input type="number" class="form-control text-right" name="qty" required>
							</div>
							<div class="form-group">
								<label class="control-label">Price</label>
								<input type="number" class="form-control text-right" name="price" required>
							</div>
						</div>
						<div class="card-footer">
							<div class="row">
								<div class="col-md-12 text-center">
									<button class="btn btn-primary">Save</button>
									<button class="btn btn-default" type="button" onclick="$('#manage-product').get(0).reset()">Cancel</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- FORM Panel -->
		</div>
	</div>
</div>

<style>
	td {
		vertical-align: middle !important;
	}

	td p {
		margin: unset;
	}

	.custom-switch {
		cursor: pointer;
	}

	.custom-switch * {
		cursor: pointer;
	}
</style>

<script>
	$('#manage-product').on('reset', function() {
		$('input:hidden').val('')
		$('.select2').val('').trigger('change')
	});

	$('#manage-product').submit(function(e) {
		e.preventDefault();
		start_load();
		$.ajax({
			url: 'ajax.php?action=save_product',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				console.log(resp); // Log the response for debugging
				if (resp == 1) {
					alert_toast("Data successfully added", 'success');
					setTimeout(function() {
						location.reload();
					}, 1500);
				} else if (resp == 2) {
					alert_toast("Data successfully updated", 'success');
					setTimeout(function() {
						location.reload();
					}, 1500);
				} else {
					alert_toast("An error occurred: " + resp, 'danger');
				}
			}
		});
	});

	$('.edit_product').click(function() {
		start_load();
		var cat = $('#manage-product');
		cat.get(0).reset();
		cat.find("[name='id']").val($(this).attr('data-id'));
		cat.find("[name='name']").val($(this).attr('data-name'));
		cat.find("[name='description']").val($(this).attr('data-description'));
		cat.find("[name='year']").val($(this).attr('data-year'));
		cat.find("[name='qty']").val($(this).attr('data-qty'));
		cat.find("[name='price']").val($(this).attr('data-price'));
		cat.find("[name='category_id']").val($(this).attr('data-category_id')).trigger('change');
	});

	$('.delete_product').click(function() {
		_conf("Are you sure to delete this product?", "delete_product", [$(this).attr('data-id')]);
	});

	function delete_product($id) {
		start_load();
		$.ajax({
			url: 'ajax.php?action=delete_product',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success');
					setTimeout(function() {
						location.reload();
					}, 1500);
				}
			}
		});
	}

	$('table').dataTable();
</script>