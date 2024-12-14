<?php
namespace App\Services;

use Gemini\Laravel\Facades\Gemini;
use App\Repositories\Message\MessageRepositoryInterface;
use App\Repositories\Conversation\ConversationRepositoryInterface;


class GeminiAlService {
    private $gemini;
    private $conversationRepository;
    private $messageRepository;
    private $keywords = [
        'lập trình', 'iphone', 'code', 'phần mềm', 'phần cứng', 'công nghệ', 
        'web', 'ứng dụng', 'developer', 'programming', 'API', 
        'framework', 'database', 'javascript', 'PHP', 'Python', 
        'Java', 'HTML', 'CSS', 'network', 'machine learning', 
        'AI', 'algorithm', 'cloud', 'devops', 'docker', 'kubernetes',
        'linux', 'windows', 'macOS', 'mobile', 'android', 'ios', 
        'react', 'vue', 'angular', 'nodejs', 'typescript', 'ruby', 
        'rails', 'laravel', 'django', 'flask', 'swift', 'objective-c',
        'C', 'C++', 'C#', 'golang', 'Rust', 'perl', 'bash', 
        'shell script', 'command line', 'git', 'version control',
        'software engineering', 'data science', 'big data', 'data mining',
        'blockchain', 'cryptocurrency', 'bitcoin', 'ethereum', 'security', 
        'encryption', 'cybersecurity', 'penetration testing', 'ethical hacking',
        'web development', 'frontend', 'backend', 'fullstack', 'REST API',
        'GraphQL', 'microservices', 'serverless', 'cloud computing',
        'AWS', 'Azure', 'Google Cloud', 'CI/CD', 'continuous integration', 
        'continuous delivery', 'testing', 'unit testing', 'integration testing',
        'UI/UX', 'user interface', 'user experience', 'design patterns',
        'software architecture', 'refactoring', 'optimization', 'scalability',
        'performance tuning', 'monitoring', 'logging', 'error handling',
        'debugging', 'troubleshooting', 'agile', 'scrum', 'kanban', 
        'project management', 'product management', 'versioning', 
        'release management', 'automation', 'scripting', 'hardware',
        'IoT', 'Internet of Things', 'robotics', 'AI', 'deep learning',
        'neural networks', 'NLP', 'natural language processing', 
        'computer vision', 'image processing', 'signal processing',
        'virtual reality', 'augmented reality', 'game development',
        '3D modeling', 'animation', 'rendering', 'graphics', 
        'GPU', 'CPU', 'motherboard', 'RAM', 'SSD', 'HDD',
        'power supply', 'cooling system', 'networking', 'firewall',
        'VPN', 'proxy', 'load balancer', 'router', 'switch',
        'DNS', 'DHCP', 'HTTP', 'HTTPS', 'FTP', 'SMTP', 
        'POP3', 'IMAP', 'SSL', 'TLS', 'encryption', 
        'cybersecurity', 'authentication', 'authorization', 
        'OAuth', 'JWT', 'SSL', 'TLS', 'XSS', 'SQL Injection',
        'CSRF', 'data encryption', 'privacy', 'GDPR', 
        'compliance', 'cloud storage', 'databases', 'NoSQL',
        'SQL', 'PostgreSQL', 'MySQL', 'MariaDB', 'SQLite',
        'MongoDB', 'CouchDB', 'Cassandra', 'Redis', 'Memcached',
        'Elasticsearch', 'Kibana', 'Logstash', 'Hadoop', 'Spark',
        'Kafka', 'RabbitMQ', 'ActiveMQ', 'Zookeeper', 'HDFS',
        'MapReduce', 'data warehouse', 'ETL', 'data lakes',
        'AI ethics', 'bioinformatics', 'quantum computing',
        'augmented reality', 'virtual reality', 'smart contracts',
        'dapps', 'decentralized finance', 'FinTech', 'InsurTech',
        'MedTech', 'HealthTech', 'RegTech', 'AdTech', 'EdTech',
        'AgriTech', 'PropTech', 'GreenTech', 'CleanTech',
        '5G', 'edge computing', 'serverless architecture', 'content delivery network',
        'CDN', 'web scraping', 'web crawling', 'data engineering', 
        'data visualization', 'predictive analytics', 'sentiment analysis',
        'chatbots', 'virtual assistants', 'RPA', 'robotic process automation',
        'metadata', 'data governance', 'data quality', 'data integration',
        'data lakes', 'data marts', 'data pipeline', 'data modeling',
        'data science lifecycle', 'ETL pipelines', 'MLops', 'data ethics',
        'computational science', 'scientific computing', 'numerical methods',
        'high-performance computing', 'distributed computing', 'cloud-native',
        'container orchestration', 'containerization', 'infrastructure as code',
        'Terraform', 'Ansible', 'Puppet', 'Chef', 'K8s', 'Kubernetes',
        'docker-compose', 'CI/CD pipelines', 'build automation', 'deployment automation',
        'security operations', 'incident response', 'risk management', 'vulnerability management',
        'penetration testing', 'ethical hacking', 'red teaming', 'blue teaming',
        'cyber threat intelligence', 'threat hunting', 'digital forensics',
        'privacy by design', 'privacy engineering', 'GDPR compliance', 'data subject rights',
        'digital transformation', 'technology adoption', 'enterprise architecture',
        'IT governance', 'ITIL', 'IT service management', 'business continuity',
        'disaster recovery', 'IT asset management', 'endpoint security',
        'network security', 'application security', 'cloud security', 'identity management',
        'access control', 'IAM', 'identity and access management', 'single sign-on',
        'multi-factor authentication', 'biometrics', 'behavioral biometrics', 
        'security auditing', 'security policy', 'security framework', 'security compliance'
    ];
    
    
    public function __construct(Gemini $gemini, ConversationRepositoryInterface $conversationRepository, MessageRepositoryInterface $messageRepository) {
        $this->gemini = $gemini;
        $this->conversationRepository = $conversationRepository;
        $this->messageRepository = $messageRepository;
    }

    public function generateText($prompt, $id = null) {
        try {
            $conversation = '';
            $keywordsList = implode(', ', $this->keywords);
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
                'text' => $resData
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