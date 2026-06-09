@extends('layouts.app')

@section('content')

<div class="container-fluid flex-grow-1 container-p-y">

    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">{{ __('order.create_sale') }}</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('sales.store', ['lang' => app()->getLocale()]) }}" method="POST">
                @csrf

                <!-- SALE DATE + CUSTOMER -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('order.sale_date') }}</label>
                        <input type="date" name="sale_date" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('order.customer') }}</label>
                        <select name="customer_id" class="form-select">
                            <option value="">{{ __('order.walk_in_customer') }}</option>

                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- PRODUCT SELECT -->
                <div class="mb-4">
                    <label class="form-label">{{ __('order.product') }}</label>
                    <select id="productSelect" class="form-select">
                        <option value="">{{ __('order.select_product') }}</option>

                        @foreach($products as $product)
                        @dd($products)
                            <option value="{{ $product->id }}"
                                data-imei="{{ $product->imei }}"
                                data-name="{{ $product->name }}"
                                data-detail="{{ $product->description }}"
                                data-price="{{ $product->price }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- PRODUCT TABLE -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle">

                        <thead>
                            <tr>
                                <th>{{ __('order.product_imei') }}</th>
                                <th>{{ __('order.product_name') }}</th>
                                <th>{{ __('order.product_detail') }}</th>
                                <th>{{ __('order.price') }} ($)</th>
                                <th width="120">{{ __('order.actions') }}</th>
                            </tr>
                        </thead>

                        <tbody id="productTableBody">
                            <tr id="emptyRow">
                                <td colspan="5" class="text-center">
                                    {{ __('order.no_data') }}
                                </td>
                            </tr>
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="3"></td>
                                <td><strong>Total:</strong></td>
                                <td><strong id="totalPrice">$0.00</strong></td>
                            </tr>
                        </tfoot>

                    </table>
                </div>

                <!-- NOTE -->
                <div class="mb-4">
                    <label class="form-label">{{ __('order.note') }}</label>
                    <textarea name="note" rows="4" class="form-control"></textarea>
                </div>

                <!-- BUTTONS -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        {{ __('order.submit_order') }}
                    </button>

                    <a href="{{ route('sales.index', ['lang' => app()->getLocale()]) }}"
                       class="btn btn-outline-secondary">
                        {{ __('order.cancel') }}
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {

        let total = 0;

        $('#productSelect').change(function() {

            let option = $(this).find(':selected');

            if (option.val() == '') {
                return;
            }

            $('#emptyRow').remove();

            let id = option.val();
            let imei = option.data('imei');
            let name = option.data('name');
            let detail = option.data('detail');
            let price = parseFloat(option.data('price'));

            total += price;
            $('#totalPrice').text('$' + total.toFixed(2));

            let row = `
            <tr>
                <td>${imei}</td>
                <td>${name}</td>
                <td>${detail}</td>
                <td>$${price.toFixed(2)}</td>
                <td>
                    <button type="button"
                            class="btn btn-danger btn-sm removeRow">
                        Remove
                    </button>

                    <input type="hidden"
                           name="products[]"
                           value="${id}">
                </td>
            </tr>
        `;

            $('#productTableBody').append(row);

            $(this).val('');
        });

        $(document).on('click', '.removeRow', function() {

            let row = $(this).closest('tr');
            let price = parseFloat(
                row.find('td:eq(3)').text().replace('$', '')
            );

            total -= price;

            $('#totalPrice').text('$' + total.toFixed(2));

            row.remove();

            if ($('#productTableBody tr').length == 0) {
                $('#productTableBody').html(`
                <tr id="emptyRow">
                    <td colspan="5" class="text-center">
                        NO DATA AVAILABLE
                    </td>
                </tr>
            `);
            }
        });

    });
</script>
@endsection