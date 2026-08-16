  <style>
      /* ===== Hero Image ===== */
      .blog-detail-hero {
          position: relative;
          width: 100%;
          height: 320px;
          overflow: hidden;
      }

      .blog-detail-hero img {
          width: 100%;
          height: 100%;
          object-fit: cover;
      }

      .back-to-blog {
          position: absolute;
          top: 20px;
          left: 20px;
          display: flex;
          align-items: center;
          gap: 8px;
          background: rgba(0, 0, 0, 0.5);
          color: #fff;
          font-size: 14px;
          font-weight: 600;
          padding: 10px 18px;
          border-radius: 30px;
          text-decoration: none;
          backdrop-filter: blur(4px);
      }

      .back-to-blog:hover {
          background: rgba(0, 0, 0, 0.7);
      }

      /* ===== Overlapping Content Card ===== */
      .Blog-detail-card {
          background: #fff;
          max-width: 990px;
          margin: -100px auto 0;
          padding: 40px 50px;
          border-radius: 20px;
          box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
          position: relative;
          text-align: center;
          animation: fadeSlideUp 0.7s ease forwards;
          opacity: 0;
      }

      @keyframes fadeSlideUp {
          from {
              opacity: 0;
              transform: translateY(30px);
          }

          to {
              opacity: 1;
              transform: translateY(0);
          }
      }

      .blog-detail-category {
          display: inline-block;
          background: #e6efff;
          color: blue;
          font-size: 14px;
          font-weight: 600;
          padding: 6px 18px;
          border-radius: 20px;
          margin-bottom: 13px;
      }

      .Blog-detail-card h1 {
          font-size: 33px;
          font-weight: 700;
          line-height: 1.3;
          color: #111;
          margin-bottom: 25px;
      }

      /* ===== Author / Date / Read time row ===== */
      .detail-meta {
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 20px;
          flex-wrap: wrap;
          opacity: 0;
          animation: fadeSlideUp 0.6s ease forwards;
          animation-delay: 0.25s;
      }

      .meta-author {
          display: flex;
          align-items: center;
          gap: 10px;
          text-align: left;
      }

      .meta-author div {
          line-height: 0.6;
      }

      .meta-author img {
          width: 60px;
          height: 60px;
          border-radius: 50%;
          border: 1px solid rgb(230, 228, 228);
          padding: 1px;
          object-fit: cover;
      }

      .detail-meta .meta-date {
          line-height: 0.6;
      }

      .meta-author p,
      .meta-date p {
          display: block;
          font-size: 15px;
          font-weight: 600;
          color: #111;
      }

      .meta-author span,
      .meta-date span {
          font-size: 13px;
          color: grey;
      }

      .meta-divider {
          width: 1px;
          height: 45px;
          background: #c2c3c4;
      }

      .meta-read {
          font-size: 14px;
          color: #333;
          display: flex;
          align-items: center;
          gap: 6px;
      }

      .blog-description-detail {
          margin: auto;
          width: 65%;
          padding: 35px;
      }

      .blog-description-detail p {
          line-height: 1.4;
      }

      .blog-description-detail div {
          margin-top: 40px;
      }

      .blog-description-detail div p {
          line-height: 0.9;
      }

      /* ===== Tags + Share Row ===== */
      .tags-share-row {
          width: 62%;
          margin: auto;
          display: flex;
          justify-content: space-between;
          align-items: center;
          flex-wrap: wrap;
          gap: 15px;
          padding: 25px 0;
          border-top: 1px solid #d5d5d6;
          border-bottom: 1px solid #d5d5d6;
          /* margin: 30px 0; */
      }

      .tags-group {
          display: flex;
          align-items: center;
          gap: 10px;
          flex-wrap: wrap;
      }

      .share-group {
          display: flex;
          align-items: center;
          gap: 10px;
          flex-wrap: wrap;
      }

      .tags-label,
      .share-label {
          font-weight: 700;
          font-size: 15px;
          margin-right: 5px;
      }

      /* reuse existing .pills style from filter section, just smaller here */
      .tags-group .pills {
          padding: 6px 16px;
          font-size: 15px;
          border-radius: 30px;
          font-weight: 600;
          border: none;
          cursor: default;
      }

      .share-icon {
          width: 34px;
          height: 34px;
          border-radius: 50%;
          background: #f3f4f6;
          color: #333;
          display: flex;
          align-items: center;
          justify-content: center;
          text-decoration: none;
          font-size: 14px;
          transition: background 0.2s ease;
      }

      .share-icon:hover {
          background: #e5e7eb;
      }

      /* ===== Author Bio Card ===== */
      .author-card {
          display: flex;
          align-items: flex-start;
          gap: 30px;
          border: 1px solid #d7d7d9;
          border-radius: 20px;
          padding: 20px 35px;
          max-width: 800px;
          margin: 50px auto 20px;
      }

      .author-card img {
          width: 130px;
          height: 150px;
          border-radius: 20px;
          object-fit: cover;
          border: 1px solid rgb(233, 232, 232);
          flex-shrink: 0;
      }

      .author-info h3 {
          font-size: 18px;
          margin-top: 10px;
      }

      .author-info h3 span {
          font-weight: 700;
      }

      .author-info p {
          font-size: 14.5px;
          line-height: 1.4;
          margin-bottom: 12px;
      }

      .author-link {
          color: blue;
          font-size: 14px;
          font-weight: 600;
          text-decoration: none;
      }

      .author-link:hover {
          text-decoration: underline;
      }

      /* ============================================= */
      /* ===== Responsive: Blog Detail Page ===== */
      /* ============================================= */

      /* ----- 1024px: Tablet / small laptop ----- */
      @media (max-width: 1024px) {
          .blog-detail-hero {
              height: 280px;
          }

          .Blog-detail-card {
              max-width: 90%;
              padding: 35px 40px;
              margin-top: -80px;
          }

          .Blog-detail-card h1 {
              font-size: 28px;
          }

          .blog-description-detail {
              width: 80%;
              padding: 25px;
          }

          .tags-share-row {
              width: 80%;
          }

          .author-card {
              max-width: 90%;
          }
      }

      /* ----- 768px: Tablet portrait ----- */
      @media (max-width: 768px) {
          .blog-detail-hero {
              height: 220px;
          }

          .back-to-blog {
              top: 14px;
              left: 14px;
              font-size: 13px;
              padding: 8px 14px;
          }

          .Blog-detail-card {
              max-width: 92%;
              padding: 30px 25px;
              margin-top: -60px;
              border-radius: 16px;
          }

          .Blog-detail-card h1 {
              font-size: 22px;
          }

          .blog-detail-category {
              font-size: 13px;
              padding: 5px 14px;
          }

          /* stack author / date / read-time instead of one row */
          .detail-meta {
              flex-direction: column;
              gap: 12px;
          }

          .meta-divider {
              width: 60px;
              height: 1px;
              /* turn vertical dividers horizontal when stacked */
          }

          .blog-description-detail {
              width: 90%;
              padding: 20px 15px;
          }

          .tags-share-row {
              width: 90%;
              flex-direction: column;
              align-items: flex-start;
              gap: 15px;
          }

          .tags-group,
          .share-group {
              width: 100%;
          }

          .author-card {
              max-width: 92%;
              flex-direction: column;
              align-items: center;
              text-align: center;
              padding: 25px;
          }

          .author-card img {
              width: 100px;
              height: 100px;
          }
      }

      /* ----- 480px: Mobile ----- */
      @media (max-width: 480px) {
          .blog-detail-hero {
              height: 180px;
          }

          .back-to-blog {
              font-size: 12px;
              padding: 7px 12px;
              gap: 6px;
          }

          .Blog-detail-card {
              padding: 22px 18px;
              margin-top: -40px;
              border-radius: 14px;
          }

          .Blog-detail-card h1 {
              font-size: 18px;
              margin-bottom: 15px;
          }

          .blog-detail-category {
              font-size: 12px;
              padding: 4px 12px;
          }

          .meta-author img {
              width: 45px;
              height: 45px;
          }

          .meta-author p,
          .meta-date p {
              font-size: 13px;
          }

          .meta-author span,
          .meta-date span {
              font-size: 11px;
          }

          .meta-read {
              font-size: 12px;
          }

          .share-group {
              margin-top: 10px;
              /* margin: auto;
        display: flex;
        justify-content: center;
        align-items: center; */
          }

          .blog-description-detail {
              width: 100%;
              padding: 15px 10px;
          }

          .blog-description-detail p {
              font-size: 14px;
          }

          .tags-share-row {
              width: 93%;
              padding: 18px 0;
          }

          .tags-group .pills {
              padding: 5px 12px;
              font-size: 13px;
          }

          .share-icon {
              width: 30px;
              height: 30px;
              font-size: 12px;
          }

          .author-card {
              max-width: 100%;
              padding: 20px 15px;
              margin: 30px auto 15px;
          }

          .author-card img {
              width: 80px;
              height: 90px;
          }

          .author-info h3 {
              font-size: 16px;
          }

          .author-info p {
              font-size: 13px;
          }
      }
  </style>
