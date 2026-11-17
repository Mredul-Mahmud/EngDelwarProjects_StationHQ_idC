<style>
    .header-section {
        color: white;
        padding: 10px 0;
        font-family: sans-serif;
        background: linear-gradient(to right, #18211a -78%, #328822 76%, #18211a 107%);
    }

    .header-text {
        font-weight: bold;
    }

    .main-title {
        font-size: 40px;
        margin-bottom: 0;
        font-weight: bold;
    }

    .header-subtext {
        font-size: 1.5rem;
        margin-top: 5px;
    }

    .logo {
        box-shadow: #ddded8 0px 0px 10px 0px;
        border-radius: 50%;
        max-width: 50%;
        /* max-width: 100%; */
        height: auto;
    }


    .header-text {
    color: #fff;
    text-shadow: 0 4px 10px rgba(0,0,0,0.6);
}
    
    /* Mobile Responsive Adjustments */
    @media (max-width: 767px) {
        .main-title {
            font-size: 20px; /* Smaller font for mobile */
        }
        .header-subtext {
            font-size: 14px;
        }
        .logo {
            max-width: 60px; /* Restrict logo size on mobile */
        }
    }
</style>

<section class="header-section">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-3 col-md-2 d-flex justify-content-end">
                <img src="{{ asset('assets/images/homePage/Asset.png') }}" alt="Logo" class="logo">
            </div>
            <div class="col-6 col-md-8 text-center header-text">
                <div class="main-title">
                    {{ session('lang', 'en') == 'bn' ? 'স্টেশন সদর দপ্তর ঢাকা' : 'STATION HEADQUARTERS DHAKA' }}
                </div>
                <div class="header-subtext">
                    {{ session('lang', 'en') == 'bn' ? 'ঢাকা সেনানিবাস' : 'Dhaka Cantonment' }}
                </div>
            </div>
            <div class="col-3 col-md-2">
                <img src="{{ asset('assets/images/homePage/Asset.png') }}" alt="Logo" class="logo">
            </div>
        </div>
    </div>
</section>
