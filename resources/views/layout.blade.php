<!DOCTYPE html>
<html lang="en" dir="ltr"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Medic Hospital')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/chatbot.js'])

    @php
        use Illuminate\Support\Facades\Auth;
    @endphp

        <meta name="description" content="Medic Hospital, a non-profit academic medical center, provides clinical and hospital care and is a leader in research, education and health information.">
        <meta property="og:title" content="Access Anytime Anywhere | Medic Hospital">
        <meta property="twitter:title" content="Access Anytime Anywhere | Medic Hospital">
        <meta property="og:description" content="Medic Hospital">
        <meta property="twitter:description" content="Medic Hospital">
        <meta property="og:image" content="images/logo-ccf.png">
        <meta property="twitter:image" content="images/logo-ccf.png">
        <meta property="twitter:card" content="summary">
        <link rel="canonical" href="{{ route('users.dashboard') }}">
        <meta property="og:url" content="{{ route('users.dashboard') }}">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="Medic Hospital">
        <meta property="twitter:site" content="@MedicHospital">
        <meta property="twitter:creator" content="@MedicHospital">


        <link rel="alternate" href="{{ route('users.dashboard') }}" hreflang="x-default">

<!-- Add Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Add Bootstrap and FontAwesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="{{ asset('css/layout.css') }}">

<!-- Additional styles from child pages -->
@yield('styles')

<style>
    .avatar-circle {
        width: 32px;
        height: 32px;
        background-color: var(--primary-color);
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    .avatar-initials {
        font-size: 14px;
        line-height: 1;
        text-transform: uppercase;
    }
    .dropdown-item i {
        width: 20px;
        text-align: center;
    }
    .nav-pills .nav-link {
        padding-left: 10px;
        padding-right: 10px;
        font-size: 0.95rem;
        white-space: nowrap;
    }
    .nav-pills .dropdown-toggle {
        padding-left: 10px;
        padding-right: 10px;
    }
    .temp-chatbot-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 70px;
        height: 70px;
        background: #3a7bd5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        z-index: 9999;
    }
    .temp-chatbot-btn img {
        width: 40px;
        height: 40px;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- jQuery (needed for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- header start -->

<header id="site-header" class="header js-site-header site-header__has-banner">

    <nav class="nav--utility js-nav--utility bg-light py-2">
        <div class="container">
            <ul class="nav justify-content-end align-items-center mb-0">
                <li class="nav-item">
                    <a href="/#notification-banner__lightbox-popup" class="nav-link text-dark">
                        <i class="fas fa-exclamation-circle text-warning"></i>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark" href="tel:037.864.9957">
                        <i class="fas fa-phone-alt me-1"></i> Hotline: 037.864.9957
                    </a>
                </li>

                <li class="nav-item"><a class="nav-link text-dark" href="/about/history">10 Years of Medic Hospital</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="/help">Need help?</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="/search" aria-label="Search" aria-controls="search-box">Search</a></li>

                <!-- Thêm nút Chat AI rõ ràng -->
                <li class="nav-item ms-2">
                    <a class="btn btn-primary btn-sm" href="{{ route('chat.ai') }}">
                        <i class="fas fa-robot me-1"></i> Chat AI
                    </a>
                </li>

                @guest
                <li class="nav-item ms-2">
                    <a class="btn btn-outline-primary btn-sm" href="{{ url('/sign-in') }}">
                        <i class="fas fa-sign-in-alt me-1"></i> Sign In
                    </a>
                </li>
                <li class="nav-item ms-2">
                    <a class="btn btn-primary btn-sm" href="{{ url('/sign-up') }}">
                        Sign Up
                    </a>
                </li>
                @else
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-circle me-2">
                            <span class="avatar-initials">{{ substr(Auth::user()->FullName, 0, 1) }}</span>
                        </div>
                        <span>{{ Auth::user()->FullName }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('users.profile') }}"><i class="fas fa-user me-2"></i>Thông tin cá nhân</a></li>
                        <li><a class="dropdown-item" href="{{ route('users.prescriptions') }}"><i class="fas fa-prescription-bottle-alt me-2"></i>Đơn thuốc của tôi</a></li>
                        <li><a class="dropdown-item" href="{{ route('users.hospitalizations') }}"><i class="fas fa-bed me-2"></i>Lịch sử nhập viện</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Cài đặt</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('home.logout') }}">
                                <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                            </a>
                        </li>
                    </ul>
                </li>
                @endguest
            </ul>
        </div>
    </nav>

    <div class="d-flex align-items-center px-4 py-2 bg-light shadow-sm">

        <!-- Logo -->
        <span class="header__logo">
            <a href="{{ route('users.dashboard') }}">
                <img src="{{ asset('images/logo-ccf.png') }}" alt="Medic Hospital logo">
            </a>
        </span>

        <!-- Navbar -->
        <nav>
            <ul class="nav nav-pills">
                <li class="nav-item border-end">
                    <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('users.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item border-end">
                    <a class="nav-link {{ request()->is('staff') ? 'active' : '' }}" href="{{ route('users.staff') }}">Find a Doctor</a>
                </li>
                <li class="nav-item border-end">
                    <a class="nav-link {{ request()->is('locations') ? 'active' : '' }}" href="{{ route('users.locations') }}">Locations & Directions</a>
                </li>
                <li class="nav-item border-end">
                    <a class="nav-link {{ request()->is('patients') ? 'active' : '' }}" href="{{ route('users.patients') }}">Patients & Visitors</a>
                </li>
                <li class="nav-item border-end">
                    <a class="nav-link {{ request()->is('appointments*') ? 'active' : '' }}" href="{{ route('users.appointments') }}">Appointments</a>
                </li>
                <li class="nav-item border-end">
                    <a class="nav-link {{ request()->is('chat') ? 'active' : '' }}" href="{{ route('chat.ai') }}">
                        <i class="fas fa-robot me-1"></i>Chat AI
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('feedback*') || request()->is('my-feedback*') ? 'active' : '' }}" href="#" id="feedbackDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Feedback
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="feedbackDropdown">
                        <li><a class="dropdown-item" href="{{ route('feedback.public') }}"><i class="fas fa-list me-2"></i>Public Feedback</a></li>
                        @auth
                        <li><a class="dropdown-item" href="{{ route('feedback.create') }}"><i class="fas fa-comment-dots me-2"></i>Provide Feedback</a></li>
                        <li><a class="dropdown-item" href="{{ route('feedback.user') }}"><i class="fas fa-comments me-2"></i>My Feedback</a></li>
                        @else
                        <li><a class="dropdown-item" href="{{ route('login') }}?redirect_to={{ route('feedback.create') }}"><i class="fas fa-comment-dots me-2"></i>Provide Feedback</a></li>
                        <li><a class="dropdown-item" href="{{ route('login') }}?redirect_to={{ route('feedback.user') }}"><i class="fas fa-comments me-2"></i>My Feedback</a></li>
                        @endauth
                    </ul>
                </li>
            </ul>
        </nav>

    </div>


</header>

<!-- Banner giới thiệu chatbot AI -->
<div class="container mt-3">
    <div class="alert alert-info d-flex align-items-center justify-content-between" role="alert">
        <div>
            <i class="fas fa-robot me-2"></i>
            <strong>Trợ lý AI thông minh của Medic Hospital!</strong> Trò chuyện ngay để được tư vấn y tế và thông tin bệnh viện.
        </div>
        <div>
            <button id="banner-chat-btn" class="btn btn-primary">
                <i class="fas fa-comments me-1"></i> Chat với AI
            </button>
        </div>
    </div>
</div>

<!-- header end -->

@yield('content')


<section class="contact-box-ribbon js-contact-box-ribbon theme-blue-gray-pattern">
    <div class="container content-pad">

        <ul class="contact-box">

                        <li class="contact-box__phone">Appointments <a href="tel:8663204573">0378.649.957</a></li>
                        <li class="contact-box__phone">Questions   <a href="tel:2164442200">0924.184.107</a></li>
                        <li><a href="{{ route('users.appointments') }}" class="button--strong button--full button--arrow">Request an Appointment</a></li>

        </ul>
    </div>
</section>

<section class="footer-social js-footer-social">
        <div class="container">
            <a href="https://www.facebook.com/MedicHospital" disablewebedit="True" class="footer-social__link" target="_blank">            <i class="icon-social-facebook" role="img" aria-label="Facebook Icon"></i> <span class="element-invisible">Facebook</span>
            </a>
            <a href="https://twitter.com/MedicHospital" disablewebedit="True" class="footer-social__link" target="_blank">            <i class="icon-social-twitter" role="img" aria-label="Twitter Icon"></i> <span class="element-invisible">Twitter</span>
            </a>
            <a href="https://www.youtube.com/user/MedicHospital" disablewebedit="True" class="footer-social__link" target="_blank">            <i class="icon-social-youtube" role="img" aria-label="YouTube Icon"></i> <span class="element-invisible">YouTube</span>
            </a>
            <a href="https://www.instagram.com/MedicHospital/" disablewebedit="True" class="footer-social__link" target="_blank">            <i class="icon-social-instagram" role="img" aria-label="Instagram Icon"></i> <span class="element-invisible">Instagram</span>
            </a>
            <a href="https://www.linkedin.com/company/MedicHospital" disablewebedit="True" class="footer-social__link" target="_blank">            <i class="icon-social-linkedin" role="img" aria-label="LinkedIn Icon"></i> <span class="element-invisible">LinkedIn</span>
            </a>
            <a href="https://www.pinterest.com/MedicHospital/" disablewebedit="True" class="footer-social__link" target="_blank">            <i class="icon-social-pinterest" role="img" aria-label="Pinterest Icon"></i> <span class="element-invisible">Pinterest</span>
            </a>
            <a href="https://www.snapchat.com/add/MedicHospital" disablewebedit="True" class="footer-social__link" target="_blank">            <i class="icon-social-snapchat" role="img" aria-label="Snapchat Icon"></i> <span class="element-invisible">Snapchat</span>
            </a>
        </div>
</section>
<footer class="footer bg-dark text-light pt-5 pb-3">
    <div class="container">
        <div class="row">
            <!-- Actions -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <h5 class="footer__title">Actions</h5>
                <nav class="footer__nav d-flex flex-column">
                    <a href="/patients/information/access">Appointments & Access</a>
                    <a href="/patients/accepted-insurance">Accepted Insurance</a>
                    <a href="/">Events Calendar</a>
                    <a href="/patients/billing-finance/financial-assistance">Financial Assistance</a>
                    <a href="/giving">Give to Cleveland Clinic</a>
                    <a href="/patients/billing-finance/payment-options">Pay Your Bill Online</a>
                    <a href="/patients/billing-finance/comprehensive-hospital-charges">Price Transparency</a>
                    <a href="/professionals/referring">Refer a Patient</a>
                    <a href="/about/contact/phone-directory">Phone Directory</a>
                    <a href="/online-services/virtual-second-opinions">Virtual Second Opinions</a>
                    <a href="/online-services/virtual-visits">Virtual Visits</a>
                </nav>
            </div>

            <!-- Blog, News & Apps -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <h5 class="footer__title">Blog, News & Apps</h5>
                <nav class="footer__nav d-flex flex-column">
                    <a href="/">Consult QD</a>
                    <a href="/">Health Essentials</a>
                    <a href="/">Newsroom</a>
                    <a href="/mobile-apps/myclevelandclinic">MyMedicHospital</a>
                    <a href="/online-services/mychart">MyChart</a>
                </nav>
            </div>

            <!-- About Medic Hospital -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <h5 class="footer__title">About Medic Hospital</h5>
                <nav class="footer__nav d-flex flex-column">
                    <a href="/about/history">10 Years of Medic Hospital</a>
                    <a href="/about">About Us</a>
                    <a href="/locations">Locations</a>
                    <a href="/departments/patient-experience/depts/quality-patient-safety">Quality & Patient Safety</a>
                    <a href="/about/community/diversity">Diversity & Inclusion</a>
                    <a href="/departments/patient-experience/depts/office-patient-experience">Patient Experience</a>
                    <a href="/research">Research & Innovations</a>
                    <a href="/about/community">Community Commitment</a>
                    <a href="/">Careers</a>
                    <a href="/about/for-employees">For Employees</a>
                    <a href="/professionals">Resources for Medical Professionals</a>
                </nav>
            </div>

            <!-- Site Info -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <h5 class="footer__title">Site Information & Policies</h5>
                <nav class="footer__nav d-flex flex-column">
                    <a href="/webcontact/webmail">Send Us Feedback</a>
                    <a href="/about/website/site-map">Site Map</a>
                    <a href="/about/website">About this Website</a>
                    <a href="/about/website/reprints-licensing">Copyright & Licensing</a>
                    <a href="/about/website/terms-of-use">Terms of Use</a>
                    <a href="/about/website/privacy-security">Privacy Policy</a>
                    <a href="/about/website/privacy-practices">Privacy Practices</a>
                    <a href="/about/website/non-discrimination-notice">Non-Discrimination Notice</a>
                </nav>
            </div>
        </div>

        <div class="text-center mt-4 small">
            9500 Euclid Avenue, Cleveland, Ohio 44195 |
            <a href="tel:8002232273" class="text-light">800.223.2273</a> |
            © 2024 Medic Hospital. All Rights Reserved.
        </div>
    </div>
</footer>

{{-- <script lang="javascript">var __vnp = {code : 24859,key:'', secret : '01a01bf0569564fa3230269838262e78'};(function() {var ga = document.createElement('script');ga.type = 'text/javascript';ga.async=true; ga.defer=true;ga.src = '//core.vchat.vn/code/tracking.js?v=44821'; var s = document.getElementsByTagName('script');s[0].parentNode.insertBefore(ga, s[0]);})();</script> --}}

<!-- Session Timeout Modal -->
<div class="modal fade" id="sessionTimeoutModal" tabindex="-1" aria-labelledby="sessionTimeoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="sessionTimeoutModalLabel">Session Timeout Warning</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Your session is about to expire due to inactivity.</p>
                <p>You will be logged out in <span id="sessionCountdown">60</span> seconds.</p>
                <p>Would you like to stay logged in?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="logoutNowBtn">Logout Now</button>
                <button type="button" class="btn btn-primary" id="stayLoggedInBtn">Stay Logged In</button>
            </div>
        </div>
    </div>
</div>

<!-- Chatbot Component -->
<div id="chatbot-app"></div>



<script>
    // Khởi tạo trang khi DOM đã sẵn sàng
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo AOS animations
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });

        // Khởi tạo chatbot sau khi trang đã tải xong
        initChatbot();
    });

    // Chatbot Functionality
    function initChatbot() {
        // Đảm bảo code chỉ chạy sau khi trang đã tải xong
        setTimeout(function() {
            const toggleBtn = document.getElementById('ai-chatbot-toggle-btn');
            const chatbot = document.getElementById('ai-chatbot');
            const closeBtn = document.getElementById('ai-chatbot-close');
            const minimizeBtn = document.getElementById('ai-chatbot-minimize');
            const clearBtn = document.getElementById('ai-chatbot-clear');
            const sendBtn = document.getElementById('ai-chatbot-send');
            const input = document.getElementById('ai-chatbot-input');
            const messagesContainer = document.getElementById('ai-chatbot-messages');

            if (!toggleBtn || !chatbot || !closeBtn || !minimizeBtn || !clearBtn || !sendBtn || !input || !messagesContainer) {
                console.error('Một hoặc nhiều thành phần chatbot không tìm thấy.');
                return;
            }

            // Tạo sessionId mới mỗi khi tải trang
            let sessionId = crypto.randomUUID();
            localStorage.setItem('ai_chat_session_id', sessionId);

            // Toggle chatbot visibility
            toggleBtn.addEventListener('click', function() {
                chatbot.classList.add('active');
                toggleBtn.classList.add('hidden');
            });

            // Close chatbot
            closeBtn.addEventListener('click', function() {
                chatbot.classList.remove('active');
                toggleBtn.classList.remove('hidden');
            });

            // Minimize chatbot
            minimizeBtn.addEventListener('click', function() {
                chatbot.classList.remove('active');
                toggleBtn.classList.remove('hidden');
            });

            // Clear chat history
            clearBtn.addEventListener('click', function() {
                // Clear UI
                while (messagesContainer.children.length > 1) {
                    messagesContainer.removeChild(messagesContainer.lastChild);
                }

                // Clear server-side history
                clearChatHistory();

                // Display confirmation
                const notification = document.createElement('div');
                notification.className = 'ai-chatbot-notification';
                notification.textContent = 'Lịch sử chat đã được xóa';
                chatbot.appendChild(notification);

                setTimeout(() => {
                    chatbot.removeChild(notification);
                }, 2000);
            });

            // Send message on Enter
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });

            // Send message on button click
            sendBtn.addEventListener('click', sendMessage);

            function sendMessage() {
                const message = input.value.trim();
                if (!message) return;

                // Display user message
                appendMessage(message, 'user');

                // Clear input
                input.value = '';

                // Show typing indicator
                showTypingIndicator();

                // Send to server
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
                    // Remove typing indicator
                    removeTypingIndicator();

                    // Display AI response
                    appendMessage(data.message, 'ai');

                    // Scroll to bottom
                    scrollToBottom();
                })
                .catch(error => {
                    // Remove typing indicator
                    removeTypingIndicator();

                    // Display error
                    appendMessage('Có lỗi xảy ra. Vui lòng thử lại.', 'ai', true);
                    console.error('Error:', error);

                    // Scroll to bottom
                    scrollToBottom();
                });
            }

            function appendMessage(content, sender, isError = false) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `ai-chatbot-message ${sender === 'user' ? 'user-message' : 'ai-message'}`;

                if (sender === 'user') {
                    messageDiv.innerHTML = `
                        <div class="ai-chatbot-bubble">
                            <p>${content}</p>
                        </div>
                    `;
                } else {
                    messageDiv.innerHTML = `
                        <div class="ai-chatbot-avatar">
                            <img src="{{ asset('images/IconChatbot.png') }}" alt="AI">
                        </div>
                        <div class="ai-chatbot-bubble ${isError ? 'error' : ''}">
                            <p>${formatMessage(content)}</p>
                        </div>
                    `;
                }

                messagesContainer.appendChild(messageDiv);
                scrollToBottom();
            }

            function formatMessage(message) {
                // Convert URLs to links
                const urlRegex = /(https?:\/\/[^\s]+)/g;
                message = message.replace(urlRegex, url => `<a href="${url}" target="_blank">${url}</a>`);

                // Convert newlines to <br>
                message = message.replace(/\n/g, '<br>');

                return message;
            }

            function showTypingIndicator() {
                const typingDiv = document.createElement('div');
                typingDiv.className = 'ai-chatbot-message ai-message';
                typingDiv.id = 'ai-typing-indicator';
                typingDiv.innerHTML = `
                    <div class="ai-chatbot-avatar">
                        <img src="{{ asset('images/IconChatbot.png') }}" alt="AI">
                    </div>
                    <div class="ai-chatbot-bubble typing">
                        <div class="ai-typing-indicator">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                `;

                messagesContainer.appendChild(typingDiv);
                scrollToBottom();
            }

            function removeTypingIndicator() {
                const typingIndicator = document.getElementById('ai-typing-indicator');
                if (typingIndicator) {
                    typingIndicator.remove();
                }
            }

            function scrollToBottom() {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Clear chat history function
            function clearChatHistory() {
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
                    // Generate new session ID
                    sessionId = crypto.randomUUID();
                    localStorage.setItem('ai_chat_session_id', sessionId);
                })
                .catch(error => {
                    console.error('Error clearing chat history:', error);
                });
            }

            // Connect banner button
            const bannerChatBtn = document.getElementById('banner-chat-btn');
            if (bannerChatBtn) {
                bannerChatBtn.addEventListener('click', function() {
                    chatbot.classList.add('active');
                    toggleBtn.classList.add('hidden');
                });
            }
        }, 500); // Đợi 500ms để đảm bảo trang đã tải xong
    }
</script>

<!-- Chatbot CSS -->
<style>
    .ai-chatbot-container {
        position: fixed;
        bottom: 90px;
        right: 20px;
        width: 350px;
        height: 500px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.2);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        z-index: 9999;
        opacity: 0;
        transform: translateY(20px);
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .ai-chatbot-container.active {
        opacity: 1;
        transform: translateY(0);
        pointer-events: all;
    }

    .ai-chatbot-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        background-color: #3a7bd5;
        color: white;
    }

    .ai-chatbot-title {
        display: flex;
        align-items: center;
    }

    .ai-chatbot-title h5 {
        margin: 0 0 0 10px;
        font-size: 16px;
    }

    .ai-chatbot-avatar img {
        width: 30px;
        height: 30px;
        border-radius: 50%;
    }

    .ai-chatbot-body {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
        background-color: #f5f5f5;
    }

    .ai-chatbot-messages {
        display: flex;
        flex-direction: column;
    }

    .ai-chatbot-message {
        display: flex;
        margin-bottom: 15px;
        align-items: flex-start;
    }

    .ai-chatbot-message.user-message {
        justify-content: flex-end;
    }

    .ai-chatbot-bubble {
        padding: 10px 15px;
        border-radius: 18px;
        max-width: 80%;
        background-color: #f1f1f1;
    }

    .ai-chatbot-message.user-message .ai-chatbot-bubble {
        background-color: #3a7bd5;
        color: white;
    }

    .ai-chatbot-bubble p {
        margin: 0;
    }

    .ai-chatbot-bubble.error {
        background-color: #f8d7da;
        color: #721c24;
    }

    .ai-chatbot-bubble.typing {
        padding: 15px;
    }

    .ai-chatbot-footer {
        display: flex;
        padding: 10px 15px;
        border-top: 1px solid #eee;
        background-color: #fff;
    }

    .ai-chatbot-input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 20px;
        outline: none;
    }

    .ai-chatbot-send {
        background-color: #3a7bd5;
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-left: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ai-chatbot-toggle {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9998;
    }

    .ai-chatbot-toggle-btn {
        width: 60px;
        height: 60px;
        background-color: #3a7bd5;
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        position: relative;
        transition: all 0.3s ease;
    }

    .ai-chatbot-toggle-btn.hidden {
        transform: scale(0);
        opacity: 0;
    }

    .ai-chatbot-toggle-btn img {
        width: 200px;
        height: 200px;
    }

    .ai-chatbot-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: red;
        color: white;
        font-size: 12px;
        font-weight: bold;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ai-typing-indicator {
        display: flex;
        gap: 5px;
    }

    .ai-typing-indicator span {
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #3a7bd5;
        border-radius: 50%;
        opacity: 0.6;
        animation: typing 1.4s infinite both;
    }

    .ai-typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .ai-typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }

    .ai-chatbot-notification {
        position: absolute;
        bottom: 70px;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(0,0,0,0.7);
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 14px;
        z-index: 10000;
    }

    @keyframes typing {
        0% {
            opacity: 0.6;
            transform: translateY(0);
        }
        50% {
            opacity: 1;
            transform: translateY(-5px);
        }
        100% {
            opacity: 0.6;
            transform: translateY(0);
        }
    }
</style>

@if(Auth::check())
<!-- Session Management Script - Only for authenticated users -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Session activity variables
        let lastActivity = new Date();
        let isIdle = false;
        let countdownTimer = null;
        let timeoutModal = null;
        let secondsRemaining = 60; // Countdown time in seconds

        // Session timeout in milliseconds
        const sessionTimeout = 15 * 60 * 1000; // 15 minutes
        const warningTime = 60 * 1000; // Show warning 1 minute before timeout

        // Initialize the modal
        timeoutModal = new bootstrap.Modal(document.getElementById('sessionTimeoutModal'));

        // Update last activity time on user interaction
        function updateActivity() {
            // Only update if user wasn't already idle
            if (!isIdle) {
                lastActivity = new Date();
            }
        }

        // Start countdown timer
        function startCountdown() {
            secondsRemaining = 60;
            updateCountdown();

            countdownTimer = setInterval(function() {
                secondsRemaining--;
                updateCountdown();

                if (secondsRemaining <= 0) {
                    clearInterval(countdownTimer);
                    performLogout();
                }
            }, 1000);
        }

        // Update countdown display
        function updateCountdown() {
            document.getElementById('sessionCountdown').textContent = secondsRemaining;
        }

        // Reset the session timeout
        function resetSession() {
            isIdle = false;
            lastActivity = new Date();
            if (countdownTimer) {
                clearInterval(countdownTimer);
                countdownTimer = null;
            }
            if (timeoutModal) {
                timeoutModal.hide();
            }
        }

        // Perform logout
        function performLogout() {
            window.location.href = "{{ route('home.logout') }}";
        }

        // Check if session might be expired
        function checkSession() {
            const now = new Date();
            const timeSinceLastActivity = now - lastActivity;

            // If we're about to timeout (less than warning time left)
            if (timeSinceLastActivity > (sessionTimeout - warningTime) && !isIdle) {
                isIdle = true;
                timeoutModal.show();
                startCountdown();
            }

            // Keep the session alive if user is active and not in countdown mode
            if (timeSinceLastActivity < (sessionTimeout / 2) && !isIdle) {
                // Send heartbeat to keep session alive
                fetch('{{ route("users.dashboard") }}', {
                    method: 'HEAD',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).catch(error => console.error('Session heartbeat error:', error));
            }
        }

        // Set up event listeners for user activity
        ['mousemove', 'mousedown', 'keypress', 'touchstart', 'scroll'].forEach(function(event) {
            document.addEventListener(event, updateActivity, true);
        });

        // Set up button event listeners
        document.getElementById('stayLoggedInBtn').addEventListener('click', function() {
            resetSession();
        });

        document.getElementById('logoutNowBtn').addEventListener('click', function() {
            performLogout();
        });

        // Check session every minute
        setInterval(checkSession, 60000);
    });
</script>
@endif

<!-- Additional scripts from child pages -->
@yield('scripts')

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>






