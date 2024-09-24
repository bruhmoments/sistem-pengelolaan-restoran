<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Food List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #35424a;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .food-list {
            list-style: none;
            padding: 0;
            margin: 20px;
        }
        .food-item {
            background: #ffffff;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .cart-summary {
            margin-top: 20px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
        }
    </style>
</head>
<body>

<header>
    <h1>Food Menu</h1>
</header>

<div class="container">
    <ul class="food-list" id="food-list">
        @foreach($menus as $food)
            <li class="food-item">
                <div>
                    <h2>{{ $food->nama }}</h2>
                    <p>{{ $food->kategori->nama }}</p>
                    <p>Price: {{ $food->harga }}</p>
                </div>
                <button onclick="addToCart({{ $food->id }}, 1)">Add to Cart</button>
            </li>
        @endforeach
    </ul>

    <div class="container" style="padding: 16px 0 32px 32px">
        <h2>Cart</h2>
        <ul class="cart-list" id="cart-list"></ul>
        <div class="cart-summary">
            <span>Total (Rp.):</span>
            <span id="total-price">0</span>
        </div>
        <br/>
        <button class="checkout-button" onclick="handleCheckout()">Checkout</button>
    </div>
    <div class="modal" id="checkout-modal" style="display:none;">
        <div class="modal-content">
            <button onclick="closeModal()">X</button>
            <h2>Confirm Checkout</h2>
            <p>Total: <span id="total-modal-price"></span></p>
            <input type="email" id="email" placeholder="Enter your email" />
            <button onclick="confirmCheckout()">Confirm</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let foodData = [
        @foreach($menus as $food)
        { id: {{ $food->id }}, nama: '{{ $food->nama }}', kategori: { nama: '{{ $food->kategori->nama }}' }, harga: {{ $food->harga }} },
        @endforeach
    ];

    let cart = [];

    function addToCart(foodId, quantity) {
        const food = foodData.find(item => item.id === foodId);
        if (food) {
            const cartItem = cart.find(item => item.food.id === foodId);
            if (cartItem) {
                cartItem.quantity += quantity;
                cartItem.subtotal = food.harga * cartItem.quantity
            } else {
                cart.push({ food: food, quantity: quantity, subtotal: food.harga });
            }
            renderCart();
        }
    }

    function updateCartQuantity(foodId, change) {
        const cartItem = cart.find(item => item.food.id === foodId);
        if (cartItem) {
            cartItem.quantity += change;
            if (cartItem.quantity <= 0) {
                cart = cart.filter(item => item.food.id !== foodId);
            }
            renderCart();
        }
    }

    function updateQuantity(foodId, value) {
        const quantity = parseInt(value);
        if (quantity > 0) {
            const cartItem = cart.find(item => item.food.id === foodId);
            if (cartItem) {
                cartItem.quantity = quantity;
            }
        }
        renderCart();
    }

    function calculateTotal() {
        return cart.reduce((total, item) => total + (item.food.harga * item.quantity), 0);
    }

    function renderCart() {
        const cartList = document.getElementById('cart-list');
        cartList.innerHTML = cart.map(item => `
            <li class="cart-item">
                <div>
                    ${item.food.nama} - Rp. ${item.food.harga}
                </div>
                <div>
                    <button onclick="updateCartQuantity(${item.food.id}, -1)">-</button>
                    <input type="number" min="1" value="${item.quantity}" onchange="updateQuantity(${item.food.id}, this.value)" />
                    <button onclick="updateCartQuantity(${item.food.id}, 1)">+</button>
                </div>
            </li>
        `).join('');

        document.getElementById('total-price').innerText = calculateTotal();
    }

    function handleCheckout() {
        if (cart.length < 1) {
            alert("Anda belum memesan apapun!");
            return;
        }
        const total = calculateTotal();
        document.getElementById('total-modal-price').innerText = total;
        document.getElementById('checkout-modal').style.display = 'block';
    }

    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    function confirmCheckout() {
        const email = document.getElementById('email').value;
        if (!email) {
            alert("Anda belum mengisi email!");
            return;
        }

        const orderDetails = {
            total: calculateTotal(),
            email_pembeli: email,
            detail_pesanan: cart.map(item => ({
                menu_id: item.food.id,
                qty: item.quantity,
                subtotal: item.subtotal
            })),
        };

        $.ajax({
            url: '/checkout',
            type: 'POST',
            data: JSON.stringify(orderDetails),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                console.log('Success:', response);
                alert('Order processed successfully!');
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                // Handle error response here, e.g., display an error message
                alert('An error occurred while processing your order.');
            }
        });
    }

    function closeModal() {
        document.getElementById('checkout-modal').style.display = 'none';
    }

    window.onload = () => {
        renderCart();
    };
</script>

</body>
</html>
