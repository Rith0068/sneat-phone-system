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
                <img src="{{ asset('assets/img/blank-product.svg') }}" alt="camera" class="max-h-32 ">
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

        <form action="{{route('products.store', withLang())}}" method="post">
            @csrf

            <div class="space-y-4 mt-10">
                <div class="grid grid-cols-2 gap-2">
                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">PRODUCT NAME</label>
                        <input type="text" name="productName" placeholder="" required class="w-full px-2 py-2 text-sm border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                    </div>

                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">PRODUCT IMEI</label>
                        <input type="text" name="productImei" placeholder="" required class="w-full px-2 py-2  text-sm border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">PRODUCT CODE</label>
                        <input type="text" name="productCode" placeholder="" required class="w-full px-2 py-2  text-sm border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                    </div>

                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">CONDITION</label>
                        <select name="condition" required
                            class="w-full px-2 py-2  text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                            <option value="used">Used</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">BRAND</label>
                        <select name="brand" required
                            class="w-full px-2 py-2  text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                            <option value="">Select an option</option>
                            <option value="">samsung</option>
                        </select>
                    </div>

                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">SERIES</label>
                        <select name="series" required
                            class="w-full px-2 py-2  text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                            <option value="">Select an option</option>
                        </select>
                    </div>
                </div>


                <div class="grid grid-cols-2 gap-2">
                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">COLOR</label>
                        <select name="color" required
                            class="w-full px-2 py-2  text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                            <option value="">Select an option</option>
                        </select>
                    </div>

                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">MODEL</label>
                        <select name="model" required
                            class="w-full px-2 py-2  text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                            <option value="">Select an option</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">STORAGE</label>
                        <select name="storage" required
                            class="w-full px-2 py-2 text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                            <option value="">Select an option</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div class="">
                            <label for="" class="text-sm font-medium text-gray-500">TYPE OF MACHINE</label>
                            <select name="machine_type" required
                                class="w-full px-2 py-2  text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                                <option value="">Select an option</option>
                            </select>
                        </div>

                        <div class="">
                            <label for="" class="text-sm font-medium text-gray-500">LOCK BY</label>
                            <select name="lock_by" required
                                class="w-full px-2 py-2  text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                                <option value="">Select an option</option>
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
                                name="batteryPercentage">

                        </div>
                    </div>

                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">PRODUCT PERCENTAGE</label>
                        <input
                            type=""
                            class="w-full px-3 py-2 pr-10 text-sm border border-gray-300 rounded-lg focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent"
                            name="batteryPercentage">


                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">PURCHASE PRICE</label>
                        <input
                            type=""
                            class="w-full px-3 py-2 pr-10 text-sm border border-gray-300 rounded-lg focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent"
                            name="batteryPercentage">

                    </div>

                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">SELLING PRICE</label>
                        <input
                            type=""
                            class="w-full px-3 py-2 pr-10 text-sm border border-gray-300 rounded-lg focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent"
                            name="batteryPercentage">

                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">PURCHASE DATE</label>
                        <input
                            type="date"
                            class="w-full px-2 py-2 pr-10 text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent"
                            name="purchaseDate">

                    </div>

                    <div class="">
                        <label for="" class="text-sm font-medium text-gray-500">PRODUCT STATUS</label>
                        <select name="productStatus" required
                            class="w-full px-2 py-2 text-sm text-gray-500 border border-gray-300 rounded focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition duration-200 bg-transparent">
                            <option value="">Select an option</option>
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