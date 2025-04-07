<?php

namespace App\Http\Services;

use App\Models\Plan;

class PlanService extends BaseService
{
    /**
     * @return string
     */
    protected function model(): string
    {
        return 'App\Models\Plan';
    }

    /**
     * @param array $request
     * @return Plan
     */
    public function createPlan(array $request): Plan
    {
        $plan = $this->create($request, ['name']);
        $lecture_id = $this->createRelationArray($request);
        $plan->lectures()->sync($lecture_id);
        return $plan;
    }

    /**
     * @param array $request
     * @param int $id
     */
    public function updateClassPlanLectures(array $request, int $id): void
    {
        $plan = $this->query->whereHas('planClasses', function ($query) use ($id) {
            $query->where('id', $id);
        })->firstOrFail();
        $plan->update(['name' => $request['name']]);
        $lecture_id = $this->createRelationArray($request);
        $plan->lectures()->sync($lecture_id);
    }

    /**
     * @param array $request
     * @return array
     */
    public function createRelationArray(array $request): array
    {
        $lecture_id = [];
        foreach ($request['lecture_list'] as $requisits) {
            $id = (int)$requisits['lecture_id'];
            $order = $requisits['lecture_order'];
            $lecture_id[$id] = ['lecture_order' => $order];
        }

        return $lecture_id;
    }

    /**
     * @param array $request
     * @return Plan
     * @throws \ErrorException
     */
    public function findPlanByGroup(array $request): Plan
    {
        $plan = $this->query->whereHas('planClasses', function ($query) use ($request) {
            $query->where('id', $request['group_id']);
        })->first();

        if (is_null($plan)) {
            return $this->createPlan($request);
        } else {
            throw new \ErrorException();
        }
    }
}
