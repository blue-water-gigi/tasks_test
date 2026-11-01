<?php

namespace App\Http\Requests;

use App\Enums\TaskCategory;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TaskUpdateInput',
    properties: [
        new OA\Property(property: 'title', type: 'string', example: 'Buy groceries', maxLength: 255),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'due_date', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'status', type: 'string', nullable: true, enum: ['done', 'not_done']),
        new OA\Property(property: 'priority', type: 'string', nullable: true, enum: ['low', 'medium', 'high']),
        new OA\Property(property: 'category', type: 'string', nullable: true, enum: ['work', 'home', 'personal', 'other']),
    ],
    type: 'object'
)]
class UpdateTaskRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'due_date' => ['sometimes', 'nullable', 'date'],
            'status' => ['sometimes', Rule::enum(TaskStatus::class)],
            'priority' => ['sometimes', Rule::enum(TaskPriority::class)],
            'category' => ['sometimes', Rule::enum(TaskCategory::class)],
        ];
    }
}
