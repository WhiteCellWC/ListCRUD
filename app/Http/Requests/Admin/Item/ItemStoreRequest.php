<?php

namespace App\Http\Requests\Admin\Item;

use Illuminate\Foundation\Http\FormRequest;

class ItemStoreRequest extends FormRequest
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
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'description' => 'required',
            'item_condition' => 'nullable',
            'item_type' => 'nullable',
            'is_published' => 'nullable',
            'image' => 'required|image',
            'owner_name' => 'required|string',
            'contact_number' => 'nullable',
            'address' => 'nullable',
            'location' => 'nullable'
        ];
    }
}
