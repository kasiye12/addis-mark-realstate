@extends('layouts.frontend')

@section('title', 'Frequently Asked Questions - ' . setting('site_name', 'Addis Mark Real Estate'))

@section('meta_description', 'Find answers to commonly asked questions about real estate in Ethiopia, property buying, selling, renting, and our services.')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
<style>
    .faq-item {
        background: white;
        border-radius: 16px;
        margin-bottom: 16px;
        border: 1px solid #f0f0f0;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .faq-item:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    }
    
    .faq-question {
        padding: 20px 24px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 600;
        color: #111827;
        transition: all 0.2s ease;
    }
    
    .faq-question:hover {
        background: #f8fafc;
    }
    
    .faq-question i {
        color: #2563eb;
        transition: transform 0.3s ease;
    }
    
    .faq-item.active .faq-question i {
        transform: rotate(180deg);
    }
    
    .faq-answer {
        padding: 0 24px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
    }
    
    .faq-item.active .faq-answer {
        padding: 0 24px 20px 24px;
        max-height: 500px;
    }
    
    .faq-answer p {
        color: #4b5563;
        line-height: 1.7;
    }
    
    .category-tab {
        padding: 12px 24px;
        border-radius: 40px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        background: white;
        color: #4b5563;
        border: 1px solid #e5e7eb;
    }
    
    .category-tab:hover {
        background: #f3f4f6;
    }
    
    .category-tab.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }
</style>
@endpush

@section('content')

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-900 py-20 lg:py-28 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/svg%3E')]"></div>
    </div>
    
    <div class="relative container mx-auto px-4 text-center">
        <span class="inline-block px-4 py-2 bg-white/10 backdrop-blur-sm text-white rounded-full text-sm font-medium mb-4" data-aos="fade-up">
            <i class="ri-question-line mr-2"></i>Got Questions?
        </span>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4" data-aos="fade-up" data-aos-delay="100">
            Frequently Asked Questions
        </h1>
        <p class="text-xl text-white/90 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
            Find answers to commonly asked questions about our services and real estate in Ethiopia.
        </p>
    </div>
</section>

<!-- FAQ Categories -->
<section class="py-8 bg-white border-b border-gray-200">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-center gap-3" data-aos="fade-up">
            <button class="category-tab active" data-category="all">All Questions</button>
            <button class="category-tab" data-category="buying">Buying</button>
            <button class="category-tab" data-category="selling">Selling</button>
            <button class="category-tab" data-category="renting">Renting</button>
            <button class="category-tab" data-category="legal">Legal & Documentation</button>
            <button class="category-tab" data-category="investment">Investment</button>
        </div>
    </div>
</section>

<!-- FAQ Content -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            
            <!-- Buying FAQs -->
            <div class="mb-8" data-category="buying">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ri-home-4-line text-blue-600"></i>
                    Buying Property
                </h3>
                
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>What is the process for buying a property in Ethiopia?</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                    <div class="faq-answer">
                        <p>The property buying process in Ethiopia involves: 1) Property search and viewing, 2) Price negotiation, 3) Legal due diligence and title verification, 4) Signing of sale agreement, 5) Payment processing, 6) Property transfer and registration at the relevant government office. We guide you through every step.</p>
                    </div>
                </div>
                
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>Can foreigners buy property in Ethiopia?</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Foreign nationals cannot own land in Ethiopia but can acquire leasehold rights for up to 99 years. Foreign investors can also purchase residential and commercial properties under specific investment regulations. We recommend consulting with our legal team for personalized guidance.</p>
                    </div>
                </div>
                
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>What documents are required to buy property?</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Required documents include: Valid ID/passport, Tax Identification Number (TIN), proof of income or bank statements (for financing), sale agreement, and property title deed verification. Our team will help you prepare all necessary documentation.</p>
                    </div>
                </div>
            </div>
            
            <!-- Selling FAQs -->
            <div class="mb-8" data-category="selling">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ri-price-tag-3-line text-blue-600"></i>
                    Selling Property
                </h3>
                
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>How do I determine the right price for my property?</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                    <div class="faq-answer">
                        <p>We provide free professional property valuation based on: current market conditions, comparable sales in your area, property condition and features, location desirability, and recent upgrades. This ensures your property is priced competitively.</p>
                    </div>
                </div>
                
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>What fees are involved in selling a property?</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Typical selling costs include: Real estate agent commission (2-5%), capital gains tax (15% on profit), legal fees, and administrative costs for document preparation and registration. We provide a complete breakdown before listing.</p>
                    </div>
                </div>
                
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>How long does it take to sell a property?</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Average selling time varies by location and property type, but typically ranges from 2-6 months. Properties priced correctly and in desirable locations can sell within weeks. We provide realistic timelines based on current market data.</p>
                    </div>
                </div>
            </div>
            
            <!-- Renting FAQs -->
            <div class="mb-8" data-category="renting">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ri-key-line text-blue-600"></i>
                    Renting Property
                </h3>
                
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>What is required to rent a property?</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Rental requirements typically include: Valid ID, proof of income or employment letter, security deposit (usually 1-3 months' rent), and references from previous landlords. We help you prepare all necessary documentation.</p>
                    </div>
                </div>
                
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>Are utilities included in the rent?</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                    <div class="faq-answer">
                        <p>This varies by property. Some rentals include water and sometimes electricity, while others require tenants to pay all utilities separately. Each listing clearly specifies what's included. We'll help you understand all costs before signing.</p>
                    </div>
                </div>
            </div>
            
            <!-- Legal FAQs -->
            <div class="mb-8" data-category="legal">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ri-scales-3-line text-blue-600"></i>
                    Legal & Documentation
                </h3>
                
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>What is a title deed and why is it important?</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                    <div class="faq-answer">
                        <p>A title deed is the legal document proving ownership of a property. It's essential for any property transaction and must be verified for authenticity and to ensure there are no encumbrances or disputes. We conduct thorough title searches for every transaction.</p>
                    </div>
                </div>
                
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>Do I need a lawyer for property transactions?</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                    <div class="faq-answer">
                        <p>While not legally required, we strongly recommend legal representation for all property transactions. Our partner law firms specialize in real estate and can ensure your interests are protected throughout the process.</p>
                    </div>
                </div>
            </div>
            
            <!-- Investment FAQs -->
            <div class="mb-8" data-category="investment">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ri-money-dollar-circle-line text-blue-600"></i>
                    Investment
                </h3>
                
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>Is real estate a good investment in Ethiopia?</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, Ethiopia's real estate market has shown consistent growth, driven by rapid urbanization, infrastructure development, and a growing middle class. Areas like Bole, CMC, and Summit have seen significant appreciation. We can help you identify the best investment opportunities.</p>
                    </div>
                </div>
                
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-question" onclick="toggleFAQ(this)">
                        <span>What are the typical returns on rental properties?</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Rental yields in Addis Ababa typically range from 6-10% annually, depending on location and property type. Luxury apartments in prime areas can yield 8-12%. We provide detailed ROI projections for investment properties.</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- Still Have Questions -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 text-center">
        <div class="max-w-2xl mx-auto" data-aos="fade-up">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Still Have Questions?</h2>
            <p class="text-gray-600 mb-8">Can't find the answer you're looking for? Please contact our friendly team.</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                <i class="ri-message-2-line mr-2"></i>
                Contact Us
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({ duration: 800, once: true, offset: 50 });
        
        // FAQ Toggle
        window.toggleFAQ = function(element) {
            const faqItem = element.closest('.faq-item');
            faqItem.classList.toggle('active');
        };
        
        // Category Filter
        const tabs = document.querySelectorAll('.category-tab');
        const faqSections = document.querySelectorAll('[data-category]');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const category = this.dataset.category;
                
                // Update active tab
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Show/hide sections
                if (category === 'all') {
                    faqSections.forEach(section => section.style.display = 'block');
                } else {
                    faqSections.forEach(section => {
                        if (section.dataset.category === category) {
                            section.style.display = 'block';
                        } else {
                            section.style.display = 'none';
                        }
                    });
                }
            });
        });
    });
</script>
@endpush