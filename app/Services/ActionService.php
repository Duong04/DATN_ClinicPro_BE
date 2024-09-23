<?php
namespace App\Services;

use App\Repositories\Action\ActionRepositoryInterface;
use App\Repositories\PermissionAction\PermissionActionRepositoryInterface;

class ActionService {
    private $actionRepository;
    private $permissionActionRepository;
    public function __construct(ActionRepositoryInterface $actionRepository, PermissionActionRepositoryInterface $permissionActionRepository) {
        $this->actionRepository = $actionRepository;
        $this->permissionActionRepository = $permissionActionRepository;
    }

    public function getAll() {
        try {
            $actions = $this->actionRepository->all();

            return response()->json(['data' => $actions], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function getPaginate($request) {
        try {
            $limit = $request->query('limit');
            $q = $request->query('q');
            $actions = $this->actionRepository->paginate($limit, $q);
    
            if ($limit) {
                return response()->json([
                    'data' => $actions->items(),
                    'prev_page_url' => $actions->previousPageUrl(),
                    'next_page_url' => $actions->nextPageUrl(),
                    'total' => $actions->total()
                ], 200);
            }
    
            return response()->json(['data' => $actions], 200);
    
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }
    

    public function findById($id) {
        try {
            $action = $this->actionRepository->find($id);
            if (empty($action)) {
                return response()->json(['error' => 'Action not found!'], 404);
            }

            return response()->json(['data' => $action], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function create($request) {
        try {
            $data = $request->validated();
            $action = $this->actionRepository->create($data);
            if (isset($data['permissions'])) {
                foreach ($data['permissions'] as $item) {
                    $this->permissionActionRepository->create([
                        'action_id' => $action->id,
                        'permission_id' => $item['permission_id'],
                    ]);
                }
            }

            return response()->json(['message' => 'Created action successfully!', 'data' => $action], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 422);
        }
    }

    public function update($request, $id) {
        try {
            $data = $request->validated();
            $action = $this->actionRepository->update($id, $data);
            if (isset($data['permissions'])) {
                $this->permissionActionRepository->delete('action_id', $id);
                foreach ($data['permissions'] as $item) {
                    $this->permissionActionRepository->create([
                        'action_id' => $id,
                        'permission_id' => $item['permission_id'],
                    ]);
                }
            }

            return response()->json(['message' => 'Updated action successfully!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 422);
        }
    }

    public function delete($id) {
        try {

            $this->actionRepository->delete($id);

            return response()->json(['message' => 'Deleted action successfully!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Action not found!'], 404);
        }
    }
}