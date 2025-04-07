<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupPlanResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id ? $this->id : '',
            'theme' => $this->theme ? $this->theme : '',
            'description' => $this->description ? $this->description : '',
        ];
    }
}
