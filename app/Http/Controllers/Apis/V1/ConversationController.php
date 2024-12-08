<?php

namespace App\Http\Controllers\Apis\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ConversationService;

class ConversationController extends Controller
{
    private $conversationService;
    public function __construct(ConversationService $conversationService) {
        $this->conversationService = $conversationService;
    }

    public function paginate(Request $request) {
        try {
            $conversations = $this->conversationService->getPaginate($request);
            return response()->json($conversations, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function getByUserId($userId, Request $request) {
        try {
            $limit = $request->query('limit', null);
            $conversations = $this->conversationService->findByUserId($userId, $limit);
            if ($limit) {
                return response()->json([
                    'success' => true,
                    'data' => $conversations->items(),
                    'prev_page_url' => $conversations->previousPageUrl(),
                    'next_page_url' => $conversations->nextPageUrl(),
                    'total' => $conversations->total()
                ], 200);
            }

            return response()->json(['success' => true, 'data' => $conversations], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 400);
        }
    }

    public function show($id, $user_id) {
        try {
            $conversation = $this->conversationService->findById($id, $user_id);
            if (empty($conversation)) {
                return response()->json(['error' => 'Conversation not found!'], 404);
            }
            return response()->json(['success' => true, 'data' => $conversation], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 400);
        }
    }

    public function delete($id) {
        try {
            $this->conversationService->delete($id);
            return response()->json(['success' => true, 'message' => 'Deleted Conversation successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 400);
        }
    }
}
