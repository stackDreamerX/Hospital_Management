@extends('layout')

@section('title', 'AI Chat - Medic Hospital')

@section('content')
<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-robot me-2"></i> Trò chuyện với AI
                    </h5>
                    <button id="clearChat" class="btn btn-sm btn-light">
                        <i class="fas fa-trash"></i> Xóa trò chuyện
                    </button>
                </div>
                <div class="card-body">
                    <div id="chatContainer" class="mb-3" style="height: 400px; overflow-y: auto;">
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('images/IconChatbot.png') }}" alt="AI" class="rounded-circle" width="40" height="40">
                            </div>
                            <div class="ms-3 p-3 bg-light rounded">
                                <div class="mb-1"><strong>AI Assistant</strong></div>
                                <div>Xin chào! Tôi là trợ lý AI của Medic Hospital. Tôi có thể giúp gì cho bạn hôm nay?</div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <input type="text" id="userMessage" class="form-control" placeholder="Nhập tin nhắn của bạn..." aria-label="Tin nhắn">
                        <button id="sendMessage" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Gửi
                        </button>
                    </div>
                </div>
                <div class="card-footer text-muted small">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-info-circle me-1"></i> Trợ lý AI cung cấp thông tin sức khỏe chung và không thay thế ý kiến bác sĩ
                        </div>
                        <div>
                            <span class="badge bg-primary">Powered by Google Gemini</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userMessage = document.getElementById('userMessage');
        const sendButton = document.getElementById('sendMessage');
        const chatContainer = document.getElementById('chatContainer');
        const clearChatButton = document.getElementById('clearChat');

        let sessionId = localStorage.getItem('ai_chat_session_id') || crypto.randomUUID();
        localStorage.setItem('ai_chat_session_id', sessionId);

        // Thêm event listener cho phím Enter
        userMessage.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        // Event listener cho nút gửi
        sendButton.addEventListener('click', sendMessage);

        // Event listener cho nút xóa trò chuyện
        clearChatButton.addEventListener('click', clearChat);

        function sendMessage() {
            const message = userMessage.value.trim();
            if (!message) return;

            // Hiển thị tin nhắn của người dùng
            displayMessage(message, 'user');

            // Xóa input
            userMessage.value = '';

            // Hiển thị đang nhập
            displayTypingIndicator();

            // Gửi tin nhắn đến server
            fetch('{{ route("chat.send-message") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message: message,
                    session_id: sessionId
                })
            })
            .then(response => response.json())
            .then(data => {
                // Xóa đang nhập
                removeTypingIndicator();

                // Hiển thị phản hồi từ AI
                displayMessage(data.message, 'ai');

                // Cuộn xuống tin nhắn mới nhất
                scrollToBottom();
            })
            .catch(error => {
                // Xóa đang nhập
                removeTypingIndicator();

                // Hiển thị lỗi
                displayMessage('Có lỗi xảy ra khi xử lý yêu cầu. Vui lòng thử lại.', 'ai', true);
                console.error('Error:', error);

                // Cuộn xuống tin nhắn mới nhất
                scrollToBottom();
            });
        }

        function clearChat() {
            // Xác nhận xóa
            if (!confirm('Bạn có chắc chắn muốn xóa toàn bộ cuộc trò chuyện?')) {
                return;
            }

            fetch('{{ route("chat.clear") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    session_id: sessionId
                })
            })
            .then(response => response.json())
            .then(data => {
                // Xóa tất cả tin nhắn
                chatContainer.innerHTML = '';

                // Hiển thị tin nhắn chào mừng mới
                displayMessage('Xin chào! Tôi là trợ lý AI của Medic Hospital. Tôi có thể giúp gì cho bạn hôm nay?', 'ai');

                // Tạo session ID mới
                sessionId = crypto.randomUUID();
                localStorage.setItem('ai_chat_session_id', sessionId);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function displayMessage(message, sender, isError = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'd-flex mb-3';

            if (sender === 'user') {
                messageDiv.innerHTML = `
                    <div class="ms-auto p-3 bg-primary text-white rounded" style="max-width: 80%;">
                        <div class="mb-1"><strong>Bạn</strong></div>
                        <div>${message}</div>
                    </div>
                `;
            } else {
                const bgColorClass = isError ? 'bg-danger text-white' : 'bg-light';
                messageDiv.innerHTML = `
                    <div class="flex-shrink-0">
                        <img src="{{ asset('images/IconChatbot.png') }}" alt="AI" class="rounded-circle" width="40" height="40">
                    </div>
                    <div class="ms-3 p-3 ${bgColorClass} rounded" style="max-width: 80%;">
                        <div class="mb-1"><strong>AI Assistant</strong></div>
                        <div>${formatMessage(message)}</div>
                    </div>
                `;
            }

            // Kiểm tra nếu có typing indicator, thêm trước nó
            const typingIndicator = document.getElementById('typingIndicator');
            if (typingIndicator) {
                chatContainer.insertBefore(messageDiv, typingIndicator);
            } else {
                chatContainer.appendChild(messageDiv);
            }

            scrollToBottom();
        }

        function formatMessage(message) {
            // Chuyển đổi URL thành liên kết
            const urlRegex = /(https?:\/\/[^\s]+)/g;
            message = message.replace(urlRegex, url => `<a href="${url}" target="_blank">${url}</a>`);

            // Chuyển đổi xuống dòng thành thẻ <br>
            message = message.replace(/\n/g, '<br>');

            return message;
        }

        function displayTypingIndicator() {
            const indicatorDiv = document.createElement('div');
            indicatorDiv.id = 'typingIndicator';
            indicatorDiv.className = 'd-flex mb-3';
            indicatorDiv.innerHTML = `
                <div class="flex-shrink-0">
                    <img src="{{ asset('images/IconChatbot.png') }}" alt="AI" class="rounded-circle" width="40" height="40">
                </div>
                <div class="ms-3 p-3 bg-light rounded">
                    <div class="mb-1"><strong>AI Assistant</strong></div>
                    <div class="typing-indicator">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            `;

            chatContainer.appendChild(indicatorDiv);
            scrollToBottom();
        }

        function removeTypingIndicator() {
            const typingIndicator = document.getElementById('typingIndicator');
            if (typingIndicator) {
                typingIndicator.remove();
            }
        }

        function scrollToBottom() {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });
</script>

<style>
    /* Typing indicator animation */
    .typing-indicator {
        display: flex;
        gap: 5px;
    }

    .typing-indicator span {
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #333;
        border-radius: 50%;
        opacity: 0.4;
        animation: typing 1.4s infinite both;
    }

    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {
        0% {
            opacity: 0.4;
            transform: translateY(0);
        }
        50% {
            opacity: 1;
            transform: translateY(-5px);
        }
        100% {
            opacity: 0.4;
            transform: translateY(0);
        }
    }
</style>
@endsection