<?php

namespace App\Repositories\Feedback;

interface FeedbackRepositoryInterface
{
    public function all();
    public function find($id);
    public function findByIdPackage($id);
    public function create($data);
    public function update($id, $data);
    public function destroy($id);
}
