<?php

namespace App\Http\Services;

use App\Models\Lecture;
use Illuminate\Database\Eloquent\Collection;

class LectureService extends BaseService
{
    /**
     * @return string
     */
    protected function model(): string
    {
        return 'App\Models\Lecture';
    }

    /**
     * @param int $classId
     * @return Collection
     */
    public function getGroupLectures(int $classId): Collection
    {
        $lectures = $this->query->select('lectures.id', 'theme', 'description')
            ->join('lecture_plan', 'lecture_id', 'lectures.id')
            ->join('plans', 'plans.id', 'plan_id')
            ->join('groups', 'groups.plan_id', 'plans.id')
            ->orderBy('lecture_plan.lecture_order')->where('groups.id', $classId)
            ->get();

        return $lectures;
    }

    /**
     * @return Collection
     */
    public function index(): Collection
    {
        $lectures = $this->query->get(['id', 'theme', 'description']);
        return $lectures;
    }

    /**
     * @param int $id
     * @return Lecture
     */
    public function show(int $id): Lecture
    {
        $lecture = $this->query->select('id', 'theme', 'description')->findOrFail($id);
        return $lecture;
    }

    /**
     * @param array $request
     * @return Lecture
     */
    public function store(array $request): Lecture
    {
        return $this->create($request, ['theme', 'description']);
    }

    /**
     * @param array $request
     * @param int $id
     */
    public function updateLecture(array $request, int $id): void
    {
        $this->update($request, $id, ['theme', 'description']);
    }

    /**
     * @param int $id
     * @throws \Exception
     */
    public function deleteLecture(int $id): void
    {
        $this->delete($id);
    }

}
