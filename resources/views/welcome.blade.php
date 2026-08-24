<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>I.K. Gujral Punjab Technical University Jalandhar - Placement Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
      rel="icon"
      type="image/svg+xml"
      href="/public/assets/classic-logo.png"
    />
    <style>
        /* ===== ROOT VARIABLES ===== */
        :root {
            --ptu-red: #d61c1c;
            --ptu-blue: #012140;
            --text-dark: #1a1a1a;
            --white: #ffffff;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: var(--white);
            color: var(--text-dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ===== CONTAINER ===== */
        .container {
            width: 90%;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* ===== NAVBAR ===== */
        .ptu-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 5%;
            height: 80px;
            background: var(--white);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid #eaeaea;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .logo-wrapper {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .brand-logo {
            height: 50px;
            width: auto;
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .univ-name {
            font-weight: 800;
            font-size: 1rem;
            color: var(--ptu-blue);
            line-height: 1.2;
        }

        .tagline {
            font-size: 0.75rem;
            color: #666;
            font-style: italic;
        }

        .nav-list {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        .nav-link {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            padding: 5px 0;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--ptu-red);
            transition: var(--transition);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link:hover {
            color: var(--ptu-red);
        }

        .apply-now-btn {
            background: var(--ptu-red);
            color: var(--white);
            padding: 12px 25px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
        }

        .apply-now-btn:hover {
            background: var(--ptu-blue);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 46, 91, 0.35);
        }

        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
        }

        .hamburger span {
            width: 26px;
            height: 3px;
            background: var(--ptu-blue);
            transition: var(--transition);
        }

        .mobile-only-action {
            display: none;
        }

        /* ===== HERO SLIDER ===== */
        .hero-slider-wrapper {
            position: relative;
            height: 80vh;
            width: 95%;
            margin: 20px auto;
            overflow: hidden;
            border-radius: 20px;
        }

        .slider-container {
            height: 100%;
            width: 100%;
            position: relative;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.8s ease, visibility 0.8s ease;
        }

        .slide.active {
            opacity: 1;
            visibility: visible;
        }

        .slide-image {
            position: absolute;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            transform: scale(1.1);
            transition: transform 6s linear;
        }

        .slide.active .slide-image {
            transform: scale(1);
        }

        .slide-content {
            position: relative;
            z-index: 10;
            height: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 5%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: var(--white);
        }

        .sub-heading {
            font-size: 1.2rem;
            color: var(--ptu-red);
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 800;
            margin-bottom: 10px;
            transform: translateY(30px);
            opacity: 0;
            transition: all 0.6s 0.2s;
        }

        .main-heading {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 25px;
            transform: translateY(40px);
            opacity: 0;
            transition: all 0.6s 0.4s;
        }

        .main-heading span {
            color: var(--white);
            background: linear-gradient(transparent 60%, var(--ptu-red) 60%);
            padding: 0 5px;
        }

        .slide.active .sub-heading,
        .slide.active .main-heading {
            transform: translateY(0);
            opacity: 1;
        }

        .package-stats {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 30px;
            opacity: 0;
            transition: all 0.6s 0.6s;
        }

        .slide.active .package-stats {
            opacity: 1;
        }

        .stat-box {
            display: flex;
            align-items: baseline;
            gap: 5px;
            flex-wrap: wrap;
        }

        .currency {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--ptu-red);
        }

        .amount {
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            font-weight: 800;
        }

        .label {
            font-size: 0.7rem;
            font-weight: 600;
            border-left: 2px solid var(--ptu-red);
            padding-left: 10px;
            text-transform: uppercase;
        }

        .hero-desc {
            font-size: 1.1rem;
            margin-bottom: 25px;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s 0.5s;
        }

        .slide.active .hero-desc {
            opacity: 0.9;
            transform: translateY(0);
        }

        .cta-container {
            opacity: 0;
            transition: all 0.6s 0.8s;
        }

        .slide.active .cta-container {
            opacity: 1;
        }

        .btn-main {
            background: var(--ptu-red);
            color: white;
            padding: 15px 35px;
            border: none;
            border-radius: 4px;
            font-weight: 800;
            font-size: 1rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 10px 20px rgba(214, 28, 28, 0.3);
            text-transform: uppercase;
            transition: var(--transition);
        }

        .btn-main:hover {
            background: var(--ptu-blue);
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(1, 33, 100, 0.4);
        }

        .btn-main i {
            transition: transform 0.3s;
        }

        .btn-main:hover i {
            transform: translateX(5px);
        }

        .slider-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 20;
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: var(--transition);
        }

        .slider-nav:hover {
            background: var(--ptu-red);
            border-color: var(--ptu-red);
            transform: translateY(-50%) scale(1.1);
        }

        .prev {
            left: 20px;
        }

        .next {
            right: 20px;
        }

        .slider-dots {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 12px;
            z-index: 20;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: 0.3s;
            border: 2px solid transparent;
        }

        .dot.active {
            background: var(--ptu-red);
            transform: scale(1.2);
            border-color: white;
        }

        /* ===== STATS BANNER ===== */
        .ikgptu-stats-banner {
            background: #f9f9f9;
            padding: 40px 5%;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .stats-track {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .stat-card {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
            background: white;
            border-radius: 12px;
            border-left: 4px solid var(--ptu-red);
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-left-color: var(--ptu-blue);
        }

        .stat-icon-wrapper {
            background: var(--ptu-red);
            min-width: 45px;
            height: 45px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon {
            font-size: 1.2rem;
            color: white;
        }

        .stat-title {
            font-size: 1rem;
            font-weight: 800;
            color: var(--ptu-blue);
            margin-bottom: 4px;
        }

        .stat-description {
            font-size: 0.8rem;
            color: #555;
        }

        .stat-description span {
            font-weight: 700;
        }

        /* ===== COMPANIES SLIDER ===== */
        .ptu-companies-slider {
            padding: 80px 5%;
            background: #f9f9fa;
            overflow: hidden;
        }

        .companies-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .eyebrow {
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--ptu-red);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 800;
            color: var(--ptu-blue);
            margin: 15px 0;
        }

        .section-subtitle {
            font-size: 1rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }

        .company-track-wrapper {
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 20px 0;
            -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
            mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        }

        .company-track {
            display: flex;
            width: fit-content;
        }

        @keyframes infiniteScroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .row-fast {
            animation: infiniteScroll 30s linear infinite;
        }

        .row-reverse {
            animation: infiniteScroll 30s linear infinite reverse;
        }

        .company-logo {
            flex: 0 0 160px;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 20px;
            padding: 15px;
            background: #fff;
            border-radius: 8px;
            border: 1px solid #eee;
            transition: var(--transition);
        }

        .company-logo img {
            max-width: 120px;
            max-height: 60px;
            transition: var(--transition);
        }

        .company-logo:hover {
            border-color: var(--ptu-red);
            transform: scale(1.1) translateY(-5px);
            box-shadow: 0 10px 30px rgba(165, 28, 48, 0.15);
        }

        .company-logo:hover img {
            filter: grayscale(0);
            opacity: 1;
        }

        .btn-primary {
            background: var(--ptu-red);
            color: white;
            padding: 12px 30px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-flex;
            gap: 10px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background: var(--ptu-blue);
            transform: translateY(-2px);
        }

        /* ===== NOTICES SECTION ===== */
        .ptu-notices-section {
            background: #fcfdff;
            padding: 60px 5%;
        }

        .notices-main-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .notices-flex-wrapper {
            display: flex;
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .notice-column {
            flex: 1;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .column-header {
            display: flex;
            align-items: center;
            padding: 20px 25px;
            color: #fff;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .crimson-theme {
            background: linear-gradient(135deg, var(--ptu-red) 60%, var(--ptu-blue) 100%);
        }

        .navy-theme {
            background: linear-gradient(135deg, var(--ptu-blue) 60%, var(--ptu-red) 100%);
        }

        .header-icon {
            margin-right: 15px;
        }

        .notices-list-container {
            flex: 1;
            padding: 25px;
            max-height: 400px;
            overflow-y: auto;
        }

        .notice-card {
            padding: 20px;
            background: #fafafa;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 3px solid #eee;
            transition: var(--transition);
        }

        .notice-card:hover {
            background: #fff;
            border-left-color: var(--ptu-blue);
        }

        .card-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.8rem;
            color: #888;
        }

        .status-tag.important {
            background: #ff9800;
            color: #fff;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .card-title {
            font-size: 1.1rem;
            color: var(--ptu-blue);
            font-weight: 700;
            margin-bottom: 10px;
        }

        .card-desc {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
        }

        .card-actions {
            display: flex;
            gap: 15px;
        }

        .btn-pdf,
        .btn-link {
            text-decoration: none;
            font-size: 0.8rem;
            padding: 6px 15px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: var(--transition);
        }

        .btn-pdf {
            color: var(--ptu-red);
            border: 1px solid var(--ptu-red);
            background: rgba(165, 28, 48, 0.05);
        }

        .btn-pdf:hover {
            background: var(--ptu-red);
            color: #fff;
        }

        .btn-link {
            color: var(--ptu-blue);
            border: 1px solid var(--ptu-blue);
            background: rgba(0, 46, 91, 0.05);
        }

        .btn-link:hover {
            background: var(--ptu-blue);
            color: #fff;
        }

        .view-all-band {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 25px;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .crimson-band {
            background: var(--ptu-red);
        }

        .navy-band {
            background: var(--ptu-blue);
        }

        .view-all-band:hover {
            padding-left: 30px;
        }

        .icon-circle {
            width: 30px;
            height: 30px;
            background: #ffd700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ===== LATEST PLACEMENTS (FIXED SLIDER) ===== */
        .latest-placements-section {
            padding: 60px 5%;
            background: #f9f9f9;
            overflow: hidden;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 50px;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
            flex-wrap: wrap;
            gap: 20px;
        }

        .section-header .title {
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            font-weight: 400;
            color: #333;
        }

        .section-header .title span {
            font-weight: 800;
            color: var(--ptu-blue);
            display: block;
        }

        .slider-controls {
            display: flex;
            gap: 15px;
        }

        .control-btn {
            width: 50px;
            height: 50px;
            border: 1px solid #ddd;
            background: #fff;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        }

        .control-btn:hover {
            background: var(--ptu-red);
            color: #fff;
            border-color: var(--ptu-red);
        }

        .placements-slider-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            overflow: hidden;
            position: relative;
        }

        .placements-track {
            display: flex;
            gap: 30px;
            transition: transform 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            will-change: transform;
        }

        .placement-card {
            flex: 0 0 calc(33.333% - 20px);
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
        }

        .placement-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        .card-image-wrapper {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .card-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s;
        }

        .placement-card:hover img {
            transform: scale(1.1);
        }

        .company-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 8px 16px;
            background: var(--ptu-red);
            color: #fff;
            font-weight: 800;
            border-radius: 4px;
        }

        .card-content {
            padding: 25px;
        }

        .placement-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--ptu-blue);
            margin-bottom: 10px;
        }

        .placement-desc {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
        }

        .know-more-link {
            text-decoration: none;
            color: var(--ptu-red);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
        }

        .know-more-link:hover {
            gap: 15px;
        }

        /* ===== TESTIMONIALS FIXED STYLES ===== */
        .testimonials-section {
            background: var(--ptu-red);
            padding: 60px 5%;
            color: var(--white);
        }

        .testimonials-section .section-title {
            color: var(--white);
            text-align: center;
            margin-bottom: 40px;
        }

        .testimonial-card {
            display: flex;
            flex-direction: column;
            background: var(--white);
            padding: 30px;
            border-radius: 16px;
            color: var(--ptu-blue);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            height: 100%;
            transition: transform 0.3s;
        }
        .testimonial-card:hover {
            transform: translateY(-5px);
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .ptu-navbar {
                padding: 0 20px;
            }

            .nav-list {
                gap: 20px;
            }

            .stats-track {
                grid-template-columns: repeat(2, 1fr);
            }

            .notices-flex-wrapper {
                flex-direction: column;
            }

            .footer-container {
                grid-template-columns: repeat(2, 1fr);
            }
            .placement-card {
                flex: 0 0 calc(50% - 15px);
            }
        }

        @media (max-width: 768px) {
            .hamburger {
                display: flex;
            }

            .nav-menu {
                position: absolute;
                top: 80px;
                left: 0;
                width: 100%;
                background: var(--ptu-blue);
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.4s;
                display: flex;
                flex-direction: column;
                align-items: center;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            }

            .nav-menu.active {
                max-height: 500px;
                padding: 30px 0;
            }

            .nav-list {
                flex-direction: column;
                gap: 15px;
                width: 100%;
                text-align: center;
            }

            .nav-link {
                color: #fff !important;
            }

            .nav-actions {
                display: none;
            }

            .mobile-only-action {
                display: block;
                width: 100%;
                padding: 0 20px;
            }

            .hero-slider-wrapper {
                height: 60vh;
            }

            .main-heading {
                font-size: 2rem;
            }

            .stats-track {
                grid-template-columns: 1fr;
            }

            .faq-grid {
                grid-template-columns: 1fr;
            }

            .footer-container {
                grid-template-columns: 1fr;
            }
            .placement-card {
                flex: 0 0 100%;
            }
            .slider-controls {
                margin-top: 10px;
            }
        }

        @media (max-width: 576px) {
            .hero-slider-wrapper {
                height: 50vh;
            }

            .company-logo {
                flex: 0 0 120px;
                height: 70px;
            }
        }

        .apply-now-strip {
            background: linear-gradient(to right, #eb8954, #414ce7);
            color: #0a1e2e;
            text-align: center;
            padding: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .apply-now-strip a {
            color: #000;
            text-decoration: none;
        }

       
/* ===== FAQ ===== */
        .ptu-faq-section {
            padding: 60px 5%;
            background: var(--white);
        }

        .faq-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .faq-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .faq-item {
            background: #f8faff;
            border: 1px solid #edf2f7;
            border-radius: 12px;
            margin-bottom: 20px;
            overflow: hidden;
            transition: var(--transition);
        }

        .faq-question {
            width: 100%;
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            color: var(--ptu-blue);
            text-align: left;
            font-size: 1rem;
        }

        .faq-question i {
            transition: transform 0.4s;
        }

        .faq-item.active {
            background: #fff;
            border-color: var(--ptu-red);
        }

        .faq-item.active .faq-question i {
            transform: rotate(45deg);
            color: var(--ptu-red);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s;
            padding: 0 25px;
        }

        .faq-item.active .faq-answer {
            max-height: 200px;
            padding-bottom: 20px;
        }

        .faq-footer {
            text-align: center;
            margin-top: 40px;
        }

        .faq-footer a {
            color: var(--ptu-red);
            font-weight: 600;
            text-decoration: none;
        }

        /* ===== FOOTER ===== */
        .site-footer {
            background: var(--ptu-blue);
            color: var(--white);
            padding: 60px 5% 20px;
        }

        .footer-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 40px;
            max-width: 1400px;
            margin: 0 auto 40px;
        }

        .footer-col h3,
        .footer-col h4 {
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-col h4::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 30px;
            height: 2px;
            background: var(--ptu-red);
        }

        .footer-list {
            list-style: none;
        }

        .footer-list li {
            margin-bottom: 10px;
        }

        .footer-list a {
            color: #b0b0b0;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-list a:hover {
            color: var(--ptu-red);
            padding-left: 5px;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            opacity: 0.6;
            max-width: 1400px;
            margin: 0 auto;
        }
.apply-now-strip {
        background: linear-gradient(to right, #eb8954, #414ce7);
        color: #0a1e2e;
        text-align: center;
        padding: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.5px;
      }

.apply-now-strip a {
  color: #000;
  text-decoration: none;
}

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 1024px) {
            .ptu-navbar {
                padding: 0 20px;
            }

            .nav-list {
                gap: 20px;
            }

            .stats-track {
                grid-template-columns: repeat(2, 1fr);
            }

            .notices-flex-wrapper {
                flex-direction: column;
            }

            .footer-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .hamburger {
                display: flex;
            }

            .nav-menu {
                position: absolute;
                top: 80px;
                left: 0;
                width: 100%;
                background: var(--ptu-blue);
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.4s;
                display: flex;
                flex-direction: column;
                align-items: center;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            }

            .nav-menu.active {
                max-height: 500px;
                padding: 30px 0;
            }

            .nav-list {
                flex-direction: column;
                gap: 15px;
                width: 100%;
                text-align: center;
            }

            .nav-link {
                color: #fff !important;
            }

            .nav-actions {
                display: none;
            }

            .mobile-only-action {
                display: block;
                width: 100%;
                padding: 0 20px;
            }

            .hero-slider-wrapper {
                height: 60vh;
            }

            .main-heading {
                font-size: 2rem;
            }

            .package-stats {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .stats-track {
                grid-template-columns: 1fr;
            }

            .faq-grid {
                grid-template-columns: 1fr;
            }

            .footer-container {
                grid-template-columns: 1fr;
            }

            .testimonial-slide {
                min-width: 100%;
            }

            .testimonial-card {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .hero-slider-wrapper {
                height: 50vh;
            }

            .company-logo {
                flex: 0 0 120px;
                height: 70px;
            }

            .placement-card {
                flex: 0 0 100%;
            }
        }
    </style>
</head>
<body>

<div class="apply-now-strip">
  <i class="fas fa-pen" aria-hidden="true"></i>
  <a href="https://ptu.ac.in/" target="_blank" rel="noopener noreferrer">
    Admissions 2026 are now open. Apply Now!
  </a>
</div>
    <!-- NAVBAR -->
    <nav class="ptu-navbar">
        <div class="nav-brand">
            <div class="logo-wrapper">
                <img src="{{ asset('public/assets/classic-logo.png') }}" alt="PTU Logo" class="brand-logo">
                <div class="logo-text">
                    <span class="univ-name">I.K. GUJRAL PUNJAB TECHNICAL UNIVERSITY</span>
                    <span class="tagline">Transforming Education, Transforming India</span>
                </div>
            </div>
        </div>
        
        <div class="nav-menu" id="nav-menu">
            <ul class="nav-list">
                <li><a href="https://ptu.ac.in/placements/training-placements-and-industrial-interface/" class="nav-link">ABOUT</a></li>
                <li><a href="https://ptu.ac.in/placements/mous/" class="nav-link">MOUs</a></li>
                <li><a href="{{ route('index') }}" class="nav-link">PLACEMENT DRIVES</a></li>
                <li><a href="https://ptu.ac.in/placements/events/" class="nav-link">EVENTS</a></li>
                <li><a href="https://ptu.ac.in/placements/corporate-relations-alumni-office/" class="nav-link">COORDINATORS</a></li>
                
 @if (Auth::check())
                <span style="margin-right: 10px; font-weight: bold; color: var(--ptu-blue);">
                    {{ Auth::user()->name }}
                </span>
                <a href="{{ route('logout') }}" class="apply-now-btn">Logout <i class="fas fa-sign-out-alt"></i></a>
            @else
                <a href="{{ route('login') }}" class="apply-now-btn">Login <i class="fas fa-arrow-right"></i></a>
            @endif
            </ul>
        </div>
        
        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>

    <!-- HERO SLIDER (shortened for brevity, works as before) -->
    <section class="hero-slider-wrapper">
        <div class="slider-container" id="heroSlider">
           @foreach($sliders as $index => $slide)
<div class="slide {{ $index == 0 ? 'active' : '' }}">
    <div class="slide-image" style="background-image: linear-gradient(to right, rgba(1,33,64,0.9), rgba(1,33,64,0.2)), url('{{ asset('/public/' . $slide->image_path) }}');"></div>
    <div class="slide-content">
        @if($slide->subheading)<h2 class="sub-heading">{{ $slide->subheading }}</h2>@endif
        @if($slide->title)<h1 class="main-heading">{!! $slide->title !!}</h1>@endif
        @if($slide->description)<p class="hero-desc">{{ $slide->description }}</p>@endif
        <div class="cta-container"><a href="{{ route('index') }}"><button class="btn-main">EXPLORE PLACEMENTS <i class="fas fa-arrow-right"></i></button></a></div>
    </div>
</div>
@endforeach
        </div>
        <button class="slider-nav prev" onclick="changeSlide(-1)"><i class="fas fa-chevron-left"></i></button>
        <button class="slider-nav next" onclick="changeSlide(1)"><i class="fas fa-chevron-right"></i></button>
        <div class="slider-dots" id="sliderDots"></div>
    </section>

    <!-- STATS BANNER, COMPANIES, NOTICES sections (unchanged existing UI) -->
    <section class="ikgptu-stats-banner"><div class="stats-track"><div class="stat-card"><div class="stat-icon-wrapper"><i class="fas fa-university stat-icon"></i></div><div class="stat-text-content"><h4 class="stat-title">Legacy</h4><p class="stat-description"><span>Public State University</span> Established in 1997</p></div></div><div class="stat-card"><div class="stat-icon-wrapper"><i class="fas fa-globe-americas stat-icon"></i></div><div class="stat-text-content"><h4 class="stat-title">Global Network</h4><p class="stat-description"><span>Over 190+</span> affiliated colleges</p></div></div><div class="stat-card"><div class="stat-icon-wrapper"><i class="fas fa-briefcase stat-icon"></i></div><div class="stat-text-content"><h4 class="stat-title">Placements</h4><p class="stat-description"><span>Training & Placement Cell</span> connecting students with top recruiters</p></div></div><div class="stat-card"><div class="stat-icon-wrapper"><i class="fas fa-award stat-icon"></i></div><div class="stat-text-content"><h4 class="stat-title">Accreditations</h4><p class="stat-description"><span>AICTE, UGC, NAAC</span> accredited</p></div></div></div></section>

<!-- NOTICES SECTION -->
    <section class="ptu-notices-section">
        <div class="notices-main-header">
            <h1 class="section-title">Announcements | Notice Board</h1>
            <p class="section-subtitle">Stay updated with the latest placement drives, corporate news, and important university notices.</p>
        </div>

        <div class="notices-flex-wrapper">
            <!-- Announcements Column -->
            <div class="notice-column">
                <div class="column-header crimson-theme">
                    <i class="fas fa-bullhorn header-icon"></i>
                    <h2>Latest Announcements</h2>
                </div>
                
                <div class="notices-list-container">
                    @forelse($recentAnnouncements ?? [] as $announcement)
                        <div class="notice-card">
                            <div class="card-meta">
                                <span><i class="far fa-calendar-alt"></i> {{ isset($announcement->publish_date) ? \Carbon\Carbon::parse($announcement->publish_date)->diffForHumans() : 'Recently' }}</span>
                                @if(isset($announcement->priority) && $announcement->priority == 'important')
                                    <span class="status-tag important">IMPORTANT</span>
                                @endif
                            </div>
                            <h3 class="card-title">{{ $announcement->title ?? 'Announcement Title' }}</h3>
                            <p class="card-desc">{{ Str::limit($announcement->content ?? '', 100) }}</p>
                            <div class="card-actions">
                                @if(isset($announcement->file_path) && $announcement->file_path)
                                    <a href="{{ asset('public/' . $announcement->file_path) }}" target="_blank" class="btn-pdf"><i class="fas fa-file-pdf"></i> View PDF</a>
                                @endif
                                @if(isset($announcement->external_link) && $announcement->external_link)
                                    <a href="{{ $announcement->external_link }}" target="_blank" class="btn-link"><i class="fas fa-external-link-alt"></i> Visit Link</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="notice-card">
                            <div class="card-meta">
                                <span><i class="far fa-calendar-alt"></i> 2 days ago</span>
                                <span class="status-tag important">IMPORTANT</span>
                            </div>
                            <h3 class="card-title">TCS Campus Recruitment Drive 2026</h3>
                            <p class="card-desc">Registration open for B.Tech (CSE/ECE/IT) students. Pre-placement session mandatory.</p>
                            <div class="card-actions">
                                <a href="#" class="btn-pdf"><i class="fas fa-file-pdf"></i> View PDF</a>
                                <a href="#" class="btn-link"><i class="fas fa-external-link-alt"></i> Visit Link</a>
                            </div>
                        </div>

                        <div class="notice-card">
                            <div class="card-meta">
                                <span><i class="far fa-calendar-alt"></i> 1 week ago</span>
                            </div>
                            <h3 class="card-title">EPAM Systems India - Virtual Off-Campus Drive</h3>
                            <p class="card-desc">Online Test on 23rd January 2026. Registration open now.</p>
                            <div class="card-actions">
                                <a href="#" class="btn-pdf"><i class="fas fa-file-pdf"></i> View PDF</a>
                                <a href="#" class="btn-link"><i class="fas fa-external-link-alt"></i> Visit Link</a>
                            </div>
                        </div>
                    @endforelse
                </div>
                
              <a href="#" class="view-all-band crimson-band" >
                    View All Announcements
                </a>

            </div>

            <!-- Notices Column -->
            <div class="notice-column">
                <div class="column-header navy-theme">
                    <i class="fas fa-chalkboard-teacher header-icon"></i>
                    <h2>Important News & Notices</h2>
                </div>
                
                <div class="notices-list-container">
                    @forelse($recentNotices ?? [] as $notice)
                        <div class="notice-card">
                            <div class="card-meta">
                                <span><i class="far fa-calendar-alt"></i> {{ isset($notice->publish_date) ? \Carbon\Carbon::parse($notice->publish_date)->diffForHumans() : 'Recently' }}</span>
                                @if(isset($notice->expiry_date) && \Carbon\Carbon::parse($notice->expiry_date)->isPast())
                                    <span class="status-tag important">EXPIRED</span>
                                @endif
                            </div>
                            <h3 class="card-title">{{ $notice->title ?? 'Notice Title' }}</h3>
                            <p class="card-desc">{{ Str::limit($notice->content ?? '', 100) }}</p>
                            <div class="card-actions">
                                @if(isset($notice->file_path) && $notice->file_path)
                                    <a href="{{ asset('public/' . $notice->file_path) }}" target="_blank" class="btn-pdf"><i class="fas fa-file-pdf"></i> View PDF</a>
                                @endif
                                @if(isset($notice->external_link) && $notice->external_link)
                                    <a href="{{ $notice->external_link }}" target="_blank" class="btn-link"><i class="fas fa-external-link-alt"></i> Read More</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="notice-card">
                            <div class="card-meta">
                                <span><i class="far fa-calendar-alt"></i> 1 day ago</span>
                            </div>
                            <h3 class="card-title">IKGPTU sets record with 1500+ offers</h3>
                            <p class="card-desc">University establishes new benchmark for placements with leading MNCs participating.</p>
                            <div class="card-actions">
                                <a href="#" class="btn-link"><i class="fas fa-external-link-alt"></i> Read More</a>
                            </div>
                        </div>

                        <div class="notice-card">
                            <div class="card-meta">
                                <span><i class="far fa-calendar-alt"></i> 2 weeks ago</span>
                                <span class="status-tag important">EXPIRED</span>
                            </div>
                            <h3 class="card-title">Jaro Education - Pool Campus Drive</h3>
                            <p class="card-desc">The application deadline has passed for this opportunity.</p>
                        </div>
                    @endforelse
                </div>
                
                <a href="#" class="view-all-band navy-band" >
                    View All News & Updates 
                </a>
            </div>
        </div>

   
    </section>

    <!-- LATEST PLACEMENTS (FIXED SLIDER) -->
    <section class="latest-placements-section">
        <div class="section-header">
            <div class="header-left"><h2 class="title">Glance at the <br><span>Latest Placement Updates</span></h2></div>
            <div class="slider-controls">
                <button class="control-btn prev" id="placement-prev"><i class="fas fa-arrow-left"></i></button>
                <button class="control-btn next" id="placement-next"><i class="fas fa-arrow-right"></i></button>
            </div>
        </div>
        <div class="placements-slider-wrapper">
            <div class="placements-track" id="placements-track">
                @foreach($placementUpdates as $placement)
                <div class="placement-card">
                    <div class="card-image-wrapper">
                        <img src="{{ asset('/public/' . $placement->image_path) }}" alt="{{ $placement->company_name }}">
                        <div class="company-badge">{{ $placement->company_name }}</div>
                    </div>
                    <div class="card-content">
                        <h3 class="placement-title">{{ $placement->package }} with {{ $placement->company_name }}</h3>
                        <p class="placement-desc">{{ $placement->description }}</p>
                        <a href="https://ptu.ac.in/placements/campus-placements/" class="know-more-link">Know more <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS - FULLY FIXED WORKING SLIDER -->
    <section class="testimonials-section">
        <h2 class="section-title">Learners to Leaders</h2>
        <div style="position: relative; max-width: 1200px; margin: 0 auto;">
            <button id="prevBtn" style="position: absolute; left: -20px; top: 50%; transform: translateY(-50%); z-index: 10; background: var(--ptu-blue); color: white; border: none; width: 42px; height: 42px; border-radius: 50%; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.2);"><i class="fas fa-chevron-left"></i></button>
            <div style="overflow: hidden; margin: 0 40px;">
                <div id="sliderTrack" style="display: flex; transition: transform 0.5s cubic-bezier(0.2, 0.9, 0.4, 1.1); gap: 30px; will-change: transform;">
                    @foreach($testimonials as $testimonial)
                    <div class="testimonial-slide-item" style="flex: 0 0 calc(33.333% - 20px); min-width: 0;">
                        <div class="testimonial-card">
                            @if($testimonial->image_path && file_exists(public_path($testimonial->image_path)))
                                <img src="{{ asset('/public/' . $testimonial->image_path) }}" alt="{{ $testimonial->name }}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-bottom: 20px; border: 3px solid #007bff;">
                            @else
                                <i class="fas fa-user-circle fa-4x" style="color: #007bff; margin-bottom: 20px;"></i>
                            @endif
                            <div>
                                <p style="font-size: 1rem; line-height: 1.6; color: #555; font-style: italic;">"{{ $testimonial->quote }}"</p>
                                <h4 style="margin-bottom: 5px; margin-top: 15px;">{{ $testimonial->name }}</h4>
                                <span style="color: #007bff;">{{ $testimonial->designation }} @if($testimonial->company), {{ $testimonial->company }}@endif</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <button id="nextBtn" style="position: absolute; right: -20px; top: 50%; transform: translateY(-50%); z-index: 10; background: var(--ptu-blue); color: white; border: none; width: 42px; height: 42px; border-radius: 50%; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.2);"><i class="fas fa-chevron-right"></i></button>
        </div>
    </section>

<!-- FAQ SECTION -->
     <!-- FAQ SECTION -->
    <section class="ptu-faq-section">
    <div class="container">
        <div class="faq-header">
            <span class="eyebrow">HELP CENTER</span>
            <h2 class="section-title">Frequently Asked <span>Questions</span></h2>
            <p class="section-subtitle">Find answers to common queries regarding placement processes, eligibility, and corporate relations at IKGPTU.</p>
        </div>

        <div class="faq-grid">
            <div class="faq-column">
                <div class="faq-item">
                    <button class="faq-question">
                        <span>How do I register for the upcoming campus placement drives?</span>
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Students must register through the official IKGPTU Placement Portal using their University Roll Number. Ensure your profile is updated with the latest CGPA and professional resume to be eligible for company-specific shortlisting.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        <span>What is the minimum eligibility criteria for IKGPTU-standard placements?</span>
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="faq-answer">
                        <p>While criteria vary by recruiter, a minimum CGPA of 6.5-7.0 with no active backlogs is generally required for Tier-1 companies. Some MNCs also require a minimum of 60% marks in 10th and 12th grades.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        <span>Does the university provide pre-placement training?</span>
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes, IKGPTU conducts rigorous Corporate Excellence Programs (CEP) including mock interviews, soft skills workshops, and technical coding bootcamps to prepare students for high-package roles.</p>
                    </div>
                </div>
            </div>

            <div class="faq-column">
                <div class="faq-item">
                    <button class="faq-question">
                        <span>Which top companies regularly recruit from IKGPTU?</span>
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Our regular recruiters include industry giants like TCS, Infosys, Cognizant, Wipro, SAP Labs, and Reltio, with packages ranging from ₹4.5 LPA to over ₹20 LPA.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        <span>Can alumni access the placement portal for job updates?</span>
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Yes, the Corporate Relations & Alumni Office provides a dedicated portal for alumni to explore lateral hiring opportunities and off-campus drive notifications.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        <span>What should I do if I face a technical glitch during an online test?</span>
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Immediately report the issue to your Department Coordinator or the T&P volunteer present. Documentation (like screenshots) of the error is recommended for further escalation to the company HR.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="faq-footer">
            <p>Still have questions? <a href="#">Contact the Placement Cell</a></p>
        </div>
    </div>
    </section>

    <!-- FOOTER -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-col">
                <h3>Training & Placement Cell</h3>
                <p>I. K. Gujral Punjab Technical University</p>
                <p>Bridging students with top recruiters through structured campus drives and industry partnerships.</p>
            </div>

            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul class="footer-list">
                    <li><a href="https://ptu.ac.in/placements/events/">PlacementEvents</a></li>
                    <li><a href="https://ptu.ac.in/placements/corporate-relations-alumni-office/">T & P Faculty Coordinators</a></li>
                    <li><a href="{{ route('index') }}">Upcoming Drives</a></li>
                    <li><a href="https://ptu.ac.in/placements/placement-drives-results/">Placement Drives Results</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>For Recruiters</h4>
                <ul class="footer-list">
                    <li><a href="https://ptu.ac.in/placements/contact-us/">Contact Us</a></li>
                    <li><a href=https://ptu.ac.in/placements/government-vacancies/">Government Vacancies</a></li>
                    <li><a href="{{ route('index') }}">Schedule Campus Drive</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Contact</h4>
                <ul class="footer-list">
                    <li><i class="fas fa-map-marker-alt"></i> Jalandhar - Kapurthala Highway, Punjab</li>
                    <li><i class="fas fa-phone"></i> <a href="tel:+919478098136">+91-9478098136</a></li>
                    <li><i class="fas fa-envelope"></i> <a href="mailto:mohitkjain@ptu.ac.in">mohitkjain@ptu.ac.in</a></li>
                </ul>
            </div>
        </div>

     
 <section
                style="
                    background: linear-gradient(135deg, #0077b6, #009688);
                    color: #ffffff;
                    padding:10px;
                    text-align: center;
                    font-family: 'Poppins', sans-serif;
                    font-size: 15px;
                    letter-spacing: 0.3px;
                    border-bottom-left-radius: 32px;
                    border-top-right-radius: 32px;
                    box-shadow: 0 -2px 15px rgba(0,0,0,0.1);
                ">
                <p style="margin: 0;">
                    <span style="opacity: 0.9;">&copy; <strong>Copyright</strong> </span>
                    <a href="https://ptu.ac.in/" style="color:#fff; font-weight:600; text-decoration:none;">IKGPTU
                        Placements
                        T&amp;P Cell</a>
                    <span style="opacity: 0.9;"> || Developed By </span>
                    <a href="https://birendrapandit.online"
                        style="color:#ffe66d; font-weight:600; text-decoration:none;">Birendra Pandit</a>
                </p>
            </section>
    </footer>

 <script>
        // Navbar toggle
        document.getElementById('hamburger').addEventListener('click', function() {
            this.classList.toggle('active');
            document.getElementById('nav-menu').classList.toggle('active');
        });

        // Hero slider logic
        let heroSlides = document.querySelectorAll('.slide');
        let currentHero = 0;
        function updateHeroDots() {
            let dotsHtml = '';
            for (let i = 0; i < heroSlides.length; i++) {
                dotsHtml += `<span class="dot ${i === currentHero ? 'active' : ''}" onclick="goToSlide(${i})"></span>`;
            }
            document.getElementById('sliderDots').innerHTML = dotsHtml;
        }
        window.changeSlide = function(d) {
            currentHero = (currentHero + d + heroSlides.length) % heroSlides.length;
            document.querySelector('.slide.active')?.classList.remove('active');
            heroSlides[currentHero].classList.add('active');
            updateHeroDots();
        };
        window.goToSlide = function(n) { currentHero = n; document.querySelector('.slide.active')?.classList.remove('active'); heroSlides[currentHero].classList.add('active'); updateHeroDots(); };
        updateHeroDots();
        if (heroSlides.length) heroSlides[0].classList.add('active');
        setInterval(() => changeSlide(1), 5000);

        // ========== PLACEMENT SLIDER (FULLY WORKING) ==========
        const placementTrack = document.getElementById('placements-track');
        const placementPrev = document.getElementById('placement-prev');
        const placementNext = document.getElementById('placement-next');
        if (placementTrack && placementPrev && placementNext) {
            let placementCards = [...placementTrack.children];
            let cardWidth = placementCards[0]?.offsetWidth || 320;
            let gap = 30;
            let visibleCount = 3;
            let currentIdx = 0;
            function updateVisibleCount() {
                if (window.innerWidth <= 768) visibleCount = 1;
                else if (window.innerWidth <= 1024) visibleCount = 2;
                else visibleCount = 3;
            }
            function getSlideWidth() {
                return (placementCards[0]?.offsetWidth || 320) + gap;
            }
            function updateTrackTransform() {
                let shift = currentIdx * getSlideWidth();
                placementTrack.style.transform = `translateX(-${shift}px)`;
            }
            function handleNext() {
                let maxIdx = Math.max(0, placementCards.length - visibleCount);
                if (currentIdx < maxIdx) {
                    currentIdx++;
                    updateTrackTransform();
                }
            }
            function handlePrev() {
                if (currentIdx > 0) {
                    currentIdx--;
                    updateTrackTransform();
                }
            }
            placementNext.addEventListener('click', handleNext);
            placementPrev.addEventListener('click', handlePrev);
            window.addEventListener('resize', () => {
                updateVisibleCount();
                let maxIdx = Math.max(0, placementCards.length - visibleCount);
                if (currentIdx > maxIdx) currentIdx = maxIdx;
                updateTrackTransform();
            });
            updateVisibleCount();
            updateTrackTransform();
        }

        // ========== TESTIMONIAL SLIDER (WORKING PERFECTLY) ==========
        const sliderTrack = document.getElementById('sliderTrack');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        if (sliderTrack && prevBtn && nextBtn) {
            let testimonialItems = [...sliderTrack.children];
            let currentTestimonialIndex = 0;
            let visibleTestimonials = 3;
            function updateTestimonialVisible() {
                if (window.innerWidth <= 768) visibleTestimonials = 1;
                else if (window.innerWidth <= 1024) visibleTestimonials = 2;
                else visibleTestimonials = 3;
                testimonialItems.forEach(item => {
                    item.style.flex = `0 0 calc(${100 / visibleTestimonials}% - ${(visibleTestimonials-1) * 30 / visibleTestimonials}px)`;
                });
            }
            function getTestimonialShift() {
                let firstItem = testimonialItems[0];
                if (!firstItem) return 0;
                let totalWidth = firstItem.offsetWidth + 30;
                return totalWidth * currentTestimonialIndex;
            }
            function moveTestimonialSlider() {
                let shiftAmount = getTestimonialShift();
                sliderTrack.style.transform = `translateX(-${shiftAmount}px)`;
            }
            function nextTestimonial() {
                let maxIndex = Math.max(0, testimonialItems.length - visibleTestimonials);
                if (currentTestimonialIndex < maxIndex) {
                    currentTestimonialIndex++;
                    moveTestimonialSlider();
                }
            }
            function prevTestimonial() {
                if (currentTestimonialIndex > 0) {
                    currentTestimonialIndex--;
                    moveTestimonialSlider();
                }
            }
            nextBtn.addEventListener('click', nextTestimonial);
            prevBtn.addEventListener('click', prevTestimonial);
            window.addEventListener('resize', () => {
                updateTestimonialVisible();
                let maxIdx = Math.max(0, testimonialItems.length - visibleTestimonials);
                if (currentTestimonialIndex > maxIdx) currentTestimonialIndex = maxIdx;
                moveTestimonialSlider();
            });
            updateTestimonialVisible();
            moveTestimonialSlider();
        }

        // FAQ Accordion
        function initFaqAccordion() {
            const faqItems = document.querySelectorAll('.faq-item');
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                question.addEventListener('click', () => {
                    const isActive = item.classList.contains('active');
                    faqItems.forEach(other => other.classList.remove('active'));
                    if (!isActive) item.classList.add('active');
                });
            });
        }
        document.addEventListener('DOMContentLoaded', initFaqAccordion);
    </script>
</body>
</html>