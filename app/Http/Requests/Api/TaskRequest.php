<?php

namespace App\Http\Requests\Api;

use App\Enums\TaskPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
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
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'priority' => ['sometimes', 'required', Rule::enum(TaskPriority::class)],
            'parent_id' => ['sometimes', 'nullable', 'exists:tasks,id'],
            'is_completed' => ['sometimes', 'boolean'],
            'labels' => ['sometimes', 'nullable', 'array'],
            'labels.*' => ['exists:labels,id'],
        ];
    }
}
