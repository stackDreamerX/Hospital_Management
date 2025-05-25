import { createApp } from 'vue';
import ChatbotComponent from './components/ChatbotComponent.vue';

// Add CSRF token to all Axios requests
import axios from 'axios';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Create and mount chatbot
document.addEventListener('DOMContentLoaded', () => {
    const app = createApp(ChatbotComponent);
    app.mount('#chatbot-app');
});