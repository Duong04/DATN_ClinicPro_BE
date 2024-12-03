<?php

namespace App\Http\Controllers\Apis\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GeminiAlService;
use App\Http\Requests\ChatAIRequest;

class ChatAIController extends Controller
{
    private $geminiService;

    public function __construct(GeminiAlService $geminiAlService) {
        $this->geminiService = $geminiAlService;
    }

    public function chat(ChatAIRequest $request) {
        try {
            $data = $request->validated();

            $id = $data['id'] ?? null;
            $prompt = $data['prompt'];

            $resData = $this->geminiService->generateText($prompt, $id);
            return response()->json(['success' => true, 'data' => $resData], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 422);
        }
    }

}
