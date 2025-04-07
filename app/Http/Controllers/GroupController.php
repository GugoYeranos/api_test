<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupCreateRequest;
use App\Http\Requests\GroupUpdateRequest;
use App\Http\Resources\CreateGroupResource;
use App\Http\Resources\SingleStudentGroupResource;
use App\Http\Services\LectureService;
use App\Http\Services\PlanService;
use App\Http\Services\GroupService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ServerResponse\ServerResponse;
use Illuminate\Http\JsonResponse;

class GroupController extends Controller
{
    private $groupService;
    private $lectureService;
    private $planService;

    /**
     * ClassController constructor.
     * @param GroupService $groupService
     * @param LectureService $lectureService
     * @param PlanService $planService
     */
    public function __construct(GroupService $groupService, LectureService $lectureService, PlanService $planService)
    {
        $this->groupService = $groupService;
        $this->lectureService = $lectureService;
        $this->planService = $planService;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $result = ServerResponse::RESPONSE_200;
        try {
            $result['data'] = $this->groupService->index();
        } catch (ModelNotFoundException $e) {
            $result = ServerResponse::RESPONSE_404;
        } catch (\Exception $e) {
            $result = ServerResponse::RESPONSE_500;
        }

        return response()->json($result, $result['status']);
    }

    /**
     * @param GroupCreateRequest $request
     * @return JsonResponse
     */
    public function store(GroupCreateRequest $request): JsonResponse
    {
        $result = ServerResponse::RESPONSE_200;
        try {
            $result['data'] = new CreateGroupResource($this->groupService->createGroup($request->validated()));
        } catch (ModelNotFoundException $e) {
            $result = ServerResponse::RESPONSE_404;
        } catch (\Exception $e) {
            $result = ServerResponse::RESPONSE_500;
        }

        return response()->json($result, $result['status']);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $result = ServerResponse::RESPONSE_200;
        try {
            $result['data'] = new SingleStudentGroupResource($this->groupService->show($id));
        } catch (ModelNotFoundException $e) {
            $result = ServerResponse::RESPONSE_404;
        } catch (\Exception $e) {
            $result = ServerResponse::RESPONSE_500;
        }

        return response()->json($result, $result['status']);
    }

    /**
     * @param GroupUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(GroupUpdateRequest $request, int $id): JsonResponse
    {
        $result = ServerResponse::RESPONSE_200;
        try {
            $this->groupService->updateClass($request->validated(), $id);
        } catch (ModelNotFoundException $e) {
            $result = ServerResponse::RESPONSE_404;
        } catch (\Exception $e) {
            $result = ServerResponse::RESPONSE_500;
        }

        return response()->json($result, $result['status']);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $result = ServerResponse::RESPONSE_200;
        try {
            $this->groupService->deleteClass($id);
        } catch (ModelNotFoundException $e) {
            $result = ServerResponse::RESPONSE_404;
        } catch (\Exception $e) {
            $result = ServerResponse::RESPONSE_500;
        }

        return response()->json($result, $result['status']);
    }
}
