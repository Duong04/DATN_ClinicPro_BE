<?php
namespace App\Services;

use App\Repositories\Conversation\ConversationRepositoryInterface;
class ConversationService {
    private $conversationRepository;

    public function __construct(ConversationRepositoryInterface $conversationRepository) {
        $this->conversationRepository = $conversationRepository;
    }

    public function getPaginate($request) {
        try {
            $limit = $request->query('limit');
            $conversations = $this->conversationRepository->paginate($limit);
            if ($limit) {
                return [
                    'data' => $conversations->items(),
                    'prev_page_url' => $conversations->previousPageUrl(),
                    'next_page_url' => $conversations->nextPageUrl(),
                    'total' => $conversations->total()
                ];
            }else {
                return ['data' => $conversations];
            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findByUserId($userId) {
        try {
            return $this->conversationRepository->findUserId($userId);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findById($id, $userId) {
        try {
            $conversation = $this->conversationRepository->find($id, $userId);
            return $conversation;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete($id) {
        try {
            return $this->conversationRepository->delete($id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}