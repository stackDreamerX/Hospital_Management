<template>
  <div class="chatbot-container" :class="{ 'chatbot-minimized': !isChatOpen }">
    <!-- Chatbot header -->
    <div class="chatbot-header" @click="toggleChat">
      <div v-if="isChatOpen" class="chatbot-title">
        <img src="/images/IconChatbot.png" alt="Medic Hospital" class="chatbot-logo">
        <span>Trợ lý Medic</span>
      </div>
      <div v-else class="chatbot-icon-container">
        <img src="/images/IconChatbot.png" alt="Medic Hospital" class="chatbot-icon">
      </div>
      <button v-if="isChatOpen" class="chatbot-minimize-btn">
        <i class="fas fa-minus"></i>
      </button>
    </div>

    <!-- Chatbot body -->
    <div v-if="isChatOpen" class="chatbot-body">
      <div class="chatbot-messages" ref="messagesContainer">
        <!-- Welcome message -->
        <div class="message bot-message">
          <div class="message-content">
            <p>Xin chào! Tôi là trợ lý ảo của Medic Hospital. Tôi có thể giúp gì cho bạn?</p>
            <span class="message-time">{{ formatTime(new Date()) }}</span>
          </div>
        </div>

        <!-- Messages -->
        <div v-for="(message, index) in messages" :key="index"
             :class="['message', message.sender === 'user' ? 'user-message' : 'bot-message']">
          <div class="message-content">
            <p v-html="formatMessage(message.text)"></p>
            <span class="message-time">{{ formatTime(message.time) }}</span>
          </div>
        </div>

        <!-- Loading indicator -->
        <div v-if="isLoading" class="message bot-message">
          <div class="message-content typing-indicator">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </div>

      <!-- Input area -->
      <div class="chatbot-input">
        <textarea
          v-model="userInput"
          @keyup.enter.prevent="sendMessage"
          placeholder="Nhập câu hỏi của bạn..."
          rows="1"
          ref="inputField"
        ></textarea>
        <button @click="sendMessage" :disabled="!userInput.trim() || isLoading">
          <i class="fas fa-paper-plane"></i>
        </button>
      </div>

      <!-- Footer -->
      <div class="chatbot-footer">
        <button @click="clearConversation" class="clear-btn">
          <i class="fas fa-trash-alt"></i> Xóa hội thoại
        </button>
        <span class="powered-by">Medic Hospital AI</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';

const isChatOpen = ref(false);
const messages = ref([]);
const userInput = ref('');
const isLoading = ref(false);
const sessionId = ref('');
const messagesContainer = ref(null);
const inputField = ref(null);

onMounted(() => {
  // Tạo sessionId ngẫu nhiên nếu người dùng chưa có
  sessionId.value = localStorage.getItem('chatbot_session_id') || generateSessionId();
  localStorage.setItem('chatbot_session_id', sessionId.value);

  // Mở chatbot tự động sau 3 giây (có thể bỏ dòng này nếu không muốn)
  setTimeout(() => {
    isChatOpen.value = true;
  }, 3000);
});

function toggleChat() {
  isChatOpen.value = !isChatOpen.value;
  if (isChatOpen.value) {
    nextTick(() => {
      inputField.value.focus();
      scrollToBottom();
    });
  }
}

function sendMessage() {
  const message = userInput.value.trim();
  if (!message || isLoading.value) return;

  // Thêm tin nhắn của người dùng vào danh sách
  addMessage(message, 'user');
  userInput.value = '';

  // Hiển thị đang tải
  isLoading.value = true;

  // Gửi tin nhắn tới server
  axios.post('/chatbot/message', {
    message: message,
    session_id: sessionId.value
  })
  .then(response => {
    // Thêm phản hồi từ bot
    addMessage(response.data.message, 'bot');
  })
  .catch(error => {
    console.error('Chatbot error:', error);
    addMessage('Có lỗi xảy ra. Vui lòng thử lại sau hoặc liên hệ bác sĩ để được tư vấn.', 'bot');
  })
  .finally(() => {
    isLoading.value = false;
    nextTick(() => {
      scrollToBottom();
      inputField.value.focus();
    });
  });
}

function addMessage(text, sender) {
  messages.value.push({
    text: text,
    sender: sender,
    time: new Date()
  });

  nextTick(() => {
    scrollToBottom();
  });
}

function scrollToBottom() {
  if (messagesContainer.value) {
    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
  }
}

function formatTime(date) {
  return new Date(date).toLocaleTimeString('vi-VN', {
    hour: '2-digit',
    minute: '2-digit'
  });
}

function formatMessage(text) {
  // Xử lý định dạng tin nhắn (URL, emojis, etc)
  // Chuyển đổi URL thành liên kết
  return text.replace(
    /(https?:\/\/[^\s]+)/g,
    '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>'
  );
}

function clearConversation() {
  // Xác nhận trước khi xóa
  if (confirm('Bạn có chắc muốn xóa toàn bộ hội thoại này không?')) {
    axios.post('/chatbot/clear-conversation', {
      session_id: sessionId.value
    })
    .then(() => {
      messages.value = [];
      addMessage('Hội thoại đã được xóa. Tôi có thể giúp gì cho bạn?', 'bot');
    })
    .catch(error => {
      console.error('Error clearing conversation:', error);
    });
  }
}

function generateSessionId() {
  // Tạo ID ngẫu nhiên
  return 'session_' + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
}
</script>

<style scoped>
.chatbot-container {
  position: fixed;
  bottom: 20px;
  right: 20px;
  width: 360px;
  max-width: calc(100vw - 40px);
  height: 500px;
  max-height: calc(100vh - 100px);
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  z-index: 9999;
  overflow: hidden;
  transition: all 0.3s ease;
}

.chatbot-minimized {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  background: transparent;
  box-shadow: none;
}

.chatbot-header {
  background: #3a7bd5;
  background: linear-gradient(to right, #3a6073, #3a7bd5);
  color: white;
  padding: 15px 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  cursor: pointer;
}

.chatbot-minimized .chatbot-header {
  background: transparent;
  padding: 0;
  width: 100%;
  height: 100%;
}

.chatbot-title {
  display: flex;
  align-items: center;
}

.chatbot-logo {
  width: 28px;
  height: 28px;
  margin-right: 10px;
  border-radius: 50%;
}

.chatbot-icon-container {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.chatbot-icon {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  object-fit: cover;
  box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
}

.chatbot-body {
  flex: 1;
  display: flex;
  flex-direction: column;
  height: calc(100% - 60px);
}

.chatbot-messages {
  flex: 1;
  padding: 15px;
  overflow-y: auto;
  scroll-behavior: smooth;
}

.message {
  margin-bottom: 10px;
  display: flex;
}

.bot-message {
  justify-content: flex-start;
}

.user-message {
  justify-content: flex-end;
}

.message-content {
  max-width: 80%;
  padding: 10px 15px;
  border-radius: 15px;
  position: relative;
}

.bot-message .message-content {
  background-color: #f1f1f1;
  color: #333;
  border-bottom-left-radius: 5px;
}

.user-message .message-content {
  background-color: #3a7bd5;
  color: white;
  border-bottom-right-radius: 5px;
}

.message-time {
  display: block;
  font-size: 10px;
  margin-top: 5px;
  opacity: 0.7;
}

.chatbot-input {
  background: #f8f8f8;
  border-top: 1px solid #e0e0e0;
  padding: 10px 15px;
  display: flex;
  align-items: center;
}

.chatbot-input textarea {
  flex: 1;
  border: none;
  border-radius: 20px;
  padding: 10px 15px;
  outline: none;
  resize: none;
  max-height: 100px;
  background: white;
}

.chatbot-input button {
  background: #3a7bd5;
  color: white;
  border: none;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  margin-left: 10px;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.chatbot-input button:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.chatbot-footer {
  background: #f8f8f8;
  padding: 10px 15px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-top: 1px solid #eee;
  font-size: 12px;
}

.clear-btn {
  background: none;
  border: none;
  color: #666;
  cursor: pointer;
  font-size: 12px;
  padding: 5px 10px;
  border-radius: 5px;
}

.clear-btn:hover {
  background: #eee;
}

.powered-by {
  color: #777;
}

/* Typing indicator */
.typing-indicator {
  display: flex;
  align-items: center;
}

.typing-indicator span {
  width: 8px;
  height: 8px;
  margin: 0 2px;
  background-color: #888;
  border-radius: 50%;
  display: inline-block;
  animation: bouncingDots 1.4s infinite ease-in-out;
}

.typing-indicator span:nth-child(1) {
  animation-delay: 0s;
}

.typing-indicator span:nth-child(2) {
  animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
  animation-delay: 0.4s;
}

@keyframes bouncingDots {
  0%, 80%, 100% {
    transform: scale(0);
    opacity: 0.5;
  }
  40% {
    transform: scale(1);
    opacity: 1;
  }
}

/* Responsive */
@media (max-width: 480px) {
  .chatbot-container {
    bottom: 10px;
    right: 10px;
    width: calc(100vw - 20px);
    height: 60vh;
    border-radius: 15px;
  }

  .chatbot-minimized {
    width: 60px;
    height: 60px;
  }
}
</style>