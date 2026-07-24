@extends('layouts.app')

@section('content')
    <section class="about-hero" aria-labelledby="about-page-title">
        <div class="about-hero__content">
            <span class="about-eyebrow"><i class="bi bi-buildings me-2"></i>About the organization</span>
            <h1 id="about-page-title">Company Name</h1>
            <p>We bring sales operations and customer relationships together through one connected, reliable, and easy-to-use management system.</p>
            <div class="about-hero__badges" aria-label="System qualities">
                <span><i class="bi bi-diagram-3"></i>Connected workflows</span>
                <span><i class="bi bi-shield-check"></i>Reliable records</span>
                <span><i class="bi bi-people"></i>Customer focused</span>
            </div>
        </div>
        <div class="about-hero__logo" aria-hidden="true">
            <img src="{{ asset('cl-logo.svg') }}" alt="" width="128" height="128">
        </div>
    </section>

    <section class="row g-4 mt-1" aria-label="Company direction">
        <div class="col-12 col-lg-6">
            <article class="about-card h-100">
                <span class="about-card__icon is-primary"><i class="bi bi-bullseye"></i></span>
                <div>
                    <span class="about-card__eyebrow">Our Mission</span>
                    <h2>Make every customer interaction count</h2>
                    <p>To support accurate, responsive, and customer-centered sales operations by connecting teams, transactions, service activities, and business insights in one dependable platform.</p>
                </div>
            </article>
        </div>
        <div class="col-12 col-lg-6">
            <article class="about-card h-100">
                <span class="about-card__icon is-teal"><i class="bi bi-eye"></i></span>
                <div>
                    <span class="about-card__eyebrow">Our Vision</span>
                    <h2>Build lasting customer relationships</h2>
                    <p>To create a connected organization where reliable information helps every team serve customers better, make confident decisions, and support sustainable growth.</p>
                </div>
            </article>
        </div>
    </section>

    <section class="about-section" aria-labelledby="system-purpose-title">
        <div class="about-section__heading">
            <span class="about-eyebrow is-dark">Sales and Customer Management System</span>
            <h2 id="system-purpose-title">What the System Connects</h2>
            <p>The platform supports the complete customer journey, from the first quotation through fulfillment, relationship management, after-sales service, and performance planning.</p>
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-6 col-xl-3">
                <article class="about-capability h-100">
                    <span><i class="bi bi-cart-check"></i></span>
                    <h3>Sales Operations</h3>
                    <p>Quotations, orders, pricing, fulfillment, invoices, inventory, and finance integration.</p>
                </article>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <article class="about-capability h-100">
                    <span><i class="bi bi-people"></i></span>
                    <h3>Customer Relationships</h3>
                    <p>Profiles, purchase history, communications, loyalty, segmentation, campaigns, and retention.</p>
                </article>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <article class="about-capability h-100">
                    <span><i class="bi bi-headset"></i></span>
                    <h3>After-Sales Support</h3>
                    <p>Support cases, service requests, warranty claims, contracts, resolutions, and satisfaction.</p>
                </article>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <article class="about-capability h-100">
                    <span><i class="bi bi-graph-up-arrow"></i></span>
                    <h3>Business Insights</h3>
                    <p>Sales reports, target tracking, forecasting, recommendations, and planning actions.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="about-system-card" aria-labelledby="system-information-title">
        <div class="about-system-card__identity">
            <img src="{{ asset('cl-logo.svg') }}" alt="Company Name logo" width="54" height="54">
            <div>
                <span class="about-card__eyebrow">System Information</span>
                <h2 id="system-information-title">Sales and Customer Management System</h2>
            </div>
        </div>
        <dl class="about-system-card__details">
            <div>
                <dt>Organization</dt>
                <dd>Company Name</dd>
            </div>
            <div>
                <dt>Release</dt>
                <dd>Version 1.0</dd>
            </div>
            <div>
                <dt>Purpose</dt>
                <dd>Connected sales and customer operations</dd>
            </div>
        </dl>
    </section>

    <style>
        .about-hero {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 36px;
            min-height: 310px;
            padding: clamp(30px, 5vw, 58px);
            overflow: hidden;
            border-radius: 24px;
            background:
                radial-gradient(circle at 85% 20%, rgba(255, 255, 255, .2), transparent 28%),
                linear-gradient(135deg, #5347CE 0%, #6658dc 48%, #16C8C7 130%);
            color: #fff;
            box-shadow: 0 18px 46px rgba(67, 56, 202, .2);
        }

        .about-hero::after {
            position: absolute;
            right: -90px;
            bottom: -130px;
            width: 310px;
            height: 310px;
            border: 44px solid rgba(255, 255, 255, .08);
            border-radius: 50%;
            content: "";
        }

        .about-hero__content {
            position: relative;
            z-index: 1;
            max-width: 720px;
        }

        .about-eyebrow,
        .about-card__eyebrow {
            display: inline-block;
            margin-bottom: 10px;
            color: inherit;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .about-eyebrow.is-dark,
        .about-card__eyebrow {
            color: #5347CE;
        }

        .about-hero h1 {
            margin-bottom: 14px;
            font-size: clamp(2rem, 5vw, 3.6rem);
            font-weight: 800;
            letter-spacing: -.04em;
        }

        .about-hero p {
            max-width: 670px;
            margin: 0;
            color: rgba(255, 255, 255, .88);
            font-size: clamp(.95rem, 2vw, 1.1rem);
            line-height: 1.8;
        }

        .about-hero__badges {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 24px;
        }

        .about-hero__badges span {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 12px;
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 999px;
            background: rgba(255, 255, 255, .11);
            font-size: 11px;
            font-weight: 600;
            backdrop-filter: blur(8px);
        }

        .about-hero__logo {
            position: relative;
            z-index: 1;
            display: grid;
            width: 176px;
            height: 176px;
            flex: 0 0 176px;
            place-items: center;
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 38px;
            background: rgba(255, 255, 255, .13);
            box-shadow: inset 0 1px rgba(255, 255, 255, .2), 0 24px 55px rgba(30, 27, 75, .22);
            backdrop-filter: blur(14px);
        }

        .about-hero__logo img {
            border-radius: 28px;
            filter: drop-shadow(0 14px 20px rgba(30, 27, 75, .2));
        }

        .about-card,
        .about-capability,
        .about-system-card {
            border: 1px solid #e7e9f3;
            background: #fff;
            box-shadow: 0 10px 28px rgba(15, 23, 42, .06);
        }

        .about-card {
            display: flex;
            gap: 18px;
            padding: clamp(22px, 4vw, 32px);
            border-radius: 20px;
        }

        .about-card__icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            flex: 0 0 50px;
            border-radius: 15px;
            font-size: 22px;
        }

        .about-card__icon.is-primary {
            background: rgba(83, 71, 206, .11);
            color: #5347CE;
        }

        .about-card__icon.is-teal {
            background: rgba(22, 200, 199, .12);
            color: #0f9f9e;
        }

        .about-card h2,
        .about-section h2,
        .about-system-card h2 {
            color: #1f2937;
            font-weight: 700;
        }

        .about-card h2 {
            margin-bottom: 10px;
            font-size: 1.15rem;
        }

        .about-card p,
        .about-section__heading p,
        .about-capability p {
            margin-bottom: 0;
            color: #64748b;
            font-size: 13px;
            line-height: 1.75;
        }

        .about-section {
            margin-top: clamp(38px, 6vw, 64px);
        }

        .about-section__heading {
            max-width: 760px;
            margin-bottom: 24px;
        }

        .about-section h2 {
            margin-bottom: 10px;
            font-size: clamp(1.5rem, 3vw, 2rem);
        }

        .about-capability {
            padding: 24px;
            border-radius: 18px;
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }

        .about-capability:hover {
            border-color: rgba(83, 71, 206, .25);
            box-shadow: 0 16px 36px rgba(83, 71, 206, .1);
            transform: translateY(-4px);
        }

        .about-capability > span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            margin-bottom: 18px;
            border-radius: 13px;
            background: linear-gradient(135deg, rgba(83, 71, 206, .12), rgba(22, 200, 199, .12));
            color: #5347CE;
            font-size: 19px;
        }

        .about-capability h3 {
            margin-bottom: 8px;
            color: #1f2937;
            font-size: .95rem;
            font-weight: 700;
        }

        .about-system-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 28px;
            margin-top: clamp(38px, 6vw, 64px);
            padding: clamp(22px, 4vw, 32px);
            border-radius: 20px;
        }

        .about-system-card__identity {
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 0;
        }

        .about-system-card__identity img {
            flex: 0 0 54px;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(83, 71, 206, .18);
        }

        .about-system-card h2 {
            margin: 0;
            font-size: clamp(1rem, 2.5vw, 1.3rem);
        }

        .about-system-card__details {
            display: grid;
            grid-template-columns: repeat(3, minmax(120px, 1fr));
            gap: 12px;
            margin: 0;
        }

        .about-system-card__details div {
            padding: 12px 15px;
            border-radius: 13px;
            background: #f8fafc;
        }

        .about-system-card__details dt {
            margin-bottom: 4px;
            color: #94a3b8;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .about-system-card__details dd {
            margin: 0;
            color: #334155;
            font-size: 11px;
            font-weight: 600;
        }

        @media (max-width: 991.98px) {
            .about-hero__logo {
                width: 132px;
                height: 132px;
                flex-basis: 132px;
                border-radius: 30px;
            }

            .about-hero__logo img {
                width: 92px;
                height: 92px;
                border-radius: 22px;
            }

            .about-system-card {
                align-items: stretch;
                flex-direction: column;
            }
        }

        @media (max-width: 767.98px) {
            .about-hero {
                align-items: flex-start;
                flex-direction: column-reverse;
                min-height: auto;
                border-radius: 19px;
            }

            .about-hero__logo {
                width: 82px;
                height: 82px;
                flex-basis: 82px;
                border-radius: 22px;
            }

            .about-hero__logo img {
                width: 58px;
                height: 58px;
                border-radius: 15px;
            }

            .about-card {
                align-items: flex-start;
                flex-direction: column;
            }

            .about-system-card__details {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575.98px) {
            .about-hero {
                padding: 26px 22px;
            }

            .about-hero__badges {
                align-items: flex-start;
                flex-direction: column;
            }

            .about-hero__badges span {
                width: 100%;
            }
        }
    </style>
@endsection
