<?php

namespace App\Models;

use App\Enums\TaskPriority;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\EnumeratesValues;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    public static function withRecursiveExpression(): Collection
    {
        $userId = auth()->id();

        $rows = \DB::select("
            WITH RECURSIVE task_tree AS (
                SELECT id, title, description, priority, is_completed, parent_id, user_id, created_at, updated_at
                FROM tasks
                WHERE parent_id IS NULL AND user_id = ?

                UNION ALL

                SELECT t.id, t.title, t.description, t.priority, t.is_completed, t.parent_id, t.user_id, t.created_at, t.updated_at
                FROM tasks t
                INNER JOIN task_tree tt ON t.parent_id = tt.id
                WHERE t.user_id = ?
            )
            SELECT * FROM task_tree
        ", [$userId, $userId]);

        $tasks = collect($rows)
            ->map(fn ($row) => new self((array) $row));

        return self::toTree($tasks);
    }

    private static function toTree(Collection $flatTasks): Collection
    {
        $map = [];
        $tree = [];

        foreach ($flatTasks as $item) {
            $item->setAttribute('children', []);
            $map[$item->id] = $item;
        }

        foreach ($map as $item) {
            if ($item->parent_id && isset($map[$item->parent_id])) {
                $parent = $map[$item->parent_id];
                $children = $parent->getAttribute('children');
                $children[] = $item;
                $parent->setAttribute('children', $children);
            } else {
                $tree[] = $item;
            }
        }

        return collect($tree);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class);
    }

    protected function casts(): array
    {
        return [
            'priority' => TaskPriority::class,
        ];
    }
}
