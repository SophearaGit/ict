<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CourseCategoryStoreRequest extends FormRequest
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
        // /**
        //  * Origin of rules:
        //  */
        // return [
        //     'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        //     'icon' => 'required|string|max:255',
        //     'name' => 'required|string|max:255|unique:course_categories',
        //     'show_at_trending' => 'nullable|boolean',
        //     'status' => 'nullable|boolean',
        // ];
        return [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon' => 'nullable|string|max:255',
            'name' => 'required|string|max:255|unique:course_categories',
            'parent_id' => 'nullable|exists:course_categories,id',
            'show_at_trending' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ];
    }
}
