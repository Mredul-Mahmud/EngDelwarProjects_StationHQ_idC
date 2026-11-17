<style>
    .main-slider {
        width: 100%;
    }

    .single-slide-wrap {
        position: relative;
        height: 450px;
        width: 100%;
    }

    .single-slide-wrap img {
        width: 100% !important;
        height: 450px !important;
        object-fit: cover !important;
        display: block;
    }

    .slider-title {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(0, 0, 0, 0.6);
        color: #e3d700;
        padding: 10px 20px;
        font-size: 24px;
        text-transform: uppercase;
        font-weight: bold;
        text-align: center;
        border-radius: 5px;
        width: 90%;
        max-width: 600px;
    }

    /* Responsive for tablets */
    @media (max-width: 992px) {
        .single-slide-wrap {
            height: 400px;
        }
        .single-slide-wrap img {
            height: 400px !important;
        }
        .slider-title {
            font-size: 20px;
            bottom: 20px;
        }
    }

    /* Responsive for mobile */
    @media (max-width: 576px) {
        .single-slide-wrap {
            height: 250px;
        }
        .single-slide-wrap img {
            height: 250px !important;
        }
        .slider-title {
            font-size: 16px;
            padding: 8px 15px;
            bottom: 15px;
        }
    }
</style>


<div class="main-slider">
    @foreach ($front_sliders as $slider)
        <div class="single-slide-wrap">
            <img src="{{ asset('assets/images/sliders/' . $slider->image) }}" alt="{{ $slider->name }}" style="width: 100%; height: 100%;">

            <div class="slider-title">{{ $slider->name ?? 'No Text'}}</div>

        </div>
    @endforeach
</div>
