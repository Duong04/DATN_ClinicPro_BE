<?php
namespace App\Services;

use Gemini\Laravel\Facades\Gemini;
use App\Repositories\Message\MessageRepositoryInterface;
use App\Repositories\Conversation\ConversationRepositoryInterface;


class GeminiAlService {
    private $gemini;
    private $conversationRepository;
    private $messageRepository;
    
    public function __construct(Gemini $gemini, ConversationRepositoryInterface $conversationRepository, MessageRepositoryInterface $messageRepository) {
        $this->gemini = $gemini;
        $this->conversationRepository = $conversationRepository;
        $this->messageRepository = $messageRepository;
    }

    public function generateText($prompt, $id = null) {
        try {
            $conversation = '';
            $instruction = "Tên của bạn là AI ClinicPro. Bạn là một AI hỗ trợ quản lý phòng khám, chuyên cung cấp thông tin về sức khỏe, bệnh tật và các dịch vụ y tế. Bạn chỉ trả lời các câu hỏi liên quan đến sức khỏe, bệnh lý, điều trị, thuốc, chế độ ăn uống, chăm sóc sức khỏe và các vấn đề liên quan đến y tế. Nếu câu hỏi không liên quan đến các lĩnh vực này, bạn hãy từ chối trả lời một cách lịch sự và hợp lý.";
            $text = $instruction . "\n\n" . $prompt;
        
            $response = $this->gemini::geminiPro()->generateContent($text);
            $resData = $response->text();
            if (auth()->check()) {
                $user_id = auth()->check() ? auth()->user()->id : null;
    
                if (!isset($id)) {
                    $conversation = $this->conversationRepository->create([
                        'title' => $prompt,
                        'user_id' => $user_id
                    ]);
    
                    $id = $conversation->id;
    
                    $this->handleData($id, $prompt, $resData);
                }else {
                    $this->handleData($id, $prompt, $resData);
                }
            }

            return [
                'id' => $id,
                'message_text' => $resData,
                'sender_type' => 'AI'
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function handleData($id, $prompt, $resData) {
        $this->messageRepository->create([
            'conversation_id' => $id,
            'sender_type' => 'USER',
            'message_text' => $prompt
        ]);

        $this->messageRepository->create([
            'conversation_id' => $id,
            'sender_type' => 'AI',
            'message_text' => $resData
        ]);
    }

}