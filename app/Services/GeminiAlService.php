<?php
namespace App\Services;

use Gemini\Laravel\Facades\Gemini;
use App\Repositories\Message\MessageRepositoryInterface;
use App\Repositories\Conversation\ConversationRepositoryInterface;
use App\Repositories\Service\ServiceRepositoryInterface;

class GeminiAlService {
    private $gemini;
    private $conversationRepository;
    private $messageRepository;
    private $serviceRepository;
    
    public function __construct(Gemini $gemini, ConversationRepositoryInterface $conversationRepository, MessageRepositoryInterface $messageRepository, ServiceRepositoryInterface $serviceRepository) {
        $this->gemini = $gemini;
        $this->conversationRepository = $conversationRepository;
        $this->messageRepository = $messageRepository;
        $this->serviceRepository = $serviceRepository;
    }

    public function generateText($prompt, $id = null) {
        try {
            $conversationText = '';
            $user_id = auth()->check() ? auth()->user()->id : null;
    
            if ($id && $user_id) {
                $previousMessages = $this->conversationRepository->find($id, $user_id);
    
                foreach ($previousMessages->messages as $message) {
                    $sender = $message->sender_type === 'user' ? 'User' : 'AI';
                    $conversationText .= "{$sender}: {$message->message_text}\n";
                }
            }
    
            $text = $conversationText . "\nUser: " . $prompt;
    
            if ($prompt === 'Tra cứu thông tin dịch vụ tại ClinicPro') {
                $services = $this->serviceRepository->all();
                $instruction = "Hãy trả lời các thông tin dịch vụ bao gồm service_name và price có ở ClinicPro dựa trên thông tin này" . json_encode($services) . 'Theo dạng text không phải table';
                $text = $instruction;
            } else {
                $instruction = "Tên của bạn là AI ClinicPro. Bạn là một AI hỗ trợ quản lý phòng khám, chuyên cung cấp thông tin về sức khỏe, bệnh tật và các dịch vụ y tế. Bạn chỉ trả lời các câu hỏi liên quan đến sức khỏe, bệnh lý, điều trị, thuốc, chế độ ăn uống, chăm sóc sức khỏe và các vấn đề liên quan đến y tế. Nếu câu hỏi không liên quan đến các lĩnh vực này, bạn hãy từ chối trả lời một cách lịch sự và hợp lý.";
                $text = $instruction . "\n\n" . $prompt;
            }
    
            $response = $this->gemini::geminiPro()->generateContent($text);
            $resData = $response->text();
    
            if ($user_id) {
                if (!$id) {
                    $conversation = $this->conversationRepository->create([
                        'title' => $prompt,
                        'user_id' => $user_id,
                    ]);
        
                    $id = $conversation->id;
                }
        
                $this->handleData($id, $prompt, $resData);
            }
    
            return [
                'id' => $id,
                'message_text' => $resData,
                'sender_type' => 'AI',
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    private function handleData($id, $prompt, $resData) {
        $user_id = auth()->check() ? auth()->user()->id : null;
    
        $this->messageRepository->create([
            'conversation_id' => $id,
            'user_id' => $user_id,
            'sender_type' => 'user',
            'message_text' => $prompt,
        ]);
    
        $this->messageRepository->create([
            'conversation_id' => $id,
            'user_id' => null, // Null vì là AI
            'sender_type' => 'ai',
            'message_text' => $resData,
        ]);
    }
    

}