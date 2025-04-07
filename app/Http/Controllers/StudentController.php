<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentCreateRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Http\Resources\SingleStudentResource;
use App\Http\Resources\StudentResource;
use App\Http\Services\StudentService;
use App\Models\Student;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use App\Http\ServerResponse\ServerResponse;
use Illuminate\Http\JsonResponse;

class StudentController extends Controller
{
    private $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $result = ServerResponse::RESPONSE_200;
        try {
            $result['data'] = StudentResource::collection($this->studentService->index());
        } catch (ModelNotFoundException $e) {
            $result = ServerResponse::RESPONSE_404;
        } catch (\Exception $e) {
            $result = ServerResponse::RESPONSE_500;
        }

        return response()->json($result, $result['status']);
    }

    /**
     * @param StudentCreateRequest $request
     * @return JsonResponse
     */
    public function store(StudentCreateRequest $request): JsonResponse
    {
        $result = ServerResponse::RESPONSE_200;
        DB::beginTransaction();
        try {
            $result['data'] = new SingleStudentResource($this->studentService->store($request->validated()));
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
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $result = ServerResponse::RESPONSE_200;
        try {
            $result['data'] = new SingleStudentResource($this->studentService->show($id));
        } catch (ModelNotFoundException $e) {
            $result = ServerResponse::RESPONSE_404;
        } catch (\Exception $e) {
            $result = ServerResponse::RESPONSE_500;
        }

        return response()->json($result, $result['status']);
    }

    /**
     * @param StudentUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(StudentUpdateRequest $request, int $id): JsonResponse
    {
        $result = ServerResponse::RESPONSE_200;
        DB::beginTransaction();
        try {
            $this->studentService->updateStudent($request->validated(), $id);
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
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $result = ServerResponse::RESPONSE_200;
        try {
            $this->studentService->deleteStudent($id);
        } catch (ModelNotFoundException $e) {
            $result = ServerResponse::RESPONSE_404;
        } catch (\Exception $e) {
            $result = ServerResponse::RESPONSE_500;
        }

        return response()->json($result, $result['status']);
    }
}
