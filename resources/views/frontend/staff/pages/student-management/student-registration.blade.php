@extends('frontend.staff.layout.master')
@section('page_title', isset($page_title) ? $page_title : 'Student Registration')
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
  @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap');
  .reg-wrap {
    font-family: 'DM Sans', sans-serif;
  }
  /* ── Card shell ── */
  .reg-card {
    background: #ffffff;
    border: 1px solid #e8eaed;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, .04), 0 8px 32px rgba(0, 0, 0, .06);
  }
  /* ── Left accent strip ── */
  .reg-card-inner {
    display: flex;
    min-height: 100%;
  }
  .reg-sidebar {
    width: 5px;
    background: linear-gradient(180deg, #0ea5e9 0%, #6366f1 50%, #8b5cf6 100%);
    flex-shrink: 0;
    border-radius: 0;
  }
  .reg-body {
    flex: 1;
    padding: 2.25rem 2.5rem;
  }
  /* ── Page header ── */
  .reg-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #f1f3f5;
  }
  .reg-title {
    font-size: 1.35rem;
    font-weight: 600;
    color: #0f172a;
    letter-spacing: -.3px;
    margin: 0 0 .25rem;
  }
  .reg-subtitle {
    font-size: .825rem;
    color: #94a3b8;
    margin: 0;
  }
  .btn-invoices {
    font-family: 'DM Sans', sans-serif;
    font-size: .8rem;
    font-weight: 500;
    color: #64748b;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: .45rem 1.1rem;
    text-decoration: none;
    transition: all .18s ease;
    white-space: nowrap;
  }
  .btn-invoices:hover {
    background: #f1f5f9;
    color: #334155;
    border-color: #cbd5e1;
  }
  /* ── Section labels ── */
  .reg-section-label {
    font-size: .7rem;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: #94a3b8;
    margin: 0 0 1rem;
    display: flex;
    align-items: center;
    gap: .5rem;
  }
  .reg-section-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #f1f3f5;
  }
  .reg-divider {
    height: 1px;
    background: #f1f3f5;
    margin: 1.75rem 0;
  }
  /* ── Floating inputs ── */
  .reg-field {
    position: relative;
  }
  .reg-field input,
  .reg-field select {
    font-family: 'DM Sans', sans-serif;
    font-size: .875rem;
    width: 100%;
    padding: 1.4rem 1rem .5rem;
    background: #f8fafc;
    border: 1.5px solid #e8eaed;
    border-radius: 12px;
    color: #0f172a;
    outline: none;
    transition: border-color .18s, background .18s, box-shadow .18s;
    appearance: none;
    -webkit-appearance: none;
  }
  .reg-field input:focus,
  .reg-field select:focus {
    border-color: #0ea5e9;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(14, 165, 233, .08);
  }
  .reg-field input[readonly] {
    background: #f1f5f9;
    color: #64748b;
    cursor: default;
  }
  .reg-field input[readonly]:focus {
    border-color: #e2e8f0;
    box-shadow: none;
  }
  .reg-field label {
    position: absolute;
    top: 50%;
    left: 1rem;
    transform: translateY(-50%);
    font-size: .8rem;
    color: #94a3b8;
    pointer-events: none;
    transition: all .15s ease;
    font-weight: 400;
  }
  .reg-field input:focus~label,
  .reg-field input:not(:placeholder-shown)~label,
  .reg-field select:focus~label,
  .reg-field select:not([value=""])~label,
  .reg-field.has-value label {
    top: .55rem;
    transform: none;
    font-size: .68rem;
    font-weight: 500;
    color: #0ea5e9;
  }
  .reg-field .field-icon {
    position: absolute;
    right: .9rem;
    top: 50%;
    transform: translateY(-50%);
    color: #cbd5e1;
    font-size: .95rem;
    pointer-events: none;
  }
  .reg-field select~.field-icon {
    pointer-events: none;
  }
  /* readonly field label always floated */
  .reg-field input[readonly]~label {
    top: .55rem;
    transform: none;
    font-size: .68rem;
    font-weight: 500;
    color: #94a3b8;
  }
  /* ── Select2 override ── */
  .reg-field .select2-container {
    width: 100% !important;
  }
  /* These two controls (course + student search) are the most important
     click target on the whole form — staff said the closed box felt too
     small/thin to comfortably click given how much detail opens up below
     it, so it's sized well above the other text inputs on this page:
     taller, bigger type, a heavier border, so it reads as the primary
     action it is rather than blending in with Full Name / Phone, etc. */
  .reg-field .select2-container--default .select2-selection--single,
  .reg-field .select2-container--default .select2-selection--multiple {
    font-family: 'DM Sans', sans-serif;
    font-size: 1rem;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    min-height: 72px;
    padding: 1.6rem 1.2rem .65rem;
    outline: none;
  }
  .reg-field .select2-container--default.select2-container--focus .select2-selection--multiple,
  .reg-field .select2-container--default.select2-container--open .select2-selection--single {
    border-color: #0ea5e9;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(14, 165, 233, .1);
  }
  .reg-field .select2-container--default .select2-selection--multiple .select2-selection__rendered {
    padding: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
  }
  .reg-field .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background: #e0f2fe;
    border: none;
    border-radius: 8px;
    padding: 5px 11px;
    font-size: .85rem;
    color: #0369a1;
    font-weight: 500;
  }
  .reg-field .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #7dd3fc;
    margin-right: 6px;
    font-size: 1rem;
  }
  .reg-field .select2-container--default .select2-selection--single .select2-selection__rendered {
    padding: 0;
    font-size: 1rem;
    font-weight: 500;
    color: #0f172a;
    line-height: 1.4;
  }
  .reg-field .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 72px;
    right: 14px;
  }
  .reg-field .select2-container--default .select2-selection--single .select2-selection__arrow b,
  .reg-field .select2-container--default .select2-selection--multiple .select2-selection__arrow b {
    transform: scale(1.4);
  }
  .reg-field .select2-selection--multiple .select2-selection__choice .chip-price {
    font-family: 'DM Mono', monospace;
    opacity: .75;
    margin-left: .25rem;
  }
  /* ── Select2 dropdown panel (appended to <body>, so not scoped under .reg-field) ── */
  .select2-dropdown {
    font-family: 'DM Sans', sans-serif;
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 14px !important;
    overflow: hidden;
    box-shadow: 0 16px 40px rgba(15, 23, 42, .14);
  }
  .select2-search--dropdown {
    padding: 12px !important;
    background: #f8fafc;
  }
  .select2-search--dropdown .select2-search__field {
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 12px !important;
    padding: .8rem 1rem .8rem 2.5rem !important;
    font-size: 1rem;
    outline: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: .85rem center;
  }
  .select2-search--dropdown .select2-search__field:focus {
    border-color: #0ea5e9 !important;
  }
  /* Select2 reuses the same "select2-results__options" class for the
     outer scrollable panel AND for each group's own nested <ul> — scoping
     this to :not(--nested) is essential, otherwise every optgroup's list
     gets its own 360px max-height with no overflow clipping, so its
     content keeps rendering past that box and the next optgroup overlaps
     on top of it (visually, options stack/overlap rather than stacking
     in order). */
  .select2-results__options:not(.select2-results__options--nested) {
    padding: 6px;
    max-height: 360px;
  }
  .select2-results__group {
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: #0ea5e9;
    padding: 10px 8px 6px;
  }
  .select2-results__option[role="group"]:not(:first-child) .select2-results__group {
    border-top: 1px solid #f1f3f5;
    margin-top: 4px;
    padding-top: 12px;
  }
  .select2-results__option {
    padding: 0 !important;
    border-radius: 10px;
    margin-bottom: 2px;
  }
  .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background: #f0f9ff !important;
    color: inherit !important;
  }
  .select2-container--default .select2-results__option[aria-selected="true"] {
    background: #e0f2fe !important;
  }
  /* ── Course option card (rendered via Select2 templateResult) ── */
  .course-opt {
    padding: .6rem .75rem;
    border-radius: 10px;
  }
  .course-opt-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    gap: .5rem;
  }
  .course-opt-title {
    font-size: .85rem;
    font-weight: 600;
    color: #0f172a;
    line-height: 1.3;
  }
  .course-opt-price {
    font-family: 'DM Mono', monospace;
    font-size: .76rem;
    font-weight: 600;
    color: #0369a1;
    background: #e0f2fe;
    padding: .1rem .55rem;
    border-radius: 999px;
    white-space: nowrap;
    flex-shrink: 0;
  }
  .course-opt-meta {
    font-size: .72rem;
    color: #64748b;
    margin-top: .3rem;
    display: flex;
    align-items: center;
    gap: .3rem;
  }
  .course-opt-foot {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: .4rem;
  }
  .course-opt-date {
    font-size: .7rem;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: .3rem;
  }
  .course-opt-status {
    font-size: .62rem;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
    padding: .15rem .55rem;
    border-radius: 999px;
  }
  .course-opt-status.is-open {
    background: #dcfce7;
    color: #16a34a;
  }
  .course-opt-status.is-closed {
    background: #fee2e2;
    color: #dc2626;
  }
  .course-opt.course-opt-closed .course-opt-title {
    color: #7f1d1d;
  }
  .course-opt.course-opt-closed .course-opt-price {
    background: #fee2e2;
    color: #b91c1c;
  }
  /* ── Course picker modal ── */
  .course-modal-backdrop,
  .student-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, .55);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    padding: 24px;
  }
  .course-modal {
    background: #fff;
    border-radius: 22px;
    width: 100%;
    max-width: 880px;
    max-height: 85vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 30px 80px rgba(15, 23, 42, .35);
    overflow: hidden;
    font-family: 'DM Sans', sans-serif;
  }
  .course-modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.5rem 1.75rem 1.1rem;
    border-bottom: 1px solid #f1f3f5;
    flex-shrink: 0;
  }
  .course-modal-header h5 {
    margin: 0 0 .2rem;
    font-size: 1.1rem;
    font-weight: 700;
    color: #0f172a;
  }
  .course-modal-header p {
    margin: 0;
    font-size: .8rem;
    color: #94a3b8;
  }
  .course-modal-close {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    border: none;
    background: #f1f5f9;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background .15s, color .15s;
    flex-shrink: 0;
    font-size: 1rem;
  }
  .course-modal-close:hover {
    background: #e2e8f0;
    color: #0f172a;
  }
  .course-modal-search {
    display: flex;
    align-items: center;
    gap: .6rem;
    margin: 1.1rem 1.75rem 0;
    padding: .75rem 1rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    background: #f8fafc;
    flex-shrink: 0;
  }
  .course-modal-search:focus-within {
    border-color: #0ea5e9;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(14, 165, 233, .1);
  }
  .course-modal-search i {
    color: #94a3b8;
  }
  .course-modal-search input {
    border: none;
    background: transparent;
    outline: none;
    flex: 1;
    font-size: .9rem;
    font-family: 'DM Sans', sans-serif;
    color: #0f172a;
  }
  .course-modal-body {
    flex: 1;
    overflow-y: auto;
    padding: 1.1rem 1.75rem 1.5rem;
  }
  .cm-group-label {
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: #0ea5e9;
    margin: 1.4rem 0 .65rem;
  }
  .cm-group-label:first-child {
    margin-top: 0;
  }
  .cm-group-label.is-closed {
    color: #dc2626;
  }
  .cm-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: .65rem;
  }
  .cm-empty {
    text-align: center;
    padding: 2.5rem 1rem;
    color: #94a3b8;
    font-size: .85rem;
  }
  .cm-card {
    position: relative;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    padding: .85rem .9rem .85rem 2.6rem;
    cursor: pointer;
    transition: border-color .15s, background .15s, box-shadow .15s;
    background: #fff;
  }
  .cm-card:hover {
    border-color: #38bdf8;
  }
  .cm-card.is-selected {
    border-color: #0ea5e9;
    background: #f0f9ff;
    box-shadow: 0 0 0 3px rgba(14, 165, 233, .12);
  }
  .cm-card.is-closed.is-selected {
    border-color: #dc2626;
    background: #fef2f2;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, .1);
  }
  .cm-check {
    position: absolute;
    left: .85rem;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 20px;
    border-radius: 6px;
    border: 2px solid #cbd5e1;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    color: #fff;
    font-size: 12px;
    transition: all .15s;
  }
  .cm-card.is-selected .cm-check {
    background: #0ea5e9;
    border-color: #0ea5e9;
  }
  .cm-card.is-closed.is-selected .cm-check {
    background: #dc2626;
    border-color: #dc2626;
  }
  .cm-check i {
    opacity: 0;
    transform: scale(.6);
    transition: all .12s;
  }
  .cm-card.is-selected .cm-check i {
    opacity: 1;
    transform: scale(1);
  }
  .course-modal-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.1rem 1.75rem;
    border-top: 1px solid #f1f3f5;
    flex-shrink: 0;
    background: #f8fafc;
    flex-wrap: wrap;
  }
  .course-modal-summary {
    font-size: .85rem;
    font-weight: 600;
    color: #0f172a;
  }
  .course-modal-summary .cm-total {
    color: #0ea5e9;
    font-family: 'DM Mono', monospace;
  }
  .course-modal-actions {
    display: flex;
    gap: .6rem;
  }
  .btn-modal-ghost {
    font-family: 'DM Sans', sans-serif;
    font-size: .82rem;
    font-weight: 600;
    color: #64748b;
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: .55rem 1.1rem;
    cursor: pointer;
    transition: all .15s;
  }
  .btn-modal-ghost:hover {
    background: #f1f5f9;
    color: #0f172a;
  }
  .btn-modal-apply {
    font-family: 'DM Sans', sans-serif;
    font-size: .85rem;
    font-weight: 600;
    color: #fff;
    background: linear-gradient(135deg, #0ea5e9, #6366f1);
    border: none;
    border-radius: 10px;
    padding: .6rem 1.4rem;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(99, 102, 241, .25);
    transition: opacity .15s, transform .15s;
  }
  .btn-modal-apply:hover {
    opacity: .92;
    transform: translateY(-1px);
  }
  body.course-modal-open {
    overflow: hidden;
  }
  @media (max-width: 640px) {
    .course-modal-backdrop,
    .student-modal-backdrop {
      padding: 0;
    }
    .course-modal {
      max-width: 100%;
      max-height: 100dvh;
      height: 100dvh;
      border-radius: 0;
    }
    .cm-grid {
      grid-template-columns: 1fr;
    }
    .course-modal-footer {
      flex-direction: column;
      align-items: stretch;
    }
    .course-modal-actions {
      justify-content: stretch;
    }
    .btn-modal-ghost,
    .btn-modal-apply {
      flex: 1;
      text-align: center;
    }
  }
  /* ── Student option card (rendered via Select2 templateResult) ── */
  .student-opt {
    display: flex;
    align-items: center;
    gap: .65rem;
    padding: .55rem .7rem;
  }
  .student-opt-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    flex-shrink: 0;
    background: linear-gradient(135deg, rgba(14, 165, 233, .16), rgba(99, 102, 241, .16));
    color: #4f46e5;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .02em;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .student-opt-body {
    min-width: 0;
  }
  .student-opt-name {
    font-size: .84rem;
    font-weight: 600;
    color: #0f172a;
    line-height: 1.3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .student-opt-meta {
    font-size: .72rem;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: .3rem;
    margin-top: 1px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  /* ── Student picker modal ── */
  .sm-list {
    display: flex;
    flex-direction: column;
    gap: .5rem;
  }
  .sm-card {
    display: flex;
    align-items: center;
    gap: .5rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    cursor: pointer;
    transition: border-color .15s, background .15s, box-shadow .15s;
    background: #fff;
    padding: .3rem .8rem .3rem .3rem;
  }
  .sm-card .student-opt {
    flex: 1;
    min-width: 0;
    padding: .55rem .4rem;
  }
  .sm-card .student-opt-name,
  .sm-card .student-opt-meta {
    white-space: normal;
  }
  .sm-card:hover {
    border-color: #38bdf8;
    background: #f8fafc;
  }
  .sm-card.is-current {
    border-color: #0ea5e9;
    background: #f0f9ff;
    box-shadow: 0 0 0 3px rgba(14, 165, 233, .12);
  }
  .sm-card.is-current::after {
    content: "Current";
    flex-shrink: 0;
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: #0ea5e9;
    background: #e0f2fe;
    padding: .25rem .55rem;
    border-radius: 999px;
  }
  .sm-empty {
    text-align: center;
    padding: 2.5rem 1rem;
    color: #94a3b8;
    font-size: .85rem;
  }
  /* ── Eye toggle ── */
  .eye-toggle {
    position: absolute;
    right: .9rem;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #94a3b8;
    font-size: 1rem;
    z-index: 5;
    transition: color .15s;
  }
  .eye-toggle:hover {
    color: #64748b;
  }
  /* ── Payment option cards ── */
  .payment-badges {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(148px, 1fr));
    gap: .6rem;
    margin-bottom: 1rem;
  }
  .pay-badge {
    font-family: 'DM Sans', sans-serif;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: .15rem;
    padding: .7rem .9rem;
    border-radius: 14px;
    border: 1.5px solid #e2e8f0;
    background: #f8fafc;
    color: #64748b;
    cursor: pointer;
    transition: all .18s ease;
    user-select: none;
  }
  .pay-badge .pb-title {
    font-size: .8rem;
    font-weight: 600;
    color: inherit;
  }
  .pay-badge .pb-sub {
    font-size: .68rem;
    font-weight: 400;
    opacity: .7;
    color: inherit;
  }
  .pay-badge:hover {
    border-color: #0ea5e9;
    color: #0ea5e9;
    background: #f0f9ff;
  }
  .pay-badge.active {
    border-color: #0ea5e9;
    background: #0ea5e9;
    color: #fff;
  }
  .pay-badge.active .pb-sub {
    opacity: .85;
  }
  .pay-badge.active.free {
    background: #10b981;
    border-color: #10b981;
  }
  .pay-badge.active.full {
    background: #6366f1;
    border-color: #6366f1;
  }
  .pay-badge.active.half {
    background: #f59e0b;
    border-color: #f59e0b;
  }
  .pay-badge.active.multi {
    background: #8b5cf6;
    border-color: #8b5cf6;
  }
  .pay-badge.active.other {
    background: #0f172a;
    border-color: #0f172a;
  }
  .pay-badge.active.normal {
    background: #0ea5e9;
    border-color: #0ea5e9;
  }
  .pay-badge.disabled-badge {
    opacity: .38;
    pointer-events: none;
  }
  /* ── Two-column layout: form + sticky order summary ── */
  .reg-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 1.75rem;
    align-items: start;
  }
  .reg-main {
    min-width: 0;
  }
  .reg-aside {
    position: sticky;
    top: 88px;
  }
  /* ── Section cards (Student / Course & Payment) ── */
  .reg-section-card {
    background: #fff;
    border: 1.5px solid #eef0f2;
    border-radius: 18px;
    padding: 1.65rem 1.75rem;
    margin-bottom: 1.5rem;
  }
  .reg-section-card:last-child {
    margin-bottom: 0;
  }
  .reg-section-label-row {
    display: flex;
    align-items: center;
    gap: .65rem;
    margin-bottom: 1.35rem;
  }
  .reg-section-label-row .reg-section-label {
    margin: 0;
  }
  .reg-section-label-row .reg-section-label::after {
    display: none;
  }
  .reg-section-icon {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(14, 165, 233, .14), rgba(99, 102, 241, .14));
    color: #4f46e5;
    font-size: 1rem;
  }
  /* ── Order summary card ── */
  .order-card {
    background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
    border-radius: 20px;
    padding: 1.6rem 1.5rem;
    color: #e2e8f0;
    box-shadow: 0 10px 36px rgba(15, 23, 42, .2);
  }
  .order-card-title {
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .09em;
    text-transform: uppercase;
    color: #94a3b8;
    margin: 0 0 1.1rem;
    display: flex;
    align-items: center;
    gap: .4rem;
  }
  .order-courses {
    margin-bottom: .25rem;
  }
  .order-empty {
    font-size: .78rem;
    color: #64748b;
    text-align: center;
    padding: 1.4rem .5rem;
    border: 1.5px dashed #334155;
    border-radius: 14px;
  }
  .order-course-item {
    display: flex;
    justify-content: space-between;
    gap: .75rem;
    font-size: .8rem;
    padding: .55rem 0;
    border-bottom: 1px solid rgba(148, 163, 184, .14);
  }
  .order-course-item:last-child {
    border-bottom: none;
  }
  .order-course-name {
    color: #e2e8f0;
    font-weight: 500;
    line-height: 1.3;
  }
  .order-course-meta {
    color: #64748b;
    font-size: .68rem;
    margin-top: 1px;
  }
  .order-course-price {
    color: #38bdf8;
    font-family: 'DM Mono', monospace;
    font-weight: 500;
    white-space: nowrap;
  }
  .order-divider {
    height: 1px;
    background: rgba(148, 163, 184, .16);
    margin: 1rem 0;
  }
  .btn-submit-aside {
    width: 100%;
    margin-top: 1.4rem;
    justify-content: center;
    display: flex;
    align-items: center;
  }
  /* ── Summary rows (sit inside the dark order card) ── */
  .summary-grid {
    display: flex;
    flex-direction: column;
    gap: .55rem;
  }
  .summary-card {
    background: rgba(255, 255, 255, .04);
    border: 1px solid rgba(148, 163, 184, .16);
    border-radius: 12px;
    padding: .7rem .9rem;
    transition: border-color .2s, background .2s;
  }
  .summary-card.highlight {
    background: rgba(56, 189, 248, .1);
    border-color: rgba(56, 189, 248, .4);
  }
  .summary-card.highlight-green {
    background: rgba(34, 197, 94, .12);
    border-color: rgba(34, 197, 94, .4);
  }
  .summary-card.highlight-amber {
    background: rgba(245, 158, 11, .12);
    border-color: rgba(245, 158, 11, .4);
  }
  .summary-card-label {
    font-size: .66rem;
    font-weight: 600;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: #94a3b8;
    margin-bottom: .25rem;
  }
  .summary-card-value {
    font-family: 'DM Mono', monospace;
    font-size: 1.1rem;
    font-weight: 500;
    color: #f1f5f9;
    min-height: 1.5rem;
  }
  .summary-card-value.muted {
    color: #64748b;
  }
  /* ── Status badge display ── */
  .status-pill {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    font-size: .75rem;
    font-weight: 600;
    padding: .3rem .8rem;
    border-radius: 20px;
    margin-top: .4rem;
  }
  .status-pill.paid {
    background: #dcfce7;
    color: #16a34a;
  }
  .status-pill.half_paid {
    background: #fef9c3;
    color: #ca8a04;
  }
  .status-pill.unpaid {
    background: #fee2e2;
    color: #dc2626;
  }
  .status-pill.free {
    background: #d1fae5;
    color: #059669;
  }
  .status-pill.empty {
    background: #f1f5f9;
    color: #94a3b8;
  }
  /* ── Manual entry panel ── */
  .manual-panel {
    /* linear red */
    background: linear-gradient(135deg, #fee2e2, #fef9c3);
    border: 1.5px solid #fde68a;
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    margin-top: .75rem;
  }
  .manual-panel-title {
    font-size: .75rem;
    font-weight: 600;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: #92400e;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: .4rem;
  }
  /* ── Multi-course note ── */
  .multi-note {
    font-size: .75rem;
    font-weight: 500;
    color: #059669;
    background: #ecfdf5;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    padding: .35rem .75rem;
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    margin-top: .5rem;
  }
  /* ── Submit button ── */
  .btn-submit {
    font-family: 'DM Sans', sans-serif;
    font-size: .875rem;
    font-weight: 600;
    color: #fff;
    background: linear-gradient(135deg, #0ea5e9, #6366f1);
    border: none;
    border-radius: 12px;
    padding: .75rem 2rem;
    cursor: pointer;
    transition: opacity .18s, transform .15s, box-shadow .18s;
    letter-spacing: .01em;
    box-shadow: 0 4px 14px rgba(99, 102, 241, .25);
  }
  .btn-submit:hover {
    opacity: .92;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(99, 102, 241, .3);
  }
  .btn-submit:active {
    transform: translateY(0);
  }
  /* ── Student type toggle tabs ── */
  .type-tabs {
    display: flex;
    background: #f1f5f9;
    border-radius: 12px;
    padding: 3px;
    gap: 3px;
    width: fit-content;
    margin-bottom: 1.5rem;
  }
  .type-tab {
    font-family: 'DM Sans', sans-serif;
    font-size: .8rem;
    font-weight: 500;
    padding: .45rem 1.2rem;
    border-radius: 9px;
    border: none;
    background: transparent;
    color: #64748b;
    cursor: pointer;
    transition: all .18s ease;
  }
  .type-tab.active {
    background: #ffffff;
    color: #0f172a;
    box-shadow: 0 1px 4px rgba(0, 0, 0, .1);
  }
  /* ── Input error ── */
  .field-error {
    font-size: .72rem;
    color: #ef4444;
    margin-top: .3rem;
    padding-left: .25rem;
  }
  /* ── Responsive ── */
  @media (max-width: 992px) {
    .reg-grid {
      grid-template-columns: 1fr;
    }
    .reg-aside {
      position: static;
    }
  }
  @media (max-width: 768px) {
    .reg-body {
      padding: 1.25rem;
    }
    .reg-section-card {
      padding: 1.25rem;
      border-radius: 14px;
    }
    .order-card {
      border-radius: 16px;
      padding: 1.35rem 1.25rem;
    }
    .reg-header {
      flex-direction: column;
      gap: 1rem;
    }
    .payment-badges {
      grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    }
    .select2-results__options:not(.select2-results__options--nested) {
      max-height: 280px;
    }
    .course-opt-title {
      font-size: .8rem;
    }
    .course-opt-meta,
    .course-opt-date {
      font-size: .68rem;
    }
    .student-opt-name {
      font-size: .8rem;
    }
    .student-opt-meta {
      font-size: .68rem;
    }
  }
  @media (max-width: 576px) {
    .payment-badges {
      grid-template-columns: 1fr 1fr;
    }
    .course-opt-row {
      flex-direction: column;
      align-items: flex-start;
      gap: .25rem;
    }
  }
</style>
@endpush
@section('content')
@php
    // When the server-side validation fails, Laravel flashes the submitted
    // input to the session (except password fields) and redirects back
    // here. These two values drive which tab / payment option / wrapper
    // should already be showing as "selected" on reload, so staff don't
    // have to redo their picks — only re-type the parts that actually
    // failed validation.
    $oldStudentType = old('student_type', 'new');
    $oldPaymentOption = old('payment_option', '');
    $oldCourseIds = old('course_ids', []);
@endphp
@include('frontend.staff.pages.partials.breadcrumb')
<div class="reg-wrap">
  <div class="reg-card">
    <div class="reg-card-inner">
      <div class="reg-sidebar"></div>
      <div class="reg-body">
        {{-- Header --}}
        <div class="reg-header">
          <div>
            <h4 class="reg-title">Student Registration</h4>
            <p class="reg-subtitle">Register a new student and assign course payment.</p>
          </div>
          <a href="{{ route('staff.invoices') }}" class="btn-invoices">
            <i class="ti ti-file-invoice me-1"></i> Invoices
          </a>
        </div>
        <form action="{{ route('staff.student.registration.submit') }}" method="POST" id="regForm">
          @csrf
          <div class="reg-grid">
          <div class="reg-main">
          <div class="reg-section-card">
          {{-- ── STUDENT SELECTION ── --}}
          <div class="reg-section-label-row">
            <div class="reg-section-icon"><i class="ti ti-user-plus"></i></div>
            <p class="reg-section-label">Student</p>
          </div>
          <div class="type-tabs">
            <button type="button" class="type-tab {{ $oldStudentType === 'existing' ? '' : 'active' }}" data-type="new">New Student</button>
            <button type="button" class="type-tab {{ $oldStudentType === 'existing' ? 'active' : '' }}" data-type="existing">Existing Student</button>
          </div>
          <div class="d-none" id="prefillBanner" style="
    background: linear-gradient(135deg, #fef2f2, #fff1f2);
    border: 1.5px solid #fecaca;
    border-radius: 14px;
    padding: 14px 18px;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
">
            <div style="display:flex;align-items:center;gap:10px;">
              <div style="
            width: 34px; height: 34px; flex-shrink: 0;
            background: #dc2626; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        ">
                <i class="ti ti-user-check" style="color:#fff;font-size:1.1rem;"></i>
              </div>
              <div>
                <div style="font-size:.85rem;font-weight:600;color:#7f1d1d;">
                  Continuing registration for <span id="prefillStudentName">this student</span>
                </div>
                <div style="font-size:.75rem;color:#b91c1c;">
                  Their previous invoice was removed. Select a course and payment below to finish.
                </div>
              </div>
            </div>
            <button type="button" class="btn-invoices" id="btnPickOtherStudent" style="
        white-space:nowrap;
        background:#fff;
        color:#dc2626;
        border-color:#fecaca;
    ">
              <i class="ti ti-user-x me-1"></i> Not this student? Pick another
            </button>
          </div>
          <input type="hidden" name="student_type" id="studentTypeInput" value="{{ $oldStudentType }}">
          {{-- Existing student selector --}}
          <div class="{{ $oldStudentType === 'existing' ? '' : 'd-none' }}" id="existingStudentWrapper">
            <div class="reg-field mb-3">
              <select class="form-select" name="student_id" id="studentSelect">
                <option value="" disabled selected></option>
                @foreach ($students as $student)
                <option value="{{ $student->id }}" data-name="{{ $student->name }}"
                  data-email="{{ $student->email }}" data-phone="{{ $student->phone }}"
                  @selected(old('student_id') == $student->id)>
                  {{ $student->name }} — {{ $student->email }}
                </option>
                @endforeach
              </select>
              <label>Search existing student</label>
            </div>
          </div>
          {{-- ── STUDENT PICKER MODAL ──
               Same idea as the course picker: Select2's own dropdown is
               intercepted (select2:opening, below) and swapped for a
               roomier dialog. Picking a student here is a single choice,
               so a card click selects it and closes the dialog right
               away — no separate Apply step needed like the multi-select
               course picker. #studentSelect stays the real form field;
               the modal only ever reads its current <option>s and writes
               back with .val(id).trigger('change'). --}}
          <div class="student-modal-backdrop d-none" id="studentModalBackdrop">
            <div class="course-modal" role="dialog" aria-modal="true" aria-labelledby="studentModalTitle">
              <div class="course-modal-header">
                <div>
                  <h5 id="studentModalTitle">Select Student</h5>
                  <p>Search and pick the student to register</p>
                </div>
                <button type="button" class="course-modal-close" id="studentModalClose" aria-label="Close">
                  <i class="ti ti-x"></i>
                </button>
              </div>
              <div class="course-modal-search">
                <i class="ti ti-search"></i>
                <input type="text" id="studentModalSearch" placeholder="Search by name, email, or phone…" autocomplete="off">
              </div>
              <div class="course-modal-body" id="studentModalBody"></div>
              <div class="course-modal-footer">
                <div class="course-modal-summary" id="studentModalSummary">&nbsp;</div>
                <div class="course-modal-actions">
                  <button type="button" class="btn-modal-ghost" id="studentModalCancel">Close</button>
                </div>
              </div>
            </div>
          </div>
          {{-- New student fields --}}
          <div id="newStudentWrapper" class="{{ $oldStudentType === 'existing' ? 'd-none' : '' }}">
            <p class="reg-section-label">Basic Information</p>
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <div class="reg-field">
                  <input type="text" name="name" placeholder=" " value="{{ old('name') }}"
                    {{ $oldStudentType === 'existing' ? 'disabled' : '' }}>
                  <label>Full Name <span class="text-danger">*</span></label>
                </div>
                <x-input-error :messages="$errors->get('name')" class="field-error" />
              </div>
              <div class="col-md-6">
                <div class="reg-field">
                  <input type="email" name="email" placeholder=" " value="{{ old('email') }}"
                    pattern="[^\s@]+@[^\s@]+\.[^\s@]+" title="Enter a valid email address, e.g. name@example.com"
                    {{ $oldStudentType === 'existing' ? 'disabled' : '' }}>
                  <label>Email Address <span class="text-danger">*</span></label>
                </div>
                <x-input-error :messages="$errors->get('email')" class="field-error" />
              </div>
              <div class="col-md-6">
                <div class="reg-field">
                  <input type="text" name="khmer_name" placeholder=" " value="{{ old('khmer_name') }}"
                    {{ $oldStudentType === 'existing' ? 'disabled' : '' }}>
                  <label>Full Name (Khmer)</label>
                </div>
                <x-input-error :messages="$errors->get('khmer_name')" class="field-error" />
              </div>
              <div class="col-md-6">
                <div class="reg-field">
                  <input type="text" name="dob" id="dobInput" placeholder=" " autocomplete="off"
                    value="{{ old('dob') }}" {{ $oldStudentType === 'existing' ? 'disabled' : '' }}>
                  <label>Date of Birth</label>
                  <i class="ti ti-calendar field-icon"></i>
                </div>
                <x-input-error :messages="$errors->get('dob')" class="field-error" />
              </div>
              <div class="col-md-6">
                <div class="reg-field">
                  <input type="password" name="password" id="pass1" placeholder=" "
                    {{ $oldStudentType === 'existing' ? 'disabled' : '' }}>
                  <label>Password <span class="text-danger">*</span></label>
                  <span class="eye-toggle" data-target="pass1"><i class="ti ti-eye-off"></i></span>
                </div>
                <x-input-error :messages="$errors->get('password')" class="field-error" />
              </div>
              <div class="col-md-6">
                <div class="reg-field">
                  <input type="password" name="password_confirmation" id="pass2" placeholder=" "
                    {{ $oldStudentType === 'existing' ? 'disabled' : '' }}>
                  <label>Confirm Password <span class="text-danger">*</span></label>
                  <span class="eye-toggle" data-target="pass2"><i class="ti ti-eye-off"></i></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="reg-field">
                  <input type="text" name="phone" placeholder=" " value="{{ old('phone') }}"
                    pattern="[0-9+\-\s]{8,20}" title="8-20 digits, optionally with +, spaces, or dashes"
                    {{ $oldStudentType === 'existing' ? 'disabled' : '' }}>
                  <label>Phone Number <span class="text-danger">*</span></label>
                </div>
                <x-input-error :messages="$errors->get('phone')" class="field-error" />
              </div>
              <div class="col-md-6">
                <div class="reg-field">
                  <input type="text" name="alternate_phone" placeholder=" " value="{{ old('alternate_phone') }}"
                    pattern="[0-9+\-\s]{8,20}" title="8-20 digits, optionally with +, spaces, or dashes"
                    {{ $oldStudentType === 'existing' ? 'disabled' : '' }}>
                  <label>Alternate Phone</label>
                </div>
              </div>
            </div>
          </div>
          </div>{{-- /.reg-section-card (Student) --}}
          <div class="reg-section-card">
          {{-- ── COURSE & PAYMENT ── --}}
          <div class="reg-section-label-row">
            <div class="reg-section-icon"><i class="ti ti-books"></i></div>
            <p class="reg-section-label">Course & Payment</p>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-12">
              <div class="reg-field">
                {{-- Two or more rows can share the exact same title (a
                     course run again as a new batch once the old one
                     closes, or two batches running at once) — staff
                     flagged that the flat list made it impossible to
                     tell which one was the new/open batch vs an old/
                     closed one with nothing but the title and schedule
                     to go on. Splitting into "Open" vs "Closed" optgroups
                     (same OPEN/CLOSE language as the course management
                     list) plus a start date on every option fixes that:
                     the open batch a student should actually join is
                     always in the first group, and a closed one can
                     still be picked deliberately (e.g. backdating a
                     late registration) without being mistaken for the
                     current one. --}}
                <select id="courseSelect" name="course_ids[]" multiple placeholder=" ">
                  @php
                  // Builds both a plain search string (kept as the <option>'s
                  // text content, so Select2's default search still matches
                  // against title/schedule/date) and structured data-*
                  // attributes the custom card renderer below reads from —
                  // far less fragile than parsing the concatenated string
                  // back apart in JS.
                  $courseMeta = function ($course) {
                  $schedule = $course->schedule;
                  $scheduleText = '';
                  if ($schedule) {
                  $days = collect(explode('-', $schedule->study_day))
                  ->map(fn($d) => ucfirst($d))
                  ->implode(' • ');
                  $shift = ucfirst($schedule->shift);
                  $start = \Carbon\Carbon::parse($schedule->start_time)->format('g:i');
                  $end = \Carbon\Carbon::parse($schedule->end_time)->format('g:i A');
                  $scheduleText = "{$days} · {$shift} ({$start}–{$end})";
                  }
                  $isOpen = $course->status === 'active';
                  $dateLabel = $isOpen ? 'Starts' : 'Started';
                  $dateText = $course->start_date ? "{$dateLabel} " . $course->start_date->format('M j, Y') : '';
                  $searchText = "\${$course->price} · {$course->title}"
                  . ($scheduleText ? " · {$scheduleText}" : '')
                  . ($dateText ? " · {$dateText}" : '');
                  return [
                  'title' => $course->title,
                  'schedule' => $scheduleText,
                  'date' => $dateText,
                  'status' => $course->status,
                  'search' => $searchText,
                  ];
                  };
                  $openBatches = $courses->where('status', 'active');
                  $closedBatches = $courses->where('status', '!=', 'active');
                  @endphp
                  @if ($openBatches->count())
                  <optgroup label="Open Batches — accepting enrollment">
                    @foreach ($openBatches as $course)
                    @php($meta = $courseMeta($course))
                    <option value="{{ $course->id }}" data-price="{{ $course->price }}"
                      data-title="{{ $meta['title'] }}" data-schedule="{{ $meta['schedule'] }}"
                      data-date="{{ $meta['date'] }}" data-status="{{ $meta['status'] }}"
                      @selected(in_array($course->id, $oldCourseIds))>
                      {{ $meta['search'] }}
                    </option>
                    @endforeach
                  </optgroup>
                  @endif
                  @if ($closedBatches->count())
                  <optgroup label="Closed Batches — enroll only if intentional">
                    @foreach ($closedBatches as $course)
                    @php($meta = $courseMeta($course))
                    <option value="{{ $course->id }}" data-price="{{ $course->price }}"
                      data-title="{{ $meta['title'] }}" data-schedule="{{ $meta['schedule'] }}"
                      data-date="{{ $meta['date'] }}" data-status="{{ $meta['status'] }}"
                      @selected(in_array($course->id, $oldCourseIds))>
                      {{ $meta['search'] }}
                    </option>
                    @endforeach
                  </optgroup>
                  @endif
                </select>
                <label>Select Course(s) <span class="text-danger">*</span></label>
              </div>
              <x-input-error :messages="$errors->get('course_ids')" class="field-error" />
            </div>
          </div>
          {{-- ── COURSE PICKER MODAL ──
               Select2's own dropdown works, but on a list this long and
               this detailed it's a thin popover fighting for space with
               the rest of the form — staff asked for something roomier.
               This intercepts the dropdown open (select2:opening, below)
               and shows a full dialog instead. #courseSelect stays the
               real form field throughout; the modal only ever reads its
               current <option>s/value when it opens and writes back to
               it with .val([...]).trigger('change') on Apply, so every
               existing handler (order summary, multi-course discount,
               totals) keeps working untouched. --}}
          <div class="course-modal-backdrop d-none" id="courseModalBackdrop">
            <div class="course-modal" role="dialog" aria-modal="true" aria-labelledby="courseModalTitle">
              <div class="course-modal-header">
                <div>
                  <h5 id="courseModalTitle">Select Courses</h5>
                  <p>Choose one or more batches for this student</p>
                </div>
                <button type="button" class="course-modal-close" id="courseModalClose" aria-label="Close">
                  <i class="ti ti-x"></i>
                </button>
              </div>
              <div class="course-modal-search">
                <i class="ti ti-search"></i>
                <input type="text" id="courseModalSearch" placeholder="Search by course, schedule, or date…" autocomplete="off">
              </div>
              <div class="course-modal-body" id="courseModalBody"></div>
              <div class="course-modal-footer">
                <div class="course-modal-summary" id="courseModalSummary">No courses selected</div>
                <div class="course-modal-actions">
                  <button type="button" class="btn-modal-ghost" id="courseModalClear">Clear</button>
                  <button type="button" class="btn-modal-ghost" id="courseModalCancel">Cancel</button>
                  <button type="button" class="btn-modal-apply" id="courseModalApply">
                    <i class="ti ti-check me-1"></i> Apply Selection
                  </button>
                </div>
              </div>
            </div>
          </div>
          {{-- Payment option badges --}}
          <p class="reg-section-label" style="margin-top:1rem">Payment Option</p>
          <input type="hidden" name="payment_option" id="paymentOptionInput" value="{{ $oldPaymentOption }}">
          <div class="payment-badges" id="paymentBadges">
            <span class="pay-badge normal {{ $oldPaymentOption === 'normal' ? 'active' : '' }}" data-value="normal">
              <span class="pb-title">Pay Full</span><span class="pb-sub">No discount</span>
            </span>
            <span class="pay-badge full {{ $oldPaymentOption === 'full' ? 'active' : '' }}" data-value="full">
              <span class="pb-title">Full + Discount</span><span class="pb-sub">$10 off</span>
            </span>
            <span class="pay-badge half {{ $oldPaymentOption === 'half' ? 'active' : '' }}" data-value="half">
              <span class="pb-title">Half Now</span><span class="pb-sub">+$20 fee</span>
            </span>
            <span class="pay-badge multi {{ $oldPaymentOption === 'multi' ? 'active' : '' }}" data-value="multi">
              <span class="pb-title">Multi-Course</span><span class="pb-sub">$25 off</span>
            </span>
            <span class="pay-badge free {{ $oldPaymentOption === 'free' ? 'active' : '' }}" data-value="free">
              <span class="pb-title">Free</span><span class="pb-sub">No charge</span>
            </span>
            <span class="pay-badge other {{ $oldPaymentOption === 'other' ? 'active' : '' }}" data-value="other">
              <span class="pb-title">Other</span><span class="pb-sub">Manual entry</span>
            </span>
          </div>
          <div id="multiNoteWrapper" class="{{ count($oldCourseIds) >= 2 ? '' : 'd-none' }}">
            <span class="multi-note">
                                <i class="ti ti-sparkles"></i> $25 multi-course discount applied
                            </span>
          </div>
          {{-- ── MANUAL ENTRY PANEL ── --}}
          <div class="manual-panel {{ $oldPaymentOption === 'other' ? '' : 'd-none' }}" id="manualEntryWrapper">
            <p class="manual-panel-title">
              <i class="ti ti-edit"></i> Manual Entry
            </p>
            <div class="row g-3">
              <div class="col-md-4">
                <div class="reg-field">
                  <input type="number" step="0.01" min="0" id="manualDiscount" name="manual_discount" placeholder=" " value="{{ old('manual_discount', 0) }}">
                  <label>Discount ($)</label>
                </div>
                <x-input-error :messages="$errors->get('manual_discount')" class="field-error" />
              </div>
              {{-- <div class="col-md-4">
                                    <div class="reg-field">
                                        <input type="number" step="0.01" min="0" id="manualExtraCharge"
                                            name="manual_extra_charge" placeholder=" " value="0">
                                        <label>Extra Charge ($)</label>
                                    </div>
                                    <x-input-error :messages="$errors->get('manual_extra_charge')" class="field-error" />
                                </div> --}}
              <div class="col-md-4">
                <div class="reg-field">
                  <input type="number" step="0.01" min="0" id="manualTotalAmount" name="manual_total_amount" placeholder=" " value="{{ old('manual_total_amount') }}">
                  <label>Total Amount ($) <span class="text-danger">*</span></label>
                </div>
                <x-input-error :messages="$errors->get('manual_total_amount')" class="field-error" />
              </div>
              <div class="col-md-4">
                <div class="reg-field">
                  <input type="number" step="0.01" min="0" id="manualPaidAmount" name="manual_paid_amount" placeholder=" " value="{{ old('manual_paid_amount') }}">
                  <label>Paid Amount ($) <span class="text-danger">*</span></label>
                </div>
                <x-input-error :messages="$errors->get('manual_paid_amount')" class="field-error" />
              </div>
              {{-- <div class="col-md-6">
                                    <div class="reg-field">
                                        <input type="number" step="0.01" id="manualRemainingAmount" placeholder=" "
                                            readonly>
                                        <label>Remaining ($)</label>
                                    </div>
                                </div> --}}
            </div>
          </div>
          </div>{{-- /.reg-section-card (Course & Payment) --}}
          </div>{{-- /.reg-main --}}
          {{-- ── ORDER SUMMARY (sticky on desktop, follows the form on mobile) ── --}}
          <div class="reg-aside">
            <div class="order-card">
              <p class="order-card-title"><i class="ti ti-receipt-2"></i> Order Summary</p>
              <div class="order-courses" id="selectedCoursesPreview">
                <div class="order-empty">
                  <i class="ti ti-school" style="font-size:1.3rem;opacity:.5;display:block;margin-bottom:.4rem;"></i>
                  No courses selected yet
                </div>
              </div>
              <div class="order-divider"></div>
              <div class="summary-grid" id="summaryGrid">
                <div class="summary-card" id="cardTotal">
                  <div class="summary-card-label">Total Amount</div>
                  <div class="summary-card-value muted" id="displayTotal">—</div>
                </div>
                <div class="summary-card" id="cardPaid">
                  <div class="summary-card-label">Amount to Pay</div>
                  <div class="summary-card-value muted" id="displayPaid">—</div>
                </div>
                <div class="summary-card" id="cardRemaining">
                  <div class="summary-card-label">Remaining</div>
                  <div class="summary-card-value muted" id="displayRemaining">—</div>
                  <div id="statusPillDisplay"></div>
                </div>
              </div>
              {{-- Hidden inputs for form submission --}}
              <input type="hidden" name="total_amount" id="totalAmount">
              <input type="hidden" name="paid_amount" id="paidAmount">
              <input type="hidden" name="payment_status" id="paymentStatus">
              {{-- ── SUBMIT ── --}}
              <button type="submit" class="btn-submit btn-submit-aside">
                <i class="ti ti-send me-2"></i> Submit Registration
              </button>
            </div>
          </div>{{-- /.reg-aside --}}
          </div>{{-- /.reg-grid --}}
        </form>
      </div>{{-- .reg-body --}}
    </div>{{-- .reg-card-inner --}}
  </div>{{-- .reg-card --}}
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  $(document).ready(function() {
    // ==============================
    // STUDENT TYPE TABS
    // ==============================
    $('.type-tab').on('click', function() {
      const type = $(this).data('type');
      $('.type-tab').removeClass('active');
      $(this).addClass('active');
      $('#studentTypeInput').val(type);
      if (type === 'existing') {
        $('#existingStudentWrapper').removeClass('d-none');
        $('#newStudentWrapper').addClass('d-none');
        $('#newStudentWrapper input').prop('required', false).prop('disabled', true);
      } else {
        $('#existingStudentWrapper').addClass('d-none');
        $('#newStudentWrapper').removeClass('d-none');
        $('#newStudentWrapper input').prop('disabled', false);
      }
      resetPaymentUI();
    });
    // ==============================
    // EYE TOGGLE
    // ==============================
    $('.eye-toggle').on('click', function() {
      const targetId = $(this).data('target');
      const input = $('#' + targetId);
      const icon = $(this).find('i');
      if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        icon.removeClass('ti-eye-off').addClass('ti-eye');
      } else {
        input.attr('type', 'password');
        icon.removeClass('ti-eye').addClass('ti-eye-off');
      }
    });
    // ==============================
    // FLATPICKR — DATE OF BIRTH
    // ==============================
    $('#dobInput').flatpickr({
      dateFormat: 'Y-m-d',
      altInput: true,
      altFormat: 'M d, Y',
      maxDate: 'today',
      // A DOB is often decades back — bound the picker to a sane range and
      // jump it to a birth-year-ish decade by default (when nothing is
      // already selected), instead of leaving it on today's month. Without
      // this, opening the month dropdown for the current year makes any
      // month after today look "missing" (it's hidden because a birth
      // date can't be in the future), which reads as a bug when really
      // you just haven't navigated to the actual birth year yet.
      minDate: new Date().getFullYear() - 100 + '-01-01',
      allowInput: true,
      disableMobile: true,
      onReady: function(selectedDates, dateStr, instance) {
        instance.altInput.setAttribute('placeholder', ' ');
        if (!selectedDates.length) {
          instance.jumpToDate(new Date(new Date().getFullYear() - 20, 0, 1));
        }
      }
    });
    // ==============================
    // SELECT2 — rich course cards
    // ==============================
    // Open vs Closed batches are already split into two <optgroup>s
    // server-side (see the blade markup above). Each <option> also carries
    // data-title/data-schedule/data-date/data-status (see blade markup) so
    // the dropdown row and the selected chip can both be rendered as small
    // structured cards instead of one long run-on string — the option's
    // plain text content is left untouched so Select2's built-in search
    // still matches title, schedule, and date normally.
    function escapeHtml(str) {
      return $('<div>').text(str == null ? '' : str).html();
    }
    function formatCourseOption(option) {
      if (!option.id || !option.element) {
        return option.text;
      }
      var el = option.element;
      var price = parseFloat(el.getAttribute('data-price')) || 0;
      var title = el.getAttribute('data-title') || option.text;
      var schedule = el.getAttribute('data-schedule') || '';
      var date = el.getAttribute('data-date') || '';
      var isOpen = (el.getAttribute('data-status') || 'active') === 'active';
      var html = '<div class="course-opt' + (isOpen ? '' : ' course-opt-closed') + '">' +
        '<div class="course-opt-row">' +
        '<span class="course-opt-title">' + escapeHtml(title) + '</span>' +
        '<span class="course-opt-price">$' + price.toFixed(2) + '</span>' +
        '</div>' +
        (schedule ? '<div class="course-opt-meta"><i class="ti ti-clock"></i> ' + escapeHtml(schedule) + '</div>' : '') +
        '<div class="course-opt-foot">' +
        '<span class="course-opt-date">' +
        (date ? '<i class="ti ti-calendar-event"></i> ' + escapeHtml(date) : '') +
        '</span>' +
        '<span class="course-opt-status ' + (isOpen ? 'is-open' : 'is-closed') + '">' +
        (isOpen ? 'Open' : 'Closed') +
        '</span>' +
        '</div>' +
        '</div>';
      return $(html);
    }
    function formatCourseSelection(option) {
      if (!option.id || !option.element) {
        return option.text;
      }
      var el = option.element;
      var price = parseFloat(el.getAttribute('data-price')) || 0;
      var title = el.getAttribute('data-title') || option.text;
      return $('<span>' + escapeHtml(title) + ' <span class="chip-price">$' + price.toFixed(2) + '</span></span>');
    }
    $('#courseSelect').select2({
      width: '100%',
      placeholder: 'Select one or more courses',
      templateResult: formatCourseOption,
      templateSelection: formatCourseSelection
    });
    // ==============================
    // COURSE PICKER MODAL
    // Select2's inline dropdown is intercepted (select2:opening, below)
    // and swapped for a full-size dialog — same course data, same
    // Open/Closed grouping, just room to actually look at it. The modal
    // reads #courseSelect's <option>s directly (built once, reused on
    // every open) rather than duplicating the Blade course list, and only
    // ever writes back via .val([...]).trigger('change') on Apply, so
    // every other handler on this field keeps working unmodified.
    // ==============================
    var courseModalBuilt = false;
    var cmSelected = new Set();
    function buildCourseModalBody() {
      var html = '';
      $('#courseSelect optgroup').each(function() {
        var isClosed = /Closed/.test(this.label);
        html += '<div class="cm-group-label' + (isClosed ? ' is-closed' : '') + '">' + escapeHtml(this.label) + '</div>';
        html += '<div class="cm-grid">';
        $(this).find('option').each(function() {
          var id = this.value;
          var price = parseFloat(this.getAttribute('data-price')) || 0;
          var title = this.getAttribute('data-title') || $(this).text().trim();
          var schedule = this.getAttribute('data-schedule') || '';
          var date = this.getAttribute('data-date') || '';
          var open = (this.getAttribute('data-status') || 'active') === 'active';
          var searchIndex = (title + ' ' + schedule + ' ' + date).toLowerCase();
          html += '<div class="cm-card' + (open ? '' : ' is-closed') + '" data-id="' + escapeHtml(id) + '" ' +
            'data-search="' + escapeHtml(searchIndex) + '">' +
            '<span class="cm-check"><i class="ti ti-check"></i></span>' +
            '<div class="course-opt-row">' +
            '<span class="course-opt-title">' + escapeHtml(title) + '</span>' +
            '<span class="course-opt-price">$' + price.toFixed(2) + '</span>' +
            '</div>' +
            (schedule ? '<div class="course-opt-meta"><i class="ti ti-clock"></i> ' + escapeHtml(schedule) + '</div>' : '') +
            '<div class="course-opt-foot">' +
            '<span class="course-opt-date">' +
            (date ? '<i class="ti ti-calendar-event"></i> ' + escapeHtml(date) : '') +
            '</span>' +
            '<span class="course-opt-status ' + (open ? 'is-open' : 'is-closed') + '">' + (open ? 'Open' : 'Closed') + '</span>' +
            '</div>' +
            '</div>';
        });
        html += '</div>';
      });
      $('#courseModalBody').html(html || '<div class="cm-empty">No courses available.</div>');
      courseModalBuilt = true;
    }
    function updateModalSummary() {
      var count = cmSelected.size;
      var total = 0;
      cmSelected.forEach(function(id) {
        var price = parseFloat($('#courseSelect option[value="' + id + '"]').attr('data-price')) || 0;
        total += price;
      });
      $('#courseModalSummary').html(
        count === 0
          ? 'No courses selected'
          : count + ' course' + (count > 1 ? 's' : '') + ' selected · <span class="cm-total">$' + total.toFixed(2) + '</span>'
      );
    }
    function updateModalSelectionUI() {
      $('#courseModalBody .cm-card').each(function() {
        $(this).toggleClass('is-selected', cmSelected.has($(this).attr('data-id')));
      });
      updateModalSummary();
    }
    function openCourseModal() {
      if (!courseModalBuilt) {
        buildCourseModalBody();
      }
      cmSelected = new Set($('#courseSelect').val() || []);
      updateModalSelectionUI();
      $('#courseModalSearch').val('');
      $('#courseModalBody .cm-card, .cm-group-label').show();
      $('#courseModalBackdrop').removeClass('d-none');
      $('body').addClass('course-modal-open');
      setTimeout(function() {
        $('#courseModalSearch').trigger('focus');
      }, 50);
    }
    function closeCourseModal() {
      $('#courseModalBackdrop').addClass('d-none');
      $('body').removeClass('course-modal-open');
    }
    $('#courseSelect').on('select2:opening', function(e) {
      e.preventDefault();
      openCourseModal();
    });
    $('#courseModalBody').on('click', '.cm-card', function() {
      var id = $(this).attr('data-id');
      if (cmSelected.has(id)) {
        cmSelected.delete(id);
      } else {
        cmSelected.add(id);
      }
      updateModalSelectionUI();
    });
    $('#courseModalSearch').on('input', function() {
      var q = $(this).val().toLowerCase().trim();
      $('#courseModalBody .cm-group-label').each(function() {
        var $grid = $(this).next('.cm-grid');
        var anyVisible = false;
        $grid.find('.cm-card').each(function() {
          var match = !q || ($(this).attr('data-search') || '').indexOf(q) !== -1;
          $(this).toggle(match);
          if (match) {
            anyVisible = true;
          }
        });
        $(this).toggle(anyVisible);
      });
    });
    $('#courseModalClear').on('click', function() {
      cmSelected.clear();
      updateModalSelectionUI();
    });
    $('#courseModalApply').on('click', function() {
      $('#courseSelect').val(Array.from(cmSelected)).trigger('change');
      closeCourseModal();
    });
    $('#courseModalCancel, #courseModalClose').on('click', closeCourseModal);
    $('#courseModalBackdrop').on('click', function(e) {
      if (e.target === this) {
        closeCourseModal();
      }
    });
    $(document).on('keydown', function(e) {
      if (e.key !== 'Escape') {
        return;
      }
      if (!$('#courseModalBackdrop').hasClass('d-none')) {
        closeCourseModal();
      }
      if (!$('#studentModalBackdrop').hasClass('d-none')) {
        closeStudentModal();
      }
    });
    // Existing-student results get the same card treatment as courses —
    // an initials avatar, the name as the primary line, email/phone as
    // secondary lines — instead of one plain "Name — email" text row.
    function studentInitials(name) {
      if (!name) return '?';
      var parts = name.trim().split(/\s+/);
      var first = parts[0] ? parts[0][0] : '';
      var second = parts.length > 1 ? parts[parts.length - 1][0] : '';
      return (first + second).toUpperCase();
    }
    function formatStudentOption(option) {
      if (!option.id || !option.element) {
        return option.text;
      }
      var el = option.element;
      var name = el.getAttribute('data-name') || option.text;
      var email = el.getAttribute('data-email') || '';
      var phone = el.getAttribute('data-phone') || '';
      var html = '<div class="student-opt">' +
        '<div class="student-opt-avatar">' + escapeHtml(studentInitials(name)) + '</div>' +
        '<div class="student-opt-body">' +
        '<div class="student-opt-name">' + escapeHtml(name) + '</div>' +
        (email ? '<div class="student-opt-meta"><i class="ti ti-mail"></i> ' + escapeHtml(email) + '</div>' : '') +
        (phone ? '<div class="student-opt-meta"><i class="ti ti-phone"></i> ' + escapeHtml(phone) + '</div>' : '') +
        '</div>' +
        '</div>';
      return $(html);
    }
    $('#studentSelect').select2({
      width: '100%',
      placeholder: 'Search student by name or email',
      templateResult: formatStudentOption
    });
    // ==============================
    // STUDENT PICKER MODAL
    // Mirrors the course picker: Select2's inline dropdown is intercepted
    // (select2:opening — see the existing studentLocked-guarded handler
    // further below, which this extends) and swapped for a roomier dialog.
    // Unlike the course modal this field is single-select, so clicking a
    // card picks that student and closes the dialog immediately — no
    // separate Apply step. The modal reads #studentSelect's own <option>s
    // (built once, reused on every open) and only ever writes back via
    // .val(id).trigger('change'), so every other handler on this field
    // (including the prefill-lock logic below) keeps working unmodified.
    // ==============================
    var studentModalBuilt = false;
    function buildStudentModalBody() {
      var rows = '';
      $('#studentSelect option').each(function() {
        if (!this.value) return;
        var id = this.value;
        var name = this.getAttribute('data-name') || $(this).text().trim();
        var email = this.getAttribute('data-email') || '';
        var phone = this.getAttribute('data-phone') || '';
        var searchIndex = (name + ' ' + email + ' ' + phone).toLowerCase();
        rows += '<div class="sm-card" data-id="' + escapeHtml(id) + '" data-search="' + escapeHtml(searchIndex) + '">' +
          '<div class="student-opt">' +
          '<div class="student-opt-avatar">' + escapeHtml(studentInitials(name)) + '</div>' +
          '<div class="student-opt-body">' +
          '<div class="student-opt-name">' + escapeHtml(name) + '</div>' +
          (email ? '<div class="student-opt-meta"><i class="ti ti-mail"></i> ' + escapeHtml(email) + '</div>' : '') +
          (phone ? '<div class="student-opt-meta"><i class="ti ti-phone"></i> ' + escapeHtml(phone) + '</div>' : '') +
          '</div>' +
          '</div>' +
          '</div>';
      });
      var html = rows
        ? '<div class="sm-list" id="smList">' + rows + '</div><div class="sm-empty d-none" id="smEmpty">No students match your search.</div>'
        : '<div class="sm-empty">No students available.</div>';
      $('#studentModalBody').html(html);
      studentModalBuilt = true;
    }
    function updateStudentModalSummary() {
      var total = $('#studentModalBody .sm-card').length;
      var visible = $('#studentModalBody .sm-card:visible').length;
      $('#studentModalSummary').html(
        total === 0
          ? '&nbsp;'
          : visible + ' of ' + total + ' student' + (total > 1 ? 's' : '')
      );
      $('#smEmpty').toggleClass('d-none', total === 0 || visible > 0);
    }
    function updateStudentModalSelectionUI() {
      var currentId = $('#studentSelect').val();
      $('#studentModalBody .sm-card').each(function() {
        $(this).toggleClass('is-current', !!currentId && $(this).attr('data-id') === String(currentId));
      });
      updateStudentModalSummary();
    }
    function openStudentModal() {
      if (!studentModalBuilt) {
        buildStudentModalBody();
      }
      // Reveal the backdrop BEFORE computing :visible counts below — jQuery's
      // :visible check walks up through ancestors, so while the backdrop still
      // carries d-none every card reports as not visible, even once shown.
      $('#studentModalBackdrop').removeClass('d-none');
      $('body').addClass('course-modal-open');
      $('#studentModalSearch').val('');
      $('#studentModalBody .sm-card').show();
      updateStudentModalSelectionUI();
      setTimeout(function() {
        $('#studentModalSearch').trigger('focus');
      }, 50);
    }
    function closeStudentModal() {
      $('#studentModalBackdrop').addClass('d-none');
      $('body').removeClass('course-modal-open');
    }
    $('#studentModalBody').on('click', '.sm-card', function() {
      var id = $(this).attr('data-id');
      $('#studentSelect').val(id).trigger('change');
      closeStudentModal();
    });
    $('#studentModalSearch').on('input', function() {
      var q = $(this).val().toLowerCase().trim();
      $('#studentModalBody .sm-card').each(function() {
        var match = !q || ($(this).attr('data-search') || '').indexOf(q) !== -1;
        $(this).toggle(match);
      });
      updateStudentModalSummary();
    });
    $('#studentModalCancel, #studentModalClose').on('click', closeStudentModal);
    $('#studentModalBackdrop').on('click', function(e) {
      if (e.target === this) {
        closeStudentModal();
      }
    });
    // ==============================
    // ORDER SUMMARY — SELECTED COURSE PREVIEW
    // Mirrors whatever is picked in #courseSelect into the sticky Order
    // Summary card, so staff can see exactly what's being billed without
    // re-opening the dropdown. Purely a read-only preview — it never
    // reads from or writes to the hidden total/paid/status inputs.
    // ==============================
    function renderSelectedCourses() {
      const $selected = $('#courseSelect').find(':selected');
      const $container = $('#selectedCoursesPreview');
      if (!$selected.length) {
        $container.html(
          '<div class="order-empty">' +
          '<i class="ti ti-school" style="font-size:1.3rem;opacity:.5;display:block;margin-bottom:.4rem;"></i>' +
          'No courses selected yet</div>'
        );
        return;
      }
      let html = '';
      $selected.each(function() {
        const price = parseFloat($(this).data('price')) || 0;
        const title = $(this).attr('data-title') || $(this).text().trim();
        const schedule = $(this).attr('data-schedule') || '';
        const date = $(this).attr('data-date') || '';
        const meta = [schedule, date].filter(Boolean).join(' · ');
        html += '<div class="order-course-item">' +
          '<div><div class="order-course-name">' + escapeHtml(title) + '</div>' +
          (meta ? '<div class="order-course-meta">' + escapeHtml(meta) + '</div>' : '') +
          '</div>' +
          '<div class="order-course-price">$' + price.toFixed(2) + '</div>' +
          '</div>';
      });
      $container.html(html);
    }
    renderSelectedCourses();
    // ==============================
    // RESTORE STATE AFTER A VALIDATION ERROR
    // The blade markup above already re-renders the previously chosen tab,
    // student, course(s) and payment option from old() so nothing looks
    // reset — this just re-runs the same calculations the click handlers
    // normally do, so the summary cards / hidden totals aren't left blank.
    // ==============================
    (function restoreStateAfterValidationError() {
      const oldPaymentOption = @json($oldPaymentOption);
      const oldCourseCount = $('#courseSelect').find(':selected').length;
      if (oldCourseCount >= 2) {
        handleMultiCourseUI(oldCourseCount);
      } else if (oldPaymentOption === 'other') {
        recalcManualEntry();
      } else if (oldPaymentOption) {
        calculateAmounts();
      }
    })();
    // ==============================
    // PRE-FILL FROM DELETED INVOICE (?student_id=...)
    // Must run AFTER select2 init and AFTER .type-tab handler is bound
    // ==============================
    // ==============================
    // PRE-FILL FROM DELETED INVOICE (?student_id=...)
    // ==============================
    const urlParams = new URLSearchParams(window.location.search);
    const preselectedStudentId = urlParams.get('student_id');
    let studentLocked = false;
    if (preselectedStudentId) {
      $('.type-tab[data-type="existing"]').trigger('click');
      $('#studentSelect').val(preselectedStudentId).trigger('change');
      studentLocked = true;
      $('.type-tab[data-type="new"]')
        .prop('disabled', true)
        .css({
          opacity: 0.4,
          cursor: 'not-allowed',
          pointerEvents: 'none'
        });
      const selectedText = $('#studentSelect option:selected').text().trim();
      if (selectedText) {
        $('#prefillStudentName').text(selectedText);
      }
      $('#prefillBanner').removeClass('d-none');
    }
    // Block the dropdown from opening while locked — value still submits normally.
    // Otherwise, swap Select2's inline dropdown for the big student-picker modal.
    $('#studentSelect').on('select2:opening', function(e) {
      e.preventDefault();
      if (!studentLocked) {
        openStudentModal();
      }
    });
    $('#btnPickOtherStudent').on('click', function() {
      studentLocked = false;
      $('.type-tab[data-type="new"]')
        .prop('disabled', false)
        .css({
          opacity: 1,
          cursor: 'pointer',
          pointerEvents: 'auto'
        });
      $('#studentSelect').val(null).trigger('change');
      $('#prefillBanner').addClass('d-none');
      window.history.replaceState({}, document.title, window.location.pathname);
    });
    // ==============================
    // PAYMENT BADGES
    // ==============================
    $('.pay-badge').on('click', function() {
      const val = $(this).data('value');
      if ($(this).hasClass('disabled-badge')) return;
      selectPaymentOption(val);
    });
    function selectPaymentOption(val) {
      $('.pay-badge').removeClass('active');
      $(`.pay-badge[data-value="${val}"]`).addClass('active');
      $('#paymentOptionInput').val(val);
      if (val === 'other') {
        $('#manualEntryWrapper').removeClass('d-none');
        clearSummary();
        applyManualDiscount();
      } else {
        $('#manualEntryWrapper').addClass('d-none');
        calculateAmounts();
      }
    }
    // ==============================
    // COURSE CHANGE
    // ==============================
    $('#courseSelect').on('change', function() {
      const count = $(this).find(':selected').length;
      renderSelectedCourses();
      handleMultiCourseUI(count);
      const currentOption = $('#paymentOptionInput').val();
      if (currentOption && currentOption !== 'other') {
        calculateAmounts();
      } else if (currentOption === 'other') {
        // Keep Total Amount matching (course price − discount) if the
        // course selection changes while Manual Entry is already open.
        applyManualDiscount();
      }
    });
    function handleMultiCourseUI(count) {
      if (count >= 2) {
        // lock to multi
        $('.pay-badge').not('[data-value="multi"]').addClass('disabled-badge');
        selectPaymentOption('multi');
        $('#multiNoteWrapper').removeClass('d-none');
      } else {
        $('.pay-badge').removeClass('disabled-badge');
        $('#multiNoteWrapper').addClass('d-none');
        const current = $('#paymentOptionInput').val();
        if (current === 'multi') {
          $('.pay-badge').removeClass('active');
          $('#paymentOptionInput').val('');
          clearSummary();
        }
      }
    }
    // ==============================
    // MANUAL ENTRY LIVE CALC
    // ==============================
    function recalcManualEntry() {
      const total = parseFloat($('#manualTotalAmount').val()) || 0;
      const paid = parseFloat($('#manualPaidAmount').val()) || 0;
      const remaining = Math.max(0, total - paid);
      $('#manualRemainingAmount').val(remaining.toFixed(2));
      updateSummary(total, paid, remaining, 'other');
    }
    // Discount auto-fills Total Amount from the selected course(s) price,
    // so staff enter the discount instead of doing the subtraction by
    // hand. Total Amount stays a normal, still-editable input afterwards
    // in case the real total needs a manual override.
    function applyManualDiscount() {
      const coursePrice = getTotalPrice($('#courseSelect').find(':selected'));
      const discount = parseFloat($('#manualDiscount').val()) || 0;
      const total = Math.max(0, coursePrice - discount);
      $('#manualTotalAmount').val(total.toFixed(2));
      recalcManualEntry();
    }
    $('#manualDiscount').on('input', applyManualDiscount);
    $('#manualTotalAmount, #manualPaidAmount').on('input', recalcManualEntry);
    // ==============================
    // MAIN CALCULATION
    // ==============================
    function calculateAmounts() {
      const selectedCourses = $('#courseSelect').find(':selected');
      const courseCount = selectedCourses.length;
      const totalPrice = getTotalPrice(selectedCourses);
      const paymentOption = $('#paymentOptionInput').val();
      if (!paymentOption || paymentOption === 'other') return;
      let discount = 0;
      let extraCharge = 0;
      if (paymentOption === 'free') {
        discount = totalPrice;
      }
      if (paymentOption === 'multi' && courseCount >= 2) {
        discount += 25;
      }
      if (paymentOption === 'full') {
        discount += 10;
      }
      if (paymentOption === 'half') {
        extraCharge += 20;
      }
      let total = Math.max(0, totalPrice - discount + extraCharge);
      if (paymentOption === 'free') total = 0;
      let paid = total;
      if (paymentOption === 'half') paid = total / 2;
      if (paymentOption === 'free') paid = 0;
      if (paid > total) paid = total;
      const remaining = total - paid;
      updateSummary(total, paid, remaining, paymentOption);
    }
    function getTotalPrice(selected) {
      let t = 0;
      selected.each(function() {
        t += parseFloat($(this).data('price')) || 0;
      });
      return t;
    }
    // ==============================
    // UPDATE SUMMARY CARDS
    // ==============================
    function updateSummary(total, paid, remaining, option) {
      // Hidden form values
      $('#totalAmount').val(total.toFixed(2));
      $('#paidAmount').val(paid.toFixed(2));
      // Display values
      $('#displayTotal').text('$' + total.toFixed(2)).removeClass('muted');
      $('#displayPaid').text('$' + paid.toFixed(2)).removeClass('muted');
      $('#displayRemaining').text('$' + remaining.toFixed(2)).removeClass('muted');
      // Card highlight
      $('#cardTotal').addClass('highlight');
      $('#cardPaid').addClass('highlight');
      if (remaining === 0 && paid > 0) {
        $('#cardRemaining').addClass('highlight-green');
        $('#cardRemaining').removeClass('highlight-amber');
      } else if (remaining > 0) {
        $('#cardRemaining').addClass('highlight-amber');
        $('#cardRemaining').removeClass('highlight-green');
      } else {
        $('#cardRemaining').removeClass('highlight-green highlight-amber');
      }
      // Status
      let status = '';
      if (option === 'free') status = 'free';
      else if (paid === 0) status = 'unpaid';
      else if (remaining === 0) status = 'paid';
      else status = 'half_paid';
      $('#paymentStatus').val(status);
      const labels = {
        paid: 'Paid',
        half_paid: 'Half Paid',
        unpaid: 'Unpaid',
        free: 'Free'
      };
      const icons = {
        paid: 'ti-circle-check',
        half_paid: 'ti-clock',
        unpaid: 'ti-alert-circle',
        free: 'ti-gift'
      };
      $('#statusPillDisplay').html(
        `<span class="status-pill ${status}">
                <i class="ti ${icons[status] || ''}"></i> ${labels[status] || ''}
            </span>`
      );
    }
    function clearSummary() {
      $('#totalAmount, #paidAmount, #paymentStatus').val('');
      $('#displayTotal, #displayPaid, #displayRemaining').text('—').addClass('muted');
      $('#cardTotal, #cardPaid, #cardRemaining').removeClass('highlight highlight-green highlight-amber');
      $('#statusPillDisplay').html('');
    }
    function resetPaymentUI() {
      $('.pay-badge').removeClass('active disabled-badge');
      $('#paymentOptionInput').val('');
      $('#manualEntryWrapper').addClass('d-none');
      $('#manualDiscount, #manualExtraCharge, #manualTotalAmount, #manualPaidAmount, #manualRemainingAmount')
        .val('');
      $('#multiNoteWrapper').addClass('d-none');
      clearSummary();
      // Switching between New/Existing Student starts a fresh registration —
      // clear the previously chosen course(s) too, not just the payment
      // fields. #courseSelect is one shared field for both tabs, so without
      // this the "Select Course(s)" chips silently carried over from
      // whichever tab you were on before, even though the payment summary
      // above it had just been reset to blank — an inconsistent state that
      // invited submitting courses nobody consciously picked for this
      // student. Cleared last (after paymentOptionInput above is already
      // ''), so the change handler's own recalculation below is a no-op.
      $('#courseSelect').val(null).trigger('change');
    }
  });
</script>
@endpush
