<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="ServeDavao connects passionate volunteers with meaningful community events across Davao City. Join us to make a difference.">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/logoDav.png') }}">
  <title>ServeDavao: Volunteer &amp; Event Management</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  <style>
    *, *::before, *::after { box-sizing: border-box; }
    html {
      scroll-behavior: smooth;
      scroll-snap-type: y mandatory;
      overflow-y: scroll;
      height: 100%;
    }
    body {
      font-family: 'Inter', sans-serif;
      margin: 0; padding: 0; height: 100%; overflow-x: hidden;
    }
    .vp-section {
      min-height: 100dvh;
      height: 100dvh;
      scroll-snap-align: start;
      scroll-snap-stop: always;
      overflow: hidden;
      position: relative;
      display: flex;
      flex-direction: column;
    }
    /* INTRO LOADING */
    #intro-loading {
      position:fixed; inset:0; z-index:999999; background:#ffffff;
      display:flex; flex-direction:column; align-items:center; justify-content:center;
      overflow:hidden; transition:transform 0.8s cubic-bezier(0.85,0,0.15,1),opacity 0.8s ease;
    }
    .intro-slide-up { transform:translateY(-100%); pointer-events:none; }
    .scale-up-subtle { animation:scaleUp 1s cubic-bezier(0.16,1,0.3,1) both; }
    @keyframes scaleUp { 0%{transform:scale(0.95);opacity:0} 100%{transform:scale(1);opacity:1} }
    .float-gentle { animation:floatGentle 4s ease-in-out infinite; }
    @keyframes floatGentle { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
    .fade-in-anim { animation:fadeIn 1.5s ease both; }
    @keyframes fadeIn { 0%{opacity:0} 100%{opacity:0.3} }
    .reveal-text-up { animation:revealTextUp 0.8s cubic-bezier(0.77,0,0.175,1) both; }
    @keyframes revealTextUp { 0%{transform:translateY(100%);opacity:0} 100%{transform:translateY(0);opacity:1} }
    .reveal-fade { animation:revealFade 0.8s ease both; }
    @keyframes revealFade { 0%{opacity:0;transform:translateY(10px)} 100%{opacity:1;transform:translateY(0)} }
    .animate-pulse-slow { animation:pulseSlow 4s ease-in-out infinite; }
    @keyframes pulseSlow { 0%,100%{opacity:0.2;transform:scale(1)} 50%{opacity:0.4;transform:scale(1.1)} }
    .delay-200 { animation-delay:0.2s; }
    .delay-400 { animation-delay:0.4s; }
    .loading-line { width:0%; animation:loadLine 1.6s cubic-bezier(0.65,0,0.35,1) forwards; }
    @keyframes loadLine { 0%{width:0%} 40%{width:50%} 100%{width:100%} }
    /* NAVBAR */
    .site-nav {
      position:fixed; top:0; left:0; right:0; z-index:100;
      background:rgba(255,255,255,0.92);
      backdrop-filter:blur(12px); -webkit-backdrop-filter:blur(12px);
      box-shadow:0 1px 24px rgba(0,0,0,0.07); transition:all 0.4s ease;
    }
    .nav-inner { max-width:1280px; margin:0 auto; padding:0.6rem 1.5rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; }
    .nav-logo { display:flex; align-items:center; gap:0.6rem; text-decoration:none; }
    .nav-logo img { width:44px; height:44px; border-radius:50%; object-fit:cover; transition:transform 0.3s; }
    .nav-logo img:hover { transform:scale(1.1); }
    .nav-logo-text { font-weight:800; font-size:1.15rem; color:#1f2937; }
    .nav-logo-text span { color:#059669; }
    .nav-links { display:flex; align-items:center; gap:2rem; list-style:none; margin:0; padding:0; }
    .nav-links a { color:#374151; font-weight:500; font-size:0.9rem; text-decoration:none; transition:color 0.2s; }
    .nav-links a:hover { color:#059669; }
    .nav-actions { display:flex; align-items:center; gap:0.75rem; }
    .btn-login { font-size:0.88rem; font-weight:600; color:#374151; text-decoration:none; transition:color 0.2s; }
    .btn-login:hover { color:#059669; }
    .btn-cta { background:#059669; color:#fff; font-size:0.88rem; font-weight:600; padding:0.5rem 1.2rem; border-radius:8px; text-decoration:none; transition:background 0.2s,transform 0.2s; }
    .btn-cta:hover { background:#047857; transform:translateY(-1px); }
    .nav-hamburger { display:none; background:none; border:none; cursor:pointer; padding:0.4rem; color:#374151; font-size:1.4rem; }
    .nav-mobile-menu { display:none; flex-direction:column; background:rgba(255,255,255,0.98); backdrop-filter:blur(12px); border-top:1px solid #e5e7eb; padding:1rem 1.5rem 1.5rem; gap:1rem; }
    .nav-mobile-menu.open { display:flex; }
    .nav-mobile-menu a { color:#374151; font-weight:500; font-size:1rem; text-decoration:none; padding:0.4rem 0; border-bottom:1px solid #f3f4f6; }
    .nav-mobile-menu a:hover { color:#059669; }
    .nav-mobile-menu .mobile-cta { background:#059669; color:#fff; text-align:center; padding:0.75rem; border-radius:8px; margin-top:0.5rem; font-weight:600; border-bottom:none; }
    @media (max-width:767px) { .nav-links,.nav-actions{display:none} .nav-hamburger{display:block} }
    /* HERO */
    .hero-section { background-color:#0a0a0a; }
    .hero-bg-img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; animation:kenBurns 20s ease-in-out infinite; }
    @keyframes kenBurns { 0%{transform:scale(1)} 50%{transform:scale(1.08)} 100%{transform:scale(1)} }
    .hero-overlay { position:absolute; inset:0; background:linear-gradient(to bottom right,rgba(0,0,0,0.60),rgba(5,120,87,0.45)); }
    .hero-content { position:relative; z-index:2; flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; padding:6rem 1.5rem 3rem; color:#fff; }
    .hero-content h1 { font-size:clamp(2rem,6vw,4rem); font-weight:900; line-height:1.1; margin-bottom:1rem; text-shadow:0 2px 24px rgba(0,0,0,0.4); }
    .hero-content p { font-size:clamp(1rem,2.5vw,1.25rem); max-width:38rem; opacity:0.9; margin-bottom:2rem; line-height:1.6; }
    .hero-cta { display:inline-flex; align-items:center; gap:0.5rem; background:#059669; color:#fff; font-weight:700; font-size:1rem; padding:0.85rem 2rem; border-radius:10px; text-decoration:none; box-shadow:0 8px 32px rgba(5,150,105,0.4); transition:all 0.3s; }
    .hero-cta:hover { background:#047857; transform:translateY(-3px); box-shadow:0 12px 40px rgba(5,150,105,0.5); }
    .scroll-hint { position:absolute; bottom:2rem; left:50%; transform:translateX(-50%); display:flex; flex-direction:column; align-items:center; gap:0.4rem; color:rgba(255,255,255,0.6); font-size:0.75rem; font-weight:500; letter-spacing:0.08em; text-transform:uppercase; animation:bounceDown 2s ease-in-out infinite; z-index:2; }
    .scroll-hint i { font-size:1.2rem; }
    @keyframes bounceDown { 0%,100%{transform:translateX(-50%) translateY(0)} 50%{transform:translateX(-50%) translateY(6px)} }
    /* SECTION INNER */
    .section-inner { flex:1; overflow-y:auto; overflow-x:hidden; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:5rem 1.5rem 3rem; -webkit-overflow-scrolling:touch; scrollbar-width:thin; scrollbar-color:#a7f3d0 transparent; }
    .section-inner::-webkit-scrollbar { width:4px; }
    .section-inner::-webkit-scrollbar-thumb { background:#a7f3d0; border-radius:99px; }
    .section-label { font-size:0.75rem; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:#059669; margin-bottom:0.75rem; }
    .section-heading { font-size:clamp(1.75rem,4vw,2.75rem); font-weight:900; color:#111827; text-align:center; line-height:1.15; margin-bottom:1rem; }
    .section-heading span { color:#059669; }
    .section-subtext { max-width:42rem; text-align:center; color:#6b7280; font-size:clamp(0.95rem,2vw,1.1rem); line-height:1.7; margin-bottom:2.5rem; }
    /* ABOUT */
    .about-section { background:#ffffff; }
    .features-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:1.25rem; width:100%; max-width:900px; }
    .feature-card { background:#f9fafb; border:1px solid #e5e7eb; border-radius:16px; padding:2rem 1.5rem; text-align:center; transition:all 0.35s cubic-bezier(0.4,0,0.2,1); }
    .feature-card:hover { transform:translateY(-6px); box-shadow:0 16px 40px -8px rgba(5,150,105,0.15); border-color:#a7f3d0; }
    .feature-card .fc-icon { width:56px; height:56px; background:linear-gradient(135deg,#d1fae5,#a7f3d0); border-radius:14px; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; font-size:1.6rem; color:#059669; }
    .feature-card h4 { font-size:1.05rem; font-weight:700; color:#111827; margin-bottom:0.5rem; }
    .feature-card p { font-size:0.9rem; color:#6b7280; line-height:1.6; }
    .stats-row { display:flex; flex-wrap:wrap; justify-content:center; gap:2rem; margin-top:2rem; max-width:700px; }
    .stat-item { text-align:center; }
    .stat-item .stat-num { font-size:clamp(1.8rem,4vw,2.5rem); font-weight:900; color:#059669; display:block; }
    .stat-item .stat-lbl { font-size:0.8rem; font-weight:600; text-transform:uppercase; letter-spacing:0.08em; color:#9ca3af; }
    /* CONTACT */
    .contact-section { background:#f0fdf4; }
    .contact-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; width:100%; max-width:1100px; margin:0 auto; }
    @media (max-width:900px) { .contact-grid{grid-template-columns:1fr} }
    .contact-info-card { background:#fff; border-radius:20px; padding:2rem; border:1px solid #d1fae5; display:flex; flex-direction:column; gap:1.25rem; }
    .contact-info-card h3 { font-size:1.2rem; font-weight:800; color:#111827; margin:0 0 0.25rem; }
    .contact-info-card > p { font-size:0.9rem; color:#6b7280; margin:0; }
    .ci-row { display:flex; align-items:flex-start; gap:1rem; }
    .ci-icon { width:40px; height:40px; min-width:40px; background:#d1fae5; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#059669; font-size:1rem; }
    .ci-row h4 { font-size:0.85rem; font-weight:700; color:#111827; margin:0 0 0.2rem; }
    .ci-row p  { font-size:0.85rem; color:#6b7280; margin:0; }
    /* FAQ CHATBOT */
    #faq-chatbot { display:flex; flex-direction:column; height:100%; min-height:320px; max-height:480px; border-radius:20px; overflow:hidden; box-shadow:0 8px 32px rgba(5,150,105,0.12); background:#fff; border:1px solid #d1fae5; }
    #faq-chatbot-header { background:linear-gradient(135deg,#059669 0%,#047857 100%); padding:0.9rem 1.25rem; display:flex; align-items:center; gap:0.75rem; flex-shrink:0; }
    #faq-chatbot-header .bot-avatar { width:36px; height:36px; background:rgba(255,255,255,0.2); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1rem; color:#fff; flex-shrink:0; }
    #faq-chatbot-header .bot-info h4 { color:#fff; font-weight:700; font-size:0.9rem; margin:0; }
    #faq-chatbot-header .bot-info p  { color:rgba(255,255,255,0.8); font-size:0.72rem; margin:0; }
    #faq-chatbot-header .online-dot  { width:8px; height:8px; background:#6ee7b7; border-radius:50%; animation:pulseDot 1.8s infinite; margin-left:auto; }
    @keyframes pulseDot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.5;transform:scale(1.3)} }
    #faq-messages { flex:1; overflow-y:auto; padding:0.9rem; display:flex; flex-direction:column; gap:0.65rem; background:#f0fdf4; scrollbar-width:thin; scrollbar-color:#a7f3d0 transparent; }
    #faq-messages::-webkit-scrollbar { width:4px; }
    #faq-messages::-webkit-scrollbar-thumb { background:#a7f3d0; border-radius:99px; }
    .faq-msg { display:flex; gap:0.5rem; align-items:flex-end; animation:msgIn 0.25s ease; }
    @keyframes msgIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
    .faq-msg.bot { flex-direction:row; }
    .faq-msg.user { flex-direction:row-reverse; }
    .faq-msg .msg-avatar { width:26px; height:26px; border-radius:50%; background:#059669; color:#fff; font-size:0.65rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .faq-msg.user .msg-avatar { background:#6b7280; }
    .faq-bubble { max-width:82%; padding:0.55rem 0.85rem; border-radius:1.1rem; font-size:0.82rem; line-height:1.5; }
    .faq-msg.bot  .faq-bubble { background:#fff; color:#1f2937; border-bottom-left-radius:4px; box-shadow:0 2px 8px rgba(0,0,0,0.07); }
    .faq-msg.user .faq-bubble { background:#059669; color:#fff; border-bottom-right-radius:4px; box-shadow:0 2px 8px rgba(5,150,105,0.25); }
    #faq-quick-btns { padding:0.4rem 0.9rem 0; display:flex; flex-wrap:wrap; gap:0.35rem; background:#f0fdf4; flex-shrink:0; }
    .faq-quick-btn { background:#d1fae5; color:#065f46; border:1px solid #a7f3d0; border-radius:999px; padding:0.25rem 0.65rem; font-size:0.72rem; font-weight:500; cursor:pointer; transition:all 0.2s; }
    .faq-quick-btn:hover { background:#059669; color:#fff; border-color:#059669; transform:translateY(-1px); }
    #faq-input-area { display:flex; align-items:center; gap:0.5rem; padding:0.65rem 0.9rem; background:#fff; border-top:1px solid #d1fae5; flex-shrink:0; }
    #faq-input { flex:1; border:1.5px solid #d1fae5; border-radius:999px; padding:0.45rem 0.9rem; font-size:0.82rem; outline:none; transition:border-color 0.2s; background:#f0fdf4; color:#1f2937; }
    #faq-input:focus { border-color:#059669; background:#fff; }
    #faq-send-btn { width:34px; height:34px; background:#059669; border:none; border-radius:50%; color:#fff; font-size:0.85rem; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all 0.2s; flex-shrink:0; }
    #faq-send-btn:hover { background:#047857; transform:scale(1.1); }
    .typing-indicator { display:flex; align-items:center; gap:4px; padding:0.4rem 0.2rem; }
    .typing-dot { width:6px; height:6px; background:#059669; border-radius:50%; animation:typingAnim 1.2s infinite; }
    .typing-dot:nth-child(2) { animation-delay:0.2s; }
    .typing-dot:nth-child(3) { animation-delay:0.4s; }
    @keyframes typingAnim { 0%,60%,100%{transform:translateY(0);opacity:0.5} 30%{transform:translateY(-5px);opacity:1} }
    .faq-action-btns { display:flex; flex-wrap:wrap; gap:0.4rem; margin-top:0.5rem; }
    .faq-action-btn { border:none; border-radius:999px; padding:0.35rem 1rem; font-size:0.76rem; font-weight:600; cursor:pointer; transition:all 0.2s; letter-spacing:0.01em; }
    .faq-action-btn.yes { background:#059669; color:#fff; }
    .faq-action-btn.yes:hover { background:#047857; transform:translateY(-2px); box-shadow:0 4px 12px rgba(5,150,105,0.3); }
    .faq-action-btn.no  { background:#e5e7eb; color:#374151; }
    .faq-action-btn.no:hover  { background:#d1d5db; transform:translateY(-2px); }
    .faq-action-btn.restart { background:linear-gradient(135deg,#059669,#047857); color:#fff; width:100%; justify-content:center; display:flex; align-items:center; gap:0.4rem; border-radius:0.5rem; }
    .faq-action-btn.restart:hover { transform:translateY(-2px); box-shadow:0 4px 14px rgba(5,150,105,0.35); }
    .faq-action-btn:disabled { opacity:0.45; cursor:not-allowed; transform:none !important; box-shadow:none !important; }
    #faq-chatbot.input-locked #faq-input    { opacity:0.45; pointer-events:none; }
    #faq-chatbot.input-locked #faq-send-btn { opacity:0.45; pointer-events:none; }
    /* CONTACT FORM */
    .contact-form-card { background:#fff; border-radius:20px; padding:2rem; border:1px solid #d1fae5; display:flex; flex-direction:column; }
    .contact-form-card h3 { font-size:1.2rem; font-weight:800; color:#111827; margin:0 0 1.25rem; }
    .cf-row { display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; }
    @media (max-width:480px) { .cf-row{grid-template-columns:1fr} }
    .cf-label { display:block; font-size:0.8rem; font-weight:600; color:#374151; margin-bottom:0.35rem; }
    .cf-input { width:100%; padding:0.65rem 1rem; font-size:0.9rem; border:1.5px solid #d1fae5; border-radius:10px; outline:none; transition:border-color 0.2s,box-shadow 0.2s; background:#f0fdf4; color:#111827; font-family:'Inter',sans-serif; }
    .cf-input:focus { border-color:#059669; box-shadow:0 0 0 3px rgba(5,150,105,0.12); background:#fff; }
    .cf-input::placeholder { color:#9ca3af; }
    .cf-group { display:flex; flex-direction:column; gap:0.35rem; }
    .btn-submit { width:100%; background:#059669; color:#fff; font-weight:700; font-size:0.95rem; padding:0.75rem; border-radius:10px; border:none; cursor:pointer; transition:all 0.25s; display:flex; align-items:center; justify-content:center; gap:0.5rem; margin-top:0.5rem; font-family:'Inter',sans-serif; }
    .btn-submit:hover { background:#047857; transform:translateY(-2px); box-shadow:0 8px 24px rgba(5,150,105,0.3); }
    /* FOOTER */
    .site-footer { background:#111827; padding:3rem 1.5rem; text-align:center; color:#9ca3af; }
    .footer-logo { display:flex; align-items:center; justify-content:center; gap:0.75rem; margin-bottom:1rem; }
    .footer-logo img { width:44px; height:44px; border-radius:50%; object-fit:cover; }
    .footer-logo-text { font-size:1.3rem; font-weight:800; color:#fff; }
    .footer-logo-text span { color:#34d399; }
    .footer-tagline { font-size:0.9rem; max-width:32rem; margin:0 auto 1.5rem; line-height:1.7; }
    .footer-social { display:flex; justify-content:center; gap:1.25rem; margin-bottom:1.5rem; }
    .footer-social a { width:38px; height:38px; background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); border-radius:10px; display:flex; align-items:center; justify-content:center; color:#9ca3af; font-size:1.1rem; text-decoration:none; transition:all 0.25s; }
    .footer-social a:hover { background:#059669; border-color:#059669; color:#fff; transform:translateY(-2px); }
    .footer-copy { font-size:0.8rem; color:#6b7280; }
    /* SCROLL ANIMATIONS */
    .scroll-fade-in { opacity:0; transform:translateY(28px); transition:opacity 0.7s ease,transform 0.7s ease; }
    .scroll-fade-in.visible { opacity:1; transform:translateY(0); }
    .stagger-animate > * { opacity:0; transform:translateY(24px); transition:opacity 0.55s ease,transform 0.55s ease; }
    .stagger-animate.visible > * { opacity:1; transform:translateY(0); }
    .stagger-animate.visible > *:nth-child(1) { transition-delay:0.1s; }
    .stagger-animate.visible > *:nth-child(2) { transition-delay:0.2s; }
    .stagger-animate.visible > *:nth-child(3) { transition-delay:0.3s; }
    .stagger-animate.visible > *:nth-child(4) { transition-delay:0.4s; }
    /* DOT NAV */
    .dot-nav { position:fixed; right:1.25rem; top:50%; transform:translateY(-50%); z-index:90; display:flex; flex-direction:column; gap:0.6rem; }
    .dot-nav a { width:10px; height:10px; border-radius:50%; background:rgba(255,255,255,0.35); border:2px solid rgba(255,255,255,0.6); display:block; transition:all 0.3s; }
    .dot-nav a.active,.dot-nav a:hover { background:#059669; border-color:#059669; transform:scale(1.3); }
    .dot-nav a.dark { background:rgba(0,0,0,0.2); border-color:rgba(0,0,0,0.35); }
    .dot-nav a.dark.active,.dot-nav a.dark:hover { background:#059669; border-color:#059669; }
    @media (max-width:640px) { .dot-nav{display:none} }
    .hidden { display:none !important; }
  </style>
</head>
<body>

<!-- INTRO LOADING -->
<div id="intro-loading">
  <div style="position:absolute;inset:0;background:radial-gradient(circle at center,#d1fae5 0%,#ffffff 100%);opacity:0.3;" class="fade-in-anim"></div>
  <div class="scale-up-subtle" style="display:flex;flex-direction:column;align-items:center;position:relative;z-index:1;">
    <div style="margin-bottom:2rem;position:relative;display:flex;align-items:center;justify-content:center;">
      <div class="animate-pulse-slow" style="position:absolute;inset:-10px;background:#34d399;border-radius:50%;filter:blur(24px);opacity:0.2;"></div>
      <img src="{{ asset('assets/img/logoDav.png') }}" alt="ServeDavao Logo" class="float-gentle"
           style="width:100px;height:100px;object-fit:contain;position:relative;z-index:1;filter:drop-shadow(0 8px 20px rgba(5,150,105,0.3));">
    </div>
    <div style="overflow:hidden;margin-bottom:0.5rem;">
      <h2 class="reveal-text-up" style="font-size:2.5rem;font-weight:900;color:#1f2937;margin:0;">Serve<span style="color:#059669;">Davao</span></h2>
    </div>
    <div style="overflow:hidden;margin-bottom:3rem;">
      <p class="reveal-text-up delay-200" style="font-size:0.7rem;font-weight:700;letter-spacing:0.3em;text-transform:uppercase;color:#9ca3af;margin:0;">Empowering Volunteers</p>
    </div>
    <div class="reveal-fade delay-400" style="width:180px;height:3px;background:#e5e7eb;border-radius:99px;overflow:hidden;">
      <div class="loading-line" style="height:100%;background:#059669;border-radius:99px;"></div>
    </div>
  </div>
</div>
<script>
  window.addEventListener('load', function () {
    setTimeout(() => {
      const loader = document.getElementById('intro-loading');
      if (loader) { loader.classList.add('intro-slide-up'); setTimeout(() => loader.remove(), 850); }
    }, 1800);
  });
</script>

<!-- DOT NAV -->
<nav class="dot-nav" aria-label="Page sections">
  <a href="#home"    class="active" data-section="home"    title="Home"></a>
  <a href="#about"   class="dark"   data-section="about"   title="About"></a>
  <a href="#contact" class="dark"   data-section="contact" title="Contact"></a>
</nav>

<!-- NAVBAR -->
<nav class="site-nav" id="site-nav">
  <div class="nav-inner">
    <a href="#home" class="nav-logo">
      <img src="{{ asset('assets/img/logoDav.png') }}" alt="ServeDavao Logo">
      <span class="nav-logo-text">Serve<span>Davao</span></span>
    </a>
    <ul class="nav-links">
      <li><a href="#home">Home</a></li>
      <li><a href="#about">About</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
    <div class="nav-actions">
      <a href="/login" class="btn-login">Login</a>
      <a href="{{ auth()->check() ? route('events.index') : route('login') }}" class="btn-cta">
        {{ auth()->check() ? 'Go to Dashboard' : 'Get Started' }}
      </a>
    </div>
    <button class="nav-hamburger" id="nav-hamburger" aria-label="Open menu"><i class="bi bi-list"></i></button>
  </div>
  <div class="nav-mobile-menu" id="nav-mobile-menu">
    <a href="#home"    onclick="closeMobileMenu()">Home</a>
    <a href="#about"   onclick="closeMobileMenu()">About</a>
    <a href="#contact" onclick="closeMobileMenu()">Contact</a>
    <a href="/login"   onclick="closeMobileMenu()">Login</a>
    <a href="{{ auth()->check() ? route('events.index') : route('login') }}" class="mobile-cta" onclick="closeMobileMenu()">
      {{ auth()->check() ? 'Go to Dashboard' : 'Get Started' }}
    </a>
  </div>
</nav>
<script>
  const hamburger  = document.getElementById('nav-hamburger');
  const mobileMenu = document.getElementById('nav-mobile-menu');
  function closeMobileMenu() { mobileMenu.classList.remove('open'); hamburger.querySelector('i').className='bi bi-list'; }
  hamburger.addEventListener('click', () => {
    const open = mobileMenu.classList.toggle('open');
    hamburger.querySelector('i').className = open ? 'bi bi-x-lg' : 'bi bi-list';
  });
</script>

<!-- SECTION 1: HERO -->
<section id="home" class="vp-section hero-section">
  <img src="{{ asset('assets/img/hero1.png') }}" alt="" class="hero-bg-img" loading="eager">
  <div class="hero-overlay"></div>
  <div class="hero-content scroll-fade-in visible">
    <h1>Empower Davao<br>Through Volunteerism</h1>
    <p>Join ServeDavao to make an impact in your community &mdash; connecting volunteers with meaningful events across the city.</p>
    <a href="{{ auth()->check() ? route('events.index') : route('login') }}" class="hero-cta">
      {{ auth()->check() ? 'Explore Events' : 'Get Started' }}
      <i class="bi bi-arrow-right"></i>
    </a>
  </div>
  <div class="scroll-hint">
    <span>Scroll</span>
    <i class="bi bi-chevron-down"></i>
  </div>
</section>

<!-- SECTION 2: ABOUT -->
<section id="about" class="vp-section about-section">
  <div class="section-inner">
    <p class="section-label scroll-fade-in">Who We Are</p>
    <h2 class="section-heading scroll-fade-in">About <span>ServeDavao</span></h2>
    <p class="section-subtext scroll-fade-in">
      ServeDavao bridges volunteers and organizers for social good. Volunteers can register, browse opportunities,
      and log their service hours &mdash; while organizers post events, verify participation, and generate reports.
    </p>
    <div class="features-grid stagger-animate">
      <div class="feature-card">
        <div class="fc-icon"><i class="bi bi-people-fill"></i></div>
        <h4>Community Driven</h4>
        <p>We connect passionate individuals with causes that matter most to their communities.</p>
      </div>
      <div class="feature-card">
        <div class="fc-icon"><i class="bi bi-calendar-event-fill"></i></div>
        <h4>Event Management</h4>
        <p>Organizers can easily create, manage, and track volunteer activities in one place.</p>
      </div>
      <div class="feature-card">
        <div class="fc-icon"><i class="bi bi-shield-check"></i></div>
        <h4>Secure Platform</h4>
        <p>All data is securely handled with AI-powered verification to protect every user.</p>
      </div>
      <div class="feature-card">
        <div class="fc-icon"><i class="bi bi-graph-up-arrow"></i></div>
        <h4>Track Impact</h4>
        <p>Monitor volunteer hours, certify attendance via QR, and celebrate community impact.</p>
      </div>
    </div>
    <div class="stats-row scroll-fade-in">
      <div class="stat-item"><span class="stat-num">500+</span><span class="stat-lbl">Volunteers</span></div>
      <div class="stat-item"><span class="stat-num">80+</span><span class="stat-lbl">Events</span></div>
      <div class="stat-item"><span class="stat-num">2,400+</span><span class="stat-lbl">Hours Served</span></div>
      <div class="stat-item"><span class="stat-num">15+</span><span class="stat-lbl">Organizations</span></div>
    </div>
  </div>
</section>

<!-- SECTION 3: CONTACT -->
<section id="contact" class="vp-section contact-section">
  <div class="section-inner">
    <p class="section-label scroll-fade-in">Reach Out</p>
    <h2 class="section-heading scroll-fade-in">Contact <span>Us</span></h2>
    <p class="section-subtext scroll-fade-in" style="margin-bottom:1.75rem;">
      Have questions or want to get involved? We&rsquo;d love to hear from you.
    </p>
    <div class="contact-grid">
      <!-- LEFT: info + chatbot -->
      <div style="display:flex;flex-direction:column;gap:1.25rem;">
        <div class="contact-info-card scroll-fade-in">
          <div><h3>Get In Touch</h3><p>Whether you&rsquo;re an organizer or volunteer, reach out with any questions.</p></div>
          <div class="ci-row"><div class="ci-icon"><i class="bi bi-geo-alt-fill"></i></div><div><h4>Our Location</h4><p>Davao City, Philippines</p></div></div>
          <div class="ci-row"><div class="ci-icon"><i class="bi bi-envelope-fill"></i></div><div><h4>Email Us</h4><p>contact@servedavao.org</p></div></div>
          <div class="ci-row"><div class="ci-icon"><i class="bi bi-telephone-fill"></i></div><div><h4>Call Us</h4><p>+63 82 123 4567</p></div></div>
          <div class="ci-row"><div class="ci-icon"><i class="bi bi-clock-fill"></i></div><div><h4>Response Time</h4><p>Typically within 24 hours</p></div></div>
        </div>
        <div id="faq-chatbot" class="scroll-fade-in">
          <div id="faq-chatbot-header">
            <div class="bot-avatar"><i class="bi bi-robot"></i></div>
            <div class="bot-info"><h4>ServeDavao Assistant</h4><p>Ask me anything about the platform</p></div>
            <span class="online-dot"></span>
          </div>
          <div id="faq-messages"></div>
          <div id="faq-quick-btns">
            <button class="faq-quick-btn" data-q="How do I become a volunteer?">&#128587; Volunteer</button>
            <button class="faq-quick-btn" data-q="Can organizations post events?">&#127968; Post events</button>
            <button class="faq-quick-btn" data-q="Is ServeDavao free?">&#128176; Free?</button>
            <button class="faq-quick-btn" data-q="How do I track my volunteer hours?">&#9200; Hours</button>
          </div>
          <div id="faq-input-area">
            <input id="faq-input" type="text" placeholder="Type your question..." autocomplete="off" />
            <button id="faq-send-btn"><i class="bi bi-send-fill"></i></button>
          </div>
        </div>
      </div>
      <!-- RIGHT: Contact Form -->
      <div class="contact-form-card scroll-fade-in">
        <h3>Send Us a Message</h3>
        <form id="contactFormSecure" action="{{ route('contact.submit') }}" method="POST" style="display:flex;flex-direction:column;gap:0.9rem;flex:1;">
          @csrf
          <div style="display:none;"><input type="text" name="website" tabindex="-1" autocomplete="off"><input type="url" name="url" tabindex="-1" autocomplete="off"></div>
          <div class="cf-row">
            <div class="cf-group"><label for="firstName" class="cf-label">First Name *</label><input type="text" id="firstName" name="firstName" required class="cf-input" placeholder="First name"></div>
            <div class="cf-group"><label for="lastName"  class="cf-label">Last Name *</label><input type="text"  id="lastName"  name="lastName"  required class="cf-input" placeholder="Last name"></div>
          </div>
          <div class="cf-group"><label for="email"   class="cf-label">Email Address *</label><input type="email" id="email"   name="email"   required class="cf-input" placeholder="your.email@example.com"></div>
          <div class="cf-group">
            <label for="subject" class="cf-label">Subject *</label>
            <select id="subject" name="subject" required class="cf-input">
              <option value="" disabled selected>Select a subject</option>
              <option value="volunteer">Volunteer Inquiry</option>
              <option value="organizer">Organizer Inquiry</option>
              <option value="partnership">Partnership Opportunity</option>
              <option value="technical">Technical Support</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="cf-group" style="flex:1;"><label for="message" class="cf-label">Message *</label><textarea id="message" name="message" rows="4" required class="cf-input" style="resize:none;" placeholder="Tell us how we can help you..."></textarea></div>
          <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}" style="transform:scale(0.88);transform-origin:left top;"></div>
          <button type="submit" class="btn-submit"><span>Send Message</span><i class="bi bi-send"></i></button>
        </form>
        <div id="successMessage" class="hidden" style="margin-top:1rem;padding:0.9rem 1rem;background:#d1fae5;border:1px solid #6ee7b7;border-radius:10px;display:flex;align-items:center;gap:0.5rem;color:#065f46;font-size:0.88rem;">
          <i class="bi bi-check-circle-fill"></i><p id="successText" style="margin:0;font-weight:600;">Thank you! Your message has been sent successfully.</p>
        </div>
        <div id="errorMessage" class="hidden" style="margin-top:1rem;padding:0.9rem 1rem;background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;display:flex;align-items:center;gap:0.5rem;color:#991b1b;font-size:0.88rem;">
          <i class="bi bi-exclamation-circle-fill"></i><p id="errorText" style="margin:0;font-weight:600;">There was an error sending your message. Please try again.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="site-footer">
  <div class="footer-logo">
    <img src="{{ asset('assets/img/logoDav.png') }}" alt="ServeDavao">
    <span class="footer-logo-text">Serve<span>Davao</span></span>
  </div>
  <p class="footer-tagline">Empowering communities through service, one event at a time.</p>
  <div class="footer-social">
    <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
    <a href="#" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
  </div>
  <p class="footer-copy">&copy; 2025 ServeDavao. All rights reserved.</p>
</footer>

<!-- FAQ CHATBOT SCRIPT -->
<script>
(function () {
  const faqs = [
    { label:'Become a volunteer', keywords:['become a volunteer','how to volunteer','volunteer','join'], answer:'To become a volunteer, create an account, browse events, and click Join on any event. Your participation is tracked automatically!' },
    { label:'Post events', keywords:['organization','org','post event','create event','organizer','manage event'], answer:'Yes! Organizations can register as an organizer to create, manage, and track volunteer events. Once approved, post events, verify attendance, and generate reports.' },
    { label:'Is it free?', keywords:['free','cost','price','pay','charge','fee'], answer:'ServeDavao is completely free for both volunteers and organizers. No hidden fees or subscriptions.' },
    { label:'Track hours', keywords:['track','hours','service hours','log hours','volunteer hours'], answer:'Volunteer hours are tracked automatically when an organizer verifies attendance. View totals in your profile dashboard.', followUp:{ label:'What are the benefits of always participating?', key:'benefits' } },
    { id:'benefits', keywords:['benefit','benefits','participating','always participate','reward','advantage','why volunteer'], answer:'Benefits of participating: Recognition monthly, official certificates, skill development, expanded network, and lasting community impact in Davao City.' },
    { label:'Create account', keywords:['create account','account','new user','sign up','register'], answer:'Click Get Started or Login at the top, then choose Register. Fill in your details, verify your email, and you are ready!' },
    { label:'Browse events', keywords:['what events','available','browse','find event','upcoming'], answer:'ServeDavao hosts events across Davao from environmental drives to community outreach. Browse the Events section after logging in!' },
    { label:'Current Events', keywords:['current events','ongoing events','ongoing','current event'], isCurrentEvents:true, answer:'Fetching current events...' },
    { label:'Contact us', keywords:['contact','email','phone','reach','support'], answer:'Reach us at contact@servedavao.org or call +63 82 123 4567. We respond within 24 hours!' },
    { conversational:true, keywords:['hello','hi','hey','good morning','good afternoon','good evening'], answer:'Hello! I am the ServeDavao Assistant. What would you like to know today?' },
    { conversational:true, keywords:['thank','thanks','salamat'], answer:'You are very welcome! Feel free to ask anytime. Happy volunteering!' }
  ];
  const chatbot=document.getElementById('faq-chatbot'),messagesEl=document.getElementById('faq-messages'),inputEl=document.getElementById('faq-input'),sendBtn=document.getElementById('faq-send-btn'),staticBtns=document.getElementById('faq-quick-btns');
  staticBtns.style.display='none';
  let isLocked=false,userHasInteracted=false;
  function scrollBottom(){messagesEl.scrollTop=messagesEl.scrollHeight;}
  function escapeHtml(t){if(!t)return'';return t.replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));}
  function addMsg(html,role){const w=document.createElement('div');w.className='faq-msg '+role;const av=document.createElement('div');av.className='msg-avatar';av.innerHTML=role==='bot'?'<i class="bi bi-robot"></i>':'<i class="bi bi-person-fill"></i>';const b=document.createElement('div');b.className='faq-bubble';b.innerHTML=html;w.appendChild(av);w.appendChild(b);messagesEl.appendChild(w);scrollBottom();return{wrap:w,bubble:b};}
  function addTyping(){const{wrap}=addMsg('<div class="typing-indicator"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div>','bot');wrap.id='faq-typing';return wrap;}
  function setLocked(l){isLocked=l;chatbot.classList.toggle('input-locked',l);inputEl.disabled=sendBtn.disabled=l;if(!l&&userHasInteracted)inputEl.focus({preventScroll:true});}
  function getAnswer(q){const l=q.toLowerCase();for(const f of faqs){if(f.keywords.some(k=>l.includes(k)))return f;}return null;}
  function showTopicMenu(introText){const tEl=addTyping();setTimeout(()=>{tEl.remove();const{bubble}=addMsg(introText||'Do you have other questions? Choose a topic or type below:','bot');const pw=document.createElement('div');pw.className='faq-action-btns';pw.style.marginTop='0.5rem';faqs.filter(f=>f.label).forEach(faq=>{const btn=document.createElement('button');btn.className='faq-quick-btn';btn.textContent=faq.label;btn.addEventListener('click',()=>{userHasInteracted=true;pw.querySelectorAll('button').forEach(b=>{b.disabled=true;b.style.opacity='0.45';});handleAnswer(faq.label,faq);});pw.appendChild(btn);});const dBtn=document.createElement('button');dBtn.className='faq-action-btn no';dBtn.innerHTML="No, I'm done";dBtn.addEventListener('click',()=>{userHasInteracted=true;pw.querySelectorAll('button').forEach(b=>{b.disabled=true;b.style.opacity='0.45';});addMsg("No, that's all. Thank you!",'user');const tEl2=addTyping();setTimeout(()=>{tEl2.remove();addMsg('Thank you for chatting! Happy volunteering!','bot');setTimeout(()=>{const{bubble:rb}=addMsg('','bot');const rBtn=document.createElement('button');rBtn.className='faq-action-btn restart';rBtn.innerHTML='Start a new conversation';rBtn.addEventListener('click',restartChat);rb.appendChild(rBtn);scrollBottom();},300);},950);});pw.appendChild(dBtn);bubble.appendChild(pw);scrollBottom();setLocked(false);},750);}
  function handleAnswer(userText,faqEntry){addMsg(userText,'user');setLocked(true);const tEl=addTyping();setTimeout(()=>{tEl.remove();if(faqEntry&&faqEntry.isCurrentEvents){fetch('/api/chatbot/current-events').then(r=>r.json()).then(data=>{if(data.success&&data.events&&data.events.length>0){let html='Ongoing Events (Top '+data.events.length+'):<br><br>';data.events.forEach((ev,i)=>{html+=`${i+1}. <strong>${escapeHtml(ev.title)}</strong><br>Registered: ${ev.current_volunteers}${ev.required_volunteers?' / '+ev.required_volunteers:''}<br>Location: ${escapeHtml(ev.location)}<br><a href="/events/${ev.id}" style="color:#059669;font-weight:600;" target="_blank">View Details</a><br><br>`;});addMsg(html,'bot');}else{addMsg('No ongoing events at the moment. Check back later!','bot');}setTimeout(showFollowup,450);}).catch(()=>{addMsg('Error fetching events. Please try again.','bot');setTimeout(showFollowup,450);});return;}const answer=faqEntry?faqEntry.answer:"I'm not sure about that. Contact us at contact@servedavao.org or use the form.";if(faqEntry&&faqEntry.conversational){setTimeout(()=>showTopicMenu(answer),450);return;}addMsg(answer,'bot');if(faqEntry&&faqEntry.followUp){setTimeout(()=>showSuggestedQuestion(faqEntry.followUp),500);}else{setTimeout(showFollowup,450);}},850+Math.random()*350);}
  function showSuggestedQuestion(fu){setLocked(true);const{bubble}=addMsg('Related question you might want to know:','bot');const btns=document.createElement('div');btns.className='faq-action-btns';btns.style.marginTop='0.45rem';const sBtn=document.createElement('button');sBtn.className='faq-action-btn yes';sBtn.innerHTML=fu.label;sBtn.addEventListener('click',()=>{sBtn.disabled=skBtn.disabled=true;handleAnswer(fu.label,faqs.find(f=>f.id===fu.key)||null);});const skBtn=document.createElement('button');skBtn.className='faq-action-btn no';skBtn.innerHTML='Skip';skBtn.addEventListener('click',()=>{sBtn.disabled=skBtn.disabled=true;btns.style.opacity='0.3';setTimeout(showFollowup,350);});btns.appendChild(sBtn);btns.appendChild(skBtn);bubble.appendChild(btns);scrollBottom();}
  function showFollowup(){setLocked(true);const{bubble}=addMsg('Was that helpful? Do you have any other questions?','bot');const btns=document.createElement('div');btns.className='faq-action-btns';btns.style.marginTop='0.4rem';const yBtn=document.createElement('button');yBtn.className='faq-action-btn yes';yBtn.innerHTML='Yes, I have more';yBtn.addEventListener('click',()=>{yBtn.disabled=nBtn.disabled=true;addMsg('Yes, I have more questions.','user');const tEl2=addTyping();setTimeout(()=>{tEl2.remove();showTopicMenu('Great! Let me show you the topics again.');},700);});const nBtn=document.createElement('button');nBtn.className='faq-action-btn no';nBtn.innerHTML="No, I'm done";nBtn.addEventListener('click',()=>{yBtn.disabled=nBtn.disabled=true;addMsg("No, that's all. Thank you!",'user');const tEl2=addTyping();setTimeout(()=>{tEl2.remove();addMsg('Thank you for chatting! Happy volunteering!','bot');setTimeout(()=>{const{bubble:rb}=addMsg('','bot');const rBtn=document.createElement('button');rBtn.className='faq-action-btn restart';rBtn.innerHTML='Start a new conversation';rBtn.addEventListener('click',restartChat);rb.appendChild(rBtn);scrollBottom();},300);},950);});btns.appendChild(yBtn);btns.appendChild(nBtn);bubble.appendChild(btns);scrollBottom();}
  function sendFreeText(q){if(!q.trim()||isLocked)return;userHasInteracted=true;inputEl.value='';handleAnswer(q,getAnswer(q));}
  sendBtn.addEventListener('click',()=>sendFreeText(inputEl.value));
  inputEl.addEventListener('keydown',e=>{if(e.key==='Enter')sendFreeText(inputEl.value);});
  function restartChat(){messagesEl.innerHTML='';isLocked=false;chatbot.classList.remove('input-locked');inputEl.disabled=sendBtn.disabled=false;inputEl.value='';greet();}
  function greet(){setLocked(true);setTimeout(()=>{addMsg('Hi there! I am the ServeDavao Assistant. Type your question below to get started.','bot');setLocked(false);},400);}
  greet();
})();
</script>

<!-- CONTACT FORM SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const contactForm=document.getElementById('contactFormSecure'),successMessage=document.getElementById('successMessage'),errorMessage=document.getElementById('errorMessage'),successText=document.getElementById('successText'),errorText=document.getElementById('errorText');
  if(!contactForm)return;
  contactForm.addEventListener('submit',function(e){
    e.preventDefault();successMessage.classList.add('hidden');errorMessage.classList.add('hidden');
    let recaptchaResponse=null;
    if(typeof grecaptcha!=='undefined'){recaptchaResponse=grecaptcha.getResponse();if(!recaptchaResponse){errorText.textContent='Please complete the reCAPTCHA verification.';errorMessage.classList.remove('hidden');return;}}
    else{errorText.textContent='Verification service not loaded. Please refresh.';errorMessage.classList.remove('hidden');return;}
    const submitButton=contactForm.querySelector('button[type="submit"]'),originalContent=submitButton.innerHTML;
    submitButton.innerHTML='<span>Sending...</span>';submitButton.disabled=true;
    const formData=new FormData(contactForm);formData.set('g-recaptcha-response',recaptchaResponse);
    fetch(contactForm.action,{method:'POST',body:formData,headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}})
    .then(r=>r.json().then(data=>({status:r.status,body:data})))
    .then(({status,body})=>{if(status>=200&&status<300&&body.success){successText.textContent=body.message||'Message sent successfully!';successMessage.classList.remove('hidden');contactForm.reset();if(typeof grecaptcha!=='undefined')grecaptcha.reset();}else{let msg=body.message||'Error sending message.';if(body.errors){const f=Object.values(body.errors)[0];if(f)msg=f[0]||msg;}errorText.textContent=msg;errorMessage.classList.remove('hidden');}})
    .catch(()=>{errorText.textContent='Network error. Please try again.';errorMessage.classList.remove('hidden');})
    .finally(()=>{submitButton.innerHTML=originalContent;submitButton.disabled=false;});
  });
});
</script>

<!-- SCROLL ANIMATIONS + DOT NAV -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const io=new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting){e.target.classList.add('visible');io.unobserve(e.target);}});},{threshold:0.15});
  document.querySelectorAll('.scroll-fade-in,.stagger-animate').forEach(el=>io.observe(el));
  const dots=document.querySelectorAll('.dot-nav a'),sections=document.querySelectorAll('.vp-section');
  function updateDots(id){const isHero=id==='home';dots.forEach(d=>{d.classList.toggle('active',d.dataset.section===id);d.classList.toggle('dark',!isHero);});}
  const secObs=new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting&&e.intersectionRatio>=0.5)updateDots(e.target.id);});},{threshold:0.5});
  sections.forEach(s=>secObs.observe(s));
  const nav=document.getElementById('site-nav');
  window.addEventListener('scroll',()=>{nav.style.background=window.scrollY>60?'rgba(255,255,255,0.97)':'rgba(255,255,255,0.92)';},{passive:true});
});
</script>

</body>
</html>
