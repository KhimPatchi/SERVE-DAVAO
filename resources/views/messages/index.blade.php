@extends ('layouts.sidebar.sidebar')

@section ('content')
<!-- Antigravity Real-time Fallback: Load Echo 1.16.0+ for REVERB support -->
<script src="https://js.pusher.com/8.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>

<div class="min-h-[600px] h-[85vh] flex flex-col lg:flex-row bg-white rounded-3xl shadow-2xl border border-gray-100" x-data="messagingApp()" x-init="init()">
    
    <!-- Sidebar / Conversation List -->
    <div class="w-full {{ isset($activeConversation) ? 'lg:w-64' : 'lg:w-80' }} bg-gray-50 border-r border-gray-100 flex flex-col {{ isset($activeConversation) ? 'hidden lg:flex' : 'flex' }} transition-all duration-300">
        <!-- Sidebar Header -->
        <div class="p-6 border-b border-gray-100 bg-white sticky top-0 z-10">
            <div class="flex items-center justify-between mb-5">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Messages</h1>
                <div class="flex gap-2.5">
                    <button class="w-9 h-9 rounded-xl bg-white shadow-sm border border-gray-100 hover:bg-gray-50 flex items-center justify-center text-gray-600 transition-all active:scale-95">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="w-9 h-9 rounded-xl bg-white shadow-sm border border-gray-100 hover:bg-gray-50 flex items-center justify-center text-gray-600 transition-all active:scale-95">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </div>
            
            <!-- Search -->
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors">
                    <i class="bi bi-search text-xs"></i>
                </div>
                <input type="text" placeholder="Search conversations..." 
                       class="w-full bg-white border border-gray-100 rounded-xl py-2.5 pl-10 pr-4 text-sm font-medium focus:ring-4 focus:ring-blue-50 focus:border-blue-200 transition-all outline-none placeholder-gray-400 shadow-sm">
            </div>
        </div>

        <!-- Conversation List -->
        <div class="flex-1 overflow-y-auto p-3 space-y-3 custom-scrollbar">
            @forelse ($conversationsData as $data)
                <a href="{{ route('messages.show', $data['conversation']->id) }}" 
                   class="active-item-check group flex items-center gap-4 p-4 rounded-2xl transition-all duration-300 {{ isset($activeConversation) && $activeConversation->id == $data['conversation']->id ? 'bg-white shadow-lg shadow-blue-500/5 border border-blue-100' : 'hover:bg-white hover:shadow-md border border-transparent' }}">
                    
                    <div class="relative shrink-0">
                        <div class="w-12 h-12 rounded-xl overflow-hidden border-2 border-white shadow-sm transition-transform duration-500">
                            <img src="{{ $data['avatar_url'] }}" class="w-full h-full object-cover">
                        </div>
                        @if (isset($activeConversation) && $activeConversation->id == $data['conversation']->id)
                            <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full shadow-sm"></span>
                        @endif
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center mb-1">
                            <h3 class="font-bold text-gray-900 truncate {{ isset($activeConversation) && $activeConversation->id == $data['conversation']->id ? 'text-blue-600' : '' }}">
                                {{ $data['display_name'] }}
                            </h3>
                            <span class="text-[9px] font-medium text-gray-400 opacity-40 uppercase shrink-0">
                                {{ $data['last_message'] ? $data['last_message']->created_at->diffForHumans(null, true, true) : '' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <p id="last-msg-{{ $data['conversation']->id }}" class="text-xs truncate text-gray-500 font-medium">
                                {{ $data['last_message'] ? $data['last_message']->message : 'Say hello!' }}
                            </p>
                            @if ($data['unread_count'] > 0)
                                <span class="min-w-[1.25rem] h-5 px-1.5 bg-blue-600 rounded-lg text-[10px] font-black text-white flex items-center justify-center shadow-lg shadow-blue-500/20 ml-2">
                                    {{ $data['unread_count'] }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center mb-4 text-gray-200">
                        <i class="bi bi-chat-heart text-2xl"></i>
                    </div>
                    <p class="text-gray-400 font-bold text-xs uppercase tracking-widest">No chats find</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 w-full flex flex-col bg-white relative {{ isset($activeConversation) ? 'flex' : 'hidden lg:flex' }}">
        @if (isset($activeConversation))
            <!-- Chat Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white sticky top-0 z-20 shadow-sm">
                <div class="flex items-center gap-4">
                    <a href="{{ route('messages.index') }}" class="lg:hidden w-10 h-10 -ml-2 rounded-xl hover:bg-gray-100 flex items-center justify-center text-gray-600 transition-all">
                        <i class="bi bi-arrow-left text-xl"></i>
                    </a>
                    
                    <div class="relative">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden border border-gray-100 shadow-sm">
                            <img src="{{ $activeConversation->getAvatarUrl(auth()->user()) }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                    
                    <div>
                        <h2 class="font-black text-gray-900 text-lg leading-tight tracking-tight">{{ $activeConversation->getDisplayName(auth()->user()) }}</h2>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="w-2 h-2 rounded-full" 
                                  :class="connectionStatus === 'connected' ? 'bg-green-500' : (connectionStatus === 'error' ? 'bg-red-500' : 'bg-yellow-500')"></span>
                            @if ($activeConversation->type === 'event_group')
                                <span class="text-[10px] font-bold text-blue-500 uppercase tracking-widest">Group Chat</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex gap-1.5">
                    <button class="w-10 h-10 rounded-xl hover:bg-blue-50 text-blue-500 flex items-center justify-center transition-all active:scale-90">
                        <i class="bi bi-camera-video"></i>
                    </button>
                    <button class="w-10 h-10 rounded-xl hover:bg-blue-50 text-blue-500 flex items-center justify-center transition-all active:scale-90">
                        <i class="bi bi-telephone"></i>
                    </button>
                    <button class="w-10 h-10 rounded-xl hover:bg-gray-100 text-gray-400 flex items-center justify-center transition-all active:scale-90">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                </div>
            </div>

            <!-- Messages Stream -->
            <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-6 bg-white custom-scrollbar">
                <!-- Date Separator -->
                <div class="flex justify-center py-4">
                    <span class="bg-gray-50/50 border border-gray-100 text-gray-400 text-[9px] font-bold px-4 py-1.5 rounded-full uppercase tracking-[0.15em]">Session Start</span>
                </div>

                <template x-for="(msg, index) in messages" :key="msg.id || index">
                    <div class="flex w-full mb-4 px-1" 
                         :class="msg.user_id === null ? 'justify-center' : (parseInt(msg.user_id) === parseInt({{ auth()->id() }}) ? 'justify-end' : 'justify-start')">
                        
                        <!-- System Message -->
                        <template x-if="msg.user_id === null">
                            <span class="bg-gray-100/80 text-gray-500 text-[11px] font-bold px-4 py-1.5 rounded-full border border-gray-200/50" x-text="msg.message"></span>
                        </template>

                        <!-- Regular Message -->
                        <template x-if="msg.user_id !== null">
                            <div class="flex max-w-[85%] lg:max-w-[70%] gap-3 items-end"
                                 :class="parseInt(msg.user_id) === parseInt({{ auth()->id() }}) ? 'flex-row-reverse' : 'flex-row'">
                                
                                <!-- Sender Avatar (Always Shown for Symmetry) -->
                                <div class="shrink-0 mb-1">
                                    <img :src="msg.sender ? msg.sender.avatar_url : '{{ asset('images/default-avatar.svg') }}'" class="w-10 h-10 rounded-xl border-2 border-white shadow-sm object-cover">
                                </div>

                                <div class="flex flex-col shrink" :class="parseInt(msg.user_id) === parseInt({{ auth()->id() }}) ? 'items-end' : 'items-start'">
                                    <!-- Attachment -->
                                    <template x-if="msg.attachment_url">
                                        <div class="mb-2">
                                            <template x-if="msg.attachment_type && msg.attachment_type.startsWith('image/')">
                                                <div class="rounded-2xl overflow-hidden shadow-sm border-4 border-white inline-block">
                                                    <img :src="msg.attachment_url" class="max-w-full max-h-72 object-cover cursor-zoom-in" @click="window.open(msg.attachment_url, '_blank')">
                                                </div>
                                            </template>
                                            <template x-if="!msg.attachment_type || !msg.attachment_type.startsWith('image/')">
                                                <a :href="msg.attachment_url" target="_blank" class="flex items-center gap-3 p-3 bg-white rounded-2xl border border-gray-100 shadow-sm">
                                                    <i class="bi bi-file-earmark-arrow-down text-xl text-blue-500"></i>
                                                    <span class="text-sm font-black text-gray-900 leading-none">File</span>
                                                </a>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- Bubble -->
                                    <div x-show="msg.message" class="px-4 py-2.5 rounded-2xl shadow-sm text-[15px] leading-snug break-words"
                                         :class="parseInt(msg.user_id) === parseInt({{ auth()->id() }}) 
                                            ? 'bg-blue-600 text-white rounded-tr-none' 
                                            : 'bg-gray-100 text-gray-800 rounded-tl-none'">
                                        <span x-text="msg.message" class="whitespace-pre-wrap"></span>
                                    </div>

                                    <!-- Meta -->
                                    <div class="flex items-center gap-1.5 mt-1 px-1 opacity-40">
                                        <span class="text-[8px] font-bold text-gray-400 uppercase tracking-tighter" x-text="msg.formatted_time || 'Just now'"></span>
                                        <template x-if="parseInt(msg.user_id) === parseInt({{ auth()->id() }})">
                                            <i class="bi bi-check2-all text-blue-500 text-xs"></i>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <!-- Input Area -->
            <div class="p-6 bg-white border-t border-gray-100 relative">
                <!-- Preview Attachment -->
                <div x-show="attachmentPreview" 
                     class="absolute bottom-full left-0 right-0 p-4 bg-white border-t border-blue-100 flex items-center gap-4">
                    <template x-if="attachmentType && attachmentType.startsWith('image/')">
                        <img :src="attachmentPreview" class="w-16 h-16 object-cover rounded-xl shadow-md border-2 border-white">
                    </template>
                    <template x-if="attachmentType && !attachmentType.startsWith('image/')">
                        <div class="w-16 h-16 flex items-center justify-center bg-blue-50 text-blue-500 rounded-xl shadow-sm">
                            <i class="bi bi-file-earmark-text text-3xl"></i>
                        </div>
                    </template>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-black text-gray-900 truncate tracking-tight" x-text="attachmentName"></p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase" x-text="(attachmentSize / 1024).toFixed(1) + ' KB'"></p>
                    </div>
                    <button @click="clearAttachment()" class="w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition-all">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form @submit.prevent="sendMessage" class="relative flex items-end gap-3 px-2">
                    <div class="flex-1 bg-gray-50 border border-gray-100 rounded-2xl transition-all duration-300 flex items-end p-1.5 shadow-inner focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-100 focus-within:border-blue-300">
                        
                        <input type="file" x-ref="fileInput" @change="handleFileSelect" class="hidden">
                        
                        <button type="button" @click="$refs.fileInput.click()" class="w-10 h-10 rounded-xl text-gray-500 hover:text-blue-600 hover:bg-gray-100 transition-all flex items-center justify-center">
                            <i class="bi bi-paperclip text-xl"></i>
                        </button>

                        <textarea x-model="newMessage" 
                                  @keydown.enter.prevent="if(!event.shiftKey) sendMessage()"
                                  placeholder="Type a message..." 
                                  class="flex-1 bg-transparent border-none focus:ring-0 py-2.5 px-3 text-[15px] text-gray-800 placeholder-gray-400 outline-none min-h-[44px] max-h-32 resize-none custom-scrollbar"
                                  rows="1"
                                  :disabled="sending"></textarea>

                        <button type="button" class="w-10 h-10 rounded-xl text-gray-400 hover:text-yellow-500 hover:bg-gray-100 transition-all flex items-center justify-center">
                            <i class="bi bi-emoji-smile text-xl"></i>
                        </button>
                    </div>

                    <button type="submit" 
                            class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-xl shadow-blue-500/20 transition-all active:scale-95 disabled:opacity-50"
                            :class="(newMessage.trim() || attachmentFile) ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                            :disabled="sending || (!newMessage.trim() && !attachmentFile)">
                        <template x-if="!sending">
                            <i class="bi bi-send-fill text-lg"></i>
                        </template>
                        <span x-show="sending" class="animate-spin w-5 h-5 border-3 border-white/30 border-t-white rounded-full"></span>
                    </button>
                </form>
            </div>

        @else
            <!-- Empty State -->
            <div class="flex-1 flex flex-col items-center justify-center bg-gray-50/20 p-12 text-center relative overflow-hidden">
                <!-- Abstract Background shapes -->
                <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-100/30 rounded-full blur-[80px] -z-10 animate-pulse"></div>
                <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-100/30 rounded-full blur-[100px] -z-10"></div>

                <div class="w-32 h-32 bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(30,64,175,0.1)] flex items-center justify-center mb-8 rotate-3 transform hover:rotate-0 transition-transform duration-700 relative group">
                    <div class="absolute inset-0 bg-blue-500 rounded-[2.5rem] opacity-0 group-hover:opacity-10 blur-xl transition-opacity"></div>
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-[1.8rem] flex items-center justify-center">
                        <i class="bi bi-chat-dots-fill text-5xl text-blue-500 drop-shadow-sm"></i>
                    </div>
                    <!-- Micro notification dot -->
                    <span class="absolute top-4 right-4 w-4 h-4 bg-red-500 border-4 border-white rounded-full animate-bounce"></span>
                </div>
                
                <h2 class="text-4xl font-black text-gray-900 mb-4 tracking-tighter">Your Hub for Connection</h2>
                <p class="text-gray-500 max-w-sm font-medium leading-relaxed">Start meaningful conversations with event organizers and fellow volunteers in Davao City.</p>
                
                <div class="mt-12 grid grid-cols-2 gap-4">
                    <div class="p-4 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <i class="bi bi-shield-lock-fill text-blue-400 text-xl block mb-2"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">End-to-End</span>
                        <p class="text-xs font-bold text-gray-700">Encrypted</p>
                    </div>
                    <div class="p-4 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <i class="bi bi-lightning-fill text-yellow-400 text-xl block mb-2"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Zero Lag</span>
                        <p class="text-xs font-bold text-gray-700">Real-time</p>
                    </div>
                </div>

                <button class="mt-10 px-8 py-3 bg-gray-900 text-white rounded-2xl font-black text-sm tracking-widest hover:bg-gray-800 transition-all active:scale-95 shadow-xl shadow-gray-200">
                    FIND PEOPLE
                </button>
            </div>
        @endif
    </div>
</div>

<style>
    /* Custom Scrollbar for sleek look */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 20px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: #94a3b8;
    }
    .animate-bounce-slow {
        animation: bounce 3s infinite;
    }
</style>

<script>
    function messagingApp() {
        return {
            messages: @json($messages instanceof \Illuminate\Pagination\AbstractPaginator ? $messages->items() : $messages->all()).reverse(),
            newMessage: '',
            attachmentFile: null,
            attachmentPreview: null,
            attachmentName: null,
            attachmentSize: null,
            attachmentType: null,
            sending: false,
            processedMessageIds: new Set(),
            connectionStatus: 'connecting',
            conversationId: {{ isset($activeConversation) ? $activeConversation->id : 'null' }},

            init() {
                if (!this.conversationId) return;

                this.scrollToBottom();
                this.setupEcho();
                
                // Update timestamps immediately then every minute
                this.refreshTimestamps();
                setInterval(() => {
                    this.refreshTimestamps();
                }, 60000);
            },

            refreshTimestamps() {
                this.messages.forEach(msg => {
                    if (msg.created_at) {
                        const now = new Date();
                        const created = new Date(msg.created_at);
                        const diffInSecs = Math.floor((now - created) / 1000);
                        
                        if (diffInSecs < 60) msg.formatted_time = 'Just now';
                        else if (diffInSecs < 3600) msg.formatted_time = Math.floor(diffInSecs / 60) + 'm ago';
                        else if (diffInSecs < 86400) msg.formatted_time = Math.floor(diffInSecs / 3600) + 'h ago';
                        else msg.formatted_time = Math.floor(diffInSecs / 86400) + 'd ago';
                    }
                });
            },

            setupEcho() {
                const echo = window.Echo;
                if (!echo || typeof echo.private !== 'function') {
                    // Only log every 5th attempt to keep console clean
                    this.__echoRetryCount = (this.__echoRetryCount || 0) + 1;
                    if (this.__echoRetryCount % 5 === 0) {
                        console.log('Antigravity: Waiting for real-time connection...');
                    }
                    setTimeout(() => this.setupEcho(), 1000);
                    return;
                }
                this.__echoRetryCount = 0;

                this.connectionStatus = 'connected';
                
                // 1. ACTIVE conversation listener
                echo.private(`conversation.${this.conversationId}`)
                    .listen('.message.sent', (e) => {
                        if (this.processedMessageIds.has(e.id)) return;
                        this.processedMessageIds.add(e.id);

                        if (!this.messages.find(m => m.id === e.id)) {
                            this.messages.push({
                                id: e.id,
                                message: e.message,
                                user_id: e.user_id ?? (e.sender ? e.sender.id : null),
                                is_system_message: e.is_system_message || false,
                                formatted_time: e.formatted_time,
                                sender: e.sender, 
                                attachment_url: e.attachment_url,
                                attachment_type: e.attachment_type
                            });
                            this.scrollToBottom();
                            this.syncSidebar(e);
                        }
                    });

                // 2. Global USER listener (already mostly handled in sidebar, but we update UI here)
                echo.private(`App.Models.User.{{ auth()->id() }}`)
                    .listen('.message.sent', (e) => {
                        if (this.processedMessageIds.has(e.id)) return;
                        
                        // Ignore self-messages (Echo toOthers handles this, but safety first)
                        if (parseInt(e.sender.id) === parseInt({{ auth()->id() }})) return;

                        // Only process background messages here (sidebar toast is global)
                        if (e.conversation_id != this.conversationId) {
                            this.processedMessageIds.add(e.id);
                            this.syncSidebar(e, true);
                        }
                    });
            },

            // Consolidated sidebar sync logic
            syncSidebar(e, isBackground = false) {
                const lastMsgEl = document.getElementById(`last-msg-${e.conversation_id}`);
                if (!lastMsgEl) return;

                // Update text
                lastMsgEl.innerText = e.message || (e.attachment_url ? 'Sent an attachment' : 'New message');

                // MOVE TO TOP
                const conversationItem = lastMsgEl.closest('a');
                if (conversationItem && conversationItem.parentElement) {
                    const container = conversationItem.parentElement;
                    if (container.firstChild !== conversationItem) {
                        container.prepend(conversationItem);
                    }
                }
                
                if (isBackground) {
                    // Update background style and badge
                    lastMsgEl.classList.remove('text-gray-500');
                    lastMsgEl.classList.add('text-gray-900', 'font-bold');
                    lastMsgEl.closest('.active-item-check')?.classList.add('bg-blue-50/50'); // Added safety

                    const badgeParent = lastMsgEl.closest('div.flex-1');
                    let badge = badgeParent.querySelector('span.bg-blue-600');
                    if (badge) {
                        badge.innerText = parseInt(badge.innerText) || 1;
                        badge.innerText = parseInt(badge.innerText) + 1;
                    } else {
                        const newBadge = document.createElement('span');
                        newBadge.className = 'min-w-[1.25rem] h-5 px-1.5 bg-blue-600 rounded-lg text-[10px] font-black text-white flex items-center justify-center shadow-lg shadow-blue-500/20 ml-2';
                        newBadge.innerText = '1';
                        // Append next to the title or in the flex container
                        badgeParent.querySelector('.flex.justify-between.items-center').appendChild(newBadge);
                    }
                } else {
                    // Update active conversation style
                    lastMsgEl.classList.remove('text-gray-500');
                    lastMsgEl.classList.add('text-blue-600', 'font-medium');
                }
            },

            handleFileSelect(event) {
                const file = event.target.files[0];
                if (!file) return;

                // Validate size (e.g., 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File is too large. Max 5MB.');
                    return;
                }

                this.attachmentFile = file;
                this.attachmentName = file.name;
                this.attachmentSize = file.size;
                this.attachmentType = file.type;

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.attachmentPreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    this.attachmentPreview = null;
                }
            },

            clearAttachment() {
                this.attachmentFile = null;
                this.attachmentPreview = null;
                this.attachmentName = null;
                this.attachmentSize = null;
                this.attachmentType = null;
                if (this.$refs.fileInput) {
                    this.$refs.fileInput.value = '';
                }
            },

            async sendMessage() {
                if ((!this.newMessage.trim() && !this.attachmentFile) || this.sending) return;
                
                const formData = new FormData();
                formData.append('message', this.newMessage);
                if (this.attachmentFile) {
                    formData.append('attachment', this.attachmentFile);
                }

                this.sending = true;
                
                // Optimistic UI (Text only for now, complex to do file preview optimistically without Blob)
                // We'll trust the fast server response for files or add a placeholder
                const tempId = Date.now();
                const tempMsg = this.newMessage;
                const tempAttachment = this.attachmentPreview; // For optimistic image
                
                this.newMessage = '';
                // Don't clear attachment yet in case of failure, but hide it for now
                // Actually better to wait for response for files to ensure URL is valid

                try {
                    const response = await fetch(`{{ route('messages.send', isset($activeConversation) ? $activeConversation->id : 0) }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Socket-ID': window.Echo ? window.Echo.socketId() : null
                        },
                        body: formData // Fetch handles Content-Type for FormData automatically
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        this.clearAttachment(); // Clear on success
                        if (!this.messages.find(m => m.id === data.message.id)) {
                            this.messages.push({
                                id: data.message.id,
                                message: data.message.message,
                                user_id: {{ auth()->id() }},
                                created_at: data.message.created_at,
                                formatted_time: data.message.formatted_time,
                                sender: data.message.sender, 
                                attachment_url: data.message.attachment_url,
                                attachment_type: data.message.attachment_type
                            });
                            this.scrollToBottom();
                            this.syncSidebar({ 
                                conversation_id: this.conversationId, 
                                message: tempMsg 
                            });
                        }
                    } else {
                        alert(data.error || 'Failed to send');
                        this.newMessage = tempMsg; // Restore on fail
                    }
                } catch (e) {
                    console.error('Send error:', e);
                    this.newMessage = tempMsg;
                } finally {
                    this.sending = false;
                    this.$nextTick(() => {
                        // Focus back on input if needed, or keep focus
                    });
                }
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    const container = document.getElementById('messages-container');
                    if (container) { container.scrollTop = container.scrollHeight; }
                });
            }
        }
    }
</script>
@endsection
