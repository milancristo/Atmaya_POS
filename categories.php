<?php include('db_connect.php'); ?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Category List</b>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="index.php?page=add-category" id="new_order">
								<i class="fa fa-plus"></i> Add Category </a></span>
					</div>
					<div class="card-body">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>Description</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$category = $conn->query("SELECT * FROM categories order by id asc");
								while ($row = $category->fetch_assoc()):
								?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td class=""><?php echo $row['name'] ?></td>
										<td><?php echo $row['description'] ?></td>
										<td class="text-center">
											<!-- Edit Button -->
											<button class="btn btn-sm btn-primary edit_category" type="button" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" data-description="<?php echo $row['description'] ?>"><i class="fa fa-edit"></i> Edit</button>
											<!-- Delete Button -->
											<button class="btn btn-sm btn-danger delete_category" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash-alt"></i> Delete</button>
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

<!-- Edit Category Modal -->
<div class="modal" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Edit Category</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="edit-category-form">
					<input type="hidden" name="id" id="category-id">
					<div class="form-group">
						<label for="category-name">Name</label>
						<input type="text" class="form-control" id="category-name" name="name" required>
					</div>
					<div class="form-group">
						<label for="category-description">Description</label>
						<textarea class="form-control" id="category-description" name="description" required></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="save-category">Save changes</button>
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
</style>

<script>
	// Open the modal and populate fields when Edit button is clicked
	$('.edit_category').click(function() {
		var categoryId = $(this).data('id');
		var categoryName = $(this).data('name');
		var categoryDescription = $(this).data('description');

		// Populate the form fields in the modal
		$('#category-id').val(categoryId);
		$('#category-name').val(categoryName);
		$('#category-description').val(categoryDescription);

		// Show the modal
		$('#editCategoryModal').modal('show');
	});

	// Handle saving changes from the modal form
	$('#save-category').click(function() {
		var formData = $('#edit-category-form').serialize();

		$.ajax({
			url: 'ajax.php?action=edit_category',
			method: 'POST',
			data: formData,
			success: function(response) {
				if (response == 1) {
					alert_toast("Category successfully updated", 'success');
					setTimeout(function() {
						location.reload();
					}, 1500);
				} else {
					alert_toast("Error updating category", 'error');
				}
			}
		});
	});

	$('.delete_category').click(function() {
		_conf("Are you sure to delete this category?", "delete_category", [$(this).attr('data-id')]);
	});

	function delete_category($id) {
		start_load();
		$.ajax({
			url: 'ajax.php?action=delete_category',
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