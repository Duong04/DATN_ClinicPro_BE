<?php

namespace App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Feedback\FeedbackRepositoryInterface;
use App\Repositories\Package\PackageRepositoryInterface;
use Exception;

class FeedbackService
{
    private $feedbackRepository;
    private $packageRepository;
    public function __construct(FeedbackRepositoryInterface $feedbackRepository, PackageRepositoryInterface $packageRepository)
    {
        $this->feedbackRepository = $feedbackRepository;
        $this->packageRepository = $packageRepository;
    }

    public function all()
    {
        $data = $this->feedbackRepository->all();
        return response()->json(['data' => $data], 200);
    }

    public function find($id)
    {
        try {
            $result = $this->feedbackRepository->find($id);
            return $this->responseWithData(fn() => $result);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Đánh giá không tồn tại'], 404);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function findByPackageId($id)
    {
        try {
            $this->findPackage($id);
            $result = $this->feedbackRepository->findByIdPackage($id);
            return $this->responseWithData(fn() => $result);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 404);
        }
    }
    public function create($request)
    {
        try {
            $data = $request->validated();
            $result = $this->feedbackRepository->create($data);
            return $this->responseWithData(fn() => $result, 'Đánh giá thành công', 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }
    public function delete($id)
    {
        try {
            $this->feedbackRepository->destroy($id);
            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Đánh giá không tồn tại!'], 404);
        }
    }

    private function findPackage($id)
    {
        try {
            $this->packageRepository->show($id);
        } catch (ModelNotFoundException $th) {
            throw new Exception('Gói khám không tồn tại!');
        }
    }

    private function responseWithData(callable $callback, $message = '', $status = 200)
    {
        return response()->json(
            [
                'message' => $message,
                'data' => $callback()
            ],
            $status
        );
    }
}
