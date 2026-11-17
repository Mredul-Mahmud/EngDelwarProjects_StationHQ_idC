  <style>
    .duty {
        color: rgb(240, 240, 15);
    }

    .star-separator {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: yellow;
        font-size: 18px;
    }

    .star-separator::before,
    .star-separator::after {
        content: "|";
        color: #ccc;
        font-size: 18px;
        line-height: 1;
    }

    .star-separator::before {
        margin-bottom: 3px;
    }

    .star-separator::after {
        margin-top: 3px;
    }

    .date {
        color: yellow;
        white-space: nowrap;
    }
    
    /* Mobile adjustments */
    @media (max-width: 767px) {
        .top-header-col {
            text-align: center;
            margin-bottom: 15px;
        }
        .top-header-col .d-flex {
            justify-content: center;
        }
        .language-switcher {
            float: none !important;
            text-align: center;
        }
    }
</style>

<div class="p-2">
    <div class="row align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-12 top-header-col">
            <div>
                <span class="duty"> {{ session('lang', 'en') == 'bn' ? 'ডিউটি ক্লার্ক :' : 'Duty Clerk :' }}</span>
                <span class="text-light">{{ $header_footer->duty_clark ?? '' }}</span><br>
                <span class="duty">{{ session('lang', 'en') == 'bn' ? 'যানবাহনের স্টিকার তথ্য :' : 'Vehicle Sticker Info :' }}</span>
                <span class="text-light"> {{ $header_footer->it_phone ?? '+8801797585010' }}</span><br>
                <span class="duty">{{ session('lang', 'en') == 'bn' ? 'কবরস্থান তথ্য :' : 'Graveyard Info :' }}</span>
                <span class="text-light">+8801706986498 </span>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12 top-header-col">
            <div class="d-flex align-items-center justify-content-center">
                <!-- Star separator -->
                <div class="me-3"> 
                    <div class="star-separator">★</div>
                </div>

                <!-- Date -->
                <div class="p-3">
                    <span class="date">
                       {{ session('lang', 'en') == 'bn' ? 'আজ :' : 'Today :' }}  <?php echo date('D, M j, Y, h:i:s A'); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12 top-header-col">
            <div class="language-switcher" style="float:right;">
                <select onchange="if(this.value){ window.location.href=this.value; }"
                        class="form-select form-select-sm"
                        style="width: auto; display: inline-block;">
                    <option value="{{ route('set-language', 'en') }}"
                        {{ session('lang', 'en') == 'en' ? 'selected' : '' }}>
                        English
                    </option>
                    <option value="{{ route('set-language', 'bn') }}"
                        {{ session('lang', 'en') == 'bn' ? 'selected' : '' }}>
                        বাংলা
                    </option>
                </select>
            </div>
        </div>
    </div>
</div>
