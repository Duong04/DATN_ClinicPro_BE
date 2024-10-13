<?php

namespace App\Http\Controllers\Apis\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeedbackRequest;
use App\Services\FeedbackService;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    private $feedbackService;
    public function __construct(FeedbackService $feedbackService)
    {
        $this->feedbackService = $feedbackService;
    }
    public function index()
    {
        return $this->feedbackService->all();
    }
    public function show($id)
    {
        return $this->feedbackService->find($id);
    }
    public function findByIdPackage($id)
    {
        return $this->feedbackService->findByPackageId($id);
    }
    public function store(FeedbackRequest $request)
    {
        return $this->feedbackService->create($request);
    }
    public function destroy($id)
    {
        return $this->feedbackService->delete($id);
    }
}
