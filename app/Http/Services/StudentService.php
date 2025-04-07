<?php

namespace App\Http\Services;

use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

class StudentService extends BaseService
{
    /**
     * @return string
     */
    protected function model(): string
    {
        return 'App\Models\Student';
    }

    /**
     * @return Collection
     */
    public function index(): Collection
    {
        $students = $this->query->get(['id', 'name', 'email']);
        return $students;
    }

    /**
     * @param int $id
     * @return Student
     */
    public function show(int $id): Student
    {
        $student = $this->query->select('name', 'email', 'group_id')->with(['studentGroup' => function ($q) {
            $q->select('id', 'name', 'plan_id')->with(['plan' => function ($query) {
                $query->with(['lectures' => function ($quer) {
                    $quer->select('lectures.id as lecture_id', 'lectures.theme as lecture_theme', 'description')->orderBy('lecture_order');
                }]);
            }]);
        }])->findOrFail($id);

        return $student;
    }

    /**
     * @param array $request
     * @return Student
     */
    public function store(array $request): Student
    {
        $student = $this->create($request, ['name', 'email']);
        if (isset($request['group_id'])) {
            $student->studentGroup()->associate($request['group_id'])->save();
        }
        return $student;
    }

    /**
     * @param array $request
     * @param int $id
     */
    public function updateStudent(array $request, int $id): void
    {
        $student = $this->update($request, $id, ['name']);
        if (isset($request['group_id'])) {
            $student->studentGroup()->associate($request['group_id'])->save();
        } else {
            $student->studentGroup()->associate(null)->save();
        }
    }

    /**
     * @param int $id
     * @throws \Exception
     */
    public function deleteStudent(int $id): void
    {
        $this->delete($id);
    }

    /**
     * @param int $lectureId
     * @return Collection
     */
    public function getStudentsByLecture(int $lectureId): Collection
    {
        $students = $this->query->select('id', 'name', 'email')->whereHas('studentGroup', function ($q) use ($lectureId) {
            $q->whereHas('plan', function ($q) use ($lectureId) {
                $q->whereHas('lectures', function ($query) use ($lectureId) {
                    $query->where('lectures.id', $lectureId);
                });
            });
        })->get();
        return $students;
    }

}
