<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    #productModal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0,0,0,0.6);
        padding: 1rem;
    }
    #productModal.open {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

{{-- PRODUCT GRID --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 pr-6 mt-6 p-0">

    @forelse($products as $product)
        <button
            class="product-card block shadow-2xl px-5 w-60 rounded-md py-5 h-85"
            type="button"
            data-id="{{ $product->id }}"
            data-name="{{ $product->product_name }}"
            data-imei="{{ $product->product_imei }}"
            data-note="{{ $product->note }}"
            data-storage="{{ $product->storage->name ?? '' }}"
            data-color="{{ $product->color->name ?? '' }}"
            data-price="{{ (float)($product->selling_price ?? 0) }}"
            data-brand="{{ strtolower($product->brand->name ?? 'other') }}"
            data-image="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/img/blank-profile.png') }}"
        >
            <div class="flex justify-center overflow-hidden">
                <img
                    class="w-40 h-50 object-contain"
                    src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/img/blank-profile.png') }}"
                    alt="{{ $product->product_name }}"
                    onerror="this.src='{{ asset('assets/img/blank-profile.png') }}'"
                >
            </div>
            <div class="px-4 py-3 block gap-1">
                <div class="flex justify-start font-semibold text-sm">
                    <h5>
                        {{ $product->product_name }}
                        @if($product->product_imei)
                            [ IMEI: {{ $product->product_imei }} ]
                        @endif
                    </h5>
                </div>
                <div class="flex justify-start text-start">
                    <p class="text-gray-500 text-xs leading-relaxed line-clamp-2">
                        @if($product->note){{ $product->note }}, @endif
                        {{ $product->product_name }}
                        @if($product->storage), {{ $product->storage->name }}@endif
                        @if($product->color), {{ $product->color->name }}@endif
                    </p>
                </div>
                <div class="mt-2 flex justify-start">
                    <span class="font-bold text-gray-900">
                        ${{ number_format((float)($product->selling_price ?? 0), 2) }}
                    </span>
                </div>
            </div>
        </button>
    @empty
        <div class="col-span-full text-center py-16 text-gray-400">
            <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
            <p class="text-sm">No products found.</p>
        </div>
    @endforelse

</div>

{{-- MODAL --}}
<div id="productModal">
    <div class="flex bg-white rounded-sm">

        <div class="flex justify-center items-center bg-gray-50 py-6 px-4">
            <img
                id="modalImage"
                src=""
                class="w-36 h-36 object-contain"
                alt=""
                onerror="this.src='{{ asset('assets/img/blank-profile.png') }}'"
            >
        </div>

        <div class="px-5 py-4 flex flex-col gap-3">

            <div class="flex flex-col gap-1">
                <span class="text-xs text-gray-400 uppercase tracking-wide">Product Name</span>
                <span class="font-semibold text-gray-900 text-sm" id="modalName">—</span>
            </div>

            <div class="flex justify-between gap-4">
                <div class="block">
                    <span class="text-xs text-gray-400 uppercase tracking-wide">IMEI</span>
                    <div><span class="text-gray-700 text-sm" id="modalImei">—</span></div>
                </div>
                <div>
                    <span class="text-xs text-gray-400 uppercase tracking-wide">Note</span>
                    <div><span class="text-gray-700 text-sm" id="modalNote">—</span></div>
                </div>
            </div>

            <div class="flex justify-between gap-4">
                <div class="block">
                    <span class="text-xs text-gray-400 uppercase tracking-wide">Storage</span>
                    <div><span class="text-gray-700 text-sm" id="modalStorage">—</span></div>
                </div>
                <div class="block">
                    <span class="text-xs text-gray-400 uppercase tracking-wide">Color</span>
                    <div><span class="text-gray-700 text-sm" id="modalColor">—</span></div>
                </div>
            </div>

            <div class="block">
                <span class="text-xs text-gray-400 uppercase tracking-wide">Selling Price</span>
                <div><span class="font-bold text-gray-900 text-lg" id="modalPrice">—</span></div>
            </div>

            <div class="flex justify-between font-bold gap-3">
                <button type="button" id="cancelBtn"
                    class="flex justify-center bg-red-500 px-6 py-2 rounded-sm text-white">
                    Cancel
                </button>
                <button type="button" id="addBtn"
                    class="flex justify-center bg-blue-500 px-8 py-2 rounded-sm text-white">
                    Add
                </button>
            </div>

        </div>
    </div>
</div>

<script>
$(function () {

    $(document).on('click', '.product-card', function () {
        const $card = $(this);

        $('#modalName').text($card.data('name')      || '—');
        $('#modalImei').text($card.data('imei')       || '—');
        $('#modalNote').text($card.data('note')       || '—');
        $('#modalStorage').text($card.data('storage') || '—');
        $('#modalColor').text($card.data('color')     || '—');
        $('#modalPrice').text('$' + parseFloat($card.data('price') || 0).toFixed(2));
        $('#modalImage').attr('src', $card.data('image') || '{{ asset("assets/img/blank-profile.png") }}');

        $('#addBtn')
            .data('id',      $card.data('id'))
            .data('name',    $card.data('name'))
            .data('imei',    $card.data('imei'))
            .data('note',    $card.data('note'))
            .data('storage', $card.data('storage'))
            .data('color',   $card.data('color'))
            .data('price',   $card.data('price'))
            .data('brand',   $card.data('brand'))
            .data('image',   $card.data('image'));

        $('#productModal').addClass('open');
    });

    $('#cancelBtn').on('click', function () {
        $('#productModal').removeClass('open');
    });

    $('#productModal').on('click', function (e) {
        if (e.target === this) $(this).removeClass('open');
    });

    $('#addBtn').on('click', function () {
        const $btn      = $(this);
        const productId = $btn.data('id');

        $btn.prop('disabled', true).text('Adding...');

        $.ajax({
            url: '{{ route("orders.add", ["lang" => app()->getLocale()]) }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_id:      productId,
                product_name:    $btn.data('name'),
                product_imei:    $btn.data('imei'),
                product_note:    $btn.data('note'),
                product_storage: $btn.data('storage'),
                product_color:   $btn.data('color'),
                product_price:   parseFloat($btn.data('price')) || 0
            },
            success: function (response) {
                $('#productModal').removeClass('open');

                $(`.product-card[data-id="${productId}"]`).fadeOut(300, function () {
                    if ($('.product-card:visible').length === 0) {
                        $('.empty-grid-msg').remove();
                        $('.grid').append(`
                            <div class="col-span-full empty-grid-msg text-center py-16 text-gray-400">
                                <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
                                <p class="text-sm">No products found.</p>
                            </div>
                        `);
                    }
                });

                $(document).trigger('cart:add', [{
                    id:      productId,
                    name:    $btn.data('name'),
                    imei:    $btn.data('imei'),
                    note:    $btn.data('note'),
                    storage: $btn.data('storage'),
                    color:   $btn.data('color'),
                    price:   parseFloat($btn.data('price')) || 0,
                    brand:   $btn.data('brand'),
                    image:   $btn.data('image')
                }]);
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert('Something went wrong!');
            },
            complete: function () {
                $btn.prop('disabled', false).text('Add');
            }
        });
    });

});
</script>