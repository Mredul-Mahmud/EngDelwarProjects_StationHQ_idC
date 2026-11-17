





<link href="https://fonts.cdnfonts.com/css/solaimanlipi" rel="stylesheet">




<div class="d-flex align-items-center bulletin-bar m-2">
  <div class="bulletin-label">Bulletin</div>
  <div class="marquee-container flex-grow-1">
    <div class="marquee-text">
      <ul class="d-flex justify-content-start align-items-center mb-0 notice-list" style="list-style:none; padding:0; margin:0; font-family: 'SolaimanLipi', sans-serif;">
        @foreach ($notices as $index => $notice)
          @if ($notice->status == 1)
            <li class="me-5 notice-item" style="color: {{ $index % 2 == 0 ? '#060706' : '#070908' }}; display:inline; font-family: 'SolaimanLipi', sans-serif;">
              @if ($notice->link_active == 1)
                @if ($notice->link_active == 1 && $notice->slug)
                <a href="{{ route('notice_details_slug', ['slug' => $notice->slug]) }}" 
                   style="color: inherit; text-decoration: none; font-weight: bold;">
                   {{ $notice->title }}
                </a>
              @else
                {{ $notice->title }}
              @endif
              @else
                {{ $notice->title }}
              @endif
              @if (!$loop->last) 
                <span class="px-2" style="color: #df631c;">*</span>
              @endif
            </li>
          @endif
        @endforeach
      </ul>
    </div>
  </div>
</div>

<style>
/* Container Row */
.bulletin-bar {
  background: #b2d235;
  min-height: 48px;
  border-bottom: 2px solid #222;
}

/* Notice List Items */
.notice-item {
    font-size: 1.4rem;
}

/* Left Side Label */
.bulletin-label {
  background: #e60026;
  color: #fff;
  font-weight: bold;
  padding: 0 28px;
  height: 48px;
  display: flex;
  align-items: center;
  font-size: 20px;
  white-space: nowrap;
}

/* Marquee Container */
.marquee-container {
  overflow: hidden;
  position: relative;
  height: 48px;
}

/* Scrolling Text */
.marquee-text {
  display: inline-block;
  white-space: nowrap;
  font-size: 22px;
  color: #222;
  line-height: 48px;
  padding-left: 100%;
  animation: marquee 120s linear infinite;
}

.marquee-text ul {
  display: inline; /* inline রাখার জন্য */
  padding: 0;
  margin: 0;
}

.marquee-text li {
  display: inline; /* এক লাইনে দেখাতে */
}

.marquee-text a {
  color: #004400;
  text-decoration: underline;
  font-weight: bold;
}

/* Animation Keyframes */
@keyframes marquee {
  0% { transform: translateX(0); }
  100% { transform: translateX(-100%); }
}

/* Mobile Responsive */
@media (max-width: 600px) {
  .bulletin-bar {
      min-height: 38px;
  }
  .bulletin-label {
    font-size: 14px;
    padding: 0 8px;
    height: 38px;
  }
  .marquee-container { height: 38px; }
  .marquee-text {
    font-size: 16px;
    line-height: 38px;
    animation: marquee 60s linear infinite;
  }
  .notice-item {
      font-size: 16px !important;
  }
}
</style>

