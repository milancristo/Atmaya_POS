<?php
include("config.php");
session_start();
if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['table_number']) && isset($_POST['menu_items'])) {
        $table_number = $_POST['table_number'];
        $menu_items = $_POST['menu_items'];
        $total_price = 0;
        
        foreach ($menu_items as $menu_id) {
            $query = "SELECT price FROM menu WHERE id = ?";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("i", $menu_id);
                $stmt->execute();
                $stmt->bind_result($price);
                if ($stmt->fetch()) {
                    $total_price += $price;
                }
                $stmt->close();
            }
        }
        
        $insert_query = "INSERT INTO orders (table_number, total_price) VALUES (?, ?)";
        if ($stmt = $conn->prepare($insert_query)) {
            $stmt->bind_param("id", $table_number, $total_price);
            if ($stmt->execute()) {
                $order_id = $stmt->insert_id;
                $stmt->close();
                
                foreach ($menu_items as $menu_id) {
                    $order_item_query = "INSERT INTO order_items (order_id, menu_id) VALUES (?, ?)";
                    if ($stmt = $conn->prepare($order_item_query)) {
                        $stmt->bind_param("ii", $order_id, $menu_id);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
                echo "Order placed successfully.";
            } else {
                echo "Error placing order.";
            }
        } else {
            echo "Error preparing statement.";
        }
    } else {
        echo "Missing table number or menu items.";
    }
}
?>
