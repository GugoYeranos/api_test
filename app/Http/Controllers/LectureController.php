<?php

namespace App\Http\Controllers;

use App\Http\Requests\LectureRequest;
use App\Http\Resources\CreateLectureResource;
use App\Http\Resources\SingleLectureResource;
use App\Http\Resources\LectureResource;
use App\Http\Services\LectureService;
use App\Http\Services\GroupService;
use App\Http\Services\StudentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use App\Http\ServerResponse\ServerResponse;

class LectureController extends Controller
{
    private $lectureService;
    private $studentService;
    private $groupService;

    public function __construct(LectureService $lectureService, StudentService $studentService, GroupService $groupService)
    {
        $this->lectureService = $lectureService;
        $this->studentService = $studentService;
        $this->groupService = $groupService;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $result = ServerResponse::RESPONSE_200;
        try {
            $result['data'] = LectureResource::collection($this->lectureService->index());
        } catch (ModelNotFoundException $e) {
            $result = ServerResponse::RESPONSE_404;
        } catch (\Exception $e) {
            $result = ServerResponse::RESPONSE_500;
        }

        return response()->json($result, $result['status']);
    }

    /**
     * @param LectureRequest $request
     * @return JsonResponse
     */
    public function store(LectureRequest $request): JsonResponse
    {
        $result = ServerResponse::RESPONSE_201;
        try {
            $lecture = $this->lectureService->store($request->validated());
            $result['data'] = (new CreateLectureResource($lecture));
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
            $lecture = $this->lectureService->show($id);
            $groups = $this->groupService->getClassesByLecture($id);
            $students = $this->studentService->getStudentsByLecture($id);
            $result['data'] = (new SingleLectureResource($lecture))->lectureClassStudent($groups, $students);
        } catch (ModelNotFoundException $e) {
            $result = ServerResponse::RESPONSE_404;
        } catch (\Exception $e) {
            $result = ServerResponse::RESPONSE_500;
        }

        return response()->json($result, $result['status']);
    }

    /**
     * @param LectureRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(LectureRequest $request, int $id): JsonResponse
    {
        $result = ServerResponse::RESPONSE_200;
        try {
            $this->lectureService->updateLecture($request->validated(), $id);
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
            $this->lectureService->deleteLecture($id);
        } catch (ModelNotFoundException $e) {
            $result = ServerResponse::RESPONSE_404;
        } catch (\Exception $e) {
            $result = ServerResponse::RESPONSE_500;
        }

        return response()->json($result, $result['status']);
    }
}
