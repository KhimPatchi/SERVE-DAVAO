<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logoDav.png') }}">
    <title>Privacy Policy — ServeDavao</title>
    <meta name="description" content="Learn how ServeDavao collects, uses, and protects your personal data in compliance with the Philippine Data Privacy Act of 2012.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;1,14..32,400&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .gradient-hero {
            background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 40%, #2563eb 100%);
        }
        .toc-link { transition: all 0.2s; }
        .toc-link:hover { color: #2563eb; padding-left: 4px; }
        .section-anchor { scroll-margin-top: 100px; }
        .prose-section h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e3a5f;
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
            color: #2563eb;
            font-weight: 700;
            position: absolute;
            left: 0;
        }
        .data-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            border: 1px solid #bfdbfe;
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
                <a href="{{ route('terms') }}" class="text-sm text-gray-500 hover:text-blue-600 transition-colors font-medium">Terms of Service</a>
                <a href="{{ route('login') }}" class="text-sm bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors font-medium">Sign In</a>
            </div>
        </div>
    </nav>

    <!-- Hero Banner -->
    <div class="gradient-hero py-14 px-6 text-center">
        <div class="max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 bg-white/15 text-white text-xs font-semibold px-4 py-1.5 rounded-full mb-5 backdrop-blur-sm">
                <i class="bi bi-shield-check"></i>
                Legal Document
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 leading-tight">Privacy Policy</h1>
            <p class="text-blue-100 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                We are committed to protecting your personal data in compliance with the <strong>Philippine Data Privacy Act of 2012 (RA 10173)</strong>.
            </p>
            <p class="text-blue-200 text-sm mt-4">
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
                        <a href="#controller" class="toc-link block text-gray-600 font-medium">1. Data Controller</a>
                        <a href="#data-collected" class="toc-link block text-gray-600 font-medium">2. Data We Collect</a>
                        <a href="#how-collected" class="toc-link block text-gray-600 font-medium">3. How We Collect Data</a>
                        <a href="#purpose" class="toc-link block text-gray-600 font-medium">4. Purpose of Processing</a>
                        <a href="#legal-basis" class="toc-link block text-gray-600 font-medium">5. Legal Basis</a>
                        <a href="#sharing" class="toc-link block text-gray-600 font-medium">6. Data Sharing</a>
                        <a href="#retention" class="toc-link block text-gray-600 font-medium">7. Data Retention</a>
                        <a href="#security" class="toc-link block text-gray-600 font-medium">8. Security</a>
                        <a href="#rights" class="toc-link block text-gray-600 font-medium">9. Your Rights</a>
                        <a href="#google" class="toc-link block text-gray-600 font-medium">10. Google OAuth</a>
                        <a href="#cookies" class="toc-link block text-gray-600 font-medium">11. Cookies & Sessions</a>
                        <a href="#minors" class="toc-link block text-gray-600 font-medium">12. Minors</a>
                        <a href="#changes-policy" class="toc-link block text-gray-600 font-medium">13. Policy Changes</a>
                        <a href="#contact-privacy" class="toc-link block text-gray-600 font-medium">14. Contact</a>
                    </nav>
                    <div class="mt-5 pt-4 border-t border-gray-100">
                        <a href="{{ route('terms') }}" class="flex items-center gap-2 text-sm text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                            <i class="bi bi-file-earmark-text"></i> Terms of Service
                        </a>
                    </div>
                </div>

                <!-- RA 10173 Badge -->
                <div class="mt-4 bg-blue-50 border border-blue-100 rounded-2xl p-4 text-center">
                    <i class="bi bi-award-fill text-blue-500 text-2xl mb-2 block"></i>
                    <p class="text-xs font-semibold text-blue-700">Compliant with</p>
                    <p class="text-xs text-blue-600 mt-0.5">Data Privacy Act of 2012<br>(Republic Act No. 10173)</p>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 space-y-8">

                <!-- Summary Card -->
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
                    <p class="font-semibold text-blue-800 flex items-center gap-2 mb-3"><i class="bi bi-info-circle-fill"></i> Privacy at a Glance</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div class="flex items-start gap-2">
                            <i class="bi bi-database text-blue-500 mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-blue-900">What we collect</p>
                                <p class="text-blue-700 text-xs mt-0.5">Name, email, avatar, volunteer activity, attendance, messages</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="bi bi-gear text-blue-500 mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-blue-900">Why we use it</p>
                                <p class="text-blue-700 text-xs mt-0.5">To run the platform, match volunteers to events, track attendance</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="bi bi-share text-blue-500 mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-blue-900">Who sees it</p>
                                <p class="text-blue-700 text-xs mt-0.5">Only you, event organizers, and our platform. Never sold.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 1 -->
                <div id="controller" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">1</span> Data Controller</h2>
                    <p>ServeDavao acts as the <strong>Personal Information Controller (PIC)</strong> as defined under the Philippine Data Privacy Act of 2012. We determine the purpose and means of processing personal data collected through the Platform.</p>
                    <div class="mt-4 bg-gray-50 rounded-xl p-4 text-sm text-gray-600">
                        <p><strong>Platform:</strong> ServeDavao</p>
                        <p class="mt-1"><strong>Location:</strong> Davao City, Philippines</p>
                        <p class="mt-1"><strong>Website:</strong> <a href="{{ url('/') }}" class="text-blue-600 hover:underline">{{ url('/') }}</a></p>
                    </div>
                </div>

                <!-- Section 2 -->
                <div id="data-collected" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">2</span> Data We Collect</h2>
                    <p>We collect only the data necessary to operate the Platform and provide our services:</p>

                    <div class="mt-4 space-y-3">
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <span class="data-badge mt-0.5"><i class="bi bi-person"></i> Identity</span>
                            <div class="text-sm">
                                <p class="font-medium text-gray-800">Profile Information</p>
                                <p class="text-gray-500 text-xs mt-0.5">Full name, email address, profile photo (from Google), Google account ID</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <span class="data-badge mt-0.5"><i class="bi bi-calendar-check"></i> Activity</span>
                            <div class="text-sm">
                                <p class="font-medium text-gray-800">Volunteer Activity</p>
                                <p class="text-gray-500 text-xs mt-0.5">Events joined, attendance records, check-in timestamps, volunteer hours</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <span class="data-badge mt-0.5"><i class="bi bi-chat-dots"></i> Content</span>
                            <div class="text-sm">
                                <p class="font-medium text-gray-800">User-Generated Content</p>
                                <p class="text-gray-500 text-xs mt-0.5">Messages sent, event feedback, poll responses, suggestions submitted</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <span class="data-badge mt-0.5"><i class="bi bi-tags"></i> Preferences</span>
                            <div class="text-sm">
                                <p class="font-medium text-gray-800">Volunteer Preferences</p>
                                <p class="text-gray-500 text-xs mt-0.5">Volunteer interest tags and categories selected in your profile settings</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <span class="data-badge mt-0.5"><i class="bi bi-pc-display"></i> Technical</span>
                            <div class="text-sm">
                                <p class="font-medium text-gray-800">Technical Data</p>
                                <p class="text-gray-500 text-xs mt-0.5">Session data, browser type, login timestamps, and platform usage logs</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3 -->
                <div id="how-collected" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">3</span> How We Collect Data</h2>
                    <p>We collect your data through the following means:</p>
                    <ul class="mt-3 space-y-1">
                        <li><strong>Google OAuth 2.0</strong> — When you sign in with Google, we receive your name, email, and profile photo from Google's API</li>
                        <li><strong>Platform activity</strong> — When you join events, check in via QR code, send messages, submit feedback, or vote in polls</li>
                        <li><strong>Profile settings</strong> — When you update your preferences, volunteer interests, or organizer verification information</li>
                        <li><strong>Automated systems</strong> — Session tokens, login timestamps, and system logs generated during normal platform use</li>
                    </ul>
                </div>

                <!-- Section 4 -->
                <div id="purpose" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">4</span> Purpose of Processing</h2>
                    <p>Your personal data is processed for the following specific purposes:</p>
                    <ul class="mt-3 space-y-1">
                        <li>Authenticating your identity and maintaining your account</li>
                        <li>Displaying your profile to organizers and co-volunteers in events you have joined</li>
                        <li>Enabling event registration, volunteer matching, and participation tracking</li>
                        <li>Generating and validating QR code check-in tickets for event attendance</li>
                        <li>Facilitating direct and group messaging between platform users</li>
                        <li>Processing feedback, poll responses, and event suggestions</li>
                        <li>Verifying organizer credentials and managing organizer accounts</li>
                        <li>Improving platform features and user experience through anonymized analytics</li>
                        <li>Complying with legal obligations under Philippine law</li>
                    </ul>
                </div>

                <!-- Section 5 -->
                <div id="legal-basis" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">5</span> Legal Basis for Processing</h2>
                    <p>Under the Philippine Data Privacy Act of 2012, we process your personal data on the following legal bases:</p>
                    <ul class="mt-3 space-y-1">
                        <li><strong>Consent</strong> — By signing in with Google and using the Platform, you consent to the collection and processing described in this Policy</li>
                        <li><strong>Contractual necessity</strong> — Processing is necessary to fulfill the services provided through ServeDavao</li>
                        <li><strong>Legitimate interests</strong> — To protect the security, integrity, and proper functioning of the Platform</li>
                        <li><strong>Legal compliance</strong> — Where required by applicable Philippine laws and regulations</li>
                    </ul>
                </div>

                <!-- Section 6 -->
                <div id="sharing" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">6</span> Data Sharing</h2>
                    <p>We do <strong>not sell, rent, or trade</strong> your personal data. We may share your data only in the following limited circumstances:</p>
                    <ul class="mt-3 space-y-1">
                        <li><strong>With organizers</strong> — Your name, profile photo, and contact details are visible to organizers of events you have registered for, for coordination purposes</li>
                        <li><strong>With co-volunteers</strong> — Your name and profile photo may be visible to other registered volunteers in shared event group chats</li>
                        <li><strong>Google LLC</strong> — We use Google OAuth for authentication; Google's own <a href="https://policies.google.com/privacy" target="_blank" class="text-blue-600 hover:underline">Privacy Policy</a> governs their data practices</li>
                        <li><strong>Legal authorities</strong> — We may disclose data if required by Philippine law, court order, or legitimate government request</li>
                        <li><strong>Platform administrators</strong> — Authorized platform administrators may access data for support, moderation, and maintenance purposes</li>
                    </ul>
                </div>

                <!-- Section 7 -->
                <div id="retention" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">7</span> Data Retention</h2>
                    <p>We retain your personal data for as long as your account is active or as needed to provide services. Specific retention periods:</p>
                    <ul class="mt-3 space-y-1">
                        <li><strong>Account data</strong> — Retained for the duration of your account; deleted within 30 days of account deletion request</li>
                        <li><strong>Attendance records</strong> — Retained for up to 2 years for operational and compliance purposes</li>
                        <li><strong>Messages</strong> — Retained while participants remain active; may be retained for up to 1 year after deletion for audit purposes</li>
                        <li><strong>Feedback and polls</strong> — Aggregated and anonymized data may be retained indefinitely for platform improvement</li>
                        <li><strong>System logs</strong> — Retained for up to 90 days for security and debugging purposes</li>
                    </ul>
                </div>

                <!-- Section 8 -->
                <div id="security" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">8</span> Data Security</h2>
                    <p>We implement appropriate technical and organizational measures to protect your personal data:</p>
                    <ul class="mt-3 space-y-1">
                        <li>Authentication exclusively via Google OAuth 2.0 — no passwords stored on our servers</li>
                        <li>QR check-in URLs are cryptographically signed to prevent tampering</li>
                        <li>Session data is encrypted and stored securely</li>
                        <li>Access to personal data is restricted to authorized platform personnel only</li>
                        <li>Sensitive routes are protected by authentication and authorization middleware</li>
                    </ul>
                    <p class="mt-3">While we take reasonable precautions, no system is completely secure. We cannot guarantee absolute security of data transmitted over the internet.</p>
                </div>

                <!-- Section 9 -->
                <div id="rights" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">9</span> Your Rights Under RA 10173</h2>
                    <p>As a data subject under the Philippine Data Privacy Act of 2012, you have the following rights:</p>
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 text-sm">
                            <p class="font-semibold text-blue-800 mb-1 flex items-center gap-1.5"><i class="bi bi-eye"></i> Right to Access</p>
                            <p class="text-blue-700 text-xs">Request a copy of the personal data we hold about you.</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 text-sm">
                            <p class="font-semibold text-blue-800 mb-1 flex items-center gap-1.5"><i class="bi bi-pencil"></i> Right to Rectification</p>
                            <p class="text-blue-700 text-xs">Correct inaccurate or incomplete personal data about you.</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 text-sm">
                            <p class="font-semibold text-blue-800 mb-1 flex items-center gap-1.5"><i class="bi bi-trash"></i> Right to Erasure</p>
                            <p class="text-blue-700 text-xs">Request deletion of your personal data, subject to legal retention requirements.</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 text-sm">
                            <p class="font-semibold text-blue-800 mb-1 flex items-center gap-1.5"><i class="bi bi-slash-circle"></i> Right to Object</p>
                            <p class="text-blue-700 text-xs">Object to the processing of your data for specific purposes.</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 text-sm">
                            <p class="font-semibold text-blue-800 mb-1 flex items-center gap-1.5"><i class="bi bi-download"></i> Right to Portability</p>
                            <p class="text-blue-700 text-xs">Receive your personal data in a structured, machine-readable format.</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 text-sm">
                            <p class="font-semibold text-blue-800 mb-1 flex items-center gap-1.5"><i class="bi bi-exclamation-triangle"></i> Right to Complain</p>
                            <p class="text-blue-700 text-xs">Lodge a complaint with the National Privacy Commission (NPC) of the Philippines.</p>
                        </div>
                    </div>
                    <p class="mt-4 text-sm">To exercise any of these rights, please contact us using the details in <a href="#contact-privacy" class="text-blue-600 hover:underline">Section 14</a>.</p>
                </div>

                <!-- Section 10 -->
                <div id="google" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">10</span> Google OAuth & Third-Party Services</h2>
                    <p>ServeDavao uses Google OAuth 2.0 as the sole method of authentication. When you sign in:</p>
                    <ul class="mt-3 space-y-1">
                        <li>Google shares your name, email address, and profile photo with ServeDavao</li>
                        <li>We do not receive your Google password, payment information, or any other Google account data</li>
                        <li>Your use of Google's services is governed by <a href="https://policies.google.com/terms" target="_blank" class="text-blue-600 hover:underline">Google's Terms of Service</a> and <a href="https://policies.google.com/privacy" target="_blank" class="text-blue-600 hover:underline">Privacy Policy</a></li>
                        <li>You can revoke ServeDavao's access to your Google account at any time via your <a href="https://myaccount.google.com/permissions" target="_blank" class="text-blue-600 hover:underline">Google Account permissions</a></li>
                    </ul>
                    <p class="mt-3">Revoking Google access will prevent future logins but will not automatically delete your ServeDavao account data.</p>
                </div>

                <!-- Section 11 -->
                <div id="cookies" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">11</span> Cookies & Session Data</h2>
                    <p>ServeDavao uses the following types of cookies and session data:</p>
                    <ul class="mt-3 space-y-1">
                        <li><strong>Session cookies</strong> — Required for authentication and maintaining your logged-in state; expire when you close your browser or log out</li>
                        <li><strong>CSRF tokens</strong> — Used to protect against cross-site request forgery attacks; required for form submissions</li>
                        <li><strong>Remember-me cookies</strong> — Optional persistent cookies that keep you signed in across browser sessions</li>
                    </ul>
                    <p class="mt-3">We do not use third-party advertising cookies or cross-site tracking cookies. You may disable cookies in your browser settings, but this may impair Platform functionality.</p>
                </div>

                <!-- Section 12 -->
                <div id="minors" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">12</span> Minors & Children's Privacy</h2>
                    <p>ServeDavao is intended for users aged <strong>15 years and above</strong>. Users under 18 years of age must have consent from a parent or legal guardian to use the Platform.</p>
                    <p class="mt-3">We do not knowingly collect personal data from children under 13 years of age. If we become aware that a user under 13 has provided personal data without parental consent, we will delete that data promptly.</p>
                    <p class="mt-3">If you believe a minor has provided personal data without proper consent, please contact us immediately.</p>
                </div>

                <!-- Section 13 -->
                <div id="changes-policy" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">13</span> Changes to This Policy</h2>
                    <p>We may update this Privacy Policy from time to time to reflect changes in our practices, the Platform's features, or applicable laws. When we make material changes, we will:</p>
                    <ul class="mt-3 space-y-1">
                        <li>Update the "Last Updated" date at the top of this page</li>
                        <li>Notify active users through the Platform where required by law</li>
                    </ul>
                    <p class="mt-3">Your continued use of ServeDavao after any changes are posted constitutes your acceptance of the revised Privacy Policy.</p>
                </div>

                <!-- Section 14 -->
                <div id="contact-privacy" class="section-anchor bg-white rounded-2xl shadow-sm border border-gray-200 p-7 prose-section">
                    <h2><span class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0">14</span> Contact & Data Requests</h2>
                    <p>For any privacy-related questions, data access requests, or to exercise your rights under RA 10173, please contact us:</p>
                    <div class="mt-4 bg-blue-50 rounded-xl p-5 border border-blue-100">
                        <p class="font-semibold text-blue-800 text-base mb-3 flex items-center gap-2">
                            <i class="bi bi-shield-lock-fill"></i> ServeDavao Data Privacy Officer
                        </p>
                        <div class="space-y-2 text-sm text-gray-700">
                            <p><i class="bi bi-geo-alt-fill text-blue-500 mr-2"></i> Davao City, Philippines</p>
                            <p><i class="bi bi-globe text-blue-500 mr-2"></i> <a href="{{ url('/') }}" class="text-blue-600 hover:underline">{{ url('/') }}</a></p>
                        </div>
                    </div>
                    <p class="mt-4 text-sm">You also have the right to file a complaint with the <strong>National Privacy Commission (NPC)</strong> of the Philippines at <a href="https://www.privacy.gov.ph" target="_blank" class="text-blue-600 hover:underline">www.privacy.gov.ph</a> if you believe your data privacy rights have been violated.</p>
                </div>

                <!-- Footer CTA -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-8 text-center text-white">
                    <h3 class="text-xl font-bold mb-2">Your data, your rights.</h3>
                    <p class="text-blue-100 text-sm mb-5">We are committed to keeping your personal information safe and transparent.</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-white text-blue-700 font-semibold px-6 py-3 rounded-xl hover:bg-blue-50 transition-colors shadow-md">
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
                <a href="{{ route('terms') }}" class="text-gray-500 hover:text-blue-600 transition-colors">Terms of Service</a>
                <span class="text-blue-600 font-medium">Privacy Policy</span>
                <a href="{{ route('landing') }}" class="text-gray-500 hover:text-blue-600 transition-colors">Home</a>
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
