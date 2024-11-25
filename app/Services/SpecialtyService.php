<?php

namespace App\Services;

use App\Repositories\Specialty\SpecialtyRepositoryInterface;

class SpecialtyService
{
    private $specialtyRepository;

    public function __construct(SpecialtyRepositoryInterface $specialtyRepository)
    {
        $this->specialtyRepository = $specialtyRepository;
    }

    public function getPaginate($request)
    {
        try {
            $limit = $request->query('limit');
            $q = $request->query('q');
            $specialties = $this->specialtyRepository->paginate($limit, $q);

            if ($limit) {
                return response()->json([
                    'data' => $specialties->items(),
                    'prev_page_url' => $specialties->previousPageUrl(),
                    'next_page_url' => $specialties->nextPageUrl(),
                    'total' => $specialties->total()
                ], 200);
            }
            return response()->json(['data' => $specialties], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function findById($id)
    {
        try {
            $specialty = $this->specialtyRepository->find($id);
            if (empty($specialty)) {
                return response()->json(['error' => 'Không tìm thấy chuyên khoa!'], 404);
            }

            return  response()->json(['data' => $specialty]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function create($request)
    {
        try {
            $data = $request->validated();
            $specialty = $this->specialtyRepository->create($data);

            return response()->json(['message' => 'Thêm chuyên khoa thành công!', 'data' => $specialty], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 422);
        }
    }

    public function update($request, $id)
    {
        try {
            $data = $request->validated();
            $this->specialtyRepository->update($id, $data);

            return response()->json(['message' => 'Cập nhật thông tin chuyên khoa thành công!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 422);
        }
    }

    public function delete($id)
    {
        try {
            $specialty = $this->specialtyRepository->find($id);
            if ($specialty->doctors_count > 0) {
                return response()->json(['error' => 'Chuyên khoa này đã được gán cho bác sĩ không thể xóa được!'], 400);
            }

            $this->specialtyRepository->delete($id);

            return response()->json(['message' => 'Đã xóa thành công!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'không tìm thấy thông tin chuyên khoa!'], 404);
        }
    }
}
