<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AllReviewActionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'action' => 'required|string|in_array:publish,unpublish,delete',
            'product_id' => 'required'
        ];
    }
}
