@extends('frontend.layouts.new.master')
@section('page_title', isset($page_title) ? $page_title : 'PROJECTS')
@push('styles')
    <style>
        .project-container {
            position: relative;
            width: 100%;
        }

        .project-showcase {
            background-image:
                linear-gradient(135deg, rgba(1, 22, 39, 0.92), rgba(1, 12, 24, 0.7)),
                url(/frontend/asset/images/advertisement/advertisement-slideshow\(2\).webp);
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            width: 100%;
            height: 240px;
            background-color: #012142;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .project-showcase {
            p {
                padding: 10px;
                width: 70%;
                text-align: center;
            }

            h2 {
                font-weight: 700;
                font-size: 42px;
            }
        }

        .project-with-slide {
            width: min(92%, 1100px);
            height: auto;
            margin: 45px auto 8px;
            display: flex;
            /* gap: 10px; */
            border-radius: 20px;
            box-shadow: inset 0px 0px 10px 0px gray;
            overflow: hidden;
            box-sizing: border-box;
        }

        .project-with-slide {
            img {
                width: 50%;
                max-width: 100%;
                flex: 0 0 50%;
                padding: 3px;
                border-radius: 20px;
                object-fit: cover;
            }
        }

        .project-content {
            padding: 22px;
            flex: 1 1 0;
            min-width: 0;
        }

        .project-content {
            span {
                color: rgb(16, 129, 242);
                font-weight: 700;
            }

            h3 {
                margin-top: 15px;
                font-weight: 700;
            }

            #detail-project-text {
                width: 100%;
            }
        }

        .project-content #skills {
            display: flex;
            gap: 20px;
            /* justify-content: space-evenly; */
        }

        .project-content #skills p {
            background-color: aliceblue;
            color: blue;
            padding: 10px;
            border-radius: 10px;
            border: 0.2px solid rgb(196, 219, 255);
        }

        .teacher-n-duration {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }

        .teacher-n-duration .teacher-duration-icon {
            display: flex;
            gap: 8px;
        }

        .teacher-n-duration .teacher-duration-icon {
            p {
                font-weight: 600;
            }

            i {
                margin-top: 4px;
            }
        }

        .view-detail {
            display: flex;
            gap: 30px;
            margin-top: 10px;
        }

        .view-detail {
            a {
                color: rgb(244, 241, 241);
                background-color: #1677fe;
                padding: 10px;
                font-weight: 600;
                font-family: var(--font-body);
                border-radius: 10px;
                text-decoration: none;
            }

            a i {
                margin-left: 5px;
            }

            #live-demo {
                background-color: white;
                color: black;
                border: 1px solid black;
            }
        }

        /* ===== Filter Pills + Search (reuses your search-box-project) ===== */
        .portfolio-filter-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            padding: 20px 80px 60px 80px;
            /* margin-bottom: 35px; */
        }

        .portfolio-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .portfolio-pill {
            padding: 10px 20px;
            border: 1px solid #d5d5d6;
            border-radius: 30px;
            background: #fff;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.2s ease;
            text-decoration: none;
            display: inline-block;
            color: #333;
        }

        .portfolio-pill.active {
            background: dodgerblue;
            color: #fff;
            border-color: dodgerblue;
        }

        .search-box-project {
            display: flex;
            width: 400px;
            background-color: #f5f6f8;
            border-radius: 30px;
            border: 0.1px solid rgb(176, 175, 175);
            gap: 3px;
            height: 45px;
        }

        .search-box-project {
            i {
                color: rgb(98, 97, 97);
                margin-top: 13px;
                font-size: 20px;
                padding-left: 10px;
            }

            input {
                border: none;
                height: 100%;
                font-size: 20px;
                border-radius: 24px;
            }
        }

        /* ===== Card Grid ===== */
        /* .portfolio-grid{
                width: 91%;
                display: grid;
                margin: auto;
                grid-template-columns: repeat(auto-fit, minmax(310px, 1fr));
                gap: 25px;
                justify-content: center;
                overflow-x: auto;
            } */
        .portfolio-grid {
            width: 97%;
            display: grid;
            margin: auto;
            grid-template-columns: repeat(3, 1fr);
            /* grid-template-columns: repeat(auto-fit, minmax(310px, 1fr)); */
            gap: 20px;
            justify-content: center;
            /* overflow-x: auto; */
        }


        .portfolio-card {
            box-shadow: 0px 0px 10px 0px gray;
            width: 100%;
            min-width: 320px;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            overflow: hidden;
            cursor: pointer;
            /* transition: box-shadow 0.2s ease, transform 0.2s ease; */
        }

        .portfolio-card:hover {
            /* box-shadow: 0 8px 20px rgba(0,0,0,0.08); */
            transform: translateY(-4px);
        }

        /* ===== Image / Cover Area ===== */
        .portfolio-img {
            position: relative;
            /* min-height: 240px;
                overflow: hidden; */
            object-fit: cover;
        }

        .portfolio-img img {
            width: 100%;
            height: 100%;
            max-width: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
            aspect-ratio: 4 / 2.6;
        }

        .portfolio-card:hover .portfolio-img img {
            transform: scale(1.08);
        }


        .tag-category {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(230, 244, 254, 0.9);
            color: #3091f9;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            z-index: 2;
        }

        .tag-featured {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #ffd43b;
            color: #4a3b00;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ===== Card Body ===== */
        .portfolio-body {
            padding: 18px 20px 20px;
        }

        .portfolio-body {
            h3 {
                font-size: 18px;
                margin-bottom: 6px;
                font-weight: 700;
                line-height: 1.3;
            }

            p {
                font-size: 14.5px;
                color: rgb(133, 133, 133);
                line-height: 1.5;
                margin-bottom: 14px;
            }

        }

        /* ===== Tool Tags (Figma / Illustrator / Photoshop) ===== */
        .tool-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 18px;
        }

        .tool-tag {
            font-size: 12px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .tag-figma {
            background: #f3e8ff;
            color: #9333ea;
        }

        .tag-illustrator {
            background: #ffedd5;
            color: #ea580c;
        }

        .tag-photoshop {
            background: #dbeafe;
            color: #2563eb;
        }

        /* ===== Footer: Author + Stats ===== */
        .portfolio-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #eee;
            padding-top: 14px;
        }

        .portfolio-author {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .portfolio-author img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .portfolio-author strong {
            display: block;
            font-size: 14px;
            color: #111;
        }

        .portfolio-author span {
            font-size: 11.5px;
            color: rgb(143, 143, 143);
        }

        .portfolio-stats {
            display: flex;
            gap: 12px;
            font-size: 12.5px;
            color: grey;
        }

        .portfolio-stats i {
            margin-right: 3px;
        }

        /* =====----------------------------- Modal Card Container-block --------------===== */
        .card-select-block {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            width: 100%;
            min-height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            position: fixed;
            inset: 0;
            z-index: 10;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE/Edge legacy */
            padding: 40px 20px;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .card-select-block::-webkit-scrollbar {
            display: none;
        }

        .card-select-block.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .modal-card {
            position: relative;
            top: 14%;
            width: 70%;
            max-width: 100%;
            /* max-width: 1100px; */
            margin: 0 auto;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 1.0s ease, transform 1.0s ease;
        }

        [data-theme="dark"] .card-select-block.active .modal-card {
            background-color: #1e293b;
        }

        .card-select-block.active .modal-card {
            opacity: 1;
            transform: translateY(0);
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #f8fbf8;
        }

        /* ===== Hero / Banner ===== */
        .modal-hero {
            position: relative;
            height: 400px;
            overflow: hidden;
            color: #fff;
            background: none;
        }

        /* Carousel fills the entire hero as a background layer */
        .hero-carousel,
        .hero-carousel .carousel-inner,
        .hero-carousel .carousel-item {
            position: absolute;
            inset: 0;
            height: 100%;
        }

        .hero-carousel img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* dark gradient over the images so white text stays readable */
        .hero-scrim {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.55) 0%, rgba(0, 0, 0, 0.75) 100%);
            z-index: 1;
        }

        /* text/badges/title block sits above the carousel + scrim, and doesn't move when slides change */
        .hero-static-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            height: 100%;
            padding: 30px;

        }

        /* .hero-static-content h1{
                font-size: 90px;
                text-align: center;
                font-weight: 700;
                background: linear-gradient(90deg, #fff, #9c80fa);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            } */

        /* .hero-static-content .tagline{
                text-align: center;
                font-size: 20px;
                font-weight: 600;
                letter-spacing: 2px;
                color: #e5e5ee;
                background: linear-gradient(90deg, #fff, #9c80fa);
                margin-top: 10px;
            } */

        /* close / arrow buttons need to sit above everything too */
        .modal-close,
        .modal-arrow {
            z-index: 3;
        }

        .modal-hero .tagline {
            padding: 20px 40px 0;
            font-size: 27px;
            font-weight: 600;
            letter-spacing: 2px;
            color: #d6d6e0;
            align-items: start;
            justify-content: left;
            /* margin: 10px 0 20px 0; */
            /* margin: auto; */
        }

        /* close button, top right */
        .modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* prev / next arrows, left & right middle */
        /* .modal-arrow{
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                width: 42px;
                height: 42px;
                border-radius: 50%;
                background: rgba(255,255,255,0.15);
                border: none;
                color: #fff;
                font-size: 18px;
                cursor: pointer;
            }
            .modal-arrow:hover{
                background: rgba(255,255,255,0.3);
            }
            .arrow-left{ left: 20px; }
            .arrow-right{ right: 20px; } */

        /* ===== Badges + Title (below hero) ===== */
        .modal-badges {
            padding: 20px 40px 0;
            display: flex;
            gap: 10px;
        }

        .badge {
            font-size: 13px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
        }

        .badge-category {
            background: #e6efff;
            color: #3777ff;
        }

        .badge-place {
            background: #f3f3f4;
            color: #333;
            border: 1px solid #ddd;
        }

        .modal-title {
            padding: 10px 40px 20px;
            font-size: 26px;
            font-weight: 700;
            color: #ffffff;
        }

        /* ========= Stats Row ========== */
        .modal-stats {
            display: flex;
            gap: 30px;
            padding: 18px 40px;
            font-size: 14px;
            /* color: #444; */
            flex-wrap: wrap;
        }

        .modal-stats span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .modal-stats i {
            color: #3777ff;
        }

        /* ===== Two Column Body ===== */
        .modal-body {
            display: flex;
            gap: 30px;
            padding: 0 40px 40px;
            align-items: flex-start;
        }

        .modal-main {
            flex: 1;
            min-width: 0;
        }

        /* action buttons */
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
            flex-wrap: wrap;
            /* if space runs out, "Documentation" drops to next line — whole buttons, not broken text */
        }

        .btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid #ddd;
            background: #fff;
            color: #222;
            white-space: nowrap;
            /* keep text on one line */
            flex-shrink: 0;
        }

        .btn i {
            flex-shrink: 0;
            /* icon stays fixed size, doesn't shrink oddly */
        }

        .btn-primary {
            background: #3777ff;
            color: #fff;
            border: none;
        }

        .btn-primary:hover {
            background: #2a5fd6;
        }

        .btn:hover {
            border: 1px solid rgb(216, 215, 215);
        }

        /* content sections */
        .section {
            margin-bottom: 22px;
        }

        .section h4 {
            /* color: #111; */
            font-size: 15px;
            margin-bottom: 8px;
        }

        .section p,
        .section li {
            font-size: 14px;
            /* color: #555; */
            line-height: 1.6;
        }

        .section ul {
            padding-left: 18px;
        }

        .two-col {
            display: flex;
            gap: 30px;
        }

        .two-col .section {
            flex: 1;
        }

        .tech-tags {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .tech-tag {
            font-size: 13px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
        }

        .tag-illustrator {
            background: #ffedd5;
            color: #ea580c;
        }

        .tag-photoshop {
            background: #dbeafe;
            color: #2563eb;
        }

        .tag-figma {
            background: #f3e8ff;
            color: #9333ea;
        }

        .tag-branding {
            background: #fff;
            border: 1px solid #ddd;
            /* color: #333; */
        }

        /* ===== Sidebar (Author Card) ===== */
        .modal-sidebar {
            width: 280px;
            border: 1px solid #e7e6e6;
            box-shadow: 0px 0px 10px 0px #dcdcdc;
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            flex-shrink: 0;
            min-width: 0;
        }

        .modal-sidebar img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .modal-sidebar h3 {
            font-size: 17px;
            /* color: black; */
            margin-bottom: 4px;
        }

        .modal-sidebar .role {
            color: #3777ff;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .modal-sidebar .batch-info {
            font-size: 13px;
            /* color: #666; */
            margin-bottom: 12px;
        }

        .certified-badge {
            display: inline-block;
            background: #e9f9ee;
            color: #1a9c4a;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 16px;
            border-radius: 20px;
            margin-bottom: 16px;
        }

        .sidebar-tags {
            display: flex;
            justify-content: center;
            gap: 8px;
            /* flex-wrap: wrap; */
            margin-bottom: 16px;
        }

        .sidebar-tags span {
            font-size: 12px;
            border: 1px solid #ddd;
            padding: 5px 12px;
            border-radius: 20px;
            /* color: #333; */
        }

        .sidebar-socials {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .sidebar-socials a {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #e6efff;
            color: #3777ff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .sidebar-socials a:hover {
            background: #d0e0ff;
        }

        /* ===== Development Process (numbered step cards) ===== */
        .process-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .process-card {
            border: 1px solid #eee;
            box-shadow: 0px 0px 10px 0px #e2e2e2;
            border-radius: 14px;
            padding: 18px;
        }

        .process-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #dbeafe;
            color: #2563eb;
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .process-card h5 {
            /* color: #111; */
            font-size: 14.5px;
            margin-bottom: 6px;
        }

        .process-card p {
            font-size: 13px;
            /* color: #666; */
            line-height: 1.5;
        }

        /* ===== Screenshots Gallery ===== */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .gallery-grid img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #d5f5d5;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .gallery-grid img:hover {
            transform: scale(1.03);
        }

        /* Slide Carousel Advertisement */
        .avertisement-slide {
            width: 95%;
            margin: auto;
            margin-top: 30px;
            padding: 40px;
            border-radius: 120px;
        }

        .avertisement-slide .carousel-inner {
            border-radius: 30px;

        }

        /* ============================================= */
        /* ===== Responsive: Project Showcase Page ===== */
        /* ============================================= */

        /* ----- 1024px: Tablet / small laptop ----- */
        @media (max-width: 1024px) {
            .project-showcase {
                height: 180px;
            }

            .project-showcase p {
                width: 85%;
            }

            .project-with-slide {
                width: 88%;
                height: auto;

            }

            .project-with-slide img {
                width: 40%;
                /* height: 400px; */
                object-fit: cover;
            }

            .project-content {
                padding: 20px;
            }

            .project-content #detail-project-text {
                width: 99%;
            }

            .teacher-n-duration {
                gap: 25px;
                row-gap: 10px;
                flex-wrap: wrap;
                line-height: 0.2;
            }

            .teacher-duration-icon p {
                margin-top: 10px;
            }


            .portfolio-filter-row {
                padding: 20px 40px 40px 40px;
            }

            .search-box-project {
                width: 320px;
            }

            .portfolio-grid {
                width: 93%;
                grid-template-columns: repeat(auto-fit, minmax(310px, 1fr));
            }


            /* ==============================Click porject block code =================================== */
            .modal-card {
                width: 85%;
                top: 8%;
            }

            .modal-hero {
                height: 340px;
            }

            /* .hero-static-content h1{
                    font-size: 60px;
                } */
            .hero-static-content .tagline {
                font-size: 17px;
            }

            .modal-badges {
                padding: 20px 30px 0;
            }

            .modal-title {
                padding: 10px 30px 15px;
                font-size: 22px;
            }

            .modal-stats {
                padding: 15px 30px;
                gap: 20px;
            }

            .modal-body {
                padding: 0 30px 30px;
                gap: 20px;
            }

            .modal-sidebar {
                width: 240px;
                padding: 20px;
            }

            .process-grid {
                grid-template-columns: repeat(2, 1fr);
                /* 4 -> 2 columns */
            }

            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
                /* 3 -> 2 columns */
            }
        }

        /* ----- 768px: Tablet portrait ----- */
        @media (max-width: 768px) {
            .project-showcase {
                height: 160px;
                padding: 0 15px;
            }

            .project-showcase h2 {
                font-size: 22px;
            }

            .project-showcase p {
                width: 95%;
                font-size: 13.5px;
            }

            .project-with-slide {
                flex-direction: column;
                width: 90%;
                margin-top: 30px;
            }

            .project-with-slide img {
                height: 220px;
                width: 100%;
            }

            .project-content h3 {
                font-size: 18px;
            }

            .project-content #skills {
                flex-wrap: wrap;
                gap: 10px;
            }

            .project-content #skills p {
                padding: 8px 12px;
                font-size: 13px;
            }

            .teacher-n-duration {
                flex-direction: column;
                gap: 10px;
                margin-top: 15px;
            }

            .view-detail {
                flex-wrap: wrap;
                gap: 15px;
            }

            .view-detail a {
                padding: 9px 14px;
                font-size: 14px;
            }

            .tag-category {
                position: absolute;
                top: 12px;
                left: 12px;
                background: rgba(230, 244, 254, 0.9);
                color: #0f80fa;
                font-size: 11px;
                font-weight: 600;
                padding: 3px 9px;
                border-radius: 20px;
                z-index: 2;
            }

            .tag-featured {
                position: absolute;
                top: 12px;
                right: 12px;
                background: #ffd43b;
                color: #4a3b00;
                font-size: 11px;
                font-weight: 700;
                padding: 3px 9px;
                border-radius: 20px;
                z-index: 2;
                display: flex;
                align-items: center;
                gap: 4px;
            }

            .portfolio-body h3 {
                font-size: 15px;
            }

            .portfolio-body p {
                font-size: 12px;
            }

            .portfolio-filter-row {
                flex-direction: column;
                align-items: stretch;
                padding: 20px 25px 35px 25px;
            }

            .portfolio-pills {
                flex-wrap: wrap;
            }

            .search-box-project {
                width: 100%;
            }

            .portfolio-grid {
                width: 88%;
                /* grid-template-columns: repeat(2, 1fr); */
                grid-template-columns: repeat(auto-fit, minmax(310px, 1fr));
            }

            /* ==============================Click porject block code =================================== */
            .modal-card {
                width: 92%;
                top: 7%;
            }

            .modal-hero {
                height: 280px;
            }

            .hero-static-content {
                padding: 25px;
            }

            /* .hero-static-content h1{
                    font-size: 42px;
                } */
            .hero-static-content .tagline {
                font-size: 15px;
                letter-spacing: 1px;
            }

            .modal-close {
                width: 32px;
                height: 32px;
                font-size: 14px;
                top: 12px;
                right: 12px;
            }

            /* .modal-arrow{
                    width: 34px;
                    height: 34px;
                    font-size: 14px;
                }
                .arrow-left{ left: 12px; }
                .arrow-right{ right: 12px; } */

            .modal-badges {
                padding: 15px 20px 0;
                flex-wrap: wrap;
            }

            .modal-title {
                padding: 10px 20px 15px;
                font-size: 19px;
            }

            .modal-stats {
                padding: 12px 20px;
                gap: 14px;
                font-size: 13px;
            }

            /* body becomes single column: main content, then sidebar below */
            .modal-body {
                flex-direction: column;
                padding: 0 20px 25px;
                gap: 20px;
            }

            .modal-sidebar {
                width: 100%;
            }

            .action-buttons {
                flex-wrap: wrap;
                gap: 10px;
            }

            .btn {
                padding: 10px 16px;
                font-size: 13px;
            }

            /* Challenges/Solutions stack instead of side-by-side */
            .two-col {
                flex-direction: column;
                gap: 15px;
            }

            .process-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .process-card {
                padding: 14px;
            }

            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .gallery-grid img {
                height: 120px;
            }
        }

        /* ----- 480px: Mobile ----- */
        @media (max-width: 480px) {
            .project-container {
                width: 99%;
                /* background-color: pink; */
                margin: auto;
            }

            .project-showcase {
                height: auto;
                width: 100%;
                padding: 25px 15px;
            }

            .project-showcase h2 {
                font-size: 20px;
            }

            .project-showcase p {
                width: 100%;
                font-size: 13px;
            }

            .project-with-slide {
                width: 92%;
                margin-top: 25px;
                border-radius: 14px;
            }

            .project-with-slide img {
                height: 180px;
                border-radius: 14px;
            }

            .project-content {
                padding: 16px;
            }

            .project-content h3 {
                font-size: 16px;
                margin-top: 10px;
            }

            .project-content p {
                font-size: 13.5px;
            }

            .project-content #skills p {
                padding: 6px 10px;
                font-size: 12px;
            }

            .teacher-n-duration .teacher-duration-icon p {
                font-size: 13px;
            }

            .view-detail {
                flex-direction: column;
                gap: 10px;
            }

            .view-detail a {
                text-align: center;
                justify-content: center;
            }

            .portfolio-filter-row {
                padding: 15px 15px 25px 15px;
            }

            .portfolio-pill {
                padding: 8px 16px;
                font-size: 13px;
            }

            .search-box-project input {
                font-size: 15px;
            }

            .portfolio-grid {
                width: 84%;
                grid-template-columns: repeat(auto-fit, minmax(310px, 1fr));
                gap: 16px;
            }

            .portfolio-img img {
                height: auto;
                max-width: 100%;
                width: 100%;
                aspect-ratio: 4 / 2.6;
            }

            .portfolio-body h3 {
                font-size: 16px;
            }

            .portfolio-body p {
                font-size: 13px;
            }

            .portfolio-author img {
                width: 34px;
                height: 34px;
            }

            .card-select-block {
                padding: 20px 10px;
            }

            /* ==============================Click porject block code =================================== */
            .modal-card {
                width: 88%;
                top: 13%;
                border-radius: 14px;
            }

            .modal-hero {
                height: 220px;
            }

            .hero-static-content {
                padding: 18px;
            }

            /* .hero-static-content h1{
                    font-size: 30px;
                } */
            .hero-static-content .tagline {
                font-size: 12px;
                padding-left: 18px;
            }

            .modal-close {
                width: 28px;
                height: 28px;
                font-size: 12px;
            }

            /* .modal-arrow{
                    width: 30px;
                    height: 30px;
                    font-size: 12px;
                } */

            .modal-badges {
                padding: 12px 15px 0;
                gap: 8px;
            }

            .badge {
                font-size: 7px;
                padding: 4px 8px;
            }

            .modal-title {
                padding: 8px 15px 7px;
                font-size: 11px;
            }

            .modal-stats {
                padding: 10px 15px;
                gap: 17px;
                font-size: 9px;
            }

            .modal-body {
                padding: 0 15px 20px;
                gap: 15px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .section h4 {
                font-size: 14px;
            }

            .section p,
            .section li {
                font-size: 13px;
            }

            .tech-tags,
            .sidebar-tags {
                gap: 6px;
            }

            .tech-tag {
                font-size: 12px;
                padding: 5px 12px;
            }

            .modal-sidebar {
                padding: 18px;
            }

            .modal-sidebar img {
                width: 60px;
                height: 60px;
            }

            .process-grid {
                grid-template-columns: 1fr;
                /* single column */
                gap: 10px;
            }

            .gallery-grid {
                grid-template-columns: 1fr;
                /* single column */
                gap: 10px;
            }

            .gallery-grid img {
                height: 160px;
            }
        }
        /* ===== Pagination ===== */
        .ict-pagination {
            display: flex;
            justify-content: center;
            padding: 10px 0 20px;
        }

        .ict-pagination-list {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: wrap;
        }

        .ict-page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 8px;
            border-radius: 50%;
            border: 1px solid #d5d5d6;
            background: #fff;
            color: #333;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s ease;
        }

        .ict-page-item:not(.disabled):not(.active) .ict-page-link:hover {
            border-color: dodgerblue;
            color: dodgerblue;
        }

        .ict-page-item.active .ict-page-link {
            background: dodgerblue;
            border-color: dodgerblue;
            color: #fff;
        }

        .ict-page-item.disabled .ict-page-link {
            color: #bbb;
            cursor: not-allowed;
            background: #f5f6f8;
        }

        .ict-page-arrow {
            border-radius: 50%;
        }

        .ict-page-dots {
            border: none;
            background: transparent;
            width: auto;
            min-width: auto;
        }

        /* ===== Loading overlay (shown while a project's detail is fetching) ===== */
        .project-loading-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 20;
            background: rgba(0, 0, 0, 0.35);
            align-items: center;
            justify-content: center;
        }

        .project-loading-overlay.active {
            display: flex;
        }

        .project-loading-box {
            background: #fff;
            padding: 18px 28px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            font-size: 14px;
            color: #222;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .project-loading-spinner {
            width: 20px;
            height: 20px;
            border: 3px solid #dbe6ff;
            border-top-color: dodgerblue;
            border-radius: 50%;
            animation: project-spin 0.7s linear infinite;
            flex-shrink: 0;
        }

        @keyframes project-spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush
@section('content')
    <div class="project-container">
        <div class="project-showcase" data-aos="fade-up">
            <h2>Project Showcase</h2>
            <p>Explore real-world projects developed by our students and instructors across various ICT disciplines.
            </p>
        </div>
        @if ($spotlight)
            <div class="project-with-slide" data-aos="fade-up">
                <img src="{{ $spotlight->thumbnail_url ?? asset('asset/images/Course-Language/networkAdvance.webp') }}"
                    alt="{{ $spotlight->title }}">
                <div class="project-content">
                    <span>{{ $spotlight->category->name ?? 'Project' }}</span>
                    <h3>{{ $spotlight->title }}</h3>
                    <p id="detail-project-text">{{ $spotlight->excerpt ?? \Illuminate\Support\Str::limit($spotlight->overview, 140) }}</p>
                    <div id="skills">
                        @foreach ($spotlight->technologies->take(3) as $tech)
                            <p>{{ $tech->name }}</p>
                        @endforeach
                    </div>
                    <div class="teacher-n-duration">
                        @if ($spotlight->student)
                            <div class="teacher-duration-icon">
                                <i class="fa-regular fa-user"></i>
                                <p>{{ $spotlight->student->name }}</p>
                            </div>
                        @endif
                        @if ($spotlight->instructor)
                            <div class="teacher-duration-icon">
                                <i class="fa-solid fa-graduation-cap"></i>
                                <p>{{ $spotlight->instructor->name }}</p>
                            </div>
                        @endif
                        @if ($spotlight->build_duration)
                            <div class="teacher-duration-icon">
                                <i class="fa-regular fa-clock"></i>
                                <p>{{ $spotlight->build_duration }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="view-detail">
                        <a href="javascript:void(0)" data-open-project="{{ $spotlight->slug }}">View Details <i
                                class="fa-solid fa-arrow-right"></i></a>
                        @if ($spotlight->live_demo_url)
                            <a href="{{ $spotlight->live_demo_url }}" target="_blank" rel="noopener" id="live-demo">
                                <i class="fa-solid fa-up-right-from-square"></i> Live Demo</a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- ===== Filter Pills + Search ===== -->
        <div class="portfolio-filter-row">
            <div class="portfolio-pills">
                <a class="portfolio-pill {{ request('category') ? '' : 'active' }}"
                    href="{{ route('projects', array_filter(['search' => request('search')])) }}">All</a>
                @foreach ($categories as $category)
                    <a class="portfolio-pill {{ request('category') === $category->slug ? 'active' : '' }}"
                        href="{{ route('projects', array_filter(['search' => request('search'), 'category' => $category->slug])) }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
            <form class="search-box-project" method="GET" action="{{ route('projects') }}">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" placeholder="Search projects..." value="{{ request('search') }}">
            </form>
        </div>

        <!-- ===== Project Grid ===== -->
        <div class="portfolio-grid">
            @forelse ($projects as $index => $project)
                <div class="portfolio-card" data-aos="fade-up" data-aos-delay="{{ min($index * 60, 300) }}"
                    data-open-project="{{ $project->slug }}">
                    <div class="portfolio-img">
                        @if ($project->category)
                            <span class="tag-category">{{ $project->category->name }}</span>
                        @endif
                        @if ($project->is_featured)
                            <span class="tag-featured"><i class="fa-solid fa-star"></i>
                                {{ $project->featured_label ?: 'Featured' }}</span>
                        @endif
                        <img src="{{ $project->thumbnail_url ?? asset('asset/images/Course-Language/networkAdvance.webp') }}"
                            alt="{{ $project->title }}">
                    </div>
                    <div class="portfolio-body">
                        <h3>{{ $project->title }}</h3>
                        <p>{{ $project->excerpt ?? \Illuminate\Support\Str::limit($project->overview, 90) }}</p>
                        <div class="tool-tags">
                            @foreach ($project->technologies->take(3) as $tech)
                                @php
                                    $tagClass = match (strtolower($tech->name)) {
                                        'figma' => 'tag-figma',
                                        'illustrator' => 'tag-illustrator',
                                        'photoshop' => 'tag-photoshop',
                                        default => '',
                                    };
                                @endphp
                                <span class="tool-tag {{ $tagClass }}"
                                    @if (!$tagClass) style="background:#f1f1f1;color:#444;" @endif>{{ $tech->name }}</span>
                            @endforeach
                        </div>
                        <div class="portfolio-footer">
                            <div class="portfolio-author">
                                <img src="{{ $project->student->avatar_url ?? asset('default-images/user/both.jpg') }}"
                                    alt="{{ $project->student->name ?? 'Student' }}">
                                <div>
                                    <strong>{{ $project->student->name ?? 'ICT Student' }}</strong>
                                    <span>{{ $project->batch_label }}</span>
                                </div>
                            </div>
                            <div class="portfolio-stats">
                                <span><i class="fa-regular fa-eye"></i> {{ $project->short_views }}</span>
                                <span><i class="fa-regular fa-heart"></i> {{ $project->likes }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align:center; padding: 60px 0; color: #888;">
                    <i class="fa-regular fa-folder-open" style="font-size: 32px; display:block; margin-bottom:10px;"></i>
                    No projects found{{ request('search') ? ' for "' . request('search') . '"' : '' }}.
                </div>
            @endforelse
        </div>

        @if ($projects->hasPages())
            <div style="width: min(97%, 1200px); margin: 30px auto;">
                {{ $projects->links('vendor.pagination.custom') }}
            </div>
        @endif
        <div class="avertisement-slide">
            <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="4000">
                        <img src="/frontend/asset/images/slide-cut-v1.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item" data-bs-interval="4000">
                        <img src="/frontend/asset/images/slide-cut-v7.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="/frontend/asset/images/ICT_SlideShow.jpg" class="d-block w-100" alt="...">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
    <!-- ===== Loading overlay: shown the instant a project is clicked ===== -->
    <div id="projectLoadingOverlay" class="project-loading-overlay">
        <div class="project-loading-box">
            <span class="project-loading-spinner"></span>
            Loading project details...
        </div>
    </div>

    <div class="card-select-block" id="projectDetailModal">
        <div class="modal-card">
            <!-- ===== Hero Banner ===== -->
            <div class="modal-hero">
                <!-- Bootstrap Carousel — background images only, slides injected by JS -->
                <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel"
                    data-bs-interval="3000">
                    <div class="carousel-inner" id="modal-hero-carousel-inner"></div>
                </div>

                <!-- dark scrim so text stays readable over changing images -->
                <div class="hero-scrim"></div>

                <button class="modal-close" type="button"><i class="fa-solid fa-xmark"></i></button>

                <div class="hero-static-content">
                    <div class="tagline">Design • Development • Growth</div>
                    <div class="modal-badges" id="modal-badges"></div>
                    <div class="modal-title" id="modal-title"></div>
                </div>
            </div>

            <!-- ===== Stats Row ===== -->
            <div class="modal-stats">
                <span><i class="fa-regular fa-calendar"></i> <span id="modal-published-date"></span></span>
                <span><i class="fa-regular fa-clock"></i> <span id="modal-time-ago"></span></span>
                <span><i class="fa-regular fa-eye"></i> <span id="modal-views"></span> views</span>
                <span><i class="fa-regular fa-heart"></i> <span id="modal-likes"></span> likes</span>
            </div>

            <!-- ===== Body: Main + Sidebar ===== -->
            <div class="modal-body">

                <!-- Main content -->
                <div class="modal-main">

                    <!-- Action buttons -->
                    <div class="action-buttons" id="modal-action-buttons"></div>

                    <!-- Project Overview -->
                    <div class="section" id="modal-overview-section">
                        <h4>Project Overview</h4>
                        <p id="modal-overview"></p>
                    </div>

                    <!-- Problem Statement -->
                    <div class="section" id="modal-problem-section">
                        <h4>Problem Statement</h4>
                        <p id="modal-problem"></p>
                    </div>

                    <!-- Objectives -->
                    <div class="section" id="modal-objectives-section">
                        <h4>Objectives</h4>
                        <ul id="modal-objectives"></ul>
                    </div>

                    <!-- Challenges + Solutions side by side -->
                    <div class="two-col" id="modal-challenges-solutions-section">
                        <div class="section">
                            <h4>Challenges</h4>
                            <p id="modal-challenges"></p>
                        </div>
                        <div class="section">
                            <h4>Solutions</h4>
                            <p id="modal-solutions"></p>
                        </div>
                    </div>

                    <!-- Technologies Used -->
                    <div class="section" id="modal-technologies-section">
                        <h4>Technologies Used</h4>
                        <div class="tech-tags" id="modal-tech-tags"></div>
                    </div>

                    <!-- Development Process -->
                    <div class="section" id="modal-process-section">
                        <h4>Development Process</h4>
                        <div class="process-grid" id="modal-process-grid"></div>
                    </div>

                    <!-- Screenshots Gallery -->
                    <div class="section" id="modal-gallery-section">
                        <h4>Screenshots Gallery</h4>
                        <div class="gallery-grid" id="modal-gallery-grid"></div>
                    </div>

                </div>

                <!-- Sidebar: Author Card -->
                <div class="modal-sidebar" id="modal-sidebar" style="display:none;">
                    <img id="modal-author-avatar" src="" alt="">
                    <h3 id="modal-author-name"></h3>
                    <div class="role" id="modal-author-role"></div>
                    <div class="batch-info" id="modal-author-batch"></div>
                    <div class="certified-badge"><i class="fa-solid fa-graduation-cap"></i> Certified Graduate</div>
                    <div class="sidebar-tags" id="modal-author-tags"></div>
                </div>

            </div>
        </div>
    </div>

    <script>
        (function() {
            // Built via route() so this works whether the app is served at the
            // domain root or under a subfolder (e.g. /ict) — never hardcode
            // absolute paths like '/projects/...' in JS for this reason.
            const projectsIndexUrl = @json(route('projects'));
            const projectShowUrlTemplate = @json(route('projects.details', ['slug' => 'SLUG_PLACEHOLDER']));
            const buildShowUrl = (slug) => projectShowUrlTemplate.replace('SLUG_PLACEHOLDER', encodeURIComponent(slug));
            const defaultAvatarUrl = @json(asset('default-images/user/both.jpg'));

            const modalEl = document.getElementById('projectDetailModal');
            const heroInner = document.getElementById('modal-hero-carousel-inner');
            const badgesEl = document.getElementById('modal-badges');
            const titleEl = document.getElementById('modal-title');
            const publishedDateEl = document.getElementById('modal-published-date');
            const timeAgoEl = document.getElementById('modal-time-ago');
            const viewsEl = document.getElementById('modal-views');
            const likesEl = document.getElementById('modal-likes');
            const actionButtonsEl = document.getElementById('modal-action-buttons');
            const overviewSection = document.getElementById('modal-overview-section');
            const overviewEl = document.getElementById('modal-overview');
            const problemSection = document.getElementById('modal-problem-section');
            const problemEl = document.getElementById('modal-problem');
            const objectivesSection = document.getElementById('modal-objectives-section');
            const objectivesEl = document.getElementById('modal-objectives');
            const challengesSolutionsSection = document.getElementById('modal-challenges-solutions-section');
            const challengesEl = document.getElementById('modal-challenges');
            const solutionsEl = document.getElementById('modal-solutions');
            const technologiesSection = document.getElementById('modal-technologies-section');
            const techTagsEl = document.getElementById('modal-tech-tags');
            const processSection = document.getElementById('modal-process-section');
            const processGridEl = document.getElementById('modal-process-grid');
            const gallerySection = document.getElementById('modal-gallery-section');
            const galleryGridEl = document.getElementById('modal-gallery-grid');
            const sidebarEl = document.getElementById('modal-sidebar');
            const authorAvatarEl = document.getElementById('modal-author-avatar');
            const authorNameEl = document.getElementById('modal-author-name');
            const authorRoleEl = document.getElementById('modal-author-role');
            const authorBatchEl = document.getElementById('modal-author-batch');
            const authorTagsEl = document.getElementById('modal-author-tags');

            const techTagClass = (name) => {
                switch ((name || '').toLowerCase()) {
                    case 'figma':
                        return 'tag-figma';
                    case 'illustrator':
                        return 'tag-illustrator';
                    case 'photoshop':
                        return 'tag-photoshop';
                    default:
                        return 'tag-branding';
                }
            };

            function showSection(el, hasContent) {
                el.style.display = hasContent ? '' : 'none';
            }

            function openModal(data) {
                // Hero carousel slides
                const images = data.screenshots.length ? data.screenshots : (data.cover_image_url ? [data.cover_image_url] : []);
                heroInner.innerHTML = images.length
                    ? images.map((src, i) => `<div class="carousel-item ${i === 0 ? 'active' : ''}"><img src="${src}" alt="slide ${i + 1}"></div>`).join('')
                    : `<div class="carousel-item active"><img src="${data.thumbnail_url || ''}" alt="${data.title}"></div>`;

                // Badges + title
                badgesEl.innerHTML = `
                    ${data.category ? `<span class="badge badge-category">${data.category}</span>` : ''}
                    ${data.featured_label ? `<span class="badge badge-place"><i class="fa-solid fa-bolt"></i> ${data.featured_label}</span>` : ''}
                `;
                titleEl.textContent = data.title;

                // Stats
                publishedDateEl.textContent = data.published_date || '—';
                timeAgoEl.textContent = data.time_ago || '';
                viewsEl.textContent = data.views ?? 0;
                likesEl.textContent = data.likes ?? 0;

                // Action buttons
                let buttons = '';
                if (data.live_demo_url) {
                    buttons += `<a href="${data.live_demo_url}" target="_blank" rel="noopener" class="btn btn-primary"><i class="fa-solid fa-arrow-up-right-from-square"></i> Live Demo</a>`;
                }
                if (data.github_url) {
                    buttons += `<a href="${data.github_url}" target="_blank" rel="noopener" class="btn"><i class="fa-brands fa-github"></i> GitHub</a>`;
                }
                if (data.documentation_url) {
                    buttons += `<a href="${data.documentation_url}" target="_blank" rel="noopener" class="btn"><i class="fa-regular fa-file-lines"></i> Documentation</a>`;
                }
                actionButtonsEl.innerHTML = buttons;
                showSection(actionButtonsEl, buttons.length > 0);

                // Overview / Problem
                overviewEl.textContent = data.overview || '';
                showSection(overviewSection, !!data.overview);
                problemEl.textContent = data.problem_statement || '';
                showSection(problemSection, !!data.problem_statement);

                // Objectives
                objectivesEl.innerHTML = (data.objectives || []).map(o => `<li>${o}</li>`).join('');
                showSection(objectivesSection, (data.objectives || []).length > 0);

                // Challenges / Solutions
                challengesEl.textContent = data.challenges || '';
                solutionsEl.textContent = data.solutions || '';
                showSection(challengesSolutionsSection, !!(data.challenges || data.solutions));

                // Technologies
                techTagsEl.innerHTML = (data.technologies || [])
                    .map(t => `<span class="tech-tag ${techTagClass(t)}">${t}</span>`).join('');
                showSection(technologiesSection, (data.technologies || []).length > 0);

                // Process steps
                processGridEl.innerHTML = (data.process_steps || []).map(step => `
                    <div class="process-card">
                        <div class="process-number">${step.step_number}</div>
                        <h5>${step.title}</h5>
                        <p>${step.description || ''}</p>
                    </div>
                `).join('');
                showSection(processSection, (data.process_steps || []).length > 0);

                // Screenshots
                galleryGridEl.innerHTML = (data.screenshots || [])
                    .map((src, i) => `<img src="${src}" alt="Screenshot ${i + 1}">`).join('');
                showSection(gallerySection, (data.screenshots || []).length > 0);

                // Sidebar author card
                if (data.student) {
                    authorAvatarEl.src = data.student.avatar_url || defaultAvatarUrl;
                    authorAvatarEl.alt = data.student.name || '';
                    authorNameEl.textContent = data.student.name || '';
                    authorRoleEl.textContent = data.category || '';
                    authorBatchEl.textContent = [data.batch_label, data.instructor ? `Instructor: ${data.instructor}` : null]
                        .filter(Boolean).join(' · ');
                    authorTagsEl.innerHTML = (data.technologies || [])
                        .slice(0, 3).map(t => `<span>${t}</span>`).join('');
                    sidebarEl.style.display = '';
                } else {
                    sidebarEl.style.display = 'none';
                }

                modalEl.classList.add('active');
                document.body.style.overflow = 'hidden';

                if (window.history && window.history.pushState) {
                    window.history.pushState({}, '', buildShowUrl(data.slug));
                }
            }

            function closeModal() {
                modalEl.classList.remove('active');
                document.body.style.overflow = '';
                if (window.history && window.history.pushState) {
                    window.history.pushState({}, '', projectsIndexUrl);
                }
            }

            const loadingOverlay = document.getElementById('projectLoadingOverlay');

            function fetchAndOpen(slug) {
                loadingOverlay.classList.add('active');

                fetch(buildShowUrl(slug), {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.ok ? res.json() : Promise.reject(res.status))
                    .then(function(data) {
                        openModal(data);
                    })
                    .catch(function() {
                        // Fetch failed — nothing to show, just stop the spinner.
                    })
                    .finally(function() {
                        loadingOverlay.classList.remove('active');
                    });
            }

            // Open on any [data-open-project] click (grid cards, spotlight "View Details")
            document.addEventListener('click', function(e) {
                const trigger = e.target.closest('[data-open-project]');
                if (trigger) {
                    fetchAndOpen(trigger.dataset.openProject);
                }
            });

            // Close interactions
            document.querySelector('.modal-close')?.addEventListener('click', closeModal);
            modalEl.addEventListener('click', function(e) {
                if (e.target === modalEl) closeModal();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modalEl.classList.contains('active')) closeModal();
            });

            // Deep link: ?open=slug on initial page load
            const autoOpenSlug = @json(request('open'));
            if (autoOpenSlug) {
                fetchAndOpen(autoOpenSlug);
            }
        })();
    </script>
@endsection
