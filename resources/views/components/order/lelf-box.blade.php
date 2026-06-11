<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

<div class="block w-[160px] px-5 py-5">

    {{-- Logo --}}
    <div class="justify-center flex">
        <a href="{{ route('orders.index', withLang()) }}" class="flex justify-center">
            <img src="{{ $company->image_logo ?? '../assets/img/blank-profile.png' }}" alt="logo" width="100px"/>
        </a>
    </div>

    {{-- Search --}}
    <button type="button" onclick="openSearch(this)" data-brand="search"
        class="nav-btn flex gap-1 items-center justify-center my-3 px-5 py-2 w-full rounded-lg shadow-xl border border-gray-300 text-gray-600 transition-all duration-150">
        <i class="fa-solid fa-magnifying-glass text-[15px]"></i>
        <p class="text-sm">Search</p>
    </button>
    

    {{-- All Phone --}}
    <button type="button" onclick="setActive(this)" data-brand="all"
        class="nav-btn block my-3 py-3 w-full rounded-lg shadow-xl border border-gray-300 transition-all duration-150">
        <div class="flex justify-center">
            <i class="fa-solid fa-mobile text-[30px]"></i>
        </div>
        <div class="flex justify-center text-[14px] mt-2">
            <p>All Phone</p>
        </div>
    </button>

    @foreach ($brands->take(5) as $brand)
        <button type="button" onclick="setActive(this)" data-brand="{{ strtolower($brand->name) }}"
            class="nav-btn block my-3 py-3 w-full rounded-lg shadow-xl border border-gray-300 transition-all duration-150">
            <div class="flex justify-center">
                @if (strtolower($brand->name) == 'apple')
                    <i class="fa-brands fa-apple text-[30px]"></i>
                @else
                    <i class="fa-solid fa-mobile text-[30px]"></i>
                @endif
            </div>
            <div class="flex justify-center text-[14px] mt-2">
                <p>{{ $brand->name }}</p>
            </div>
        </button>
    @endforeach

</div>

<script>
function setActive(el) {
    $('.nav-btn').each(function () {
        $(this)
            .removeClass('bg-blue-500 text-white border-blue-500')
            .addClass('border-gray-300 text-gray-600');
        $(this).find('i, p').css('color', '');
    });

    $(el)
        .addClass('bg-blue-500 text-white border-blue-500')
        .removeClass('border-gray-300 text-gray-600');
    $(el).find('i, p').css('color', 'white');

    const brand = $(el).data('brand') || 'all';
    filterProducts(brand);
}

function filterProducts(brand) {

    let cartIds = [];
    try {
        cartIds = JSON.parse(localStorage.getItem('pos_cart') || '[]').map(p => String(p.id));
    } catch(e) {}

    $('.product-card').each(function () {
        const cardBrand = String($(this).data('brand') || '').toLowerCase();
        const cardId    = String($(this).data('id'));
        const inCart    = cartIds.includes(cardId);

        if (inCart) {
            $(this).hide();
            return;
        }

        if (brand === 'all' || cardBrand === brand) {
            $(this).fadeIn(200);
        } else {
            $(this).hide();
        }
    });

    $('.empty-grid-msg').remove();
    if ($('.product-card:visible').length === 0) {
        $('.grid').append(`
            <div class="col-span-full empty-grid-msg text-center py-16 text-gray-400">
                <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
                <p class="text-sm">No products found.</p>
            </div>
        `);
    }
}
function openSearch(el) {
    const isActive = $(el).hasClass('bg-blue-500');

    // Reset all nav buttons
    $('.nav-btn').each(function () {
        $(this)
            .removeClass('bg-blue-500 text-white border-blue-500')
            .addClass('border-gray-300 text-gray-600');
        $(this).find('i, p').css('color', '');
    });

    if (isActive) {
        // Clicking Search again → hide it and deactivate
        $('#search-bar').slideUp(200);
        return;
    }

    // Activate the button
    $(el)
        .addClass('bg-blue-500 text-white border-blue-500')
        .removeClass('border-gray-300 text-gray-600');
    $(el).find('i, p').css('color', 'white');

    // Show the search bar and focus it
    $('#search-bar').slideDown(200, function () {
        $('input[name="search"]').focus().select();
    });
}

</script>