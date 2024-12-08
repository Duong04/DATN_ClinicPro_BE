<?php
namespace App\Repositories\Message;

use App\Repositories\Message\MessageRepositoryInterface;
use App\Models\Message;

class MessageRepository implements MessageRepositoryInterface {
    public function all() {
        return Message::all();
    }
    public function find($id) {
        return Message::find($id);
    }
    public function paginate($limit) {
        return $limit ? Message::paginate($limit) : Message::all();
    }
    public function create(array $data) {
        return Message::create($data);
    }
    public function update($id, array $data) {
        $message = Message::find($id);
        return $message->update($data);
    }
    public function delete($id) {
        $message = Message::find($id);
        return $message->delete();
    }
}