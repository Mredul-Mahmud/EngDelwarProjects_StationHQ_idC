<div class="slider-wrapper">
    <button class="nav-button prev-button">&#10094;</button>
    <div class="slider-track" id="sliderTrack"></div>
    <button class="nav-button next-button">&#10095;</button>
</div>

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #111;
        /* font-family: sans-serif;
        overflow: hidden; */
    }

    .slider-wrapper {
        width: 100%;
        /* max-width: 1200px; */
        margin: 0 auto;
        overflow: hidden;
        height: 380px;
        padding: 30px 0;
        position: relative;
        display: flex;
        align-items: center;
        background-color: #9df1e0;
    }

    .slider-track {
        display: flex;
        transition: transform 0.6s ease;
        will-change: transform;
    }

    .slide {
        flex-shrink: 0;
        background: #444;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        position: relative;
        opacity: 0.7;
        transition: 0.6s all;
        transform: scale(0.7);
    }

    .slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .slide.active {
        box-shadow: 0 0 20px rgba(0, 255, 127, 0.6);
        opacity: 1;
        transform: scale(1);
    }

    .slide::after {
        /* content: attr(data-label); */
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background: rgba(0, 0, 0, 0.7);
        color: #fff;
        text-align: center;
        font-size: 18px;
        padding: 10px;
    }

    .nav-button {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: #00ff77;
        border: none;
        padding: 12px 18px;
        font-size: 18px;
        cursor: pointer;
        z-index: 10;
        color: #111;
        font-weight: bold;
        border-radius: 8px;
    }

    .prev-button {
        left: 10px;
    }

    .next-button {
        right: 10px;
    }

    .slide-title {
        position: absolute;
        bottom: 15px;
        /* image er niche */
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.6);
        color: #fff;
        padding: 8px 12px;
        border-radius: 5px;
        font-size: 16px;
        text-align: center;
        width: calc(100% - 20px);
        /* responsive */
        box-sizing: border-box;
    }

    /* Mobile Vertical Mode */
    @media screen and (max-width: 768px) {
        .slider-wrapper {
            /* keep horizontal layout on mobile */
            height: 320px;
            padding: 20px 10px;
        }

        .slider-track {
            /* keep horizontal track */
            flex-direction: row;
            width: auto;
        }

        .nav-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 20;
        }

        .prev-button {
            left: 8px;
        }

        .next-button {
            right: 8px;
        }

        .slide {
            /* slides will be sized by JS for mobile */
            width: auto;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }
</style>

<script>
    const config = {
        slidesToShow: 3,
        centerMode: true,
        gap: 20,
        autoplay: true,
        autoplaySpeed: 5000
    };

    const images = [
        @foreach ($front_sliders as $slider)
            {
                id: {{ $loop->index }},
                src: "{{ asset('assets/images/sliders/' . $slider->image) }}",
                label: "{{ $slider->name }}"
            }
            @if (!$loop->last)
                ,
            @endif
        @endforeach
    ];

    const track = document.getElementById('sliderTrack');
    let currentLogicalIndex = 0;
    let currentPhysicalIndex = 0;
    let slides = [];
    let isTransitioning = false;

    let sideSlideSize = 0;
    let centerSlideSize = 0;
    let isMobile = window.innerWidth <= 768;
    let autoplayInterval;

    function calculateWidths() {
        const sliderWrapper = document.querySelector('.slider-wrapper');
        const style = window.getComputedStyle(sliderWrapper);
        // For mobile we still want horizontal (left-to-right) sliding,
        // so measure available width in both cases.
        const paddingHorizontal = parseFloat(style.paddingLeft) + parseFloat(style.paddingRight);
        const containerSize = sliderWrapper.offsetWidth - paddingHorizontal;

        const currentSlidesToShow = isMobile ? 1 : config.slidesToShow;
        const totalGaps = config.gap * (currentSlidesToShow - 1);
        const availableSize = containerSize - totalGaps;

        if (currentSlidesToShow === 1) {
            centerSlideSize = availableSize;
            sideSlideSize = availableSize;
        } else if (config.centerMode) {
            centerSlideSize = availableSize * 0.4;
            sideSlideSize = (availableSize - centerSlideSize) / (currentSlidesToShow - 1);
        } else {
            sideSlideSize = availableSize / currentSlidesToShow;
            centerSlideSize = sideSlideSize;
        }
    }

    function createSlide(item) {
        const slide = document.createElement('div');
        slide.className = 'slide';
        // slide.dataset.label = item.label;
        slide.dataset.id = item.id;
        slide.innerHTML = `<img src="${item.src}" alt="${item.label}">
         <div class="slide-title">${item.label || 'Your Static Title Here'}</div>
        `;
        return slide;
    }

    function buildInfiniteTrack() {
        track.innerHTML = '';
        calculateWidths();
        const repeatCount = 20;

        for (let rep = 0; rep < repeatCount; rep++) {
            images.forEach(item => {
                const slide = createSlide(item);
                track.appendChild(slide);
            });
        }

        slides = Array.from(track.children);
        currentPhysicalIndex = Math.floor(slides.length / 2);
        currentLogicalIndex = currentPhysicalIndex % images.length;
        applyWidths();
    }

    function applyWidths() {
        slides.forEach((slide, index) => {
            const isCenter = index === currentPhysicalIndex && config.centerMode;
            const size = isCenter ? centerSlideSize : sideSlideSize;

            // Use horizontal sizing on all viewports (mobile included)
            slide.style.width = `${size}px`;
            slide.style.height = isMobile ? '220px' : '300px';
            slide.style.marginLeft = '0px';
            slide.style.marginRight = `${config.gap}px`;
        });

        if (slides.length > 0) {
            if (isMobile) {
                slides[slides.length - 1].style.marginBottom = '0px';
            } else {
                slides[slides.length - 1].style.marginRight = '0px';
            }
        }
    }

    function getCenterOffset(slideIndex) {
        const sliderWrapper = document.querySelector('.slider-wrapper');
        const style = window.getComputedStyle(sliderWrapper);

        // Always calculate horizontally (center X of container)
        const containerCenter = sliderWrapper.offsetWidth / 2;
        const paddingStart = parseFloat(style.paddingLeft);

        let offset = 0;

        for (let i = 0; i < slideIndex; i++) {
            const size = (i === currentPhysicalIndex) ? centerSlideSize : sideSlideSize;
            offset += size + config.gap;
        }

        const currentSize = (slideIndex === currentPhysicalIndex) ? centerSlideSize : sideSlideSize;
        offset += currentSize / 2;

        // Account for container padding which shifts the track start
        offset += paddingStart;

        return offset - containerCenter;
    }

    function updateSlider(animate = true) {
        if (isTransitioning && animate) return;
        applyWidths();

        slides.forEach(slide => slide.classList.remove('active'));
        if (slides[currentPhysicalIndex] && config.centerMode) {
            slides[currentPhysicalIndex].classList.add('active');
        }

        const offset = getCenterOffset(currentPhysicalIndex);
        const direction = 'translateX';
        track.style.transition = animate ? 'transform 0.6s ease' : 'none';
        track.style.transform = `${direction}(-${offset}px)`;

        if (animate) {
            isTransitioning = true;
            setTimeout(() => {
                isTransitioning = false;
                resetPositionIfNeeded();
            }, 600);
        }
    }

    function resetPositionIfNeeded() {
        const resetThreshold = images.length * 2;
        const middlePosition = Math.floor(slides.length / 2);
        if (currentPhysicalIndex < resetThreshold || currentPhysicalIndex > slides.length - resetThreshold) {
            const targetLogical = currentLogicalIndex;
            currentPhysicalIndex = middlePosition + (targetLogical - (middlePosition % images.length));
            updateSlider(false);
        }
    }

    function nextSlide() {
        if (isTransitioning) return;
        currentPhysicalIndex++;
        currentLogicalIndex = (currentLogicalIndex + 1) % images.length;
        updateSlider(true);
    }

    function prevSlide() {
        if (isTransitioning) return;
        currentPhysicalIndex--;
        currentLogicalIndex = (currentLogicalIndex - 1 + images.length) % images.length;
        updateSlider(true);
    }

    function bindEvents() {
        document.querySelector('.next-button').addEventListener('click', nextSlide);
        document.querySelector('.prev-button').addEventListener('click', prevSlide);

        window.addEventListener('keydown', e => {
            if (e.key === 'ArrowRight' || e.key === 'ArrowDown') nextSlide();
            else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') prevSlide();
        });

        window.addEventListener('resize', () => {
            isMobile = window.innerWidth <= 768;
            calculateWidths();
            applyWidths();
            updateSlider(false);
        });
    }

    function startAutoplay() {
        if (config.autoplay) {
            autoplayInterval = setInterval(() => {
                nextSlide();
            }, config.autoplaySpeed);
        }
    }


    buildInfiniteTrack();
    updateSlider(false);
    bindEvents();
    startAutoplay();
</script>
