@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

<div class="min-h-screen bg-gray-100 py-10 px-4">
    <div class="max-w-full mx-auto bg-white shadow-lg rounded-sm">

        {{-- Top action bar --}}
        <div class="flex justify-between items-center px-8 pt-6 pb-4 border-b border-gray-100">
            <a href="{{ route('orders.index', ['lang' => app()->getLocale()]) }}"
               class="text-sm text-gray-500 hover:text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Orders
            </a>
            <div class="flex gap-2">
                <a href="{{ route('orders.index', ['lang' => app()->getLocale(), 'order' => $order->id]) }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-sm flex items-center gap-2">
                    <i class="fa-solid fa-print"></i> Print Invoice
                </a>
            </div>
        </div>

        <div class="px-[40px] py-[30px]">

            {{-- Shop Header --}}
            <div class="flex justify-center text-center mb-6">
                <div>
                    <h4 class="text-xl font-bold text-gray-800 mb-1">{{ $company->name }}</h4>
                    <p class="text-sm text-gray-500">{{ $company->detail }}</p>
                </div>
            </div>

            {{-- Shop Info + Invoice Info --}}
            <div class="flex justify-between mb-6 text-sm text-gray-600">
                <div class="flex flex-col gap-1">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-phone text-gray-400 w-4"></i>
                        <span>{{ $company->phone }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-location-pin text-gray-400 w-4"></i>
                        <span>{{ $company->address }}</span>
                    </div>
                    <div class="flex items-start gap-2 mt-1">
                        <i class="fa-solid fa-note-sticky text-gray-400 w-4 mt-0.5"></i>
                        <span class="text-gray-400 text-xs">{{ $company->default_loan_note }}</span>
                    </div>
                </div>
                <div class="text-right flex flex-col gap-1">
                    <p><span class="text-gray-400">Invoice:</span> <span class="font-semibold">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span></p>
                    <p><span class="text-gray-400">Issued Date:</span> {{ $order->created_at->format('d/m/Y') }}</p>
                    <p><span class="text-gray-400">Order Date:</span> {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</p>
                    <p><span class="text-gray-400">Staff:</span> {{ $order->employee->name ?? Auth::user()->name ?? '—' }}</p>
                </div>
            </div>

            <hr class="mb-6 border-gray-200">

            {{-- Customer Info --}}
            <div class="mb-6 text-sm">
                <p class="text-gray-400 uppercase text-xs tracking-widest mb-1">Bill To</p>
                <p class="font-semibold text-gray-800">{{ $order->customer->name ?? 'Walk-in Customer' }}</p>
                @if($order->customer && $order->customer->phone)
                    <p class="text-gray-500">{{ $order->customer->phone }}</p>
                @endif
                @if($order->customer && $order->customer->address)
                    <p class="text-gray-500">{{ $order->customer->address }}</p>
                @endif
            </div>

            {{-- Invoice Title --}}
            <div class="flex justify-center mb-6">
                <h5 class="text-lg font-bold text-gray-700 tracking-widest uppercase">Invoice</h5>
            </div>

            {{-- Order Items Tab{{ $company->default_invoice_note }}le --}}
            <div class="overflow-x-auto mb-6">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-t border-b border-gray-200 text-gray-400 uppercase text-xs tracking-wider">
                            <th class="py-3 text-left font-medium">#</th>
                            <th class="py-3 text-left font-medium">Item</th>
                            <th class="py-3 text-left font-medium">Description</th>
                            <th class="py-3 text-left font-medium">IMEI</th>
                            <th class="py-3 text-right font-medium">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order_details as $index => $detail)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="py-3 text-gray-400">{{ $index + 1 }}</td>
                            <td class="py-3 text-gray-800 font-medium">
                                {{ $detail->product->product_name ?? '—' }}
                            </td>
                            <td class="py-3 text-gray-500">
                                @if($detail->product)
                                    {{ $detail->product->storage->name ?? '' }}
                                    @if($detail->product->storage && $detail->product->color) · @endif
                                    {{ $detail->product->color->name ?? '' }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="py-3 text-gray-500 text-xs">
                                {{ $detail->product->product_imei ?? '—' }}
                            </td>
                            <td class="py-3 text-right font-semibold text-gray-800">
                                ${{ number_format($detail->unit_price, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400 text-sm">
                                <i class="fa-solid fa-box-open text-2xl mb-2 block"></i>
                                No items found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Totals --}}
            <div class="flex justify-end mb-8">
                <div class="w-64 flex flex-col gap-2 text-sm">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal</span>
                        <span>${{ number_format($order_details->sum('unit_price'), 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Discount</span>
                        <span>$0.00</span>
                    </div>
                    <hr class="border-gray-200">
                    <div class="flex justify-between font-bold text-gray-800 text-base">
                        <span>Total</span>
                        <span>${{ number_format($order_details->sum('unit_price'), 2) }}</span>
                    </div>
                </div>
            </div>

            <hr class="mb-6 border-gray-200">

            {{-- Footer note --}}
            <div class="text-center text-xs text-gray-400 pb-2">
                <p>Thank you for your purchase!</p>
                    <p class="mt-1">{{ $company->default_loan_note }}</p>
            </div>

        </div>
    </div>
</div>

@endsection