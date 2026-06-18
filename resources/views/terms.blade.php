<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logoDav.png') }}">
    <title>Terms of Service — ServeDavao</title>
    <meta name="description" content="Read ServeDavao's Terms of Service governing the use of our volunteer and event management platform in Davao City.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;1,14..32,400&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .gradient-hero {
            background: linear-gradient(135deg, #064e3b 0%, #065f46 40%, #047857 100%);
        }
        .toc-link { transition: all 0.2s; }
        .toc-link:hover { color: #059669; padding-left: 4px; }
        .section-anchor { scroll-margin-top: 100px; }
        .prose-section h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #064e3b;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .prose-section p, .prose-section li {
            color: #374151;
            line-height: 1.75;
            font-size: 0.9375rem;
        }
        .prose-section ul { list-style: none; padding-left: 0; }
        .prose-section ul li { padding: 0.3rem 0 0.3rem 1.5rem; position: relative; }
        .prose-section ul li::before {
            content: "•";
            color: #059669;
            font-weight: 700;
            position: absolute;
            left: 0;
        }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-50 antialiased">

    <!-- Sticky Top Nav -->
    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-200 shadow-sm no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-2.5 group">
                <img src="{{ asset('assets/img/logoDav.png') }}" alt="ServeDavao Logo" class="w-9 h-9 rounded-full shadow">
                <span class="font-extrabold text-gray-900 text-lg tracking-tight">Serve<span class="text-emerald-600">Davao</span></span>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('privacy') }}" class="text-sm text-gray-500 hover:text-emerald-600 transition-colors font-medium">Privacy Policy</a>
                <a href="{{ route('login') }}" class="text-sm bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors font-medium">Sign In</a>
            </div>
        </div>
    </nav>

    <!-- Hero Banner -->
    <div class="gradient-hero py-14 px-6 text-center">
        <div class="max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 bg-white/15 text-white text-xs font-semibold px-4 py-1.5 rounded-full mb-5 backdrop-blur-sm">
                <i class="bi bi-file-earmark-text"></i>
                Legal Document
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 leading-tight">Terms of Service</h1>
            <p class="text-emerald-100 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                These terms govern your use of ServeDavao — a volunteer and event management platform serving Davao City, Philippines.
            </p>
            <p class="text-emerald-200 text-sm mt-4">
                <i class="bi bi-calendar3 mr-1"></i> Effective Date: <strong>June 18, 2025</strong> &nbsp;|&nbsp; Last Updated: <strong>June 18, 2026</strong>
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-10">

            <!-- Table of Contents (Sidebar) -->
            <aside class="lg:w-64 flex-shrink-0 no-print">
                <div class="sticky top-24 bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Contents</p>
                    <nav class="space-y-1.5 text-sm">
                        <a href="#acceptance" class="toc-link block text-gray-600 font-medium">1. Acceptance of Terms</a>
                        <a href="#platform" class="toc-link block text-gray-600 font-medium">2. About the Platform</a>
                        <a href="#eligibility" class="toc-link block text-gray-600 font-medium">3. Eligibility</a>
                        <a href="#accounts" class="toc-link block text-gray-600 font-medium">4. User Accounts</a>
                        <a href="#roles" class="toc-link block text-gray-600 font-medium">5. Roles & Responsibilities</a>
                        <a href="#events" class="toc-link block text-gray-600 font-medium">6. Events & Participation</a>
                        <a href="#attendance" class="toc-link block text-gray-600 font-medium">7. Attendance & QR Codes</a>
                        <a href="#messaging" class="toc-link block text-gray-600 font-medium">8. Messaging</a>
                        <a href="#content" class="toc-link block text-gray-600 font-medium">9. User Content</a>
                        <a href="#conduct" class="toc-link block text-gray-600 font-medium">10. Prohibited Conduct</a>
                        <a href="#termination" class="toc-link block text-gray-600 font-medium">11. Termination</a>
                        <a href="#liability" class="toc-link block text-gray-600 font-medium">12. Liability</a>
                        <a href="#changes" class="toc-link block text-gray-600 font-medium">13. Changes to Terms</a>
                        <a href="#governing" class="toc-link block text-gray-600 font-medium">14. Governing Law</a>
                        <a href="#contact-us" class="toc-link block text-gray-600 font-medium">15. Contact</a>
                    </nav>
                    <div class="mt-5 pt-4 border-t border-gray-100">
                        <a href="{{ route('privacy') }}" class="flex items-center gap-2 text-sm text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                            <i class="bi bi-shield-check"></i> Privacy Policy
                        </a>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 space-y-8">

                <!-- Section 1 -->
                <div id="acceptance" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">1</span> Acceptance of Terms</h2>
                    <p>By accessing or using ServeDavao (the "Platform"), whether through the website or any related services, you agree to be bound by these Terms of Service ("Terms"). If you do not agree to these Terms, you must not use the Platform.</p>
                    <p class="mt-3">By clicking "Continue with Google" and completing authentication, you confirm that you have read, understood, and agree to these Terms along with our <a href="{{ route('privacy') }}" class="text-emerald-600 hover:underline">Privacy Policy</a>.</p>
                </div>

                <!-- Section 2 -->
                <div id="platform" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">2</span> About the Platform</h2>
                    <p>ServeDavao is a volunteer and event management platform designed to connect volunteers with community-driven events and organizations in Davao City, Philippines. The Platform provides tools for:</p>
                    <ul class="mt-3 space-y-1">
                        <li>Discovering and joining volunteer events</li>
                        <li>Creating and managing community events as an organizer</li>
                        <li>QR code-based attendance tracking and check-in</li>
                        <li>Direct and group messaging between volunteers and organizers</li>
                        <li>Submitting feedback, polls, and event suggestions</li>
                        <li>Organizer verification and accreditation</li>
                        <li>Tracking volunteer activity and participation history</li>
                    </ul>
                </div>

                <!-- Section 3 -->
                <div id="eligibility" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">3</span> Eligibility</h2>
                    <p>To use ServeDavao, you must:</p>
                    <ul class="mt-3 space-y-1">
                        <li>Be at least <strong>15 years old</strong> (or have guardian consent if younger)</li>
                        <li>Have a valid Google account for authentication</li>
                        <li>Provide accurate and up-to-date information</li>
                        <li>Not be previously suspended or banned from the Platform</li>
                    </ul>
                    <p class="mt-3">The Platform is primarily intended for users based in or connected to Davao City and the broader Davao Region.</p>
                </div>

                <!-- Section 4 -->
                <div id="accounts" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">4</span> User Accounts</h2>
                    <p>Accounts are created exclusively through Google OAuth 2.0. By signing in, you allow ServeDavao to access your Google profile name, email address, and profile photo.</p>
                    <ul class="mt-3 space-y-1">
                        <li>You are responsible for all activity that occurs under your account</li>
                        <li>You must not share your account credentials or allow others to use your account</li>
                        <li>You must notify us immediately if you suspect unauthorized use of your account</li>
                        <li>You may only maintain one active account on the Platform</li>
                        <li>We reserve the right to suspend accounts that violate these Terms</li>
                    </ul>
                </div>

                <!-- Section 5 -->
                <div id="roles" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">5</span> Roles & Responsibilities</h2>
                    <p>The Platform has two primary user roles:</p>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                            <p class="font-semibold text-emerald-800 mb-2 flex items-center gap-2"><i class="bi bi-person-fill"></i> Volunteers</p>
                            <ul class="space-y-1 text-sm">
                                <li>Browse and join events</li>
                                <li>Attend events and check in via QR</li>
                                <li>Submit event feedback</li>
                                <li>Participate in polls and suggestions</li>
                                <li>Message organizers and co-volunteers</li>
                            </ul>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                            <p class="font-semibold text-blue-800 mb-2 flex items-center gap-2"><i class="bi bi-building"></i> Organizers</p>
                            <ul class="space-y-1 text-sm">
                                <li>Create and manage volunteer events</li>
                                <li>Review and accept volunteer registrations</li>
                                <li>Manage QR-based check-in and attendance</li>
                                <li>Communicate with registered volunteers</li>
                                <li>Must complete organizer verification</li>
                            </ul>
                        </div>
                    </div>
                    <p class="mt-4"><strong>Organizers</strong> bear full responsibility for the accuracy, legality, and safety of the events they post. ServeDavao does not endorse, sponsor, or guarantee the quality of any event listed on the Platform.</p>
                </div>

                <!-- Section 6 -->
                <div id="events" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">6</span> Events & Participation</h2>
                    <p>When joining or creating events on ServeDavao, the following rules apply:</p>
                    <ul class="mt-3 space-y-1">
                        <li>Volunteers who register for an event are expected to attend or cancel in advance</li>
                        <li>Organizers must ensure events comply with applicable local laws and public safety guidelines</li>
                        <li>Organizers must not charge volunteers any fees unless explicitly disclosed and agreed upon</li>
                        <li>Events promoting illegal, discriminatory, or harmful activities are strictly prohibited</li>
                        <li>ServeDavao reserves the right to remove any event that violates these Terms or community standards</li>
                        <li>Participation in any event is voluntary; ServeDavao is not liable for any incidents during events</li>
                    </ul>
                </div>

                <!-- Section 7 -->
                <div id="attendance" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">7</span> Attendance & QR Codes</h2>
                    <p>ServeDavao uses a QR code-based check-in system to track attendance at volunteer events:</p>
                    <ul class="mt-3 space-y-1">
                        <li>Each registered volunteer receives a unique, signed QR ticket for each event</li>
                        <li>QR codes are personal and must not be shared with or used by another person</li>
                        <li>Only organizers of the specific event may scan QR codes for attendance purposes</li>
                        <li>Check-in data (time, user identity, event) is recorded and may be included in your volunteer activity history</li>
                        <li>Attempting to forge, tamper with, or misuse QR codes is a violation of these Terms and may result in account suspension</li>
                    </ul>
                </div>

                <!-- Section 8 -->
                <div id="messaging" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">8</span> Messaging</h2>
                    <p>The Platform provides direct and group messaging features for communication between users. When using messaging:</p>
                    <ul class="mt-3 space-y-1">
                        <li>Messages must be related to events, volunteering, or community coordination</li>
                        <li>Harassment, threats, spam, or unsolicited promotional messages are prohibited</li>
                        <li>You may not use messaging to solicit personal information from other users</li>
                        <li>ServeDavao may review messages in response to abuse reports or legal requirements</li>
                        <li>Deleted messages may still be retained in our systems for a limited period per our data retention policy</li>
                    </ul>
                </div>

                <!-- Section 9 -->
                <div id="content" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">9</span> User Content</h2>
                    <p>You retain ownership of any content you submit to the Platform (feedback, suggestions, event descriptions, messages). However, by submitting content, you grant ServeDavao a non-exclusive, royalty-free license to display, use, and reproduce that content within the Platform for operational purposes.</p>
                    <ul class="mt-3 space-y-1">
                        <li>You are solely responsible for the content you post</li>
                        <li>Content must be truthful, accurate, and not misleading</li>
                        <li>Content must not infringe on third-party intellectual property rights</li>
                        <li>We reserve the right to remove content that violates these Terms without prior notice</li>
                    </ul>
                </div>

                <!-- Section 10 -->
                <div id="conduct" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">10</span> Prohibited Conduct</h2>
                    <p>The following are strictly prohibited on ServeDavao:</p>
                    <ul class="mt-3 space-y-1">
                        <li>Impersonating another person, organization, or government entity</li>
                        <li>Creating fake events to collect volunteer data or deceive users</li>
                        <li>Using automated bots, scrapers, or scripts to access the Platform</li>
                        <li>Attempting to hack, disrupt, or gain unauthorized access to the Platform</li>
                        <li>Posting discriminatory, hateful, or violent content</li>
                        <li>Using the Platform for commercial solicitation without prior written consent</li>
                        <li>Sharing or distributing another user's personal information without consent</li>
                        <li>Any activity that violates Philippine laws, including RA 10173 and the Cybercrime Prevention Act of 2012</li>
                    </ul>
                </div>

                <!-- Section 11 -->
                <div id="termination" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">11</span> Termination</h2>
                    <p>ServeDavao reserves the right to suspend or permanently terminate your account, with or without notice, if you violate these Terms or engage in conduct deemed harmful to the Platform or its users.</p>
                    <p class="mt-3">You may also request deletion of your account at any time by contacting us. Upon account deletion, your personal data will be handled in accordance with our <a href="{{ route('privacy') }}" class="text-emerald-600 hover:underline">Privacy Policy</a>.</p>
                </div>

                <!-- Section 12 -->
                <div id="liability" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">12</span> Limitation of Liability</h2>
                    <p>ServeDavao is provided on an "as-is" and "as-available" basis. To the fullest extent permitted by law:</p>
                    <ul class="mt-3 space-y-1">
                        <li>We do not guarantee uninterrupted, error-free, or secure access to the Platform</li>
                        <li>We are not liable for any direct, indirect, incidental, or consequential damages arising from your use of the Platform</li>
                        <li>We are not responsible for the actions, conduct, or events organized by third-party organizers listed on the Platform</li>
                        <li>We are not liable for any physical, financial, or emotional harm resulting from participation in events posted on the Platform</li>
                    </ul>
                    <p class="mt-3">Users participate in events entirely at their own risk. Always verify event details directly with organizers.</p>
                </div>

                <!-- Section 13 -->
                <div id="changes" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">13</span> Changes to Terms</h2>
                    <p>We may update these Terms from time to time to reflect changes in the Platform, legal requirements, or operational practices. When material changes are made, we will update the "Last Updated" date at the top of this page.</p>
                    <p class="mt-3">Continued use of ServeDavao after changes are posted constitutes your acceptance of the revised Terms. We recommend reviewing this page periodically.</p>
                </div>

                <!-- Section 14 -->
                <div id="governing" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">14</span> Governing Law</h2>
                    <p>These Terms are governed by and construed in accordance with the laws of the Republic of the Philippines. Any disputes arising from or relating to these Terms shall be subject to the exclusive jurisdiction of the appropriate courts in Davao City, Philippines.</p>
                    <p class="mt-3">Relevant Philippine laws applicable to the Platform include:</p>
                    <ul class="mt-2 space-y-1">
                        <li><strong>Republic Act No. 10173</strong> — Data Privacy Act of 2012</li>
                        <li><strong>Republic Act No. 10175</strong> — Cybercrime Prevention Act of 2012</li>
                        <li><strong>Republic Act No. 8792</strong> — Electronic Commerce Act of 2000</li>
                    </ul>
                </div>

                <!-- Section 15 -->
                <div id="contact-us" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">15</span> Contact Us</h2>
                    <p>If you have questions, concerns, or requests regarding these Terms, please reach out to us:</p>
                    <div class="mt-4 bg-emerald-50 rounded-xl p-5 border border-emerald-100">
                        <p class="font-semibold text-emerald-800 text-base mb-3 flex items-center gap-2">
                            <i class="bi bi-envelope-fill"></i> ServeDavao Support
                        </p>
                        <div class="space-y-2 text-sm text-gray-700">
                            <p><i class="bi bi-geo-alt-fill text-emerald-600 mr-2"></i> Davao City, Philippines</p>
                            <p><i class="bi bi-globe text-emerald-600 mr-2"></i> <a href="{{ url('/') }}" class="text-emerald-600 hover:underline">{{ url('/') }}</a></p>
                        </div>
                    </div>
                </div>

                <!-- Footer CTA -->
                <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl p-8 text-center text-white">
                    <h3 class="text-xl font-bold mb-2">Ready to make a difference?</h3>
                    <p class="text-emerald-100 text-sm mb-5">Join thousands of volunteers serving Davao City through ServeDavao.</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-white text-emerald-700 font-semibold px-6 py-3 rounded-xl hover:bg-emerald-50 transition-colors shadow-md">
                        <i class="bi bi-google"></i> Sign in with Google
                    </a>
                </div>

            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="border-t border-gray-200 bg-white mt-12 py-8 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} ServeDavao. All rights reserved.</p>
            <div class="flex items-center gap-6 text-sm">
                <span class="text-emerald-600 font-medium">Terms of Service</span>
                <a href="{{ route('privacy') }}" class="text-gray-500 hover:text-emerald-600 transition-colors">Privacy Policy</a>
                <a href="{{ route('landing') }}" class="text-gray-500 hover:text-emerald-600 transition-colors">Home</a>
            </div>
        </div>
    </footer>

    <!-- Smooth scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth' });
            });
        });
    </script>
</body>
</html>
