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
    :root {
      --nav-h: 70px;
      --primary: #059669;
      --primary-dark: #047857;
      --primary-light: #d1fae5;
      --text-dark: #111827;
      --text-muted: #6b7280;
    }
    @media (max-width: 768px) {
      :root {
        --nav-h: 62px;
      }
    }

    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html {
      scroll-behavior: smooth;
      scroll-snap-type: y mandatory;
      height: 100%;
      overflow-y: scroll;
    }

    body {
      height: 100%;
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      background-color: #ffffff;
      color: #1f2937;
      overflow-x: hidden;
      -webkit-font-smoothing: antialiased;
    }

    /* INTRO LOADING OVERLAY */
    #intro-loading {
      position: fixed;
      inset: 0;
      z-index: 999999;
      background: #ffffff;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      transition: transform 0.8s cubic-bezier(0.85, 0, 0.15, 1), opacity 0.8s ease;
    }
    .intro-slide-up {
      transform: translateY(-100%);
      pointer-events: none;
    }
    .scale-up-subtle { animation: scaleUp 1s cubic-bezier(0.16, 1, 0.3, 1) both; }
    @keyframes scaleUp { 0% { transform: scale(0.95); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
    .float-gentle { animation: floatGentle 4s ease-in-out infinite; }
    @keyframes floatGentle { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
    .fade-in-anim { animation: fadeIn 1.5s ease both; }
    @keyframes fadeIn { 0% { opacity: 0; } 100% { opacity: 0.3; } }
    .reveal-text-up { animation: revealTextUp 0.8s cubic-bezier(0.77, 0, 0.175, 1) both; }
    @keyframes revealTextUp { 0% { transform: translateY(100%); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
    .reveal-fade { animation: revealFade 0.8s ease both; }
    @keyframes revealFade { 0% { opacity: 0; transform: translateY(10px); } 100% { opacity: 1; transform: translateY(0); } }
    .animate-pulse-slow { animation: pulseSlow 4s ease-in-out infinite; }
    @keyframes pulseSlow { 0%, 100% { opacity: 0.2; transform: scale(1); } 50% { opacity: 0.4; transform: scale(1.1); } }
    .delay-200 { animation-delay: 0.2s; }
    .delay-400 { animation-delay: 0.4s; }
    .loading-line { width: 0%; animation: loadLine 1.6s cubic-bezier(0.65, 0, 0.35, 1) forwards; }
    @keyframes loadLine { 0% { width: 0%; } 40% { width: 50%; } 100% { width: 100%; } }

    /* NAVBAR */
    .site-nav {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: var(--nav-h);
      z-index: 1000;
      background: rgba(255, 255, 255, 0.94);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-bottom: 1px solid rgba(229, 231, 235, 0.8);
      display: flex;
      align-items: center;
      transition: background 0.3s ease, box-shadow 0.3s ease;
    }
    .nav-inner {
      width: 100%;
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 1.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .nav-logo {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      text-decoration: none;
    }
    .nav-logo img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      transition: transform 0.3s;
    }
    .nav-logo img:hover { transform: scale(1.08); }
    .nav-logo-text {
      font-weight: 800;
      font-size: 1.15rem;
      color: #1f2937;
      letter-spacing: -0.02em;
    }
    .nav-logo-text span { color: var(--primary); }
    .nav-links {
      display: flex;
      align-items: center;
      gap: 2rem;
      list-style: none;
    }
    .nav-links a {
      color: #4b5563;
      font-weight: 500;
      font-size: 0.92rem;
      text-decoration: none;
      transition: color 0.2s ease;
      position: relative;
    }
    .nav-links a:hover, .nav-links a.active {
      color: var(--primary);
    }
    .nav-actions {
      display: flex;
      align-items: center;
      gap: 0.85rem;
    }
    .btn-login {
      font-size: 0.9rem;
      font-weight: 600;
      color: #374151;
      text-decoration: none;
      padding: 0.45rem 0.85rem;
      border-radius: 8px;
      transition: color 0.2s;
    }
    .btn-login:hover { color: var(--primary); }
    .btn-cta {
      background: var(--primary);
      color: #ffffff;
      font-size: 0.88rem;
      font-weight: 600;
      padding: 0.55rem 1.25rem;
      border-radius: 8px;
      text-decoration: none;
      box-shadow: 0 4px 14px rgba(5, 150, 105, 0.25);
      transition: all 0.2s ease;
    }
    .btn-cta:hover {
      background: var(--primary-dark);
      transform: translateY(-1px);
      box-shadow: 0 6px 18px rgba(5, 150, 105, 0.35);
    }
    .nav-hamburger {
      display: none;
      background: none;
      border: none;
      cursor: pointer;
      padding: 0.4rem;
      color: #374151;
      font-size: 1.5rem;
    }
    .nav-mobile-menu {
      display: none;
      position: absolute;
      top: var(--nav-h);
      left: 0;
      right: 0;
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(16px);
      border-bottom: 1px solid #e5e7eb;
      padding: 1.25rem 1.5rem;
      flex-direction: column;
      gap: 1rem;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    .nav-mobile-menu.open { display: flex; }
    .nav-mobile-menu a {
      color: #374151;
      font-weight: 600;
      font-size: 1rem;
      text-decoration: none;
      padding: 0.5rem 0;
      border-bottom: 1px solid #f3f4f6;
    }
    .nav-mobile-menu a:hover { color: var(--primary); }
    .nav-mobile-menu .mobile-cta {
      background: var(--primary);
      color: #fff;
      text-align: center;
      padding: 0.75rem;
      border-radius: 8px;
      margin-top: 0.5rem;
      border-bottom: none;
    }

    @media (max-width: 768px) {
      .nav-links, .nav-actions { display: none; }
      .nav-hamburger { display: block; }
    }

    /* VIEWPORT SECTION CORE FRAMEWORK */
    .vp-section {
      width: 100%;
      height: 100dvh;
      min-height: 100dvh;
      max-height: 100dvh;
      scroll-snap-align: start;
      scroll-snap-stop: always;
      padding-top: var(--nav-h);
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      box-sizing: border-box;
    }

    .vp-container {
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 1rem 1.5rem;
      box-sizing: border-box;
      overflow-y: auto;
      scrollbar-width: thin;
      scrollbar-color: rgba(5, 150, 105, 0.3) transparent;
    }
    .vp-container::-webkit-scrollbar { width: 4px; }
    .vp-container::-webkit-scrollbar-thumb { background: rgba(5, 150, 105, 0.3); border-radius: 99px; }

    /* TYPOGRAPHY COMPONENTS */
    .section-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      font-size: 0.75rem;
      font-weight: 700;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: var(--primary);
      background: var(--primary-light);
      padding: 0.35rem 0.85rem;
      border-radius: 99px;
      margin-bottom: 0.75rem;
    }
    .section-title {
      font-size: clamp(1.6rem, 3.5vw, 2.5rem);
      font-weight: 900;
      color: #111827;
      text-align: center;
      line-height: 1.2;
      margin-bottom: 0.6rem;
      letter-spacing: -0.02em;
    }
    .section-title span { color: var(--primary); }
    .section-desc {
      max-width: 38rem;
      text-align: center;
      color: #6b7280;
      font-size: clamp(0.88rem, 1.8vw, 1.05rem);
      line-height: 1.6;
      margin-bottom: 1.75rem;
    }

    /* SECTION 1: HERO */
    .hero-section {
      background-color: #050505;
      color: #ffffff;
    }
    .hero-bg-img {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      animation: kenBurns 22s ease-in-out infinite;
      opacity: 0.65;
    }
    @keyframes kenBurns {
      0% { transform: scale(1); }
      50% { transform: scale(1.08); }
      100% { transform: scale(1); }
    }
    .hero-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, rgba(0,0,0,0.55) 0%, rgba(5,150,105,0.4) 60%, rgba(0,0,0,0.75) 100%);
    }
    .hero-content {
      position: relative;
      z-index: 2;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      max-width: 820px;
      padding: 1rem;
    }
    .hero-badge {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.25);
      color: #ffffff;
      font-size: 0.78rem;
      font-weight: 600;
      padding: 0.4rem 1rem;
      border-radius: 99px;
      margin-bottom: 1.25rem;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }
    .hero-content h1 {
      font-size: clamp(2rem, 5.5vw, 3.75rem);
      font-weight: 900;
      line-height: 1.1;
      margin-bottom: 1.25rem;
      letter-spacing: -0.03em;
      text-shadow: 0 4px 30px rgba(0,0,0,0.5);
    }
    .hero-content p {
      font-size: clamp(0.95rem, 2.2vw, 1.2rem);
      max-width: 36rem;
      opacity: 0.92;
      margin-bottom: 2rem;
      line-height: 1.65;
      text-shadow: 0 2px 10px rgba(0,0,0,0.5);
    }
    .hero-cta-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.6rem;
      background: var(--primary);
      color: #ffffff;
      font-weight: 700;
      font-size: 1.05rem;
      padding: 0.85rem 2.25rem;
      border-radius: 12px;
      text-decoration: none;
      box-shadow: 0 8px 30px rgba(5, 150, 105, 0.45);
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .hero-cta-btn:hover {
      background: var(--primary-dark);
      transform: translateY(-3px);
      box-shadow: 0 12px 40px rgba(5, 150, 105, 0.6);
    }
    .scroll-hint {
      position: absolute;
      bottom: 1.5rem;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.3rem;
      color: rgba(255, 255, 255, 0.7);
      font-size: 0.72rem;
      font-weight: 600;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      animation: bounceDown 2s ease-in-out infinite;
      z-index: 2;
    }
    @keyframes bounceDown {
      0%, 100% { transform: translateX(-50%) translateY(0); }
      50% { transform: translateX(-50%) translateY(6px); }
    }

    /* SECTION 2: ABOUT */
    .about-section {
      background: #f9fafb;
    }
    .about-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
      gap: 1.25rem;
      width: 100%;
      max-width: 1100px;
    }
    .about-card {
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 16px;
      padding: 1.5rem 1.25rem;
      text-align: center;
      transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
      box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .about-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 16px 36px rgba(5, 150, 105, 0.12);
      border-color: #a7f3d0;
    }
    .about-card .ac-icon {
      width: 52px;
      height: 52px;
      background: linear-gradient(135deg, #d1fae5, #a7f3d0);
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
      font-size: 1.5rem;
      color: var(--primary);
    }
    .about-card h3 {
      font-size: 1.05rem;
      font-weight: 700;
      color: #111827;
      margin-bottom: 0.4rem;
    }
    .about-card p {
      font-size: 0.88rem;
      color: #6b7280;
      line-height: 1.55;
    }
    .stats-row {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 2rem;
      margin-top: 1.75rem;
      width: 100%;
      max-width: 850px;
      padding: 1rem;
      background: #ffffff;
      border-radius: 16px;
      border: 1px solid #e5e7eb;
    }
    .stat-item { text-align: center; flex: 1; min-width: 120px; }
    .stat-num { font-size: clamp(1.5rem, 3vw, 2.2rem); font-weight: 900; color: var(--primary); display: block; }
    .stat-lbl { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; }

    /* SECTION 3: EVENTS / HOW IT WORKS */
    .events-section {
      background: #ffffff;
    }
    .steps-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      width: 100%;
      max-width: 1100px;
    }
    .step-card {
      background: #f9fafb;
      border: 1.5px solid #f3f4f6;
      border-radius: 20px;
      padding: 1.75rem 1.5rem;
      position: relative;
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }
    .step-card:hover {
      background: #ffffff;
      border-color: var(--primary-light);
      box-shadow: 0 14px 35px rgba(5, 150, 105, 0.1);
      transform: translateY(-4px);
    }
    .step-num {
      width: 42px;
      height: 42px;
      background: var(--primary);
      color: #ffffff;
      font-weight: 900;
      font-size: 1.1rem;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1.25rem;
      box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    }
    .step-card h3 {
      font-size: 1.1rem;
      font-weight: 800;
      color: #111827;
      margin-bottom: 0.5rem;
    }
    .step-card p {
      font-size: 0.9rem;
      color: #6b7280;
      line-height: 1.6;
    }

    /* SECTION 4: CONTACT */
    .contact-section {
      background: #f0fdf4;
    }
    .contact-layout {
      display: grid;
      grid-template-columns: 1fr 1.1fr;
      gap: 1.5rem;
      width: 100%;
      max-width: 1150px;
      height: 100%;
      max-height: calc(100dvh - var(--nav-h) - 130px);
    }
    @media (max-width: 900px) {
      .contact-layout {
        grid-template-columns: 1fr;
        max-height: none;
        overflow-y: visible;
      }
    }
    .contact-info-box {
      background: #ffffff;
      border-radius: 20px;
      padding: 1.5rem;
      border: 1px solid #d1fae5;
      display: flex;
      flex-direction: column;
      gap: 1rem;
      box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .ci-row { display: flex; align-items: center; gap: 0.85rem; }
    .ci-icon {
      width: 38px;
      height: 38px;
      min-width: 38px;
      background: #d1fae5;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary);
      font-size: 1rem;
    }
    .ci-details h4 { font-size: 0.85rem; font-weight: 700; color: #111827; margin-bottom: 0.1rem; }
    .ci-details p { font-size: 0.82rem; color: #6b7280; }

    /* FAQ CHATBOT COMPONENT */
    #faq-chatbot {
      display: flex;
      flex-direction: column;
      flex: 1;
      min-height: 240px;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 8px 30px rgba(5,150,105,0.12);
      background: #ffffff;
      border: 1px solid #d1fae5;
    }
    #faq-chatbot-header {
      background: linear-gradient(135deg, #059669 0%, #047857 100%);
      padding: 0.75rem 1rem;
      display: flex;
      align-items: center;
      gap: 0.65rem;
      flex-shrink: 0;
    }
    #faq-chatbot-header .bot-avatar {
      width: 32px;
      height: 32px;
      background: rgba(255,255,255,0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.9rem;
      color: #fff;
    }
    #faq-chatbot-header .bot-info h4 { color: #fff; font-weight: 700; font-size: 0.85rem; }
    #faq-chatbot-header .bot-info p { color: rgba(255,255,255,0.8); font-size: 0.7rem; }
    #faq-messages {
      flex: 1;
      overflow-y: auto;
      padding: 0.75rem;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      background: #f0fdf4;
    }
    .faq-msg { display: flex; gap: 0.4rem; align-items: flex-end; animation: msgIn 0.25s ease; }
    @keyframes msgIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
    .faq-msg.user { flex-direction: row-reverse; }
    .faq-msg .msg-avatar {
      width: 24px; height: 24px; border-radius: 50%; background: var(--primary);
      color: #fff; font-size: 0.6rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .faq-msg.user .msg-avatar { background: #6b7280; }
    .faq-bubble { max-width: 85%; padding: 0.5rem 0.75rem; border-radius: 1rem; font-size: 0.8rem; line-height: 1.45; }
    .faq-msg.bot .faq-bubble { background: #fff; color: #1f2937; border-bottom-left-radius: 4px; box-shadow: 0 2px 6px rgba(0,0,0,0.06); }
    .faq-msg.user .faq-bubble { background: var(--primary); color: #fff; border-bottom-right-radius: 4px; }

    #faq-input-area {
      display: flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.5rem 0.75rem;
      background: #fff;
      border-top: 1px solid #d1fae5;
      flex-shrink: 0;
    }
    #faq-input {
      flex: 1;
      border: 1.5px solid #d1fae5;
      border-radius: 99px;
      padding: 0.4rem 0.85rem;
      font-size: 0.8rem;
      outline: none;
      background: #f0fdf4;
      color: #1f2937;
    }
    #faq-input:focus { border-color: var(--primary); background: #fff; }
    #faq-send-btn {
      width: 30px; height: 30px; background: var(--primary); border: none; border-radius: 50%;
      color: #fff; font-size: 0.8rem; cursor: pointer; display: flex; align-items: center; justify-content: center;
    }

    /* FORM CARD */
    .contact-form-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 1.5rem;
      border: 1px solid #d1fae5;
      display: flex;
      flex-direction: column;
      box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .cf-group { display: flex; flex-direction: column; gap: 0.3rem; margin-bottom: 0.65rem; }
    .cf-label { font-size: 0.78rem; font-weight: 700; color: #374151; }
    .cf-input {
      width: 100%;
      padding: 0.55rem 0.85rem;
      font-size: 0.85rem;
      border: 1.5px solid #d1fae5;
      border-radius: 10px;
      outline: none;
      background: #f0fdf4;
      color: #111827;
      font-family: inherit;
    }
    .cf-input:focus { border-color: var(--primary); background: #ffffff; }
    .btn-submit {
      width: 100%;
      background: var(--primary);
      color: #ffffff;
      font-weight: 700;
      font-size: 0.9rem;
      padding: 0.65rem;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      margin-top: 0.4rem;
      transition: all 0.2s;
    }
    .btn-submit:hover { background: var(--primary-dark); transform: translateY(-1px); }

    /* FOOTER BAR INSIDE CONTACT SECTION */
    .section-footer {
      width: 100%;
      margin-top: auto;
      padding-top: 0.75rem;
      border-top: 1px solid rgba(5, 150, 105, 0.15);
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: #6b7280;
      font-size: 0.78rem;
    }

    /* DOT NAV */
    .dot-nav {
      position: fixed;
      right: 1.25rem;
      top: 50%;
      transform: translateY(-50%);
      z-index: 999;
      display: flex;
      flex-direction: column;
      gap: 0.65rem;
    }
    .dot-nav a {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: rgba(0, 0, 0, 0.2);
      border: 2px solid rgba(255, 255, 255, 0.8);
      display: block;
      transition: all 0.3s ease;
    }
    .dot-nav a.active {
      background: var(--primary);
      border-color: var(--primary);
      transform: scale(1.35);
    }
    .dot-nav.on-dark a {
      background: rgba(255, 255, 255, 0.35);
      border-color: rgba(255, 255, 255, 0.6);
    }
    .dot-nav.on-dark a.active {
      background: var(--primary);
      border-color: var(--primary);
    }
    @media (max-width: 640px) { .dot-nav { display: none; } }
    .hidden { display: none !important; }
  </style>
</head>
<body>

<!-- INTRO LOADING OVERLAY -->
<div id="intro-loading">
  <div style="position:absolute;inset:0;background:radial-gradient(circle at center,#d1fae5 0%,#ffffff 100%);opacity:0.3;" class="fade-in-anim"></div>
  <div class="scale-up-subtle" style="display:flex;flex-direction:column;align-items:center;position:relative;z-index:1;">
    <div style="margin-bottom:1.5rem;position:relative;display:flex;align-items:center;justify-content:center;">
      <div class="animate-pulse-slow" style="position:absolute;inset:-10px;background:#34d399;border-radius:50%;filter:blur(24px);opacity:0.2;"></div>
      <img src="{{ asset('assets/img/logoDav.png') }}" alt="ServeDavao Logo" class="float-gentle"
           style="width:90px;height:90px;object-fit:contain;position:relative;z-index:1;filter:drop-shadow(0 8px 20px rgba(5,150,105,0.3));">
    </div>
    <div style="overflow:hidden;margin-bottom:0.4rem;">
      <h2 class="reveal-text-up" style="font-size:2.2rem;font-weight:900;color:#1f2937;margin:0;">Serve<span style="color:#059669;">Davao</span></h2>
    </div>
    <div style="overflow:hidden;margin-bottom:2rem;">
      <p class="reveal-text-up delay-200" style="font-size:0.7rem;font-weight:700;letter-spacing:0.25em;text-transform:uppercase;color:#9ca3af;margin:0;">Empowering Volunteers</p>
    </div>
    <div class="reveal-fade delay-400" style="width:160px;height:3px;background:#e5e7eb;border-radius:99px;overflow:hidden;">
      <div class="loading-line" style="height:100%;background:#059669;border-radius:99px;"></div>
    </div>
  </div>
</div>
<script>
  window.addEventListener('load', function () {
    setTimeout(() => {
      const loader = document.getElementById('intro-loading');
      if (loader) { loader.classList.add('intro-slide-up'); setTimeout(() => loader.remove(), 850); }
    }, 1600);
  });
</script>

<!-- DOT NAVIGATION -->
<nav class="dot-nav on-dark" id="dotNav" aria-label="Page section navigation">
  <a href="#home" class="active" data-section="home" title="Home"></a>
  <a href="#about" data-section="about" title="About"></a>
  <a href="#events" data-section="events" title="Events"></a>
  <a href="#contact" data-section="contact" title="Contact"></a>
</nav>

<!-- FIXED NAVBAR -->
<nav class="site-nav" id="site-nav">
  <div class="nav-inner">
    <a href="#home" class="nav-logo">
      <img src="{{ asset('assets/img/logoDav.png') }}" alt="ServeDavao Logo">
      <span class="nav-logo-text">Serve<span>Davao</span></span>
    </a>
    <ul class="nav-links">
      <li><a href="#home" class="active">Home</a></li>
      <li><a href="#about">About</a></li>
      <li><a href="#events">Events</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
    <div class="nav-actions">
      <a href="/login" class="btn-login">Login</a>
      <a href="{{ auth()->check() ? route('events.index') : route('login') }}" class="btn-cta">
        {{ auth()->check() ? 'Go to Dashboard' : 'Get Started' }}
      </a>
    </div>
    <button class="nav-hamburger" id="nav-hamburger" aria-label="Toggle navigation menu"><i class="bi bi-list"></i></button>
  </div>
  <div class="nav-mobile-menu" id="nav-mobile-menu">
    <a href="#home" onclick="closeMobileMenu()">Home</a>
    <a href="#about" onclick="closeMobileMenu()">About</a>
    <a href="#events" onclick="closeMobileMenu()">Events</a>
    <a href="#contact" onclick="closeMobileMenu()">Contact</a>
    <a href="/login" onclick="closeMobileMenu()">Login</a>
    <a href="{{ auth()->check() ? route('events.index') : route('login') }}" class="mobile-cta" onclick="closeMobileMenu()">
      {{ auth()->check() ? 'Go to Dashboard' : 'Get Started' }}
    </a>
  </div>
</nav>
<script>
  const hamburger = document.getElementById('nav-hamburger');
  const mobileMenu = document.getElementById('nav-mobile-menu');
  function closeMobileMenu() { mobileMenu.classList.remove('open'); hamburger.querySelector('i').className='bi bi-list'; }
  hamburger.addEventListener('click', () => {
    const open = mobileMenu.classList.toggle('open');
    hamburger.querySelector('i').className = open ? 'bi bi-x-lg' : 'bi bi-list';
  });
</script>

<!-- SECTION 1: HOME / HERO -->
<section id="home" class="vp-section hero-section">
  <img src="{{ asset('assets/img/hero1.png') }}" alt="Volunteers serving in Davao" class="hero-bg-img" loading="eager">
  <div class="hero-overlay"></div>
  <div class="vp-container">
    <div class="hero-content">
      <div class="hero-badge">
        <i class="bi bi-geo-alt-fill" style="color:#34d399;"></i> ServeDavao Volunteer Platform
      </div>
      <h1>Empower Davao<br>Through Volunteerism</h1>
      <p>Join ServeDavao to make a lasting impact in your community — seamlessly connecting passionate volunteers with verified events across Davao City.</p>
      <a href="{{ auth()->check() ? route('events.index') : route('login') }}" class="hero-cta-btn">
        {{ auth()->check() ? 'Explore Events' : 'Get Started' }}
        <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </div>
  <div class="scroll-hint">
    <span>Scroll Down</span>
    <i class="bi bi-chevron-down"></i>
  </div>
</section>

<!-- SECTION 2: ABOUT -->
<section id="about" class="vp-section about-section">
  <div class="vp-container">
    <span class="section-badge"><i class="bi bi-info-circle-fill"></i> Who We Are</span>
    <h2 class="section-title">About <span>ServeDavao</span></h2>
    <p class="section-desc">
      ServeDavao bridges volunteers and organizers for social good. Volunteers can register, browse opportunities, and log service hours — while organizers post events, verify participation, and celebrate community impact.
    </p>

    <div class="about-grid">
      <div class="about-card">
        <div class="ac-icon"><i class="bi bi-people-fill"></i></div>
        <h3>Community Driven</h3>
        <p>Connecting passionate individuals with local causes that matter most in Davao City.</p>
      </div>
      <div class="about-card">
        <div class="ac-icon"><i class="bi bi-calendar-event-fill"></i></div>
        <h3>Event Management</h3>
        <p>Organizers easily publish, schedule, and track volunteer participation in real time.</p>
      </div>
      <div class="about-card">
        <div class="ac-icon"><i class="bi bi-shield-check"></i></div>
        <h3>Verified Impact</h3>
        <p>AI-assisted verification and QR attendance code tracking for genuine service credit.</p>
      </div>
      <div class="about-card">
        <div class="ac-icon"><i class="bi bi-graph-up-arrow"></i></div>
        <h3>Certificates & Stats</h3>
        <p>Monitor your volunteer hours, build your civic profile, and earn verified certificates.</p>
      </div>
    </div>

    <div class="stats-row">
      <div class="stat-item"><span class="stat-num">500+</span><span class="stat-lbl">Volunteers</span></div>
      <div class="stat-item"><span class="stat-num">80+</span><span class="stat-lbl">Events</span></div>
      <div class="stat-item"><span class="stat-num">2,400+</span><span class="stat-lbl">Hours Served</span></div>
      <div class="stat-item"><span class="stat-num">15+</span><span class="stat-lbl">Organizations</span></div>
    </div>
  </div>
</section>

<!-- SECTION 3: EVENTS / HOW IT WORKS -->
<section id="events" class="vp-section events-section">
  <div class="vp-container">
    <span class="section-badge"><i class="bi bi-lightning-charge-fill"></i> How It Works</span>
    <h2 class="section-title">Get Started in <span>3 Easy Steps</span></h2>
    <p class="section-desc">
      Whether you want to join an event or organize your own community project, ServeDavao makes the process simple and transparent.
    </p>

    <div class="steps-grid">
      <div class="step-card">
        <div class="step-num">1</div>
        <h3>Create Your Account</h3>
        <p>Sign up as a volunteer or organizer. AI-assisted ID verification keeps the community safe and trustworthy.</p>
      </div>
      <div class="step-card">
        <div class="step-num">2</div>
        <h3>Find or Post Events</h3>
        <p>Browse upcoming community drives in Davao City by location, date, and cause, or publish your own event.</p>
      </div>
      <div class="step-card">
        <div class="step-num">3</div>
        <h3>Log Hours & Earn Credit</h3>
        <p>Check in via QR code during the event. Track your volunteer hours automatically and generate certificates.</p>
      </div>
    </div>
  </div>
</section>

<!-- SECTION 4: CONTACT & SUPPORT -->
<section id="contact" class="vp-section contact-section">
  <div class="vp-container">
    <span class="section-badge"><i class="bi bi-envelope-heart-fill"></i> Get In Touch</span>
    <h2 class="section-title">Contact <span>&amp; Support</span></h2>
    <p class="section-desc" style="margin-bottom:1.25rem;">
      Have questions or want to partner with ServeDavao? Chat with our assistant or send us a direct message.
    </p>

    <div class="contact-layout">
      <!-- LEFT COLUMN: Contact Info + FAQ Chatbot -->
      <div style="display:flex;flex-direction:column;gap:0.85rem;height:100%;">
        <div class="contact-info-box">
          <div class="ci-row">
            <div class="ci-icon"><i class="bi bi-geo-alt-fill"></i></div>
            <div class="ci-details"><h4>Davao City, Philippines</h4><p>Local Community Hub</p></div>
          </div>
          <div class="ci-row">
            <div class="ci-icon"><i class="bi bi-envelope-fill"></i></div>
            <div class="ci-details"><h4>contact@servedavao.org</h4><p>24/7 Inquiry Support</p></div>
          </div>
        </div>

        <div id="faq-chatbot">
          <div id="faq-chatbot-header">
            <div class="bot-avatar"><i class="bi bi-robot"></i></div>
            <div class="bot-info"><h4>ServeDavao Assistant</h4><p>Ask anything about volunteering</p></div>
          </div>
          <div id="faq-messages"></div>
          <div id="faq-input-area">
            <input id="faq-input" type="text" placeholder="Ask a question..." autocomplete="off" />
            <button id="faq-send-btn"><i class="bi bi-send-fill"></i></button>
          </div>
        </div>
      </div>

      <!-- RIGHT COLUMN: Contact Form -->
      <div class="contact-form-card">
        <h3 style="font-size:1.05rem;font-weight:800;color:#111827;margin-bottom:0.75rem;">Send Us a Message</h3>
        <form id="contactFormSecure" action="{{ route('contact.submit') }}" method="POST" style="display:flex;flex-direction:column;gap:0.5rem;flex:1;">
          @csrf
          <div style="display:none;"><input type="text" name="website" tabindex="-1" autocomplete="off"><input type="url" name="url" tabindex="-1" autocomplete="off"></div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;">
            <div class="cf-group"><label for="firstName" class="cf-label">First Name *</label><input type="text" id="firstName" name="firstName" required class="cf-input" placeholder="First name"></div>
            <div class="cf-group"><label for="lastName" class="cf-label">Last Name *</label><input type="text" id="lastName" name="lastName" required class="cf-input" placeholder="Last name"></div>
          </div>
          <div class="cf-group"><label for="email" class="cf-label">Email Address *</label><input type="email" id="email" name="email" required class="cf-input" placeholder="your.email@example.com"></div>
          <div class="cf-group">
            <label for="subject" class="cf-label">Subject *</label>
            <select id="subject" name="subject" required class="cf-input">
              <option value="" disabled selected>Select a subject</option>
              <option value="volunteer">Volunteer Inquiry</option>
              <option value="organizer">Organizer Inquiry</option>
              <option value="partnership">Partnership</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="cf-group" style="flex:1;"><label for="message" class="cf-label">Message *</label><textarea id="message" name="message" rows="3" required class="cf-input" style="resize:none;" placeholder="How can we help you?"></textarea></div>
          <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}" style="transform:scale(0.8);transform-origin:left top;margin-bottom:-10px;"></div>
          <button type="submit" class="btn-submit"><span>Send Message</span><i class="bi bi-send"></i></button>
        </form>
        <div id="successMessage" class="hidden" style="margin-top:0.5rem;padding:0.65rem;background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;color:#065f46;font-size:0.8rem;display:flex;align-items:center;gap:0.4rem;">
          <i class="bi bi-check-circle-fill"></i><p id="successText" style="margin:0;font-weight:600;">Message sent successfully!</p>
        </div>
        <div id="errorMessage" class="hidden" style="margin-top:0.5rem;padding:0.65rem;background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;color:#991b1b;font-size:0.8rem;display:flex;align-items:center;gap:0.4rem;">
          <i class="bi bi-exclamation-circle-fill"></i><p id="errorText" style="margin:0;font-weight:600;">Error sending message.</p>
        </div>
      </div>
    </div>

    <!-- INTEGRATED FOOTER STRIP -->
    <div class="section-footer">
      <div style="display:flex;align-items:center;gap:0.5rem;">
        <img src="{{ asset('assets/img/logoDav.png') }}" alt="" style="width:20px;height:20px;border-radius:50%;">
        <strong style="color:#374151;">ServeDavao</strong> &copy; 2025. All rights reserved.
      </div>
      <div>Empowering Davao City through volunteerism.</div>
    </div>
  </div>
</section>

<!-- FAQ CHATBOT SCRIPT -->
<script>
(function () {
  const faqs = [
    { label:'Become a volunteer', keywords:['become a volunteer','how to volunteer','volunteer','join'], answer:'To become a volunteer, create an account, browse events, and click Join on any event. Your service hours are tracked automatically!' },
    { label:'Post events', keywords:['organization','org','post event','create event','organizer'], answer:'Organizations can register as organizers to post events, manage volunteers, scan QR attendance codes, and generate reports.' },
    { label:'Is it free?', keywords:['free','cost','price','fee'], answer:'ServeDavao is completely free for both volunteers and organizers.' },
    { label:'Track hours', keywords:['track','hours','service hours','log hours'], answer:'Service hours are recorded automatically when attendance is verified by organizers via QR code.' }
  ];
  const chatbot=document.getElementById('faq-chatbot'),messagesEl=document.getElementById('faq-messages'),inputEl=document.getElementById('faq-input'),sendBtn=document.getElementById('faq-send-btn');
  function addMsg(text,role){
    const w=document.createElement('div');w.className='faq-msg '+role;
    const av=document.createElement('div');av.className='msg-avatar';av.innerHTML=role==='bot'?'<i class="bi bi-robot"></i>':'<i class="bi bi-person-fill"></i>';
    const b=document.createElement('div');b.className='faq-bubble';b.textContent=text;
    w.appendChild(av);w.appendChild(b);messagesEl.appendChild(w);messagesEl.scrollTop=messagesEl.scrollHeight;
  }
  function handleInput(q){
    if(!q.trim())return;
    addMsg(q,'user');inputEl.value='';
    const l=q.toLowerCase();
    const match=faqs.find(f=>f.keywords.some(k=>l.includes(k)));
    setTimeout(()=>{
      addMsg(match?match.answer:"Thanks for reaching out! Contact us directly via email at contact@servedavao.org.",'bot');
    },500);
  }
  sendBtn.addEventListener('click',()=>handleInput(inputEl.value));
  inputEl.addEventListener('keydown',e=>{if(e.key==='Enter')handleInput(inputEl.value);});
  addMsg('Hello! Ask me any question about ServeDavao volunteering.','bot');
})();
</script>

<!-- CONTACT FORM SUBMIT SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const contactForm=document.getElementById('contactFormSecure'),successMessage=document.getElementById('successMessage'),errorMessage=document.getElementById('errorMessage'),successText=document.getElementById('successText'),errorText=document.getElementById('errorText');
  if(!contactForm)return;
  contactForm.addEventListener('submit',function(e){
    e.preventDefault();successMessage.classList.add('hidden');errorMessage.classList.add('hidden');
    let recaptchaResponse=null;
    if(typeof grecaptcha!=='undefined'){recaptchaResponse=grecaptcha.getResponse();if(!recaptchaResponse){errorText.textContent='Please complete the reCAPTCHA verification.';errorMessage.classList.remove('hidden');return;}}
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

<!-- NAVIGATION SNAP & DOT HIGHLIGHT SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const dotNav = document.getElementById('dotNav');
  const dots = document.querySelectorAll('.dot-nav a');
  const navLinks = document.querySelectorAll('.nav-links a');
  const sections = document.querySelectorAll('.vp-section');

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting && entry.intersectionRatio >= 0.5) {
        const id = entry.target.id;
        
        // Update Dot Nav active state
        dots.forEach(d => d.classList.toggle('active', d.dataset.section === id));
        // Toggle dark vs light dot styling
        if (id === 'home') {
          dotNav.classList.add('on-dark');
        } else {
          dotNav.classList.remove('on-dark');
        }

        // Update Top Navbar link active state
        navLinks.forEach(link => {
          link.classList.toggle('active', link.getAttribute('href') === `#${id}`);
        });
      }
    });
  }, { threshold: 0.5 });

  sections.forEach(sec => observer.observe(sec));
});
</script>

</body>
</html>