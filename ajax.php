<?php
ob_start();
include 'admin_class.php';
$crud = new Action();
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Check if the action is valid
if ($action) {
	switch ($action) {
		case 'login':
			$response = $crud->login();
			break;

		case 'login2':
			$response = $crud->login2();
			break;

		case 'logout':
			$response = $crud->logout();
			break;

		case 'logout2':
			$response = $crud->logout2();
			break;

		case 'save_user':
			$response = $crud->save_user();
			break;

		case 'delete_user':
			$response = $crud->delete_user();
			break;

		case 'signup':
			$response = $crud->signup();
			break;

		case 'update_account':
			$response = $crud->update_account();
			break;

		case 'save_settings':
			$response = $crud->save_settings();
			break;

		case 'save_category':
			$response = $crud->save_category();
			break;

		case 'edit_category':
			$response = $crud->edit_category();
			break;

		case 'delete_category':
			$response = $crud->delete_category();
			break;

		case 'save_product':
			$response = $crud->save_product();
			break;

		case 'edit_product': // New case for saving edited product
			$response = $crud->edit_product();
			break;

		case 'delete_product':
			$response = $crud->delete_product();
			break;

		case 'save_order':
			$response = $crud->save_order();
			break;

		case 'delete_order':
			$response = $crud->delete_order();
			break;

		default:
			$response = 'Invalid action';
			break;
	}

	// Echo response
	echo $response;
}

ob_end_flush();
