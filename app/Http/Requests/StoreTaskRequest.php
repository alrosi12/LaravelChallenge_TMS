<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:pending,in_progress,done',
            'due_date' => 'nullable|date|after:today',
            'assignee_id' => 'nullable|integer|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'عنوان المهمة مطلوب',
            'title.max' => 'عنوان المهمة لايتجاوز 100 حرف',
            'due_date.after' => 'تاريخ الانتهاء لازم يكون ف المستقبل',
            'due_date.date' => 'تاريخ الانتهاء غير صالح',
            'assignee_id.exists' => 'المستخدم المكلف غير موجود',
            'status.in' => 'حالة المهمة غير صالحة',
        ];
    }
}
