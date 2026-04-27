@extends('layouts.app')
@section('title', $pagesDetail->page_meta_title ?? DEFAULT_META_TITLE)
@section('keywords', $pagesDetail->page_meta_keyword ?? DEFAULT_META_KEYWORD)
@section('description', $pagesDetail->page_meta_desc ?? DEFAULT_META_DESCRIPTION)
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')
@section('content')
    <!-- Breadcrumb -->
    @if($pagesDetail->page_image!='' && file_exists(public_path('/uploads/pages/'.$pagesDetail->page_image)))
        @php
            $pageBanner = asset('/uploads/pages/'.$pagesDetail->page_image);
        @endphp
    @else
        @php
            $pageBanner = 'image/innerbanner.jpg';
        @endphp
    @endif
    <div class="breadcrumb breadcrumb-list mb-0" style="background-image: url({{ $pageBanner }});">
        <div class="container">
            <h1 class="text-white">{{ $pagesDetail->page_title ?? 'Chat' }}</h1>
            <ul>
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>{{ $pagesDetail->page_title ?? 'Chat' }}</li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <!-- Dashboard Menu -->
    <div class="dashboard-section coach-dash-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="dashboard-menu coaurt-menu-dash text-center">
                        <ul>
                            <li>
                                <a href="{{ url('/my-account') }}" class="d-inline-flex align-items-center justify-content-center" style="min-width: auto; padding: 15px 25px;">
                                    <img src="{{ asset('image/profile-icon.svg') }}') }}" alt="Profile Setting" style="margin-bottom: 0; margin-right: 10px; width: 20px;">
                                    <span style="display: inline-block; font-weight: 600;">Profile Setting</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/seller-inquiry') }}" class="d-inline-flex align-items-center justify-content-center" style="min-width: auto; padding: 15px 25px;">
                                    <img src="{{ asset('image/u_plus-square.svg') }}') }}" alt="Seller Inquiries" style="margin-bottom: 0; margin-right: 10px; width: 22px;">
                                    <span style="display: inline-block; font-weight: 600;">Sell Your Machine</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/my-listing') }}" class="d-inline-flex align-items-center justify-content-center" style="min-width: auto; padding: 15px 25px;">
                                    <i class="feather-list" style="margin-right: 10px; font-size: 20px;"></i>
                                    <span style="display: inline-block; font-weight: 600;">My Machines</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/chat') }}" class="active d-inline-flex align-items-center justify-content-center" style="min-width: auto; padding: 15px 25px;">
                                    <i class="feather-message-square" style="margin-right: 10px; font-size: 20px;"></i>
                                    <span style="display: inline-block; font-weight: 600;">Messages</span>
                                    @php
                                        $unreadDashboard = \App\Models\Chat::where('receiver_id', Session::get('customer_id'))->where('is_read', false)->count();
                                    @endphp
                                    @if($unreadDashboard > 0)
                                        <span class="badge badge-danger rounded-circle ms-2" style="background: #ff4d4d; font-size: 10px; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center;">{{ $unreadDashboard }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="javascript: void (0)" onclick="return logout()" class="d-inline-flex align-items-center justify-content-center" style="min-width: auto; padding: 15px 25px;">
                                    <img src="{{ asset('image/wallet-icon.svg') }}') }}" alt="Logout" style="margin-bottom: 0; margin-right: 10px; width: 20px;">
                                    <span style="display: inline-block; font-weight: 600;">Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Dashboard Menu -->

    <!-- Page Content -->
    <div class="content court-bg">
        <div class="container">
            <div class="row">
            <div class="col-md-12">
                <div class="chat-window" style="display: flex; background: #fff; border: 1px solid #f0f0f0; border-radius: 12px; overflow: hidden; height: 700px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);">
                
                    <!-- Chat Left -->
                    <div class="chat-cont-left" style="width: 320px; border-right: 1px solid #f0f0f0; display: flex; flex-direction: column;">
                        <div class="chat-search" style="padding: 20px; border-bottom: 1px solid #f0f0f0;">
                            <div class="form-custom">
                                <input type="text" id="contact_search" class="form-control" placeholder="Search contacts...">
                            </div>
                        </div>
                        <div class="chat-users-list" style="flex: 1; overflow-y: auto;">
                            <h3 style="padding: 15px 20px; margin: 0; font-size: 16px; font-weight: 800; color: #102a3a; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #f0f0f0;">Contacts</h3>
                            <div class="chat-scroll">
                                @forelse($contacts as $otherId => $data)
                                    @php
                                        $otherUser = $data['user'];
                                        $encryptedId = \Crypt::encrypt($otherId);
                                    @endphp
                                    <a href="{{ url('chat') }}?receiver_id={{ $encryptedId }}" class="media conversation-item" data-other-id="{{ $otherId }}" style="display: flex; padding: 15px 20px; border-bottom: 1px solid #f0f0f0; text-decoration: none; color: inherit; transition: all 0.2s ease;">
                                        <div class="media-img-wrap" style="position: relative; margin-right: 15px;">
                                            @php
                                                $lastMsgAt = $data['last_message_at'] ? \Carbon\Carbon::parse($data['last_message_at']) : null;
                                                $isRecent = $lastMsgAt && $lastMsgAt->diffInMinutes() < 1;
                                                $statusColor = $isRecent ? '#39a68d' : '#999';
                                            @endphp
                                            <div class="avatar">
                                                <img src="{{ $data['user_image'] }}" alt="User" class="avatar-img rounded-circle" style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                                                <span class="status-dot-sidebar" id="status_dot_{{ $otherId }}" data-last-at="{{ $data['last_message_at'] }}" style="position: absolute; bottom: 0; right: 0; width: 12px; height: 12px; border-radius: 50%; background: {{ $statusColor }}; border: 2px solid #fff;"></span>
                                            </div>
                                        </div>
                                        <div class="media-body" style="flex: 1; overflow: hidden;">
                                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2px;">
                                                <div class="user-name" style="font-weight: 700; color: #102a3a; font-size: 15px;">{{ $otherUser->customer_name }}</div>
                                                <div class="last-chat-time" style="font-size: 11px; color: #999;">{{ $data['last_message_at'] ? \Carbon\Carbon::parse($data['last_message_at'])->diffForHumans(null, true) : '' }}</div>
                                            </div>
                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <div class="user-last-chat" style="font-size: 13px; color: #7a8a9a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px;">{{ $data['last_message'] ?: 'Start a conversation' }}</div>
                                                @if($data['unread_count'] > 0)
                                                    <div class="badge badge-success badge-pill unread-badge" style="background: #39a68d; font-size: 10px; padding: 4px 8px;">{{ $data['unread_count'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-4 text-center text-muted">No conversations found.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <!-- /Chat Left -->
                
                    <!-- Chat Right -->
                    <div class="chat-cont-right" id="chat_window_right" style="display: none; flex: 1; flex-direction: column; background: #fff;">
                        <div class="chat-header" style="padding: 15px 25px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; justify-content: space-between; background: #fff;">
                            <div class="media" style="display: flex; align-items: center;">
                                <a id="back_user_list" href="javascript:void(0)" class="back-user-list" style="margin-right: 15px; display: none;">
                                    <i class="feather-chevrons-left" style="font-size: 20px;"></i>
                                </a>
                                <div class="media-img-wrap" style="position: relative; margin-right: 12px;">
                                    <div class="avatar avatar-online">
                                        <img src="" id="receiver_image" alt="User" class="avatar-img rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        <span id="receiver_status_dot" class="green-active" style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; border-radius: 50%; background: #39a68d; border: 2px solid #fff;"></span>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <div class="user-name" id="receiver_name" style="font-weight: 800; color: #102a3a; font-size: 16px;"></div>
                                    <div id="receiver_status_text" style="font-size: 12px; color: #39a68d;">Online</div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-body" id="chat_body_scroll" style="flex: 1; overflow-y: auto; padding: 30px; background: #fdfdfd; background-opacity: 0.05;">
                            <ul class="list-unstyled" id="chat_messages_list">
                                <!-- Messages will be loaded here via JS -->
                            </ul>
                        </div>
                        <div class="chat-footer" style="padding: 20px 25px; border-top: 1px solid #f0f0f0; background: #fff;">
                            <form id="chat_form">
                                <div class="form-custom" style="display: flex; align-items: center; background: #f5f7f9; border-radius: 30px; padding: 5px 20px;">
                                    <input type="text" id="chat_input" class="form-control" placeholder="Type your message here..." style="flex: 1; border: none !important; background: transparent !important; padding: 10px 0; box-shadow: none !important;">
                                    <button type="submit" class="btn msg-send-btn" style="background: linear-gradient(135deg, #0d6e7a 0%, #39a68d 100%); color: #fff; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-left: 10px; border: none;">
                                        <i class="feather-send"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="chat-cont-right" id="chat_window_empty" style="flex: 1; display: flex; align-items: center; justify-content: center; background: #fff;">
                         <div class="text-center" style="max-width: 300px;">
                             <div style="width: 100px; height: 100px; background: rgba(57, 166, 141, 0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                                 <i class="feather-message-square" style="font-size: 40px; color: #39a68d;"></i>
                             </div>
                             <h4 style="font-weight: 800; color: #102a3a; margin-bottom: 10px;">Select a Conversation</h4>
                             <p style="color: #7a8a9a; line-height: 1.6;">Click on a contact from the list to view your chat history and start messaging.</p>
                         </div>
                    </div>
                    <!-- /Chat Right -->
                    
                </div>
            </div>		
        </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection

@section('page-js')
<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
<script>
$(document).ready(function() {
    let currentOtherId = null;
    let currentUserId = "{{ Session::get('customer_id') }}";
    let currentProductId = new URLSearchParams(window.location.search).get('product_id');
    let offlineTimer;

    function refreshStatus(status = 'Online', timestamp = null) {
        if (!currentOtherId) return;

        // If timestamp provided, calculate if it should be offline
        if (timestamp) {
            let diff = (new Date() - new Date(timestamp)) / 1000;
            if (diff > 60) status = 'Offline';
        }
        
        if (status === 'Online') {
            $('#receiver_status_text').text('Online').css('color', '#39a68d');
            $('#receiver_status_dot').css('background', '#39a68d');
            
            clearTimeout(offlineTimer);
            offlineTimer = setTimeout(function() {
                $('#receiver_status_text').text('Offline').css('color', '#999');
                $('#receiver_status_dot').css('background', '#999');
            }, 60000);
        } else {
            $('#receiver_status_text').text('Offline').css('color', '#999');
            $('#receiver_status_dot').css('background', '#999');
        }
    }

    function updateSidebarStatus() {
        $('.status-dot-sidebar').each(function() {
            let otherId = $(this).attr('id').replace('status_dot_', '');
            let lastAt = $(this).attr('data-last-at');
            if (lastAt) {
                let diff = (new Date() - new Date(lastAt)) / 1000;
                let statusColor = (diff > 60) ? '#999' : '#39a68d';
                $(this).css('background', statusColor);
                
                // Also update main header if this is the active chat
                if (otherId == currentOtherId) {
                    if (diff > 60) {
                        $('#receiver_status_text').text('Offline').css('color', '#999');
                        $('#receiver_status_dot').css('background', '#999');
                    } else {
                        $('#receiver_status_text').text('Online').css('color', '#39a68d');
                        $('#receiver_status_dot').css('background', '#39a68d');
                    }
                }
            }
        });
    }

    // Run every 10 seconds to update sidebar dots
    setInterval(updateSidebarStatus, 10000);
    updateSidebarStatus();

    // Enable pusher logging
    Pusher.logToConsole = true;

    var pusher = new Pusher('3895b457e494ad4f2faa', {
      cluster: 'ap2'
    });

    pusher.connection.bind('connected', function() {
      console.log('Pusher connected!');
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
        console.log('Pusher message received', data);
        let msg = data.chat;
        
        // Reset timer if we are in this conversation
        if (msg.sender_id == currentOtherId || msg.receiver_id == currentOtherId) {
            refreshStatus('Online');
        }
        
        // 1. Update Sidebar always
        let otherPartyId = (msg.sender_id == currentUserId) ? msg.receiver_id : msg.sender_id;
        let convItem = $(`.conversation-item[data-other-id="${otherPartyId}"]`);
        
        if (convItem.length) {
            convItem.find('.user-last-chat').text(msg.message);
            convItem.find('.last-chat-time').text('just now');
            
            // Update last timestamp for status logic
            convItem.find('.status-dot-sidebar').attr('data-last-at', msg.created_at).css('background', '#39a68d');
            
            // Highlight if not current
            if (otherPartyId != currentOtherId && msg.sender_id != currentUserId) {
                if (convItem.find('.unread-badge').length == 0) {
                    convItem.find('.media-body > div:last-child').append('<div class="badge badge-success badge-pill unread-badge" style="background: #39a68d; font-size: 10px; padding: 4px 8px;">1</div>');
                } else {
                    let count = parseInt(convItem.find('.unread-badge').text());
                    convItem.find('.unread-badge').text(count + 1);
                }
            }
            $('.chat-scroll').prepend(convItem);
        } else {
            // New conversation handling would go here, maybe refresh list
        }

        // 2. Append to chat body if open and not self-sent (self-sent is handled by AJAX success)
        if (msg.sender_id == currentOtherId && msg.receiver_id == currentUserId) {
            appendMessage(msg, data.sender_image, false);
            
            // Mark as read immediately if chat is open
            $.ajax({
                url: "{{ url('chat') }}/" + currentOtherId,
                type: "GET",
                success: function() {} // Just to trigger read update on server
            });
        }
    });

    // Search functionality
    $('#contact_search').on('keyup', function() {
        let value = $(this).val().toLowerCase();
        $('.chat-scroll .conversation-item').filter(function() {
            $(this).toggle($(this).find('.user-name').text().toLowerCase().indexOf(value) > -1);
        });
    });

    @if(isset($autoOpenId))
        let autoOpenItem = $(`.conversation-item[data-other-id="{{ $autoOpenId }}"]`);
        if (autoOpenItem.length) {
            autoOpenItem.click();
        }
    @endif

    $('.conversation-item').on('click', function(e) {
        if ($(window).width() > 768) {
            e.preventDefault();
            $('.conversation-item').removeClass('active');
            $(this).addClass('active');

            currentOtherId = $(this).data('other-id');
            $(this).find('.unread-badge').remove();

            // Update URL without refreshing
            let url = $(this).attr('href');
            history.pushState(null, null, url);

            loadMessages(currentOtherId);
        }
    });

    function formatChatDate(dateStr) {
        const date = new Date(dateStr);
        const today = new Date();
        const yesterday = new Date();
        yesterday.setDate(today.getDate() - 1);

        if (date.toDateString() === today.toDateString()) {
            return 'Today';
        } else if (date.toDateString() === yesterday.toDateString()) {
            return 'Yesterday';
        } else {
            return date.toLocaleDateString([], { day: 'numeric', month: 'short', year: 'numeric' });
        }
    }

    function loadMessages(otherId) {
        $.ajax({
            url: "{{ url('chat') }}/" + otherId,
            type: "GET",
            success: function(response) {
                $('#chat_window_empty').hide();
                $('#chat_window_right').show();
                
                $('#receiver_name').text(response.receiver.customer_name);
                let imgPath = response.receiver.user_image;
                $('#receiver_image').attr('src', imgPath);

                let messagesHtml = '';
                let lastDate = '';
                let senderImg = response.sender_image;
                window.currentUserImg = senderImg;
                window.currentReceiverImg = imgPath;
                
                response.messages.forEach(function(msg) {
                    let isSent = msg.sender_id == currentUserId;
                    let msgDate = new Date(msg.created_at).toDateString();
                    
                    if (msgDate !== lastDate) {
                        messagesHtml += `<div class="chat-date-separator"><span>${formatChatDate(msg.created_at)}</span></div>`;
                        lastDate = msgDate;
                    }

                    messagesHtml += getMessageHtml(msg, isSent ? senderImg : imgPath, isSent);
                });
                $('#chat_messages_list').html(messagesHtml);
                scrollToBottom();

                // Set initial status based on last message in the response
                if (response.messages.length > 0) {
                    let lastMsg = response.messages[response.messages.length - 1];
                    refreshStatus('Online', lastMsg.created_at);
                } else {
                    refreshStatus('Offline');
                }
            }
        });
    }

    function getMessageHtml(msg, img, isSent) {
        let time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
        if (isSent) {
            return `
                <li class="media sent">
                    <div class="avatar">
                        <img src="${img}" alt="User" class="avatar-img rounded-circle">
                    </div>
                    <div class="media-body">
                        <div class="msg-box">
                            <div class="msg-text">${msg.message}</div>
                            <div class="msg-footer">
                                <span class="msg-time">${time}</span>
                                <span class="msg-status ${msg.is_read ? 'read' : ''}"><i class="fa-solid fa-check-double"></i></span>
                            </div>
                        </div>
                    </div>
                </li>`;
        } else {
            return `
                <li class="media received">
                    <div class="avatar">
                        <img src="${img}" alt="User" class="avatar-img rounded-circle">
                    </div>
                    <div class="media-body">
                        <div class="msg-box">
                            <div class="msg-text">${msg.message}</div>
                            <div class="msg-footer">
                                <span class="msg-time">${time}</span>
                            </div>
                        </div>
                    </div>
                </li>`;
        }
    }

    function appendMessage(msg, img, isSent) {
        let msgHtml = getMessageHtml(msg, img, isSent);
        $('#chat_messages_list').append(msgHtml);
        scrollToBottom();
    }

    $('#chat_form').submit(function(e) {
        e.preventDefault();
        let message = $('#chat_input').val();
        if (!message.trim() || !currentOtherId) return;

        $.ajax({
            url: "{{ route('chat.store') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                receiver_id: currentOtherId,
                product_id: currentProductId,
                message: message
            },
            success: function(response) {
                $('#chat_input').val('');
                appendMessage(response.chat, response.sender_image, true);
                refreshStatus('Online');
                
                // Update sidebar
                let convItem = $(`.conversation-item[data-other-id="${currentOtherId}"]`);
                if (convItem.length) {
                    convItem.find('.user-last-chat').text(response.chat.message);
                    convItem.find('.last-chat-time').text('just now');
                    convItem.find('.status-dot-sidebar').attr('data-last-at', response.chat.created_at).css('background', '#39a68d');
                    $('.chat-scroll').prepend(convItem);
                }
            }
        });
    });

    function scrollToBottom() {
        let container = $('#chat_body_scroll');
        if (container.length) {
            container.scrollTop(container[0].scrollHeight);
        }
    }
});
</script>
@endsection
