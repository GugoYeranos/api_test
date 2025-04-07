<?php

namespace App\Http\Resources;

use App\Models\Group;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;

class CreatePlanResource extends JsonResource
{

    protected $groups;
    protected $lectures;

    /**
     * @param Group $groups
     * @param Collection $lectures
     * @return Collection
     */
    public function lectureClassStudent(Group $groups, Collection $lectures): CreatePlanResource
    {
        $this->groups = $groups;
        $this->lectures = $lectures;
        return $this;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id ? $this->id : '',
            'name' => $this->name ? $this->name : '',
            'group_id' => $this->groups->id,
            'group_name' => $this->groups->name,
            'lectures_list' => $this->lectures
        ];
    }
}
