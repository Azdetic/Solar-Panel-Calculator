<div class="flex flex-row h-screen pt-20 px-4 md:px-8 pb-8 gap-6 bg-gray-50">
    <!-- Sidebar: Conversations List -->
    <div class="w-1/3 xl:w-1/4 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-full overflow-hidden">
        <div class="p-5 border-b border-gray-100">
            <h2 class="text-xl font-bold font-manrope text-gray-800">Messages</h2>
        </div>
        
        <div class="flex-1 overflow-y-auto">
            @forelse($users as $u)
                <button type="button" wire:key="chat-user-{{ $u->id }}" wire:click="selectConversation({{ $u->id }})"
                   class="w-full text-left flex items-center justify-between p-4 border-b border-gray-50 hover:bg-gray-50 transition-colors cursor-pointer {{ $activeUserId == $u->id ? 'bg-indigo-50 border-l-4 border-l-indigo-500' : '' }}">
                    <div class="flex flex-col">
                        <span class="font-semibold font-inter text-gray-800">{{ $u->name }}</span>
                        @if($u->role)
                            <span class="text-xs text-gray-500 mt-1">{{ ucfirst($u->role) }}</span>
                        @endif
                    </div>
                </button>
            @empty
                <div class="p-8 text-center text-gray-400 font-inter">
                    <span class="material-symbols-outlined text-4xl mb-2">inbox</span>
                    <p>No conversations yet.</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Main Chat Area -->
    <div class="w-2/3 xl:w-3/4 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-full overflow-hidden">
        @if($activeUser)
            <!-- Chat Header -->
            <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-white z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                        {{ substr($activeUser->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold font-manrope text-gray-800">{{ $activeUser->name }}</h3>
                        <span class="text-xs text-green-500 flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span> Online
                        </span>
                    </div>
                </div>
            </div>

            <!-- Messages Container -->
            <div class="flex-1 p-6 overflow-y-auto bg-gray-50/50" wire:poll.3s>
                <div class="flex flex-col space-y-4">
                    @forelse($chats as $chat)
                        @if($chat->sender_id == auth()->id())
                            <!-- Sent Message -->
                            <div class="flex justify-end">
                                <div class="bg-indigo-600 text-white rounded-2xl rounded-tr-none px-5 py-3 max-w-[75%] lg:max-w-[60%] shadow-sm">
                                    <p class="font-inter text-sm wrap-break-word">{{ $chat->message }}</p>
                                    <p class="text-[10px] text-indigo-200 text-right mt-2">{{ $chat->created_at->format('H:i') }}</p>
                                </div>
                            </div>
                        @else
                            <!-- Received Message -->
                            <div class="flex justify-start">
                                <div class="bg-white border border-gray-100 text-gray-800 rounded-2xl rounded-tl-none px-5 py-3 max-w-[75%] lg:max-w-[60%] shadow-sm">
                                    <p class="font-inter text-sm wrap-break-word">{{ $chat->message }}</p>
                                    <p class="text-[10px] text-gray-400 text-right mt-2">{{ $chat->created_at->format('H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="flex-1 flex flex-col items-center justify-center text-gray-400 pt-10">
                            <span class="material-symbols-outlined text-6xl mb-4 text-gray-200">forum</span>
                            <p class="font-inter">Start the conversation with {{ $activeUser->name }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t border-gray-100">
                <form wire:submit.prevent="sendMessage" class="flex gap-3">
                    <input type="text" 
                        wire:model="messageText" 
                        class="flex-1 bg-gray-50 border border-gray-200 rounded-full px-6 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-inter transition-all"
                        placeholder="Type your message..." 
                        required>
                    <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-full w-12 h-12 flex items-center justify-center transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-xl ml-1">send</span>
                    </button>
                </form>
            </div>
        @else
            <!-- No Conversation Selected State -->
            <div class="flex-1 flex flex-col items-center justify-center text-gray-400 bg-gray-50/30">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-5xl text-gray-400">chat_bubble</span>
                </div>
                <h3 class="text-xl font-bold font-manrope text-gray-600 mb-2">Your Messages</h3>
                <p class="font-inter text-sm text-gray-500">Select a user from the sidebar to view chat history.</p>
            </div>
        @endif
    </div>
</div>
