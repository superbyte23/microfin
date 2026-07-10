# Microfinance Landing Page Design Concept

## Style

Modern • Trustworthy • Financial • Enterprise SaaS

The overall design should communicate **trust, financial stability,
accessibility, and professionalism**, similar to modern fintech
companies rather than traditional banks.

## Design Philosophy

-   Clean whitespace
-   Soft rounded corners (16--24px)
-   Light glassmorphism only where necessary
-   Professional illustrations instead of stock photos
-   Large typography
-   Minimal but elegant animations
-   Lime accent used only for CTAs and highlights
-   Neutral Mist/Zinc palette for everything else

## Color Palette

  Purpose             Color
  ------------------- -------------
  Background          Zinc-50
  Section Alternate   Zinc-100
  Card                White
  Border              Zinc-200
  Primary Text        Zinc-900
  Secondary Text      Zinc-600
  Muted               Zinc-500
  Accent              Lime-400
  Accent Hover        Lime-500
  Success             Emerald-500
  Error               Red-500

The page should remain mostly neutral with only **5--10% accent color
usage**.

## Navigation

-   Logo
-   Home
-   Loans
-   Savings
-   Features
-   Testimonials
-   About
-   Contact
-   Login
-   Open Account (Primary CTA)

Sticky navigation with subtle blur on scroll.

## Hero Section

### Left Column

**Headline**

> Empowering Communities Through Accessible Microfinance

**Subheading**

> Fast loan applications, secure savings, digital payments, and member
> management---all in one modern platform.

**Primary Buttons**

-   Apply for Loan
-   Watch Demo

**Trust Badges**

-   Secure
-   Fast Approval
-   Trusted by Cooperatives
-   Mobile Ready

### Right Column

Dashboard mockup featuring:

-   Total Members
-   Active Loans
-   Collections
-   Monthly Growth

Floating cards:

-   Loan Approved -- ₱50,000
-   98% Collection Rate

## Statistics

-   10,000+ Members
-   ₱500M+ Loans Released
-   98% Repayment Rate
-   50+ Branches

## Services

-   Loan Management
-   Savings Accounts
-   Payment Collection
-   Insurance Services
-   Accounting
-   Mobile Banking

Cards should use rounded corners, white background, subtle borders, and
hover elevation.

## Why Choose Us

-   Fast Approval
-   Low Interest Rates
-   Secure Transactions
-   Mobile Access
-   Real-time Reporting
-   Multi-Branch Support

## Process

1.  Register
2.  Apply
3.  Verification
4.  Approval
5.  Receive Funds

## Dashboard Preview

Modern admin dashboard with:

-   Sidebar
-   Charts
-   Loans
-   Collections
-   Recent Payments
-   Members

## Mobile App

Phone mockup featuring:

-   Loan Balance
-   Monthly Payment
-   Apply Loan
-   Notifications
-   QR Payment

## Testimonials

Three customer testimonial cards with 5-star ratings.

## Pricing

Plans:

-   Starter
-   Professional (Highlighted)
-   Enterprise

## FAQ

-   How long does approval take?
-   What are the requirements?
-   Is mobile banking available?
-   Can cooperatives use this system?
-   How secure is the platform?

## Call to Action

**Ready to Modernize Your Microfinance Operations?**

Start managing members, loans, savings, and collections today.

Buttons:

-   Open Account
-   Schedule Demo

## Footer

Columns:

-   Company
-   Products
-   Resources
-   Social Links

## Typography

-   Hero: text-6xl
-   Section: text-4xl
-   Cards: text-xl
-   Body: text-base text-zinc-600

## Border Radius

-   Buttons: rounded-xl
-   Cards: rounded-2xl
-   Mockups: rounded-3xl

## Shadows

-   shadow-lg
-   hover: shadow-2xl

## Animations

-   Fade-up
-   Floating cards
-   Counter animation
-   Hover scale
-   Smooth 300ms transitions

## Tailwind / FluxUI Tokens

``` html
<body class="bg-zinc-50 text-zinc-900">
```

Primary CTA:

``` html
class="bg-accent text-accent-foreground hover:bg-lime-500 rounded-xl px-6 py-3 font-semibold transition-all"
```

Card:

``` html
class="bg-white border border-zinc-200 rounded-2xl shadow-lg hover:shadow-xl transition-all"
```
