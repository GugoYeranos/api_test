<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleStudentResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'student_class' => $this->studentGroup ? $this->studentGroup->name : '',
            'lectures_list' => $this->studentGroup && $this->studentGroup->plan && $this->studentGroup->plan->lectures ? $this->studentGroup->plan->lectures : [],
        ];
    }
}
