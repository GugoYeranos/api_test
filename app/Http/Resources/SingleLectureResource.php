<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;

class SingleLectureResource extends JsonResource
{

    protected $groups;
    protected $students;

    /**
     * @param Collection $groups
     * @param Collection $students
     * @return Collection
     */
    public function lectureClassStudent(Collection $groups, Collection $students): SingleLectureResource
    {
        $this->groups = $groups;
        $this->students = $students;
        return $this;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'lecture_theme' => $this->theme,
            'lecture_description' => $this->description,
            'class_list' => $this->groups,
            'students_list' => $this->students
        ];
    }

    /**
     * @param mixed $resource
     * @return AnonymousResourceCollection
     */
    public static function collection($resource): AnonymousResourceCollection
    {
        return new UserResourceCollection($resource);
    }
}
