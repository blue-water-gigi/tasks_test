<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Collection;

readonly class TaskRepository
{
    public function __construct(private Task $task)
    {
    }

    /**
     * Get list of tasks. Can be sorted by due_date or created_at
     *
     * @return Collection<int,Task>
     */
    public function getTasks(
        ?string $search = null,
        string $sort = 'created_at',
        ?int $perPage = null,
        int $page = 1
    ): Collection
    {
        $allowedSorts = ['created_at', 'due_date'];

        //default
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        $query = $this->task->newQuery();

        if ($search !== null) {
            $query->where('title', 'LIKE', '%' . trim($search) . '%');
        }

        if ($perPage !== null) {
            $query->limit($perPage)->offset(($page - 1) * $perPage);
        }

        return $query->orderBy($sort)->orderBy('id')->get();
    }

    public function storeTask(array $data): Task
    {
        return $this->task->query()->create($data);
    }

    public function getById(int $id): Task
    {
        return $this->task->query()->findOrFail($id);
    }

    public function deleteById(int $id): void
    {
        $this->getById($id)->delete();
    }

    public function updateTaskById(int $taskId, array $data): Task
    {
        $task = $this->getById($taskId);
        $task->update($data);

        return $task;
    }
}
