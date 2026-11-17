  <style>
      .header {
          background: #1a3d1a;
          border-bottom: 4px solid #3c6e3c;
      }

      .header-title {
          color: #fff;
          font-size: 2rem;
          letter-spacing: 2px;
      }

      .subtitle {
          color: #b2ffb2;
          font-size: 1.1rem;
      }

      /* Logo size */
      .header-logo {
          max-height: 60px;
          /* adjust as needed */
      }

      /* Responsive scaling for small devices */
      @media (max-width: 576px) {
          .header-title {
              font-size: 1.4rem;
          }

          .subtitle {
              font-size: 0.9rem;
          }

          .header-logo {
              max-height: 40px;
          }
      }
  </style>


  {{-- <style>
      .carousel-container {
          width: 100%;
          max-width: 1000px;
          margin: auto;
          position: relative;
      }

      .carousel-inner {
          display: flex;
          align-items: center;
          justify-content: center;
      }

      .carousel-item {
          flex: 0 0 33.33%;
          /* 3 items visible */
          display: flex;
          justify-content: center;
          transition: transform 0.6s ease-in-out;
      }

      .carousel-item img {
          width: 80%;
          transition: transform 0.6s ease-in-out;
          border-radius: 10px;
      }

      .carousel-item.active img {
          transform: scale(1.2);
          /* make center bigger */
          z-index: 2;
      }
  </style> --}}


  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

  <div class="w-100" style="background:#23241d; border-bottom:2px solid #3c6e3c;">
      <div class="container-fluid px-4 py-2 d-flex flex-column flex-md-row align-items-center justify-content-between"
          style="font-size:1rem;">
          <div class="d-flex flex-column flex-md-row align-items-md-center text-start">
              <span class="me-md-4" style="color:#ffd700;">Duty Clerk:<span style="color:#fff;">+880
                      1769013630</span></span>
              <span class="me-md-4" style="color:#ffd700;">Vehicle Sticker Info: <span style="color:#fff;">+880
                      1797585010</span></span>
              <span class="me-md-4" style="color:#ffd700;">Graveyard Info: <span style="color:#fff;">+880
                      1706986498</span></span>
          </div>
          <div class="d-flex align-items-center mt-2 mt-md-0">
              <span class="me-2" style="color:#ffd700; font-size:1.2rem;">&#9733;</span>
              <span style="color:#ffd700;"><span id="datetime" style="color:#ffd700;"></span></span>
          </div>
      </div>
      <div style="height:3px; background:linear-gradient(90deg,#3c6e3c 60%,#ffd700 100%);"></div>

      {{-- <div class="header container-fluid py-3">
          <div class="row align-items-center justify-content-center text-center">
              <!-- Left Logo -->
              <div class="col-auto">
                  <img src="{{ asset('assets/images/LogosS.png') }}" alt="Left Logo" class="img-fluid header-logo">
              </div>

              <!-- Title & Subtitle -->
              <div class="col-auto">
                  <h1 class="mb-0 header-title">STATION HEADQUARTERS DHAKA</h1>
                  <div class="subtitle">Dhaka Cantonment</div>
              </div>

              <!-- Right Logo -->
              <div class="col-auto">
                  <img src="{{ asset('assets/images/LogosS.png') }}" alt="Right Logo" class="img-fluid header-logo">
              </div>
          </div>
      </div> --}}


      {{-- <div id="customCarousel" class="carousel slide carousel-container" data-bs-ride="carousel">
          <div class="carousel-inner">
              <div class="carousel-item active">
                  <img src="{{ asset('assets/images/LogosS.png') }}" alt="Slide 1">
              </div>
              <div class="carousel-item">
                  <img src="https://picsum.photos/600/300?random=2" alt="Slide 2">
              </div>
              <div class="carousel-item">
                  <img src="{{ asset('assets/images/LogosS.png') }}" alt="Slide 3">
              </div>
              <div class="carousel-item">
                  <img src="{{ asset('assets/images/LogosS.png') }}" alt="Slide 4">
              </div>
              <div class="carousel-item">
                  <img src="{{ asset('assets/images/LogosS.png') }}" alt="Slide 5">
              </div>
          </div>

          <button class="carousel-control-prev" type="button" data-bs-target="#customCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#customCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon"></span>
          </button>
      </div> --}}
  </div>


  <script>
      function updateDateTime() {
          const now = new Date();
          const options = {
              weekday: 'short',
              year: 'numeric',
              month: 'short',
              day: '2-digit',
              hour: '2-digit',
              minute: '2-digit',
              second: '2-digit'
          };
          document.getElementById('datetime').textContent =
              now.toLocaleString('en-US', options);
      }

      updateDateTime(); // run on page load
      setInterval(updateDateTime, 1000); // update every second
  </script>
