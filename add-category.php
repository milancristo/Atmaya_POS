<?php include('db_connect.php'); ?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-12">
				<form action="" id="manage-category">
					<div class="card">
						<div class="card-header">
							Category Form
						</div>
						<div class="card-body">
							<input type="hidden" name="id"> <!-- Hidden input for category ID -->
							<div class="form-group">
								<label class="control-label">Name</label>
								<input type="text" class="form-control" name="name">
							</div>
							<div class="form-group">
								<label class="control-label">Description</label>
								<textarea name="description" id="description" cols="30" rows="4" class="form-control"></textarea>
							</div>
						</div>

						<div class="card-footer">
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-primary"> Save</button>
									<button class="btn btn-default" type="button" onclick="$('#manage-category').get(0).reset()"> Cancel</button>
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
</style>

<script>
	// Reset form when it's reset
	$('#manage-category').on('reset', function() {
		$('input:hidden').val(''); // Reset hidden input
	});

	// Form submission
	$('#manage-category').submit(function(e) {
		e.preventDefault();
		start_load();

		$.ajax({
			url: 'ajax.php?action=save_category',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			success: function(resp) {
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
				}
			}
		});
	});

	$('.edit_category').click(function() {
		start_load();
		var cat = $('#manage-category');
		cat.get(0).reset(); // Reset the form

		// Get the category ID from the clicked button
		var catId = $(this).attr('data-id');

		// Use AJAX to fetch the category data
		$.ajax({
			url: 'ajax.php?action=get_category',
			method: 'GET',
			data: {
				id: catId
			},
			success: function(resp) {
				if (resp) {
					// Parse response data (assuming JSON format)
					var category = JSON.parse(resp);

					// Fill form with the existing category data
					cat.find("[name='id']").val(category.id);
					cat.find("[name='name']").val(category.name);
					cat.find("[name='description']").val(category.description);
				}
				end_load();
			}
		});
	});


	// Delete button handling
	$('.delete_category').click(function() {
		_conf("Are you sure to delete this category?", "delete_category", [$(this).attr('data-id')]);
	});

	// Deleting a category
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

	<?php
	// Fetch category data for editing
	function get_category($id)
	{
		// Ensure $id is a valid integer
		$id = intval($id);

		// Query the database to get the category by id
		global $conn;
		$qry = $conn->query("SELECT * FROM categories WHERE id = $id");

		// Check if the query was successful and if the category exists
		if ($qry && $qry->num_rows > 0) {
			return $qry->fetch_assoc(); // Return the category data as an associative array
		}

		// If the category doesn't exist, return false
		return false;
	}
	?>


	// Initialize DataTable
	$('table').dataTable();
</script>