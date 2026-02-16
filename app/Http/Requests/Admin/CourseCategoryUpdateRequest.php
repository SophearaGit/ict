<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CourseCategoryUpdateRequest extends FormRequest
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
        // // origin rules
        // return [
        //     'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3000',
        //     'icon' => 'required|string|max:255',
        //     'name' => 'required|string|max:255|unique:course_categories,name,' . $this->course_category->id,
        //     'show_at_trending' => 'nullable|boolean',
        //     'status' => 'nullable|boolean',
        // ];

        return [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3000',
            'icon' => 'nullable|string|max:255',
            'name' => 'required|string|max:255|unique:course_categories,name,' . $this->course_category->id,
            'parent_id' => 'nullable|exists:course_categories,id',
            'show_at_trending' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ];

    }
}
