<?php

namespace App\Http\Resources;

use App\Enums\TaskPriority;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property TaskPriority $priority
 * @property bool $is_completed
 * @property int $user_id
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Task> $children
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Label> $labels
 */
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * @throws \Exception
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'priority'    => $this->priority->label(),
            'is_completed'=> $this->is_completed,
            'labels'      => LabelResource::collection($this->whenLoaded('labels')),
            'children' => TaskResource::collection($this->whenLoaded('children')),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'created_at'  => $this->created_at->toDateTimeString(),
        ];
    }
}
