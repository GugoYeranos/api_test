<?php

namespace App\Http\Services;

use App\Models\Group;
use Illuminate\Database\Eloquent\Collection;

class GroupService extends BaseService
{
    /**
     * @return string
     */
    protected function model(): string
    {
        return 'App\Models\Group';
    }

    /**
     * @return Collection
     */
    public function index(): Collection
    {
        $student_classes = $this->query->get(['id', 'name']);
        return $student_classes;
    }

    /**
     * @param int $id
     * @return Group
     */
    public function show(int $id): Group
    {
        $student = $this->query->select('id', 'name')->with(['students' => function ($q) {
            $q->select('name', 'email', 'group_id');
        }])->findOrFail($id);
        return $student;
    }

    /**
     * @param $id
     */
    public function getPlan($id): void
    {
        $this->findBy(['plan_id' => $id]);
    }

    /**
     * @param $plan
     * @param int $classId
     * @return Group
     */
    public function createGroupPlan($plan, int $classId): Group
    {
        $class = $this->find($classId);
        $class->plan()->associate($plan->id)->save();
        return $class;
    }

    /**
     * @param array $request
     * @return Group
     */
    public function createGroup(array $request): Group
    {
        return $this->create($request, ['name']);
    }

    /**
     * @param array $request
     * @param int $id
     */
    public function updateClass(array $request, int $id): void
    {
        $this->update($request, $id, ['name']);
    }

    /**
     * @param $classId
     * @throws \Exception
     */
    public function deleteClass($classId): void
    {
        $this->delete($classId);
    }

    /**
     * @param $lectureId
     * @return Collection
     */
    public function getClassesByLecture($lectureId): Collection
    {
        $classes = $this->query->select('id', 'name')->whereHas('plan', function ($q) use ($lectureId) {
            $q->whereHas('lectures', function ($query) use ($lectureId) {
                $query->where('lectures.id', $lectureId);
            });
        })->get();
        return $classes;
    }

    /**
     * @param int $id
     */
    public function deleteClassPlan(int $id): void
    {
        $class = $this->find($id);
        $class->plan()->delete();
    }
}
