@extends('layouts.app')
@push('styles')
@endpush

@section('content')
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<div class="flex justify-between mt-2 m-4">

    <div class="w-full bg-white border border-gray-200 rounded-lg shadow-md p-6 sm:p-10">
        <div class="text-start">
            <h1 class="text-2xl font-medium text-gray-500 mb-2">Register Product</h1>
        </div>

        <div class="">
            <div class="flex gap-10">
                <img src="{{ $product->image ? asset('images/product/' . $product->image) : asset('assets/img/blank-product.svg') }}"alt="camera" class="max-h-32">
                <div class=" justify-start gap-4 pt-2">
                    <button type="button" id="cancelBtn"
                        class="w-full sm:w-auto px-6 py-2.5 text-sm font-medium bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-200">
                        Upload New Photo
                    </button>
                    <button type="button"
                        class="w-full sm:w-auto px-6 py-2.5 text-sm font-medium border border-gray-300 rounded text-blue-600 hover:bg-blue-50/40 transition duration-200">
                        Reset
                    </button>
                    <p class="text-md text-gray-500 ">Allow JPG, GIF, or PNG.</p>
                </div>
            </div>

        </div>

        <form action="{{ route('products.update', ['lang' => app()->getLocale(), 'product' => $product->id]) }}"
            method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-4 mt-10">
                <div class="grid grid-cols-2 gap-2">
                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">PRODUCT NAME</label>
                        <input type="text"
                               name="product_name"
                               value="{{ old('product_name', $product->product_name) }}"
                               required
                               class="w-full px-2 py-2 text-sm border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                    </div>

                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">PRODUCT IMEI</label>
                        <input type="text" name="product_imei" placeholder="" value="{{ old('product_imei', $product->product_imei) }}" required class="w-full px-2 py-2  text-sm border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">PRODUCT CODE</label>
                        <input type="text" name="product_code" placeholder="" value="{{old('product_code', $product->product_code) }}" required class="w-full px-2 py-2  text-sm border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                    </div>

                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">CONDITION</label>
                        <select name="condition_id" required
                            class="w-full px-2 py-2  text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                            <option value="used">Used</option>

                           @foreach ($conditions as $id => $name)
                               <option value="{{ $id }}"
                                   {{ old('condition', $product->condition) == $id ? 'selected' : '' }}>
                                   {{ $name }}
                               </option>
                           @endforeach

                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">BRAND</label>
                        <select name="brand" required
                            class="w-full px-2 py-2  text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                            <option value="">Select an option</option>

                            @foreach($brands as $brand)
                            <option value="{{ $brand->id }}"
                                {{ old('brand', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">SERIES</label>
                        <select name="series" required
                            class="w-full px-2 py-2  text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                            <option value="">Select an option</option>

                            @foreach($series as $series)
                            <option value="{{ $series->id }}"
                                {{ old('series', $product->series_id) == $series->id ? 'selected' : '' }}>
                                {{ $series->name }}
                            </option>
                            @endforeach

                        </select>
                    </div>
                </div>


                <div class="grid grid-cols-2 gap-2">
                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">COLOR</label>
                        <select name="color" required
                            class="w-full px-2 py-2  text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                            <option value="">Select an option</option>

                            @foreach($colors as $color)
                            <option value="{{ $color->id }}"
                                {{ old('color', $product->color_id) == $color->id ? 'selected' : '' }}>
                                {{ $color->name }}
                            </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">MODEL</label>
                        <select name="model_type_id" required
                            class="w-full px-2 py-2  text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                            <option value="">Select an option</option>

                                @foreach ($modelTypes as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ old('model_type_id', $product->model_type_id ?? '') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach

                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">STORAGE</label>
                        <select name="storage" required
                            class="w-full px-2 py-2 text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                            <option value="">Select an option</option>

                            @foreach($storages as $storage)
                            <option value="{{ $storage->id }}"
                                {{ $product->storage_id == $storage->id ? 'selected' : '' }}>
                                {{ $storage->name }}
                            </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div class="">
                            <label for="" class="text-sm font-medium text-gray-500">TYPE OF MACHINE</label>
                            <select name="type_of_machine" required
                                class="w-full px-2 py-2  text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                                <option value="">Select an option</option>

                               @foreach ($typeOfMachines as $id => $name)
                                   <option value="{{ $id }}"
                                       {{ old('type_of_machine', $product->type_of_machine) == $id ? 'selected' : '' }}>
                                       {{ $name }}
                                   </option>
                               @endforeach

                            </select>
                        </div>

                        <div class="">
                            <label for="" class="text-sm font-medium text-gray-500">LOCK BY</label>
                            <select name="network_id" required
                                class="w-full px-2 py-2 text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                            
                                <option value="">Select an option</option>
                            
                                @foreach ($networks as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ old('network_id', $product->network_id ?? '') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">BATTERY PERCENTAGE</label>
                        <div class="relative w-full">
                            <input
                                type=""
                                class="w-full px-3 py-2 pr-10 text-sm border border-gray-300 rounded-lg focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent"
                                name="battery_percentage" value="{{old('battery_percentage', $product->battery_percentage) }}">

                        </div>
                    </div>

                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">PRODUCT PERCENTAGE</label>
                        <input
                            type=""
                            class="w-full px-3 py-2 pr-10 text-sm border border-gray-300 rounded-lg focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent"
                            name="percentage" value="{{old('percentage', $product->percentage) }}">


                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">PURCHASE PRICE</label>
                        <input
                            type=""
                            class="w-full px-3 py-2 pr-10 text-sm border border-gray-300 rounded-lg focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent"
                            name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}">

                    </div>

                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">SELLING PRICE</label>
                        <input
                            type=""
                            class="w-full px-3 py-2 pr-10 text-sm border border-gray-300 rounded-lg focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent"
                            name="selling_price" value="{{old('selling_price', $product->selling_price) }}">

                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">PURCHASE DATE</label>
                        <input
                            type="date"
                            class="w-full px-2 py-2 pr-10 text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent"
                            name="purchase_date" value="{{old('purchase_date', $product->purchase_date) }}">

                    </div>

                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">PRODUCT STATUS</label>
                        <select name="status" required
                            class="w-full px-2 py-2 text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                            <option value="">Select an option</option>

                            @foreach($statuses as $id => $name)
                                <option value="{{ $id }}"
                                    {{ old('status', $product->status) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4">
                    <a href="{{ url()->previous() }}" class="w-full sm:w-auto text-center px-6 py-2.5 text-sm font-medium border border-gray-300 rounded text-blue-600 hover:bg-blue-50/40 transition duration-200">
                        Cancel
                    </a>


                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-2.5 text-sm font-medium bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-200">
                        Save
                    </button>

                </div>
            </div>

        </form>
    </div>
</div>
@endsection