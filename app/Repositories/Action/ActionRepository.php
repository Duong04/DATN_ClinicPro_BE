<?php
namespace App\Repositories\Action;

use App\Repositories\Action\ActionRepositoryInterface;
use App\Models\Action;

class ActionRepository implements ActionRepositoryInterface {
    public function all() {
        return Action::with('permissionActions')->get();
    }
    public function paginate($limit, $q) {
        $actions = Action::with('permissionActions');
        if ($q !== null) {
            $actions->where(function ($query) use ($q) {
                $query->where('value', 'like', "%{$q}%")
                ->orWhere('name', 'like', "%{$q}%");
            });
        }

        return $limit ? $actions->paginate($limit) : $actions->get();
    }
    public function find($id) {
        return Action::with('permissionActions')->find($id);
    }
    public function create(array $data) {
        return Action::create($data);
    }
    public function update($id, array $data) {
        $action = Action::find($id);
        return $action->update($data);
    }
    public function delete($id) {
        $action = Action::find($id);
        return $action->delete();
    }
}