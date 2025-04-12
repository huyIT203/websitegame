<?php
include 'check.php';

$user_id = $_SESSION['id'];

$user = $query->executeQuery("SELECT * FROM accounts WHERE id = $user_id")->fetch_assoc();
$cart = $query->executeQuery("SELECT * FROM cart WHERE user_id = $user_id");

$price_old_Sum = 0;
$price_current_Sum = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            color: #6c5ce7;
            margin-bottom: 30px;
            font-size: 2.5em;
            font-weight: 700;
            position: relative;
            padding-bottom: 15px;
        }
        
        h2:after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: #6c5ce7;
            border-radius: 2px;
        }

        h3 {
            color: #2d3436;
            font-size: 1.5em;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f1f1;
        }

        .user-information,
        .cart-summary {
            margin-bottom: 40px;
            background-color: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .user-information ul {
            list-style-type: none;
            padding: 0;
            font-size: 1.1em;
            color: #555;
        }

        .user-information li {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f1f1f1;
        }

        .user-information li:last-child {
            border-bottom: none;
        }

        .user-information li strong {
            color: #6c5ce7;
            font-weight: 600;
            display: inline-block;
            width: 150px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 18px 20px;
            text-align: left;
            border: none;
            font-size: 1.05em;
        }

        th {
            background-color: #6c5ce7;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #e0f7fa;
            transition: background-color 0.3s ease;
        }

        .total {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .total p {
            font-size: 1.2em;
            font-weight: 500;
            color: #2d3436;
            margin: 15px 0;
            text-align: right;
        }

        .total p:last-child {
            margin-bottom: 30px;
        }

        .total span {
            font-weight: 700;
        }

        .price del {
            color: #e74c3c;
            font-size: 0.9em;
            margin-right: 10px;
        }

        .price {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price span {
            color: #6c5ce7;
            font-weight: 700;
        }

        .payment-button {
            background-color: #6c5ce7;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            font-size: 1.2em;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s ease;
            display: block;
            width: 100%;
            max-width: 300px;
            margin-left: auto;
        }

        .payment-button:hover {
            background-color: #5649c0;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(108, 92, 231, 0.3);
        }

        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Checkout Summary</h2>

        <div class="user-information">
            <h3>User Information</h3>
            <ul>
                <li><strong>Name:</strong> <?= htmlspecialchars($user['name']); ?></li>
                <li><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></li>
                <li><strong>Phone Number:</strong> <?= htmlspecialchars($user['number']); ?></li>
            </ul>
        </div>

        <div class="cart-summary">
            <h3>Cart Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($cart as $index => $item) {
                        $product_id = $item["product_id"];
                        $product = $query->executeQuery("SELECT * FROM products WHERE id = $product_id")->fetch_assoc();
                        $price_old = $product['price_old'];
                        $price_current = $product['price_current'];

                        $price_old_Sum += $price_old * $item['number_of_products'];
                        $price_current_Sum += $price_current * $item['number_of_products'];
                    ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($product['name']); ?></td>

                            <td>
                                <div class="price">
                                    <del>$<?= number_format($price_old, 2); ?></del>
                                    <span>$<?= number_format($price_current, 2); ?></span>
                                </div>
                            </td>

                            <td><?= $item['number_of_products']; ?></td>

                            <td>
                                <div class="price">
                                    <del>$<?= number_format($price_old * $item['number_of_products'], 2); ?></del>
                                    <span>$<?= number_format($price_current * $item['number_of_products'], 2); ?></span>
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="total">
            <p>Total (Old Price): <span><del>$<?= number_format($price_old_Sum, 2); ?></del></span></p>
            <p>Total (Current Price): <span style="color: #6c5ce7">$<?= number_format($price_current_Sum, 2); ?></span>
            </p>
            
            <div class="shipping-address" style="margin: 25px 0; text-align: left;">
                <h3 style="color: #2d3436; font-size: 1.2em; margin-bottom: 15px;">Shipping Address</h3>
                <textarea id="shipping_address" style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 5px; min-height: 100px; font-family: inherit;" placeholder="Enter your shipping address here..."></textarea>
            </div>
            
            <button onclick="processPayment()" class="payment-button">Process Payment</button>
        </div>
    </div>

    <!-- Add SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function processPayment() {
            // Get shipping address
            const shippingAddress = document.getElementById('shipping_address').value;
            
            // Send payment request to process_payment.php
            fetch('process_payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    total: <?php echo $price_current_Sum; ?>,
                    shipping_address: shippingAddress
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        title: 'Payment Successful!',
                        text: 'Your order has been processed successfully.',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#6c5ce7',
                        cancelButtonColor: '#7fad39',
                        confirmButtonText: 'View Order Details',
                        cancelButtonText: 'Continue Shopping'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'order_detail.php?id=' + data.order_id;
                        } else {
                            window.location.href = 'profile.php#orders';
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Payment failed. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    </script>

</body>

</html>