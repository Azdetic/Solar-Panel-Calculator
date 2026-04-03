<?php
namespace App\Livewire;
use Livewire\Component;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Messages extends Component
{
    public $activeUserId = null;
    public $messageText = '';

    public function selectConversation(int $userId): void
    {
        $this->activeUserId = $userId;
    }

    public function mount()
    {
        $this->activeUserId = request()->query('user');
        if ($this->activeUserId !== null) {
            $this->activeUserId = (int) $this->activeUserId;
        }
    }

    public function sendMessage()
    {
        if (empty(trim($this->messageText)) || !$this->activeUserId) return;

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->activeUserId,
            'message' => $this->messageText
        ]);

        $this->messageText = '';
    }

    public function render()
    {
        $currentUserId = Auth::id();
        
        $participantIds = Message::query()
            ->where('sender_id', $currentUserId)
            ->orWhere('receiver_id', $currentUserId)
            ->get(['sender_id', 'receiver_id'])
            ->map(function ($msg) use ($currentUserId) {
                return $msg->sender_id == $currentUserId ? $msg->receiver_id : $msg->sender_id;
            })
            ->unique()
            ->values();

        $users = User::whereIn('id', $participantIds)->get();

        if ($this->activeUserId && !$users->contains('id', $this->activeUserId)) {
            $manualChatUser = User::find($this->activeUserId);
            if ($manualChatUser) {
                $users->push($manualChatUser);
            }
        }

        if (!$this->activeUserId && $users->isNotEmpty()) {
            $this->activeUserId = (int) $users->first()->id;
        }

        $chats = [];
        if ($this->activeUserId) {
            $targetUserId = $this->activeUserId;
            $chats = Message::where(function ($query) use ($currentUserId, $targetUserId) {
                $query->where('sender_id', $currentUserId)->where('receiver_id', $targetUserId);
            })->orWhere(function ($query) use ($currentUserId, $targetUserId) {
                $query->where('sender_id', $targetUserId)->where('receiver_id', $currentUserId);
            })->orderBy('created_at', 'asc')->get();

            Message::where('sender_id', $targetUserId)
                ->where('receiver_id', $currentUserId)
                ->update(['is_read' => true]);
        }

        return view('livewire.messages', [
            'users' => $users,
            'chats' => $chats,
            'activeUser' => $this->activeUserId ? User::find($this->activeUserId) : null
        ])->layout('layouts.calculator');
    }
}

