<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="block shadow-2xl w-80 xl:px-5 md:px-3 xl:py-5 flex flex-col xl:h-screen md:h-screen ">

    {{-- Header --}}
    <div>
        <p class="font-semibold text-gray-700">Order: #00953</p>
    </div>

    {{-- Customer select --}}
    <div class="mt-2 text-[18px] flex gap-3 items-center">
        <i class="fa-solid fa-user mt-1 text-gray-500"></i>
        <select class="border px-3 py-1 xl:w-full md:w-50 text-sm" id="customerSelect">
            <option value="">-- Select Customer --</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}" data-name="{{ $customer->name }}">{{ $customer->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Order items --}}
    <div class="mt-4 flex-1 overflow-y-auto" id="orderItems">
        <div id="emptyState" class="text-center py-10 text-gray-400">
            <i class="fa-solid fa-cart-shopping text-3xl mb-2 block"></i>
            <p class="text-sm">No products yet.</p>
        </div>
    </div>

    {{-- Footer --}}
    <div class="mt-auto">
        <hr class="my-3">
        <div class="flex justify-between text-black mb-3">
            <p class="font-medium">Total</p>
            <h5 class="font-bold text-[17px]" id="orderTotal">$0.00</h5>
        </div>
        <div class="flex justify-between gap-2">
            <button class="flex-1 bg-blue-500 text-white rounded-sm py-2 text-sm">
                <i class="fa-solid fa-receipt block text-center mb-1"></i>
                Bill
            </button>
            <button id="submitOrderBtn" class="flex-1 bg-blue-500 text-white rounded-sm py-2 text-sm">
                <i class="fa-solid fa-paper-plane block text-center mb-1"></i>
                Submit Order
            </button>
        </div>
    </div>

</div>

<script>
$(function () {

    const CART_KEY = 'pos_cart';

    function saveCart(items) {
        localStorage.setItem(CART_KEY, JSON.stringify(items));
    }
    function loadCart() {
        try {
            return JSON.parse(localStorage.getItem(CART_KEY)) || [];
        } catch(e) { return []; }
    }
    function clearCart() {
        localStorage.removeItem(CART_KEY);
    }

    let orderTotal = 0;

    function renderCartItem(product) {
        let name    = product.name    || '—';
        let imei    = product.imei    || '—';
        let storage = product.storage || '—';
        let color   = product.color   || '—';
        let price   = parseFloat(product.price || 0);
        let id      = product.id;
        let image   = product.image   || '{{ asset("assets/img/blank-profile.png") }}';

        $('#emptyState').remove();

        $('#orderItems').append(`
            <div class="order-item flex justify-between items-start border-b border-gray-100 py-3">
                <div class="flex gap-3">
                    <img
                        src="${image}"
                        class="w-12 h-12 object-contain rounded bg-gray-50"
                        onerror="this.src='{{ asset('assets/img/blank-profile.png') }}'"
                    >
                    <div>
                        <p class="font-semibold text-sm text-gray-800">${name}</p>
                        <p class="text-xs text-gray-400">${storage} · ${color}</p>
                        <p class="text-xs text-gray-400">IMEI: ${imei}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold text-sm text-gray-900">$${price.toFixed(2)}</p>
                    <button
                        class="remove-item text-red-400 text-xs mt-1 hover:text-red-600"
                        data-price="${price}"
                        data-id="${id}"
                    >
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        `);
    }

    function restoreCart() {
        const items = loadCart();
        if (items.length === 0) return;

        items.forEach(function (product) {
            renderCartItem(product);
            $(`.product-card[data-id="${product.id}"]`).hide();
            orderTotal += parseFloat(product.price || 0);
        });

        $('#orderTotal').text('$' + orderTotal.toFixed(2));
    }

    restoreCart();

    $(document).on('cart:add', function (e, product) {
        let price = parseFloat(product.price || 0);

        renderCartItem(product);

        const items = loadCart();
        items.push(product);
        saveCart(items);

        orderTotal += price;
        $('#orderTotal').text('$' + orderTotal.toFixed(2));
    });

    $(document).on('click', '.remove-item', function () {
        const $btn      = $(this);
        const price     = parseFloat($btn.data('price') || 0);
        const productId = String($btn.data('id'));

        $btn.closest('.order-item').remove();

        orderTotal -= price;
        if (orderTotal < 0) orderTotal = 0;
        $('#orderTotal').text('$' + orderTotal.toFixed(2));

        const activeBrand = $('.nav-btn.bg-blue-500').data('brand') || 'all';
        const $card       = $(`.product-card[data-id="${productId}"]`);
        const cardBrand   = String($card.data('brand') || '').toLowerCase();

        if (activeBrand === 'all' || cardBrand === activeBrand) {
            $card.fadeIn(300);
        }

        const items = loadCart().filter(function (p) {
            return String(p.id) !== productId;
        });
        saveCart(items);

        if ($('.product-card:visible').length > 0) {
            $('.empty-grid-msg').remove();
        }

        if ($('#orderItems .order-item').length === 0) {
            clearCart();
            $('#orderItems').append(`
                <div id="emptyState" class="text-center py-10 text-gray-400">
                    <i class="fa-solid fa-cart-shopping text-3xl mb-2 block"></i>
                    <p class="text-sm">No products yet.</p>
                </div>
            `);
        }
    });

    $('#submitOrderBtn').on('click', function () {
        const items = loadCart();
        const $selectedCustomer = $('#customerSelect option:selected');

        if (items.length === 0) {
            alert('Cart is empty!');
            return;
        }

        // Disable button to prevent double submit
        $('#submitOrderBtn').prop('disabled', true).text('Submitting...');

        $.ajax({
            url: '{{ route("orders.store", ["lang" => app()->getLocale()]) }}',
            method: 'POST',
            contentType: 'application/json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: JSON.stringify({
                customer_id:   $('#customerSelect').val()       || null,
                customer_name: $selectedCustomer.data('name')   || null,
                note:          $('#orderNote').val()             || null,
                items:         items,
            }),
            success: function (res) {
                if (res.success) {

                    // ✅ Show all product cards that were hidden (in cart)
                    items.forEach(function (product) {
                        $(`.product-card[data-id="${product.id}"]`).show();
                    });

                    // ✅ Remove empty grid message if cards are now visible
                    if ($('.product-card:visible').length > 0) {
                        $('.empty-grid-msg').remove();
                    }

                    clearCart();
                    orderTotal = 0;
                    $('#orderTotal').text('$0.00');
                    $('#customerSelect').val('');

                    $('#orderItems').html(`
                        <div id="emptyState" class="text-center py-10 text-gray-400">
                            <i class="fa-solid fa-cart-shopping text-3xl mb-2 block"></i>
                            <p class="text-sm">No products yet.</p>
                        </div>
                    `);

                    window.location.href = res.redirect;
                }
            },
            error: function (xhr) {
                $('#submitOrderBtn').prop('disabled', false).html(`
                    <i class="fa-solid fa-paper-plane block text-center mb-1"></i>
                    Submit Order
                `);
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let msg = '';
                    $.each(errors, function (key, val) {
                        msg += val[0] + '\n';
                    });
                    alert('Validation Error:\n' + msg);
                } else {
                    console.error(xhr.responseText);
                    alert('Submit failed! Check console.');
                }
            }
        });
    });
});
</script>