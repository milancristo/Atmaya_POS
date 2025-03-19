<?php
include('db_connect.php');

// Fetch categories to display in the modal
$qry = $conn->query("SELECT * FROM categories order by name asc");
while ($row = $qry->fetch_assoc()):
	$cname[$row['id']] = ucwords($row['name']);
endwhile;
?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Product List</b>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="index.php?page=add-product" id="new_order">
								<i class="fa fa-plus"></i> Add Product </a></span>
					</div>
					<div class="card-body">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Category</th>
									<th>Name</th>
									<th>Description</th>
									<th>Year</th>
									<th>Qty</th>
									<th>Price</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$product = $conn->query("SELECT * FROM products order by id asc");
								while ($row = $product->fetch_assoc()):
								?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td class=""><?php echo $cname[$row['category_id']] ?></td>
										<td class=""><?php echo $row['name'] ?></td>
										<td class=""><?php echo $row['description'] ?></td>
										<td class=""><?php echo $row['year']; ?></td> <!-- Display year without formatting -->
										<td class=""><?php echo number_format($row['qty']) ?></td>
										<td class=""><?php echo number_format($row['price'], 2) ?></td>
										<td class="text-center">
											<!-- Edit Button -->
											<button class="btn btn-sm btn-primary edit_product"
												data-id="<?php echo $row['id']; ?>"
												data-name="<?php echo $row['name']; ?>"
												data-description="<?php echo $row['description']; ?>"
												data-year="<?php echo $row['year']; ?>"
												data-qty="<?php echo $row['qty']; ?>"
												data-price="<?php echo $row['price']; ?>"
												data-category_id="<?php echo $row['category_id']; ?>">
												<i class="fa fa-edit"></i> Edit
											</button>
											<!-- Delete Button -->
											<button class="btn btn-sm btn-danger delete_product" type="button" data-id="<?php echo $row['id'] ?>">
												<i class="fa fa-trash-alt"></i> Delete
											</button>
										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>
</div>

<!-- Edit Product Modal -->
<div class="modal" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Edit Product</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="edit-product-form">
					<input type="hidden" name="id" id="product-id">
					<div class="form-group">
						<label for="category_id">Category</label>
						<select name="category_id" id="category_id" class="custom-select select2" required>
							<option value=""></option>
							<?php
							// Fetch categories for the select input
							$qry = $conn->query("SELECT * FROM categories order by name asc");
							while ($row = $qry->fetch_assoc()):
							?>
								<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
							<?php endwhile; ?>
						</select>
					</div>
					<div class="form-group">
						<label for="product-name">Name</label>
						<input type="text" class="form-control" id="product-name" name="name" required>
					</div>
					<div class="form-group">
						<label for="product-description">Description</label>
						<textarea class="form-control" id="product-description" name="description" required></textarea>
					</div>
					<div class="form-group">
						<label for="product-year">Year</label>
						<input type="number" class="form-control" id="product-year" name="year" required>
					</div>
					<div class="form-group">
						<label for="product-qty">Quantity</label>
						<input type="number" class="form-control" id="product-qty" name="qty" required>
					</div>
					<div class="form-group">
						<label for="product-price">Price</label>
						<input type="number" class="form-control" id="product-price" name="price" step="0.01" required>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="save-product">Save changes</button>
			</div>
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
	// Open the modal and populate fields when Edit button is clicked
	$('.edit_product').click(function() {
		var productId = $(this).data('id');
		var productName = $(this).data('name');
		var productDescription = $(this).data('description');
		var productYear = $(this).data('year');
		var productQty = $(this).data('qty');
		var productPrice = $(this).data('price');
		var productCategoryId = $(this).data('category_id');

		// Populate the form fields in the modal
		$('#product-id').val(productId);
		$('#product-name').val(productName);
		$('#product-description').val(productDescription);
		$('#product-year').val(productYear);
		$('#product-qty').val(productQty);
		$('#product-price').val(productPrice);
		$('#category_id').val(productCategoryId).trigger('change');

		// Show the modal
		$('#editProductModal').modal('show');
	});

	// Handle saving changes from the modal form
	$('#save-product').click(function() {
		var formData = $('#edit-product-form').serialize();

		$.ajax({
			url: 'ajax.php?action=edit_product',
			method: 'POST',
			data: formData,
			success: function(response) {
				if (response == 1) {
					alert_toast("Product successfully updated", 'success');
					setTimeout(function() {
						location.reload();
					}, 1500);
				} else {
					alert_toast("Error updating product", 'error');
				}
			}
		});
	});

	// Handle the delete product action
	$('.delete_product').click(function() {
		var productId = $(this).data('id');
		_conf("Are you sure you want to delete this product?", "delete_product", [productId]);
	});

	function delete_product(productId) {
		$.ajax({
			url: 'ajax.php?action=delete_product',
			method: 'POST',
			data: {
				id: productId
			},
			success: function(response) {
				if (response == 1) {
					alert_toast("Product successfully deleted", 'success');
					setTimeout(function() {
						location.reload();
					}, 1500);
				} else {
					alert_toast("Error deleting product", 'error');
				}
			}
		});
	}
</script>