@extends('layouts.sidebar.sidebar')

@section('content')
{{-- Real-time fallback: Echo 1.16+ for Reverb --}}
<script src="https://js.pusher.com/8.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>

<style>
/* ── Avatar images — never resize their container ── */
#chat-root img {
    display: block;
    max-width: 100%;
    max-height: 100%;
}
/* ── Rigid Avatar Constraints to bypass Tailwind compile failures ── */
.avatar-sidebar-outer {
    width: 44px !important;
    height: 44px !important;
    min-width: 44px !important;
    min-height: 44px !important;
    max-width: 44px !important;
    max-height: 44px !important;
    flex-shrink: 0 !important;
}
.avatar-sidebar-inner {
    width: 44px !important;
    height: 44px !important;
    min-width: 44px !important;
    min-height: 44px !important;
    max-width: 44px !important;
    max-height: 44px !important;
    border-radius: 9999px !important;
    overflow: hidden !important;
}
.avatar-sidebar-img {
    width: 100% !important;
    height: 100% !important;
    min-width: 100% !important;
    min-height: 100% !important;
    max-width: 100% !important;
    max-height: 100% !important;
    object-fit: cover !important;
    display: block !important;
}

.avatar-header-outer {
    width: 40px !important;
    height: 40px !important;
    min-width: 40px !important;
    min-height: 40px !important;
    max-width: 40px !important;
    max-height: 40px !important;
    flex-shrink: 0 !important;
}
.avatar-header-inner {
    width: 40px !important;
    height: 40px !important;
    min-width: 40px !important;
    min-height: 40px !important;
    max-width: 40px !important;
    max-height: 40px !important;
    border-radius: 9999px !important;
    overflow: hidden !important;
}
.avatar-header-img {
    width: 100% !important;
    height: 100% !important;
    min-width: 100% !important;
    min-height: 100% !important;
    max-width: 100% !important;
    max-height: 100% !important;
    object-fit: cover !important;
    display: block !important;
}

.avatar-bubble-outer {
    width: 32px !important;
    height: 32px !important;
    min-width: 32px !important;
    min-height: 32px !important;
    max-width: 32px !important;
    max-height: 32px !important;
    flex-shrink: 0 !important;
}
.avatar-bubble-img {
    width: 32px !important;
    height: 32px !important;
    min-width: 32px !important;
    min-height: 32px !important;
    max-width: 32px !important;
    max-height: 32px !important;
    border-radius: 9999px !important;
    object-fit: cover !important;
    display: block !important;
}
/* ── Scrollbars ─────────────────────────────────── */
.chat-scroll::-webkit-scrollbar          { width: 4px; }
.chat-scroll::-webkit-scrollbar-track   { background: transparent; }
.chat-scroll::-webkit-scrollbar-thumb   { background: #e2e8f0; border-radius: 99px; }
.chat-scroll::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

/* ── Message background ─────────────────────────── */
#msg-body {
    background-color: #f8fafc;
    background-image: radial-gradient(circle, #dde4ed 1px, transparent 1px);
    background-size: 22px 22px;
}

/* ── Bubble shapes ──────────────────────────────── */
.bbl-out { border-radius: 16px 16px 4px 16px; }
.bbl-in  { border-radius: 16px 16px 16px 4px; }

/* ── New-message slide-up ───────────────────────── */
@keyframes slideUp {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
}
.msg-new { animation: slideUp 0.2s ease-out both; }

/* ── Online pulse ───────────────────────────────── */
@keyframes onlinePulse {
    0%,100% { box-shadow: 0 0 0 0 rgba(16,185,129,.4); }
    50%      { box-shadow: 0 0 0 4px rgba(16,185,129,0); }
}
.online-dot { animation: onlinePulse 2s ease-in-out infinite; }

/* ── Spinner ────────────────────────────────────── */
@keyframes spinMe { to { transform: rotate(360deg); } }
.spin-icon { animation: spinMe .7s linear infinite; }

/* ── Date separator pill ────────────────────────── */
.date-sep-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
    background: rgba(255,255,255,0.92);
    border: 1px solid #e2e8f0;
    border-radius: 999px;
    padding: 3px 12px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    backdrop-filter: blur(4px);
    letter-spacing: .03em;
}

.date-sep-passed {
    background: rgba(241, 245, 249, 0.5) !important;
    border: 1px solid rgba(226, 232, 240, 0.4) !important;
    color: #94a3b8 !important;
    box-shadow: none !important;
}

/* ── Active conversation item ───────────────────── */
.conv-link.is-active {
    background: linear-gradient(135deg,#eff6ff,#eef2ff);
    border-color: #bfdbfe !important;
}
.conv-link:not(.is-active):hover { background: #f8fafc; }

/* ── Cloak ──────────────────────────────────────── */
[x-cloak] { display: none !important; }

/* ── Telegram-style Message Flashing ──────────────── */
.msg-flash {
    animation: flashHighlight 2.5s ease-out;
}
@keyframes flashHighlight {
    0% {
        background-color: rgba(254, 243, 199, 0.95) !important;
        box-shadow: 0 0 0 2px rgba(252, 211, 77, 0.8) !important;
        border-radius: 8px;
    }
    100% {
        background-color: transparent;
        box-shadow: 0 0 0 0 transparent;
    }
}
</style>

{{-- ════════════════════════════════════════════════
     COMPONENT ROOT — Alpine.js binding here
     ════════════════════════════════════════════════ --}}
<div id="chat-root"
     class="flex h-[85vh] min-h-[540px] bg-white rounded-2xl shadow-xl border border-slate-200/60 overflow-hidden"
     x-data="chatApp()" x-init="init()"
     data-messages="{{ json_encode($messages instanceof \Illuminate\Pagination\LengthAwarePaginator ? $messages->items() : $messages) }}"
     data-conversation-id="{{ json_encode($activeConversation->id ?? null) }}"
     data-auth-id="{{ json_encode(auth()->id()) }}"
     data-send-route="{{ isset($activeConversation) ? route('messages.send', ':id') : '' }}"
     data-all-messages-route="{{ isset($activeConversation) ? route('messages.all-messages', ':id') : '' }}"
     data-csrf="{{ csrf_token() }}">

    {{-- ══════════════════════════════════════════
         LEFT: SIDEBAR / CONVERSATION LIST
         ══════════════════════════════════════════ --}}
    <aside class="flex flex-col w-full lg:w-72 bg-white border-r border-slate-200/60
                  {{ isset($activeConversation) ? 'hidden lg:flex' : 'flex' }} shrink-0">

        {{-- Sidebar header --}}
        <div class="px-4 pt-5 pb-4 border-b border-slate-100 shrink-0">
            <div class="flex items-center justify-between mb-3">
                <h1 class="text-xl font-extrabold text-emerald-600 tracking-tight">Messages</h1>
                <button title="Compose"
                        class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-blue-50 hover:text-blue-600
                               text-slate-500 flex items-center justify-center transition-colors text-sm">
                    <i class="bi bi-pencil-square"></i>
                </button>
            </div>
            {{-- Search --}}
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                <input id="sidebar-search" type="text" placeholder="Search conversations…"
                       class="w-full bg-slate-100 rounded-xl py-2 pl-8 pr-3 text-sm text-slate-800
                              placeholder-slate-400 outline-none focus:ring-2 focus:ring-blue-200
                              focus:bg-white transition-all">
            </div>
        </div>

        {{-- Conversation list --}}
        <div id="conv-list" class="flex-1 overflow-y-auto chat-scroll py-2 space-y-0.5">
            @forelse($conversationsData as $data)
                <a href="{{ route('messages.show', $data['conversation']->id) }}"
                   id="conv-{{ $data['conversation']->id }}"
                   class="conv-link active-item-check flex items-center gap-3 mx-2 px-3 py-3
                          rounded-xl border border-transparent transition-all duration-150
                          {{ isset($activeConversation) && $activeConversation->id == $data['conversation']->id ? 'is-active' : '' }}">

                    @php
                        $otherUser = $data['conversation']->getOtherParticipant(auth()->user());
                        $otherUserId = $otherUser ? $otherUser->id : 0;
                    @endphp
                    {{-- Avatar: outer div = anchor, inner div clips the image --}}
                    <div class="relative avatar-sidebar-outer">
                        <div class="avatar-sidebar-inner ring-2 ring-white shadow-sm">
                            <img src="{{ $data['avatar_url'] }}"
                                 class="avatar-sidebar-img">
                        </div>
                        <template x-if="onlineUsers.includes({{ $otherUserId }})">
                            <span class="online-dot absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500
                                         rounded-full border-2 border-white z-10"></span>
                        </template>
                    </div>

                    {{-- Text --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-baseline justify-between gap-1">
                            <span class="font-semibold text-slate-900 text-sm truncate">
                                {{ $data['display_name'] }}
                            </span>
                            <span class="text-[10px] text-slate-400 shrink-0">
                                {{ $data['last_message'] ? $data['last_message']->created_at->diffForHumans(null, true, true) : '' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-1 mt-0.5">
                            <p id="last-msg-{{ $data['conversation']->id }}"
                               class="text-xs text-slate-500 truncate flex-1">
                                {{ $data['last_message'] ? $data['last_message']->message : 'Say hello! 👋' }}
                            </p>
                            @if($data['unread_count'] > 0)
                                <span class="shrink-0 min-w-[18px] h-[18px] px-1 bg-blue-600 rounded-full
                                             text-[10px] font-bold text-white flex items-center justify-center">
                                    {{ $data['unread_count'] }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                    <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mb-3">
                        <i class="bi bi-chat-heart text-slate-300 text-2xl"></i>
                    </div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">No conversations</p>
                </div>
            @endforelse
        </div>
    </aside>

    {{-- ══════════════════════════════════════════
         RIGHT: CHAT AREA
         ══════════════════════════════════════════ --}}
    <main class="flex-1 flex flex-col min-w-0 {{ isset($activeConversation) ? '' : 'hidden lg:flex' }}">

        @if(isset($activeConversation))

            {{-- ── Chat header ── --}}
            <header class="flex items-center gap-3 px-4 py-3 border-b border-slate-200/60 bg-white shrink-0 z-10">
                {{-- Mobile back --}}
                <a href="{{ route('messages.index') }}"
                   class="lg:hidden -ml-1 w-9 h-9 rounded-xl hover:bg-slate-100 flex items-center
                          justify-center text-slate-500 transition-colors">
                    <i class="bi bi-chevron-left text-lg"></i>
                </a>

                @php
                    $headerOtherUser = $activeConversation->getOtherParticipant(auth()->user());
                    $headerOtherUserId = $headerOtherUser ? $headerOtherUser->id : 0;
                @endphp
                {{-- Contact avatar + status dot (rigid 40×40 wrapper) --}}
                <div class="relative avatar-header-outer">
                    <div class="avatar-header-inner ring-2 ring-slate-100 shadow-sm">
                        <img src="{{ $activeConversation->getAvatarUrl(auth()->user()) }}"
                             class="avatar-header-img">
                    </div>
                    @if($activeConversation->type === 'direct')
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white z-10"
                              :class="onlineUsers.includes({{ $headerOtherUserId }}) ? 'bg-emerald-500 online-dot' : 'bg-slate-300'">
                        </span>
                    @endif
                </div>

                {{-- Name & online text --}}
                <div class="flex-1 min-w-0">
                    <h2 class="font-bold text-slate-900 text-sm truncate leading-tight">
                        {{ $activeConversation->getDisplayName(auth()->user()) }}
                        @if($activeConversation->type === 'event_group')
                            <span class="ml-1.5 text-[10px] font-bold text-blue-500 uppercase tracking-wider">Group</span>
                        @endif
                    </h2>
                    @if($activeConversation->type === 'direct')
                        <p class="text-[11px] mt-0.5 transition-colors"
                           :class="onlineUsers.includes({{ $headerOtherUserId }}) ? 'text-emerald-500' : 'text-slate-400'"
                           x-text="onlineUsers.includes({{ $headerOtherUserId }}) ? 'Active now' : 'Offline'">
                        </p>
                    @else
                        <p class="text-[11px] mt-0.5 text-slate-400">
                            Group Chat
                        </p>
                    @endif
                </div>

                {{-- Action buttons --}}
                <div class="flex gap-1 shrink-0 relative" x-data="{ showMoreMenu: false }">
                    <button title="Video" class="w-9 h-9 rounded-xl hover:bg-slate-100 text-slate-400
                                                  hover:text-blue-500 flex items-center justify-center transition-colors">
                        <i class="bi bi-camera-video text-base"></i>
                    </button>
                    <button title="Call"  class="w-9 h-9 rounded-xl hover:bg-slate-100 text-slate-400
                                                  hover:text-blue-500 flex items-center justify-center transition-colors">
                        <i class="bi bi-telephone text-base"></i>
                    </button>
                    <button type="button" title="More" 
                            @click="showMoreMenu = !showMoreMenu"
                            @click.away="showMoreMenu = false"
                            class="w-9 h-9 rounded-xl hover:bg-slate-100 text-slate-400 flex items-center justify-center transition-colors relative">
                        <i class="bi bi-three-dots-vertical text-base"></i>
                    </button>

                    {{-- More Dropdown Menu --}}
                    <div x-cloak x-show="showMoreMenu"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 top-11 w-48 bg-white border border-slate-200/80 rounded-xl shadow-lg py-1.5 z-50">
                         
                         {{-- Calendar Search Item --}}
                         <button type="button" @click="showMoreMenu = false; $nextTick(() => openDatePicker())"
                                 class="w-full text-left px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 flex items-center gap-2">
                             <i class="bi bi-calendar-event text-slate-400 text-sm"></i>
                             Search by Date
                         </button>
                         
                         <button type="button" class="w-full text-left px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 flex items-center gap-2">
                             <i class="bi bi-bell-slash text-slate-400 text-sm"></i>
                             Mute Notifications
                         </button>
                         
                         <button type="button" class="w-full text-left px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 flex items-center gap-2">
                             <i class="bi bi-trash text-red-400 text-sm"></i>
                             Delete Chat
                         </button>
                    </div>
                </div>
            </header>

            {{-- ── Messages body ── --}}
            <div id="msg-body" class="flex-1 overflow-y-auto chat-scroll px-4 py-5">

                {{-- Message loop --}}
                <template x-for="(msg, index) in messages" :key="msg.id ?? index">
                    <div>
                        {{-- Date separator: first message of each calendar day --}}
                        <div x-show="isFirstOfDay(index)" class="flex justify-center my-4">
                            <span class="date-sep-pill"
                                  :class="isDatePassed(msg) ? 'date-sep-passed' : ''">
                                <i class="bi bi-calendar3 text-slate-400" style="font-size:10px" :class="isDatePassed(msg) ? 'text-slate-400/70' : ''"></i>
                                <span x-text="getDateLabel(msg)"></span>
                            </span>
                        </div>

                        {{-- Outer wrapper: controls overall row direction & spacing --}}
                        <div :id="'msg-container-' + (msg.id ?? index)"
                             class="flex w-full mb-1 transition-all duration-500 px-2 py-1"
                             :class="[
                                 isSystemMsg(msg) ? 'justify-center' : (isMine(msg) ? 'justify-end' : 'justify-start'),
                                 highlightedMessageId === msg.id ? 'msg-flash' : ''
                             ]">

                        {{-- ① SYSTEM message --}}
                        <template x-if="isSystemMsg(msg)">
                            <span class="text-[11px] text-slate-500 font-medium bg-white border
                                         border-slate-200 px-3 py-1 rounded-full shadow-sm my-2"
                                  x-text="msg.message">
                            </span>
                        </template>

                        {{-- ② INCOMING message (other user) --}}
                        <template x-if="!isSystemMsg(msg) && !isMine(msg)">
                            <div class="flex items-end gap-2 max-w-[72%]">

                                {{-- Avatar: visible only on last in group --}}
                                <div class="avatar-bubble-outer self-end"
                                     :style="isLastInGroup(index) ? '' : 'visibility:hidden'">
                                    <img :src="msg.sender && msg.sender.avatar_url
                                                 ? msg.sender.avatar_url
                                                 : '{{ asset('images/default-avatar.svg') }}'"
                                         class="avatar-bubble-img ring-2 ring-white shadow-sm">
                                </div>

                                {{-- Bubble + meta --}}
                                <div class="flex flex-col items-start gap-0.5">
                                    {{-- Sender name: first in group only --}}
                                    <span x-show="isFirstInGroup(index)"
                                          x-text="msg.sender ? msg.sender.name : ''"
                                          class="text-[11px] font-semibold text-slate-500 px-1">
                                    </span>

                                    {{-- Image attachment --}}
                                    <img x-show="msg.attachment_url && msg.attachment_type && msg.attachment_type.startsWith('image/')"
                                         :src="msg.attachment_url"
                                         class="max-h-56 rounded-2xl object-cover cursor-zoom-in shadow-sm border border-slate-200/60 mb-0.5"
                                         @click="msg.attachment_url && window.open(msg.attachment_url,'_blank')">

                                    {{-- File attachment --}}
                                    <a x-show="msg.attachment_url && (!msg.attachment_type || !msg.attachment_type.startsWith('image/'))"
                                       :href="msg.attachment_url" target="_blank"
                                       class="flex items-center gap-2.5 px-3 py-2 bg-white border border-slate-200
                                              rounded-2xl shadow-sm hover:border-blue-300 transition-colors mb-0.5">
                                        <div class="w-8 h-8 bg-blue-50 rounded-xl flex items-center justify-center shrink-0">
                                            <i class="bi bi-file-earmark-arrow-down text-blue-500"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold text-slate-800">Attachment</p>
                                            <p class="text-[10px] text-slate-400">Tap to open</p>
                                        </div>
                                    </a>

                                    {{-- Text bubble --}}
                                    <div x-show="msg.message && msg.message.trim() !== ''"
                                         class="bbl-in px-3.5 py-2 text-[13.5px] leading-snug break-words
                                                bg-white border border-slate-200 text-slate-800 shadow-sm">
                                        <span x-text="msg.message" class="whitespace-pre-wrap"></span>
                                    </div>

                                    {{-- Timestamp: last in group only --}}
                                    <span x-show="isLastInGroup(index)"
                                          x-text="msg.formatted_time || 'Just now'"
                                          class="text-[10px] text-slate-400 px-1 mt-0.5">
                                    </span>
                                </div>
                            </div>
                        </template>

                        {{-- ③ OUTGOING message (mine) --}}
                        <template x-if="!isSystemMsg(msg) && isMine(msg)">
                            <div class="flex flex-col items-end gap-0.5 max-w-[72%]">

                                {{-- Image attachment --}}
                                <img x-show="msg.attachment_url && msg.attachment_type && msg.attachment_type.startsWith('image/')"
                                     :src="msg.attachment_url"
                                     class="max-h-56 rounded-2xl object-cover cursor-zoom-in shadow-sm border border-slate-200/60 mb-0.5"
                                     @click="msg.attachment_url && window.open(msg.attachment_url,'_blank')">

                                {{-- File attachment --}}
                                <a x-show="msg.attachment_url && (!msg.attachment_type || !msg.attachment_type.startsWith('image/'))"
                                   :href="msg.attachment_url" target="_blank"
                                   class="flex items-center gap-2.5 px-3 py-2 bg-white border border-slate-200
                                          rounded-2xl shadow-sm hover:border-blue-300 transition-colors mb-0.5">
                                    <div class="w-8 h-8 bg-blue-50 rounded-xl flex items-center justify-center shrink-0">
                                        <i class="bi bi-file-earmark-arrow-down text-blue-500"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-slate-800">Attachment</p>
                                        <p class="text-[10px] text-slate-400">Tap to open</p>
                                    </div>
                                </a>

                                {{-- Text bubble --}}
                                <div x-show="msg.message && msg.message.trim() !== ''"
                                     class="bbl-out px-3.5 py-2 text-[13.5px] leading-snug break-words
                                            bg-blue-600 text-white shadow-sm">
                                    <span x-text="msg.message" class="whitespace-pre-wrap"></span>
                                </div>

                                {{-- Timestamp + read tick: last in group only --}}
                                <div x-show="isLastInGroup(index)" class="flex items-center gap-1 px-1 mt-0.5">
                                    <i class="bi bi-check2-all text-[11px] text-blue-400"></i>
                                    <span x-text="msg.formatted_time || 'Just now'"
                                          class="text-[10px] text-slate-400"></span>
                                </div>
                            </div>
                        </template>
                        </div>{{-- close message row flex div --}}
                    </div>{{-- close outer message+separator wrapper --}}
                </template>

                {{-- Scroll anchor --}}
                <div id="msg-end"></div>
            </div>

            {{-- ── Input area ── --}}
            <div class="bg-white border-t border-slate-200/60 px-4 py-3 shrink-0">

                {{-- Attachment preview bar --}}
                <div x-cloak x-show="attachmentPreview"
                     class="flex items-center gap-3 mb-3 p-3 bg-slate-50 border border-slate-200 rounded-xl">
                    <img x-show="attachmentIsImage"
                         :src="attachmentPreview"
                         class="w-12 h-12 object-cover rounded-lg shadow-sm shrink-0">
                    <div x-show="!attachmentIsImage"
                         class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center shrink-0">
                        <i class="bi bi-file-earmark-text text-blue-500 text-xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-800 truncate" x-text="attachmentName"></p>
                        <p class="text-[10px] text-slate-400" x-text="attachmentSizeLabel"></p>
                    </div>
                    <button @click="clearAttachment()"
                            class="w-7 h-7 rounded-full bg-red-50 hover:bg-red-100 text-red-500
                                   flex items-center justify-center transition-colors shrink-0">
                        <i class="bi bi-x text-sm font-bold"></i>
                    </button>
                </div>

                {{-- Compose row --}}
                <form @submit.prevent="sendMessage()" class="flex items-end gap-2">

                    {{-- Hidden file input --}}
                    <input type="file" x-ref="fileInput" @change="handleFile($event)" class="hidden">

                    {{-- Attach button --}}
                    <button type="button" @click="$refs.fileInput.click()" title="Attach file"
                            class="shrink-0 w-10 h-10 rounded-xl bg-slate-100 hover:bg-blue-50
                                   text-slate-500 hover:text-blue-600 flex items-center justify-center
                                   transition-colors">
                        <i class="bi bi-paperclip text-lg"></i>
                    </button>

                    {{-- Text input wrapper --}}
                    <div class="flex-1 flex items-end bg-slate-100 rounded-2xl border border-transparent
                                focus-within:bg-white focus-within:border-blue-300 focus-within:ring-2
                                focus-within:ring-blue-100 transition-all">
                        <textarea x-ref="ta"
                                  x-model="draft"
                                  @keydown.enter.prevent="$event.shiftKey ? null : sendMessage()"
                                  @input="growTA()"
                                  placeholder="Type a message…"
                                  rows="1"
                                  :disabled="sending"
                                  class="flex-1 bg-transparent border-none outline-none ring-0
                                         py-2.5 pl-4 pr-2 text-[13.5px] text-slate-800 placeholder-slate-400
                                         resize-none chat-scroll"
                                  style="max-height:112px; overflow-y:hidden">
                        </textarea>
                        <button type="button" title="Emoji"
                                class="w-9 h-9 rounded-xl text-slate-400 hover:text-amber-500 shrink-0
                                       flex items-center justify-center transition-colors mb-0.5 mr-0.5">
                            <i class="bi bi-emoji-smile text-lg"></i>
                        </button>
                    </div>

                    {{-- Send button --}}
                    <button type="submit"
                            class="shrink-0 w-10 h-10 rounded-2xl flex items-center justify-center
                                   transition-all active:scale-90"
                            :class="canSend()
                                      ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-200'
                                      : 'bg-slate-100 text-slate-400 cursor-not-allowed'"
                            :disabled="!canSend() || sending">
                        <i x-show="!sending" class="bi bi-send-fill text-sm"></i>
                        <span x-show="sending"
                              class="spin-icon w-4 h-4 border-2 border-white/30 border-t-white rounded-full inline-block">
                        </span>
                    </button>
                </form>
            </div>

        @else

            {{-- ── Empty state ── --}}
            <div class="flex-1 flex flex-col items-center justify-center bg-slate-50/40 p-10 text-center">
                <div class="w-20 h-20 bg-emerald-50 border border-emerald-100 rounded-3xl shadow-sm
                             flex items-center justify-center mb-5">
                    <i class="bi bi-chat-dots text-4xl text-emerald-500"></i>
                </div>
                <h2 class="text-xl font-extrabold text-emerald-600 tracking-tight mb-2">Your Messages</h2>
                <p class="text-sm text-slate-500 max-w-xs leading-relaxed">
                    Pick a conversation from the list to start chatting with organizers and volunteers.
                </p>
                <div class="mt-8 flex gap-3">
                    <div class="flex flex-col items-center gap-1.5 p-4 bg-white border border-emerald-100/60
                                rounded-2xl w-28 shadow-sm">
                        <i class="bi bi-shield-check text-emerald-500 text-xl"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Secure</span>
                    </div>
                    <div class="flex flex-col items-center gap-1.5 p-4 bg-white border border-emerald-100/60
                                rounded-2xl w-28 shadow-sm">
                        <i class="bi bi-lightning-charge text-emerald-500 text-xl"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Real-time</span>
                    </div>
                </div>
            </div>

        @endif
    </main>

    {{-- ── Date Picker Modal ── --}}
    <div x-cloak x-show="showDatePickerModal"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4 transition-all duration-300"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
         <div class="w-full max-w-sm bg-white rounded-3xl shadow-2xl border border-slate-200/80 overflow-hidden transform transition-all"
              @click.away="showDatePickerModal = false"
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 scale-95 translate-y-4"
              x-transition:enter-end="opacity-100 scale-100 translate-y-0"
              x-transition:leave="transition ease-in duration-200"
              x-transition:leave-start="opacity-100 scale-100 translate-y-0"
              x-transition:leave-end="opacity-0 scale-95 translate-y-4">
              
              {{-- Header --}}
              <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                  <h3 class="text-sm font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                      <i class="bi bi-calendar3 text-blue-500"></i>
                      Jump to Date
                  </h3>
                  <button type="button" @click="showDatePickerModal = false" 
                          class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                      <i class="bi bi-x text-lg font-bold"></i>
                  </button>
              </div>
              
              {{-- Calendar Navigation --}}
              <div class="px-6 py-4 flex items-center justify-between">
                  <button type="button" @click="prevMonth()" 
                          class="w-8 h-8 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 flex items-center justify-center transition-colors">
                      <i class="bi bi-chevron-left text-sm"></i>
                  </button>
                  <span class="text-sm font-bold text-slate-800" x-text="getPickerMonthName() + ' ' + pickerYear"></span>
                  <button type="button" @click="nextMonth()" 
                          class="w-8 h-8 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 flex items-center justify-center transition-colors">
                      <i class="bi bi-chevron-right text-sm"></i>
                  </button>
              </div>
              
              {{-- Days of Week Headers --}}
              <div class="grid grid-cols-7 gap-1 px-6 text-center text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                  <div>Su</div>
                  <div>Mo</div>
                  <div>Tu</div>
                  <div>We</div>
                  <div>Th</div>
                  <div>Fr</div>
                  <div>Sa</div>
              </div>
              
              {{-- Days Grid --}}
              <div class="grid grid-cols-7 gap-1 px-6 pb-6 text-center">
                  <template x-for="dayObj in pickerDays">
                      <div class="aspect-square">
                          <template x-if="dayObj.day === null">
                              <div class="w-full h-full"></div>
                          </template>
                          
                          <template x-if="dayObj.day !== null">
                              <button type="button" @click="scrollToDate(dayObj.dateString); showDatePickerModal = false"
                                      :class="[
                                          'w-full h-full rounded-xl text-xs font-semibold transition-all relative flex flex-col items-center justify-center',
                                          dayObj.isToday 
                                              ? 'bg-blue-50 text-blue-600 border border-blue-200 font-bold' 
                                              : (dayObj.isPassed 
                                                  ? 'text-slate-400/60 bg-slate-50/40 hover:bg-slate-100/50' 
                                                  : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900'),
                                          dayObj.hasMessages
                                              ? 'font-bold text-blue-600 ring-1 ring-blue-100'
                                              : ''
                                      ]">
                                  <span x-text="dayObj.day"></span>
                                  {{-- Tiny dot indicator if day has messages --}}
                                  <template x-if="dayObj.hasMessages">
                                      <span class="absolute bottom-1.5 w-1 h-1 bg-blue-600 rounded-full"></span>
                                  </template>
                              </button>
                          </template>
                      </div>
                  </template>
              </div>
              
              {{-- Quick Selection / Legend --}}
              <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 text-[11px] text-slate-500 flex justify-between items-center">
                  <div class="flex items-center gap-1.5">
                      <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                      <span>Has messages</span>
                  </div>
                  <button type="button" @click="scrollToDate(new Date().toISOString().split('T')[0]); showDatePickerModal = false"
                          class="text-blue-600 hover:text-blue-700 font-bold transition-colors">
                      Today
                  </button>
              </div>
         </div>
    </div>
</div>

<script>
function chatApp() {
    return {
        /* ── State ─────────────────────────────────── */
        messages:        [],
        draft:           '',
        sending:         false,
        connectionStatus: 'connecting',
        onlineUsers:     window.onlineUsers || [],

        /* Attachment state */
        attachmentFile:    null,
        attachmentPreview: null,
        attachmentIsImage: false,
        attachmentName:    '',
        attachmentSizeLabel: '',

        /* Datepicker state */
        showDatePickerModal: false,
        pickerYear: new Date().getFullYear(),
        pickerMonth: new Date().getMonth(),
        pickerDays: [],
        highlightedMessageId: null,

        /* Internal */
        conversationId:    null,
        authId:            null,
        csrfToken:         null,
        sendRoute:         null,
        allMessagesRoute:  null,
        seenIds:           new Set(),

        /* ── Helpers ───────────────────────────────── */
        isMine(msg)      { return parseInt(msg.user_id) === parseInt(this.authId); },
        isSystemMsg(msg) { return msg.user_id === null || msg.user_id === undefined || !!msg.is_system_message; },
        canSend()        { return !this.sending && (this.draft.trim() !== '' || this.attachmentFile !== null); },

        isLastInGroup(index) {
            if (index >= this.messages.length - 1) return true;
            const a = this.messages[index];
            const b = this.messages[index + 1];
            if (this.isSystemMsg(a) || this.isSystemMsg(b)) return true;
            return a.user_id !== b.user_id;
        },
        isFirstInGroup(index) {
            if (index === 0) return true;
            const a = this.messages[index];
            const b = this.messages[index - 1];
            if (this.isSystemMsg(a) || this.isSystemMsg(b)) return true;
            return a.user_id !== b.user_id;
        },

        /* ── Date helpers (Telegram-style separators) ── */
        _msgDay(msg) {
            if (!msg.created_at) return null;
            const d = new Date(msg.created_at);
            return `${d.getFullYear()}-${d.getMonth()}-${d.getDate()}`;
        },
        isFirstOfDay(index) {
            if (index === 0) return true;
            return this._msgDay(this.messages[index]) !== this._msgDay(this.messages[index - 1]);
        },
        getDateLabel(msg) {
            if (!msg.created_at) return '';
            const d   = new Date(msg.created_at);
            const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            const msgDate = new Date(d.getFullYear(), d.getMonth(), d.getDate());
            const diffDays = Math.round((today - msgDate) / 86400000);
            if (diffDays === 0) return 'Today';
            if (diffDays === 1) return 'Yesterday';
            const opts = diffDays < 365
                ? { month: 'long', day: 'numeric' }
                : { month: 'long', day: 'numeric', year: 'numeric' };
            return d.toLocaleDateString(undefined, opts);
        },
        isDatePassed(msg) {
            if (!msg.created_at) return false;
            const d = new Date(msg.created_at);
            const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            const msgDate = new Date(d.getFullYear(), d.getMonth(), d.getDate());
            return msgDate.getTime() < today.getTime();
        },

        /* ── Datepicker & Search by Date helpers ── */
        openDatePicker() {
            this.pickerYear = new Date().getFullYear();
            this.pickerMonth = new Date().getMonth();
            this.generatePickerDays();
            this.showDatePickerModal = true;
        },

        generatePickerDays() {
            const year = this.pickerYear;
            const month = this.pickerMonth;
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const firstDay = new Date(year, month, 1).getDay();
            
            const arr = [];
            for (let i = 0; i < firstDay; i++) {
                arr.push({ day: null, hasMessages: false, isToday: false, dateString: '' });
            }
            
            const now = new Date();
            const todayY = now.getFullYear();
            const todayM = now.getMonth();
            const todayD = now.getDate();
            const today = new Date(todayY, todayM, todayD);
            
            for (let d = 1; d <= daysInMonth; d++) {
                const isToday = (year === todayY && month === todayM && d === todayD);
                const cellDate = new Date(year, month, d);
                const isPassed = cellDate.getTime() < today.getTime();
                const hasMessages = this.hasMessagesOnDate(year, month, d);
                const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                
                arr.push({
                    day: d,
                    hasMessages,
                    isToday,
                    isPassed,
                    dateString
                });
            }
            this.pickerDays = arr;
        },

        hasMessagesOnDate(year, month, day) {
            return this.messages.some(m => {
                if (!m.created_at) return false;
                const d = new Date(m.created_at);
                return d.getFullYear() === year && d.getMonth() === month && d.getDate() === day;
            });
        },

        prevMonth() {
            if (this.pickerMonth === 0) {
                this.pickerMonth = 11;
                this.pickerYear--;
            } else {
                this.pickerMonth--;
            }
            this.generatePickerDays();
        },

        nextMonth() {
            if (this.pickerMonth === 11) {
                this.pickerMonth = 0;
                this.pickerYear++;
            } else {
                this.pickerMonth++;
            }
            this.generatePickerDays();
        },

        getPickerMonthName() {
            const months = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            return months[this.pickerMonth];
        },

        async scrollToDate(dateString) {
            if (!dateString) return;
            const parts = dateString.split('-');
            const targetY = parseInt(parts[0]);
            const targetM = parseInt(parts[1]) - 1;
            const targetD = parseInt(parts[2]);

            // Helper function to find message index in the current array
            const findMsgIndex = (arr) => {
                for (let i = 0; i < arr.length; i++) {
                    const msg = arr[i];
                    if (!msg.created_at) continue;
                    const msgDate = new Date(msg.created_at);
                    const msgY = msgDate.getFullYear();
                    const msgM = msgDate.getMonth();
                    const msgD = msgDate.getDate();
                    
                    const msgCompare = new Date(msgY, msgM, msgD).getTime();
                    const targetCompare = new Date(targetY, targetM, targetD).getTime();
                    
                    if (msgCompare >= targetCompare) {
                        return i;
                    }
                }
                return -1;
            };

            let foundIndex = findMsgIndex(this.messages);

            // If not found in currently loaded list, let's fetch all messages from the database
            if (foundIndex === -1 && this.allMessagesRoute) {
                try {
                    const url = this.allMessagesRoute.replace(':id', this.conversationId ?? 0);
                    const response = await fetch(url);
                    const resData = await response.json();
                    
                    if (resData.success && resData.messages) {
                        // Normalize incoming messages
                        this.messages = resData.messages.map(m => this.normalizeEvent(m));
                        // Re-format times
                        this.refreshTimes();
                        
                        // Find index again in the complete list
                        foundIndex = findMsgIndex(this.messages);
                    }
                } catch (err) {
                    console.error('[Chat] Failed to fetch historical messages:', err);
                }
            }

            if (foundIndex !== -1) {
                const msg = this.messages[foundIndex];
                const msgIdentifier = msg.id ?? foundIndex;
                this.highlightedMessageId = msg.id;
                
                this.$nextTick(() => {
                    const el = document.getElementById(`msg-container-${msgIdentifier}`);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        setTimeout(() => {
                            if (this.highlightedMessageId === msg.id) {
                                this.highlightedMessageId = null;
                            }
                        }, 2500);
                    }
                });
            } else {
                alert("No messages found on or after this date.");
            }
        },

        /* ── Init ──────────────────────────────────── */
        init() {
            const root = document.getElementById('chat-root');
            const raw  = root.dataset.messages ? JSON.parse(root.dataset.messages) : [];
            this.messages        = raw.reverse();
            this.conversationId  = root.dataset.conversationId ? JSON.parse(root.dataset.conversationId) : null;
            window.currentConversationId = this.conversationId;
            this.authId          = root.dataset.authId ? JSON.parse(root.dataset.authId) : null;
            this.csrfToken       = root.dataset.csrf;
            this.sendRoute       = root.dataset.sendRoute;
            this.allMessagesRoute = root.dataset.allMessagesRoute;

            window.addEventListener('online-users-updated', (e) => {
                this.onlineUsers = e.detail;
            });

            /* Compute initial formatted_times */
            this.refreshTimes();
            setInterval(() => this.refreshTimes(), 60000);

            /* Real-time */
            this.connectEcho();

            /* Scroll to bottom */
            if (this.conversationId) this.scrollBottom();

            /* Sidebar search filter */
            document.getElementById('sidebar-search')?.addEventListener('input', (e) => {
                const q = e.target.value.toLowerCase().trim();
                document.querySelectorAll('#conv-list .conv-link').forEach(el => {
                    el.style.display = !q || el.textContent.toLowerCase().includes(q) ? '' : 'none';
                });
            });
        },

        /* ── Timestamp helper ──────────────────────── */
        refreshTimes() {
            const now = Date.now();
            this.messages.forEach(m => {
                if (!m.created_at) return;
                const diff = Math.floor((now - new Date(m.created_at)) / 1000);
                if      (diff < 60)    m.formatted_time = 'Just now';
                else if (diff < 3600)  m.formatted_time = Math.floor(diff / 60) + 'm ago';
                else if (diff < 86400) m.formatted_time = Math.floor(diff / 3600) + 'h ago';
                else                   m.formatted_time = Math.floor(diff / 86400) + 'd ago';
            });
        },

        /* ── Echo / WebSocket ──────────────────────── */
        connectEcho() {
            const echo = window.Echo;
            if (!echo || typeof echo.private !== 'function') {
                this._echoTries = (this._echoTries || 0) + 1;
                if (this._echoTries % 5 === 1) console.log('[Chat] Waiting for Echo…');
                setTimeout(() => this.connectEcho(), 1000);
                return;
            }
            this._echoTries      = 0;
            this.connectionStatus = 'connected';

            /* Active conversation channel */
            if (this.conversationId) {
                echo.private(`conversation.${this.conversationId}`)
                    .listen('.message.sent', (e) => {
                        if (this.seenIds.has(e.id)) return;
                        this.seenIds.add(e.id);
                        if (!this.messages.find(m => m.id === e.id)) {
                            this.messages.push(this.normalizeEvent(e));
                            this.scrollBottom();
                            this.updateSidebar(e, false);
                        }
                    });
            }

            /* Background / global user channel */
            echo.private(`App.Models.User.${this.authId}`)
                .listen('.message.sent', (e) => {
                    if (this.seenIds.has(e.id)) return;
                    if (parseInt(e.sender?.id) === parseInt(this.authId)) return;
                    if (e.conversation_id != this.conversationId) {
                        this.seenIds.add(e.id);
                        this.updateSidebar(e, true);
                    }
                });
        },

        /* Normalize an Echo event into a message object */
        normalizeEvent(e) {
            return {
                id:               e.id,
                message:          e.message,
                user_id:          e.user_id ?? (e.sender ? e.sender.id : null),
                is_system_message: e.is_system_message || false,
                formatted_time:   e.formatted_time || 'Just now',
                created_at:       e.created_at,
                sender:           e.sender,
                attachment_url:   e.attachment_url  || null,
                attachment_type:  e.attachment_type || null,
            };
        },

        /* ── Sidebar sync ──────────────────────────── */
        updateSidebar(e, isBackground) {
            const lastMsgEl = document.getElementById(`last-msg-${e.conversation_id}`);
            if (!lastMsgEl) return;

            lastMsgEl.textContent = e.message
                ? e.message
                : (e.attachment_url ? '📎 Attachment' : 'New message');

            /* Move to top of list */
            const convLink = lastMsgEl.closest('a');
            if (convLink?.parentElement && convLink.parentElement.firstElementChild !== convLink) {
                convLink.parentElement.prepend(convLink);
            }

            if (isBackground) {
                lastMsgEl.classList.add('font-semibold', 'text-slate-900');
                lastMsgEl.classList.remove('text-slate-500');

                /* Unread badge */
                let badge = convLink?.querySelector('span.bg-blue-600');
                if (badge) {
                    badge.textContent = (parseInt(badge.textContent) || 0) + 1;
                } else if (lastMsgEl.parentElement) {
                    const b = document.createElement('span');
                    b.className = 'shrink-0 min-w-[18px] h-[18px] px-1 bg-blue-600 rounded-full text-[10px] font-bold text-white flex items-center justify-center';
                    b.textContent = '1';
                    lastMsgEl.parentElement.appendChild(b);
                }
            }
        },

        /* ── File handling ─────────────────────────── */
        handleFile(event) {
            const file = event.target.files[0];
            if (!file) return;
            if (file.size > 5 * 1024 * 1024) {
                alert('File is too large. Maximum allowed size is 5 MB.');
                event.target.value = '';
                return;
            }
            this.attachmentFile      = file;
            this.attachmentName      = file.name;
            this.attachmentSizeLabel = (file.size / 1024).toFixed(1) + ' KB';
            this.attachmentIsImage   = file.type.startsWith('image/');

            if (this.attachmentIsImage) {
                const reader = new FileReader();
                reader.onload = (ev) => { this.attachmentPreview = ev.target.result; };
                reader.readAsDataURL(file);
            } else {
                this.attachmentPreview = 'has-file'; /* non-null truthy sentinel */
            }
        },

        clearAttachment() {
            this.attachmentFile      = null;
            this.attachmentPreview   = null;
            this.attachmentIsImage   = false;
            this.attachmentName      = '';
            this.attachmentSizeLabel = '';
            if (this.$refs.fileInput) this.$refs.fileInput.value = '';
        },

        /* ── Textarea auto-grow ────────────────────── */
        growTA() {
            const ta = this.$refs.ta;
            if (!ta) return;
            ta.style.height = 'auto';
            ta.style.height = Math.min(ta.scrollHeight, 112) + 'px';
        },

        /* ── Send message ──────────────────────────── */
        async sendMessage() {
            if (!this.canSend()) return;

            const formData = new FormData();
            formData.append('message', this.draft);
            if (this.attachmentFile) formData.append('attachment', this.attachmentFile);

            const savedDraft = this.draft;
            this.draft   = '';
            this.sending = true;

            /* Reset textarea height */
            if (this.$refs.ta) {
                this.$refs.ta.style.height = 'auto';
            }

            try {
                const url      = this.sendRoute.replace(':id', this.conversationId ?? 0);
                const response = await fetch(url, {
                    method:  'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept':       'application/json',
                        'X-Socket-ID':  window.Echo?.socketId?.() ?? '',
                    },
                    body: formData,
                });

                const data = await response.json();

                if (data.success) {
                    this.clearAttachment();
                    if (!this.messages.find(m => m.id === data.message.id)) {
                        this.messages.push({
                            id:              data.message.id,
                            message:         data.message.message,
                            user_id:         this.authId,
                            is_system_message: false,
                            created_at:      data.message.created_at,
                            formatted_time:  data.message.formatted_time || 'Just now',
                            sender:          data.message.sender ?? null,
                            attachment_url:  data.message.attachment_url  ?? null,
                            attachment_type: data.message.attachment_type ?? null,
                        });
                        this.scrollBottom();
                        this.updateSidebar({ conversation_id: this.conversationId, message: savedDraft }, false);
                    }
                } else {
                    alert(data.error || 'Failed to send the message. Please try again.');
                    this.draft = savedDraft;
                }
            } catch (err) {
                console.error('[Chat] Send error:', err);
                this.draft = savedDraft;
            } finally {
                this.sending = false;
                this.$nextTick(() => this.$refs.ta?.focus());
            }
        },

        /* ── Scroll ────────────────────────────────── */
        scrollBottom() {
            this.$nextTick(() => {
                document.getElementById('msg-end')?.scrollIntoView({ behavior: 'smooth', block: 'end' });
            });
        },
    };
}

// AJAX dynamic conversation loading to prevent full page reloads
document.addEventListener('DOMContentLoaded', () => {
    // Guard: ensure we only attach this once globally
    if (window.chatAjaxLoaded) return;
    window.chatAjaxLoaded = true;

    document.addEventListener('click', async (e) => {
        const link = e.target.closest('.conv-link');
        if (!link) return;
        
        e.preventDefault();
        const url = link.getAttribute('href');
        if (!url) return;
        
        const mainEl = document.querySelector('#chat-root main');
        if (mainEl) mainEl.style.opacity = '0.5';
        
        try {
            const response = await fetch(url);
            const htmlText = await response.text();
            
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlText, 'text/html');
            const newChatRoot = doc.getElementById('chat-root');
            
            if (newChatRoot) {
                // Leave active Echo channel
                if (window.Echo && window.currentConversationId) {
                    window.Echo.leave(`conversation.${window.currentConversationId}`);
                }
                
                const oldChatRoot = document.getElementById('chat-root');
                if (oldChatRoot) {
                    oldChatRoot.replaceWith(newChatRoot);
                    window.history.pushState(null, '', url);
                }
            }
        } catch (err) {
            console.error('[Chat] Dynamic load failed, falling back to full reload:', err);
            window.location.href = url;
        }
    });

    window.addEventListener('popstate', () => {
        window.location.reload();
    });
});
</script>
@endsection