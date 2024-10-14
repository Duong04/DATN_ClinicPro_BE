<?php

namespace App\Repositories\Feedback;

use App\Models\Feedback;

class FeedbackRepository implements FeedbackRepositoryInterface
{
    private $feedback;
    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }
    public function all()
    {
        return $this->feedback::all();
    }
    public function find($id)
    {
        return $this->feedback::findOrFail($id);
    }

    public function findByIdPackage($id)
    {
        return $this->feedback::where('package_id', $id)->get();
    }
    public function create($data)
    {
        return $this->feedback::create($data);
    }
    public function update($id, $data) {}
    public function destroy($id)
    {
        $feedback = $this->feedback::findOrFail($id);
        return $feedback->delete();
    }
}
