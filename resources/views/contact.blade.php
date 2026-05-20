@extends('layouts.app')
@php $pageTitle = 'Contact Us' @endphp
@section('content')

<div class="page-header">
    <div class="container">
        <h1>Contact Us</h1>
        <div class="breadcrumb"><a href="{{ route('home') }}">Home</a> <span>/</span> Contact</div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="contact-grid">
            <div>
                <div class="about-tag">Get In Touch</div>
                <h2 style="color:var(--white);margin-bottom:16px;">We'd Love To Hear From You</h2>
                <p style="color:var(--grey);line-height:1.8;margin-bottom:40px;">Have questions about sizing, shipping, or your order? Drop us a message.</p>
                <div class="contact-info-item"><span class="contact-icon">📍</span><div><div class="contact-label">Address</div><div class="contact-value">No. 12, Jalan Ampang Hilir,<br>Kuala Lumpur, 50450</div></div></div>
                <div class="contact-info-item"><span class="contact-icon">📧</span><div><div class="contact-label">Email</div><div class="contact-value">hello@dripculture.my</div></div></div>
                <div class="contact-info-item"><span class="contact-icon">📱</span><div><div class="contact-label">WhatsApp</div><div class="contact-value">+60 12-345 6789</div></div></div>
                <div class="contact-info-item"><span class="contact-icon">🕐</span><div><div class="contact-label">Business Hours</div><div class="contact-value">Mon – Fri: 9am – 6pm<br>Sat: 10am – 3pm</div></div></div>
            </div>
            <div>
                <div class="form-card" style="max-width:100%;">
                    @if (session('success'))
                        <div class="flash flash-success" style="text-align:center;padding:40px 20px;">
                            <div style="font-size:3rem;margin-bottom:16px;">✅</div>
                            <div style="font-size:1.1rem;color:var(--white);font-weight:700;margin-bottom:8px;">Message Sent!</div>
                            <p>Thank you for reaching out. We'll reply within 24 hours.</p>
                        </div>
                    @else
                        <div class="form-title">Send a Message</div>
                        <p class="form-sub">Fill in the form and we'll get back to you.</p>
                        <form method="POST" action="{{ route('contact.send') }}" novalidate>
                            @csrf
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" name="name" class="form-control @error('name') input-error @enderror"
                                           placeholder="Your name" value="{{ old('name') }}">
                                    @error('name') <div class="error-msg">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control @error('email') input-error @enderror"
                                           placeholder="your@email.com" value="{{ old('email') }}">
                                    @error('email') <div class="error-msg">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Subject *</label>
                                <input type="text" name="subject" class="form-control @error('subject') input-error @enderror"
                                       placeholder="What is this about?" value="{{ old('subject') }}">
                                @error('subject') <div class="error-msg">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Message *</label>
                                <textarea name="message" rows="5" class="form-control @error('message') input-error @enderror"
                                          placeholder="Tell us how we can help...">{{ old('message') }}</textarea>
                                @error('message') <div class="error-msg">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-full">Send Message →</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
