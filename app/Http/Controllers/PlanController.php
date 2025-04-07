<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGroupPlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Http\Resources\CreatePlanResource;
use App\Http\Resources\GroupPlanResource;
use App\Http\Services\GroupService;
use App\Http\Services\LectureService;
use App\Http\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\ServerResponse\ServerResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PlanController extends Controller
{
    private $groupService;
    private $lectureService;
    private $planService;

    public function __construct(GroupService $groupService, LectureService $lectureService, PlanService $planService)
    {
        $this->lectureService = $lectureService;
        $this->planService = $planService;
        $this->groupService = $groupService;
    }

    /**
     * @param CreateGroupPlanRequest $request
     * @return JsonResponse
     */
    public function store(CreateGroupPlanRequest $request): JsonResponse
    {
        $result = ServerResponse::RESPONSE_200;
        DB::beginTransaction();
        try {
            $group_id = $request->validated()['group_id'];
            $plan = $this->planService->findPlanByGroup($request->validated());
            $group = $this->groupService->createGroupPlan($plan, $group_id);
            $lectures = $this->lectureService->getGroupLectures($group_id);
            $result['data'] = (new CreatePlanResource($plan))->lectureClassStudent($group, $lectures);
            DB::commit();
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            $result = ServerResponse::RESPONSE_404;
        } catch (\Exception $e) {
            DB::rollback();
            $result = ServerResponse::RESPONSE_500;
        }

        return response()->json($result, $result['status']);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $result = ServerResponse::RESPONSE_200;
        try {
            $result['data'] = GroupPlanResource::collection($this->lectureService->getGroupLectures($id));
        } catch (ModelNotFoundException $e) {
            $result = ServerResponse::RESPONSE_404;
        } catch (\Exception $e) {
            $result = ServerResponse::RESPONSE_500;
        }

        return response()->json($result, $result['status']);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdatePlanRequest $request, $id): JsonResponse
    {
        $result = ServerResponse::RESPONSE_200;
        DB::beginTransaction();
        try {
            $this->planService->updateClassPlanLectures($request->validated(), $id);
            DB::commit();
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            $result = ServerResponse::RESPONSE_404;
        } catch (\Exception $e) {
            DB::rollback();
            $result = ServerResponse::RESPONSE_500;
        }

        return response()->json($result, $result['status']);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = ServerResponse::RESPONSE_200;
        try {
            $this->groupService->deleteClassPlan($id);
        } catch (ModelNotFoundException $e) {
            $result = ServerResponse::RESPONSE_404;
        } catch (\Exception $e) {
            $result = ServerResponse::RESPONSE_500;
        }

        return response()->json($result, $result['status']);
    }
}
