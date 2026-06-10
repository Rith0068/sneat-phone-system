<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<div class="flex w-full justify-between">
        <div>
                <x-order.lelf-box 
                :products="$products"
                :search="$search"
                />
        </div>
        
        <div>
                <x-order.cart class=""
                :products="$products"
                />  
        </div>
        
        <div>
                <x-order.right 
                :product="$product"
                :customers="$customers"
                />
        </div>
        
        
</div>
