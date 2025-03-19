<?php
include('db_connect.php');

// Check if 'id' is set in the URL
if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$query = $conn->query("SELECT * FROM products WHERE id = $id");
	$product = $query->fetch_assoc();  // Fetch the product data
} else {
	// Handle the case where 'id' is not set (e.g., invalid access)
	die("Product ID is missing");
}
?>

<!-- Button to trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editProductModal">
	Edit Product
</button>

<!-- Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editProductModalLabel">Edit Product Form</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="" id="manage-product">
				<div class="modal-body">
					<!-- Hidden Field for Product ID -->
					<input type="hidden" name="id" value="<?php echo $product['id']; ?>">

					<!-- Category Selection -->
					<div class="form-group">
						<label class="control-label">Category</label>
						<select name="category_id" id="category_id" class="custom-select select2" required>
							<option value=""></option>
							<?php
							// Get all categories
							$qry = $conn->query("SELECT * FROM categories ORDER BY name ASC");
							while ($row = $qry->fetch_assoc()):
								$selected = ($row['id'] == $product['category_id']) ? 'selected' : '';
							?>
								<option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>>
									<?php echo $row['name']; ?>
								</option>
							<?php endwhile; ?>
						</select>
					</div>

					<!-- Product Name -->
					<div class="form-group">
						<label class="control-label">Name</label>
						<input type="text" class="form-control" name="name" value="<?php echo $product['name']; ?>" required>
					</div>

					<!-- Product Description -->
					<div class="form-group">
						<label class="control-label">Description</label>
						<textarea name="description" id="description" cols="30" rows="4" class="form-control" required><?php echo $product['description']; ?></textarea>
					</div>

					<!-- Product Year -->
					<div class="form-group">
						<label class="control-label">Year</label>
						<input type="number" class="form-control text-right" name="year" value="<?php echo $product['year']; ?>" required>
					</div>

					<!-- Product Qty -->
					<div class="form-group">
						<label class="control-label">Qty</label>
						<input type="number" class="form-control text-right" name="qty" value="<?php echo $product['qty']; ?>" required>
					</div>

					<!-- Product Price -->
					<div class="form-group">
						<label class="control-label">Price</label>
						<input type="number" class="form-control text-right" name="price" value="<?php echo $product['price']; ?>" required>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Save</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Include Bootstrap CSS and JS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- jQuery Script for handling the form submission -->
<script>
	$('#manage-product').submit(function(e) {
		e.preventDefault();
		start_load(); // Assuming you have a function to show loading

		// AJAX request to update product
		$.ajax({
			url: 'ajax.php?action=save_edit_product', // Call the method in ajax.php to handle product editing
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Product updated successfully", 'success'); // Assuming you have a function for alerts
					setTimeout(function() {
						location.href = 'products.php'; // Redirect to products page after success
					}, 1500);
				} else {
					alert_toast("Error updating product", 'error');
				}
			}
		});
	});
</script>

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