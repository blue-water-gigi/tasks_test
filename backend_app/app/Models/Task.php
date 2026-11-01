<?php

namespace App\Models;

use App\Enums\TaskCategory;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Task',
    required: ['id', 'title', 'status', 'priority', 'category', 'created_at', 'updated_at'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Buy groceries'),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'due_date', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'status', type: 'string', enum: ['done', 'not_done']),
        new OA\Property(property: 'priority', type: 'string', enum: ['low', 'medium', 'high']),
        new OA\Property(property: 'category', type: 'string', enum: ['work', 'home', 'personal', 'other']),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ],
    type: 'object'
)]
class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'status',
        'priority',
        'category'
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
            'status' => TaskStatus::class,
            'priority' => TaskPriority::class,
            'category' => TaskCategory::class,
        ];
    }
}
