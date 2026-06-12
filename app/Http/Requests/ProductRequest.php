<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

      return [

      'product_code'       => 'nullable|string|max:255',
            'product_name'       => 'required|string|max:255',
            'product_imei'       => 'required|numeric',
            'brand_id'           => 'required|integer|exists:brands,id',
            'series_id'          => 'required|integer|exists:series,id',
            'color_id'           => 'required|integer|exists:colors,id',
            'condition'          => 'required|integer',
            'storage_id'         => 'required|integer|exists:storages,id',
            'model_type_id'      => 'required|integer|exists:model_types,id',
            'network_id'         => 'nullable|integer|exists:networks,id', 
            'battery_percentage' => 'nullable|integer|between:0,100',
            'percentage'         => 'nullable|numeric',
            'purchase_price'     => 'required|numeric|min:0',
            'selling_price'      => 'nullable|numeric|min:0', 
            'purchase_date'      => 'required|date',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'note'               => 'nullable|string',
            'type_of_machine'    => 'required|string|max:255',
            'status'             => 'required|integer',














        // 'product_code' => '',
        // 'product_name' => 'required|string|max:255',
        // 'product_imei' => 'required|numeric',
        // 'brand_id' => 'required|integer|exists:brands,id',
        // 'series_id' => 'required|integer|exists:series,id',
        // 'color_id' => 'required|integer|exists:colors,id',
        // 'model_type_id ' => 'required|integer|exists:model_types,id',
        // 'condition' => 'required|string|max:255',
        // 'storage_id' => 'required|integer|exists:storages,id',
        // 'type_of_machine' => 'required|string|max:255',
        // 'network_id' => 'required_if:type_of_machine,2',
        // 'battery_percentage' =>'',
        // 'percentage' => '',
        // 'purchase_price' => 'required|numeric',
        // 'selling_price' => 'numeric',
        // 'employee_id' => '',
        // 'purchase_date ' => '',
        // 'image' => '',
        // 'purchase_date' => 'required|date',
        // 'status' => 'required|string', // Change 'active' and 'inactive' to the valid status values.
    ];
    }
}
