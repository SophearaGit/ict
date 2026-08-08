<!DOCTYPE html>
@php
/**
* Quote of the day for the login photo panel. Picked deterministically
* from the day-of-year so it's stable for everyone all day and rotates
* automatically at midnight — no cron job, no external API call on a
* page that needs to stay fast and dependency-free.
*/
$ictQuotes = [
'Every expert was once a beginner.',
'Great things are built one lesson at a time.',
'Discipline is choosing between what you want now and what you want most.',
'Small steps every day lead to big results.',
'The best way to predict the future is to create it.',
'Consistency beats intensity — show up daily.',
'Learning never exhausts the mind.',
'Progress, not perfection.',
'Focus on being productive instead of busy.',
'Success is the sum of small efforts repeated daily.',
];
$ictQuoteOfTheDay = $ictQuotes[now()->dayOfYear % count($ictQuotes)];
@endphp
<html lang="en">
  <head>

    <!-- Title -->
    <title>ICT | ADMIN | LOGIN</title>

    <!-- Required Meta Tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="Mordenize" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('/frontend/assets/ictImg/logo/ictLogo.jpg') }}" />

    <!-- Core admin theme (form-control, form-check, grid utilities, etc.) -->
    <link id="themeColors" rel="stylesheet" href="/admin/assets/dist/css/style.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" />
    <style>
      :root {
        --ict-brand: #5D87FF;
        --ict-brand-dark: #3f5fe0;
        --ict-ink: #1b2a4a;
      }
      .ict-auth,
      .ict-auth * {
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
      }
      /* Full-bleed background photo, split into layers so we can push its
           brightness up independently of the card content sitting on top. */
      .ict-auth {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        overflow: hidden;
      }
      .ict-auth::before {
        content: "";
        position: absolute;
        inset: 0;
        background-color: #dbe6ff;
        background-image: url('/admin/assets/dist/images/backgrounds/login-bg-2.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        filter: brightness(1.15) saturate(.82) blur(3px);
        z-index: 0;
      }
      .ict-auth::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(160deg, rgba(255, 255, 255, .68), rgba(93, 135, 255, .18));
        z-index: 1;
      }
      /* ---------- Card shell (photo side + form side) ---------- */
      .ict-card {
        position: relative;
        z-index: 2;
        display: flex;
        width: 100%;
        max-width: 940px;
        min-height: 560px;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(31, 54, 110, .22);
      }
      /* Big photo side — kept sharp, not glassy, since the photo itself
           is what needs to stand out. */
      .ict-photo-side {
        position: relative;
        flex: 1 1 45%;
      }
      .ict-photo-side img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
      }
      .ict-photo-caption {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        padding: 40px 32px 28px;
        background: linear-gradient(0deg, rgba(10, 16, 36, .92) 0%, rgba(10, 16, 36, .78) 55%, transparent 100%);
        color: #fff;
      }
      .ict-photo-caption h2 {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 6px;
        text-shadow: 0 2px 8px rgba(0, 0, 0, .4);
        color: white;
      }
      .ict-photo-caption p {
        font-size: 13.5px;
        font-style: italic;
        color: rgba(255, 255, 255, .88);
        margin: 0;
        text-shadow: 0 1px 6px rgba(0, 0, 0, .35);
      }
      /* Glass form side */
      .ict-form-side {
        flex: 1 1 55%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: rgba(255, 255, 255, .93);
        backdrop-filter: blur(22px) saturate(180%);
        -webkit-backdrop-filter: blur(22px) saturate(180%);
        padding: 52px 56px;
      }
      .ict-card h3 {
        text-align: left;
        font-size: 22px;
        font-weight: 700;
        color: var(--ict-ink);
        margin-bottom: 4px;
      }
      .ict-card .ict-sub {
        text-align: left;
        color: rgba(27, 42, 74, .6);
        font-size: 13.5px;
        margin-bottom: 30px;
      }
      .ict-input-box {
        position: relative;
        margin-bottom: 24px;
      }
      .ict-input-box input {
        width: 100%;
        border: none;
        border-bottom: 2px solid rgba(27, 42, 74, .22);
        background: transparent;
        padding: 10px 28px 10px 2px;
        font-size: 15px;
        color: var(--ict-ink);
        outline: none;
        transition: .3s;
      }
      .ict-input-box input:focus,
      .ict-input-box input:valid {
        border-bottom-color: var(--ict-brand);
      }
      .ict-input-box label {
        position: absolute;
        left: 2px;
        top: 10px;
        font-size: 15px;
        color: rgba(27, 42, 74, .5);
        pointer-events: none;
        transition: .3s;
      }
      .ict-input-box input:focus~label,
      .ict-input-box input:valid~label {
        top: -14px;
        font-size: 12px;
        color: var(--ict-brand);
      }
      .ict-input-box box-icon {
        position: absolute;
        right: 2px;
        top: 12px;
        color: rgba(27, 42, 74, .35);
        transition: .3s;
      }
      .ict-input-box input:focus~box-icon,
      .ict-input-box input:valid~box-icon {
        color: var(--ict-brand);
      }
      .ict-remember .form-check-input:checked {
        background-color: var(--ict-brand);
        border-color: var(--ict-brand);
      }
      .ict-remember .form-check-label {
        font-size: 13.5px;
        color: rgba(27, 42, 74, .65);
      }
      .ict-forgot {
        color: var(--ict-brand-dark);
        font-weight: 500;
        font-size: 13.5px;
        text-decoration: none;
      }
      .ict-forgot:hover {
        text-decoration: underline;
      }
      .ict-btn {
        display: block;
        width: 100%;
        padding: 13px;
        border-radius: 40px;
        border: none;
        background: linear-gradient(135deg, var(--ict-brand), var(--ict-brand-dark));
        color: #fff;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        box-shadow: 0 12px 24px rgba(63, 95, 224, .35);
        transition: transform .2s ease, box-shadow .2s ease;
      }
      .ict-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 16px 30px rgba(63, 95, 224, .42);
      }
      .ict-footnote {
        text-align: left;
        font-size: 12.5px;
        color: rgba(27, 42, 74, .45);
        margin-top: 26px;
      }
      @media (max-width: 820px) {
        .ict-card {
          flex-direction: column;
          max-width: 440px;
          min-height: 0;
        }
        .ict-photo-side {
          flex: 0 0 220px;
        }
        .ict-form-side {
          padding: 40px 32px;
        }
      }
      @media (max-width: 480px) {
        .ict-form-side {
          padding: 36px 24px;
        }
        .ict-photo-side {
          flex-basis: 180px;
        }
      }
      .ict-fade {
        opacity: 0;
        transform: translateY(14px);
        animation: ictFadeIn .6s ease forwards;
      }
      @keyframes ictFadeIn {
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
    </style>
  </head>
  <body>
    <div class="ict-auth">
      <div class="ict-card ict-fade" style="animation-delay:.05s">

        <!-- Photo side -->
        <div class="ict-photo-side">
          <img src="{{ asset('/admin/assets/dist/images/admin/nhanh_nhim.jpg') }}" alt="ICT Admin">
          <div class="ict-photo-caption">
            <h2>Welcome to ICT Administration</h2>
            <p>&ldquo;{{ $ictQuoteOfTheDay }}&rdquo;</p>
          </div>
        </div>

        <!-- Form side -->
        <div class="ict-form-side">
          <h3 class="ict-fade" style="animation-delay:.15s">Sign In</h3>
          <p class="ict-sub ict-fade" style="animation-delay:.2s">Please provide your credential below.</p>
          <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf
            <div class="ict-input-box ict-fade" style="animation-delay:.25s">
              <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
              <label for="email">Email</label>
              <box-icon name='envelope' type='solid'></box-icon>
              <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
            </div>
            <div class="ict-input-box ict-fade" style="animation-delay:.3s">
              <input type="password" id="password" name="password" required autocomplete="current-password">
              <label for="password">Password</label>
              <box-icon name='lock-alt' type='solid'></box-icon>
              <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
            </div>
            <div class="d-flex align-items-center justify-content-between mb-4 ict-fade ict-remember" style="animation-delay:.35s">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="flexCheckChecked" name="remember" checked>
                <label class="form-check-label" for="flexCheckChecked">
                  Remember this device
                </label>
              </div>
              <a class="ict-forgot" href="{{ route('admin.password.request') }}">Forgot password?</a>
            </div>
            <button type="submit" class="ict-btn ict-fade" style="animation-delay:.4s">
              Sign In
            </button>
          </form>
          <p class="ict-footnote ict-fade" style="animation-delay:.45s">
            Protected admin area — authorized personnel only.
          </p>
        </div>
      </div>
    </div>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
  </body>
</html>
