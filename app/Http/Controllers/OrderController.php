<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Series;
use App\Models\Employee;
use App\Models\Color;
use App\Models\ModelType;
use App\Models\Storage;
use App\Models\Customer;
use App\Models\Cart;
use App\Models\CompanySetting;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:order-list|order-create|order-edit|order-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:order-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:order-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:order-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $customers = Customer::all();
        $query = Order::query();
        $parameterNames = [];

        if ($request->search) {
            $filters = $request->only(['customer', 'from_date', 'to_date']);

            if (!empty($filters['customer'])) {
                $query->where('customer_id', $filters['customer']);
                $parameterNames['customer'] = $filters['customer'];
            }

            if (!empty($filters['from_date']) && !empty($filters['to_date'])) {
                $query->whereBetween('order_date', [$filters['from_date'], $filters['to_date']]);
                $parameterNames['from_date'] = $filters['from_date'];
                $parameterNames['to_date'] = $filters['to_date'];
            } elseif (!empty($filters['from_date'])) {
                $query->where('order_date', '>=', $filters['from_date']);
                $parameterNames['from_date'] = $filters['from_date'];
            } elseif (!empty($filters['to_date'])) {
                $query->where('order_date', '<=', $filters['to_date']);
                $parameterNames['to_date'] = $filters['to_date'];
            }
        }

        $orders = $query->orderBy('order_date', 'desc')->paginate(20)->appends($parameterNames);
        session(['printInvoiceId' => null]);

        return view('orders.index', compact(
            'orders',
            'customers',
            'parameterNames',
        ));
    }

    public function show(string $lang, Order $order)
    {
        $company = CompanySetting::first();
        $order->load('customer', 'employee');

        $order_details = OrderDetail::where('order_id', $order->id)
            ->with('product.storage', 'product.color')
            ->get();

        return view('orders.show', compact('order', 'order_details', 'company'));
    }

    public function checkProductOrder(Request $request)
    {
        foreach ($request->productIds as $productId) {
            $product = Product::available()->find($productId);
            if (!$product) {
                return response()->json(['message' => 'Product not found.'], 404);
            }
        }
        return response()->json(['message' => 'Submiting Order'], 201);
    }

    public function destroy(string $lang, Order $order)
    {
        $orderDetails = OrderDetail::where('order_id', $order->id)->get();

        foreach ($orderDetails as $detail) {
            Product::where('id', $detail->product_id)->update([
                'status'     => 1,
            ]);
        }
        $order->delete();
        $orderDetails->each(fn($detail) => $detail->update(['deleted_at' => now()]));
        
        $order->update(['deleted_at' => now()]);

        return redirect()->route('sales.index', withLang())->with('success', 'Sale deleted successfully');
    }

    public function invoice(string $lang, Order $order)
    {
        $order->load('orderDetails', 'customer', 'employee');
        $order_detals = OrderDetail::where('order_id', $order->id)->with('product')->get();

        return view('orders.invoice', compact('order', 'order_detals'));
    }

    public function invoicePdf(Request $request, string $lang, Order $order)
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $order->load('orderDetails', 'customer', 'employee');
        $order_detals = OrderDetail::where('order_id', $order->id)->with('product')->get();
        $file_pdf = 'invoice-' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . '.pdf';
        $type = $request->type ?? 'download';

        return view('orders.invoice-pdf', compact('order', 'order_detals', 'currentDate', 'file_pdf', 'type'));
    }

    public function create(Request $request)
    {
        $brands = Brand::all();
        $customers = Customer::all();
        $query = Product::where('status', 1);

        $search = $request->search;
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('product_code', 'like', "%{$search}%")
                    ->orWhere('product_name', 'like', "%{$search}%")
                    ->orWhere('product_imei', 'like', "%{$search}%")
                    ->orWhere('selling_price', 'like', "%{$search}%")
                    ->orWhereHas('brand', function ($query) use ($search) {$query->where('name', 'like', "%{$search}%");});
            });
        }

        $products = $query->get();
        $product = $request->product_id ? Product::find($request->product_id) : null;

        return view('orders.create', compact(
            'products',
            'product',
            'brands',  
            'customers',
            'search'
        ));
    }
    public function store(Request $request, string $lang)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items'       => 'required|array|min:1',
            'items.*.id'  => 'required|exists:products,id',
        ]);

        $employee = Employee::where('user_id', auth()->id())->first();

        $totalAmount = array_sum(array_column($request->items, 'price'));

        $order = Order::create([
            'customer_id'    => $request->customer_id,
            'employee_id'    => $employee?->id,
            'status'         => Order::STATUS_ACTIVE,
            'payment_status' => Order::PAYMENT_STATUS_UNPAID,
            'payment_type'   => Order::PAYMENT_TYPE_CASH,
            'total_amount'   => $totalAmount,
            'order_date'     => now(),
            'note'           => $request->note,
        ]);

        foreach ($request->items as $item) {
            OrderDetail::create([
                'order_id'   => $order->id,
                'product_id' => $item['id'],
                'unit_price' => $item['price'],
            ]);
            Product::where('id', $item['id'])->update(['status' => 2]);
        }

        return response()->json([
            'success'  => true,
            'redirect' => route('orders.show', ['lang' => $lang, 'order' => $order->id])
        ]);
    }

    public function storeOrder(Request $request)
    {
        return response()->json([
            'success'         => true,
            'message'         => 'Product added to cart!',
            'product_id'      => $request->product_id,
            'product_name'    => $request->product_name,
            'product_imei'    => $request->product_imei,
            'product_note'    => $request->product_note,
            'product_storage' => $request->product_storage,
            'product_color'   => $request->product_color,
            'product_price'   => $request->product_price,
        ]);
    }
    public function createSale()
    {
        $customers = Customer::all();
        $products = Product::available()->get();

        return view('orders.create-sale', compact(
            'customers',
            'products'
        ));
    }
    public function storeSale(Request $request)
    {
        Order::create([

            'name' => $request->name,
            'customer_id' => $request->customer_id,

            'employee_id' => $request->employee_id,
            'update_id' => $request->update_id,
            'create_id' => $request->create_id
        ]);

        return redirect()->route('sales.index', ['lang' => app()->getLocale()]);
    }
}
