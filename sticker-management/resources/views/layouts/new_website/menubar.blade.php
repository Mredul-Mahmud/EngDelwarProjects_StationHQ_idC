<!-- site-header -->
<header class="sticky-top site-header" style="">

    <!-- Top header Start -->

    <!-- Top header End -->

    <!-- main-header closed-->

    <!-- main-menu-section starts -->
    <style>
        .main-menu li {
            /* padding: 2px; */
            margin: 0 -12px;
        }

        .main-menu li a {
            text-align: center !important;
        }

        .submenu li a {
             text-align: left !important;
        }

        @media (max-width: 600px) {
          
          .main-menu li a {
            text-align: left !important;
        }

        }

    </style>

    <div class="main-menu-section">
        <div class="container-fluid">
            <div class="row m-0 p-0">
                <div class="col-lg-12 p-0">
                    <nav class="navbar navbar-expand-lg main-menu-wrap justify-content-center py-0">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainMenu"
                            aria-controls="mainMenu" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fas fa-bars" style="color:#fff; font-size:24px;"></i>
                        </button>

                        <div class="collapse navbar-collapse justify-content-center" id="mainMenu">
                            <ul class="navbar-nav main-menu">
                                {{-- <ul class="d-flex align-items-center justify-content-center main-menu"> --}}
                                <li class="{{ request()->is('/') ? 'active' : '' }}">
                                    @if (auth()->guard('applicant')->check())
                                        <a
                                            href="{{ url('/customer/home') }}">{{ session('lang', 'en') == 'bn' ? 'হোম' : 'Home' }}</a>
                                        {{-- <a href="{{ url('/customer/home') }}">Home</a> --}}
                                    @else
                                        {{-- <a href="{{ url('/') }}">Home</a> --}}
                                        <a
                                            href="{{ url('/') }}">{{ session('lang', 'en') == 'bn' ? 'হোম' : 'Home' }}</a>
                                    @endif
                                </li>
                                @php
                                    // Define an array of paths for the submenu items to check for active status
                                    $applyForStickerActive =
                                        request()->is('apply-for-sticker') ||
                                        request()->is('customer/login') ||
                                        request()->is('customer/register') ||
                                        request()->is('policy');
                                    request()->is('login');
                                @endphp

                                @if (
                                    $header_footer->is_sticker_login == 'yes' ||
                                        $header_footer->is_sticker_register == 'yes' ||
                                        $header_footer->is_sticker_policy == 'yes' ||
                                        // $header_footer->is_sticker_tutorial == 'yes' ||
                                        $header_footer->login == 'yes')
                                    <li class="{{ $applyForStickerActive ? 'active' : '' }}">
                                        <a href="#">{{ session('lang', 'en') == 'bn' ? 'স্টিকারের আবেদন' : 'Apply For Sticker' }}
                                        </a>
                                        <ul class="submenu">
                                            @if (!auth()->guard('applicant')->check())
                                                @if ($header_footer->is_sticker_login == 'yes')
                                                    <li class="{{ request()->is('customer/login') ? 'active' : '' }}"><a
                                                            href="{{ route('customer.login') }}">{{ session('lang', 'en') == 'bn' ? 'লগইন' : 'Login' }}
                                                        </a></li>
                                                @endif
                                                @if ($header_footer->is_sticker_register == 'yes')
                                                    <li
                                                        class="{{ request()->is('customer/register') ? 'active' : '' }}">
                                                        <a
                                                            href="{{ route('customer.register') }}">{{ session('lang', 'en') == 'bn' ? 'নিবন্ধন' : 'Register' }}</a>
                                                    </li>
                                                @endif
                                            @endif
                                            @if ($header_footer->is_sticker_policy == 'yes')
                                                <li class="{{ request()->is('policy') ? 'active' : '' }}"><a
                                                        href="{{ url('/policy') }}">{{ session('lang', 'en') == 'bn' ? 'নীতিমালা' : 'Policy' }}</a>
                                                </li>
                                            @endif
                                            {{-- @if ($header_footer->is_sticker_tutorial == 'yes')
                                            <li>
                                                <a href="javascript:void(0);"
                                                    onclick="toggleTutorial('tutorial-submenu')">Tutorial</a>
                                                <ul class="submenu tutorial-submenu">
                                                    <li><a href="{{ asset('/assets/images/fixed_files/' . $all_fixed_file->sign_up_tutorial) }}"
                                                            target="_blank">Sign up</a></li>
                                                    <li><a href="{{ asset('/assets/images/fixed_files/' . $all_fixed_file->sticker_tutorial) }}"
                                                            target="_blank">Sticker</a></li>
                                                </ul>
                                            </li>
                                        @endif --}}
                                            @if ($header_footer->login == 'yes')
                                                <li class="{{ request()->is('login') ? 'active' : '' }}"><a
                                                        href="{{ url('/login') }}">{{ session('lang', 'en') == 'bn' ? 'স্টিকার তথ্য পরীক্ষা করুন' : 'Sticker Info Check' }}</a>
                                                </li>
                                            @endif

                                        </ul>
                                    </li>
                                @endif
                                @php
                                    // Define the condition to check if any submenu under "Apply for ID Card" is active
                                    $applyForIDCardActive =
                                        request()->is('apply-for-id-card') ||
                                        request()->is('fid.stahq.com/employee/login') ||
                                        request()->is('fid.stahq.com/employee/register') ||
                                        request()->is('uid.stahq.com/employee/login') ||
                                        request()->is('uid.stahq.com/employee/register') ||
                                        request()->is('eid.stahq.com/student/login') ||
                                        request()->is('eid.stahq.com/student/register') ||
                                        request()->is('eid.stahq.com/unit/login');
                                @endphp


                                @if (
                                    $header_footer->is_enable_family_id == 'yes' ||
                                        $header_footer->is_enable_batman_id == 'yes' ||
                                        $header_footer->is_enable_employee_id == 'yes')
                                    <li class="{{ $applyForIDCardActive ? 'active' : '' }}">
                                        <a href="#">{{ session('lang', 'en') == 'bn' ? 'আইডি কার্ডের আবেদন' : 'Apply for ID Card' }}
                                        </a>
                                        <ul class="submenu">
                                            @if ($header_footer->is_enable_family_id == 'yes')
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        onclick="toggleTutorial('fid-submenu')">{{ session('lang', 'en') == 'bn' ? 'ফ্যামিলি আইডি কার্ড' : 'Family ID Card' }}
                                                    </a>
                                                    <ul class="submenu otherid-submenu">
                                                        {{-- <ul class="issued-sticker-list.blade.php fid-submenu"> --}}
                                                        <li
                                                            class="{{ request()->is('fid.stahq.com/employee/login') ? 'active' : '' }}">
                                                            <a target="_blank"
                                                                href="https://fid.stahq.com/employee/login">{{ session('lang', 'en') == 'bn' ? 'লগইন' : 'Login' }}</a>
                                                        </li>
                                                        <li
                                                            class="{{ request()->is('fid.stahq.com/employee/register') ? 'active' : '' }}">
                                                            <a href="https://fid.stahq.com/employee/register"
                                                                target="_blank">{{ session('lang', 'en') == 'bn' ? 'নিবন্ধন' : 'Register' }}</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            @endif
                                            @if ($header_footer->is_enable_batman_id == 'yes')
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        onclick="toggleTutorial('otherid-submenu')">{{ session('lang', 'en') == 'bn' ? 'ব্যাটম্যান, গৃহপরিচারিকা ও অন্যান্য আইডি কার্ড' : 'Batman, Maidservant & Other ID Card' }}</a>
                                                    <ul class="submenu otherid-submenu">
                                                        <li
                                                            class="{{ request()->is('uid.stahq.com/employee/login') ? 'active' : '' }}">
                                                            <a target="_blank"
                                                                href="https://uid.stahq.com/employee/login">{{ session('lang', 'en') == 'bn' ? 'লগইন' : 'Login' }}</a>
                                                        </li>
                                                        <li
                                                            class="{{ request()->is('uid.stahq.com/employee/register') ? 'active' : '' }}">
                                                            <a href="https://uid.stahq.com/employee/register"
                                                                target="_blank">{{ session('lang', 'en') == 'bn' ? 'নিবন্ধন' : 'Register' }}</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            @endif
                                            @if ($header_footer->is_enable_employee_id == 'yes')
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        onclick="toggleTutorial('bus-submenu')">{{ session('lang', 'en') == 'bn' ? 'স্কুল বাস কার্ড' : 'School Bus Card' }}</a>
                                                    <ul class="submenu bus-submenu">
                                                        <li
                                                            class="{{ request()->is('eid.stahq.com/student/login') ? 'active' : '' }}">
                                                            <a target="_blank"
                                                                href="https://eid.stahq.com/student/login">{{ session('lang', 'en') == 'bn' ? 'লগইন' : 'Login' }}</a>
                                                        </li>
                                                        <li
                                                            class="{{ request()->is('eid.stahq.com/student/register') ? 'active' : '' }}">
                                                            <a href="https://eid.stahq.com/student/register"
                                                                target="_blank">{{ session('lang', 'en') == 'bn' ? 'নিবন্ধন' : 'Register' }}</a>
                                                        </li>
                                                    </ul>
                                                </li>

                                                <li>
                                                    <a href="javascript:void(0);"
                                                        onclick="toggleTutorial('civil-submenu')">{{ session('lang', 'en') == 'bn' ? 'সিভিল স্থায়ী / অস্থায়ী কর্মচারী' : 'CIVIL Permanent / Temporary Employee' }}
                                                    </a>
                                                    <ul class="submenu civil-submenu">
                                                        <li
                                                            class="{{ request()->is('eid.stahq.com/unit/login') ? 'active' : '' }}">
                                                            <a target="_blank"
                                                                href="https://eid.stahq.com/unit/login">{{ session('lang', 'en') == 'bn' ? 'ইউনিট লগইন' : 'Unit Login' }}</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>

                                @endif

                                @php
                                    $graveyardActive =
                                        request()->is('graveyard') ||
                                        request()->is('graveyard-policy') ||
                                        request()->is('graveyard-application');
                                @endphp
                                @if (
                                    $header_footer->is_graveyard_allotment == 'yes' ||
                                        $header_footer->is_graveyard_policy == 'yes' ||
                                        $header_footer->is_graveyard_application_form == 'yes')
                                    <li class="{{ $graveyardActive ? 'active' : '' }}">
                                        <a href="#">{{ session('lang', 'en') == 'bn' ? 'কবরস্থান' : 'Graveyard' }}
                                        </a>
                                        <ul class="submenu">
                                            @if ($header_footer->is_graveyard_allotment == 'yes')
                                                <li class="{{ request()->is('graveyard') ? 'active' : '' }}"><a
                                                        href="{{ url('/graveyard') }}">{{ session('lang', 'en') == 'bn' ? 'কবরস্থান বরাদ্দ' : 'Graveyard Allotment' }}
                                                    </a></li>
                                            @endif
                                            @if ($header_footer->is_graveyard_policy == 'yes')
                                                <li class="{{ request()->is('graveyard-policy') ? 'active' : '' }}">
                                                    <a href="{{ asset('assets/images/fixed_files/' . $all_fixed_file->graveyard_policy) }}"
                                                        target="_blank">{{ session('lang', 'en') == 'bn' ? 'কবরস্থান নীতি' : 'Graveyard Policy' }}</a>
                                                </li>
                                            @endif
                                            @if ($header_footer->is_graveyard_application_form == 'yes')
                                                <li
                                                    class="{{ request()->is('graveyard-application') ? 'active' : '' }}">
                                                    <a href="{{ asset('assets/images/fixed_files/' . $all_fixed_file->graveyard_application) }}"
                                                        target="_blank">{{ session('lang', 'en') == 'bn' ? 'কবরস্থান আবেদন ফর্ম' : 'Graveyard Application Form' }}</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endif
                                @if ($header_footer->is_photo_gallery == 'yes')
                                    <li class="{{ request()->is('gallery') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('gallery') }}">{{ session('lang', 'en') == 'bn' ? 'গ্যালারি' : 'Photo Gallery' }}</a>
                                    </li>
                                @endif
                                @if ($header_footer->is_staf_org == 'yes')
                                    <li class="{{ request()->is('staf-org') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('staf-org') }}">{{ session('lang', 'en') == 'bn' ? 'অফিস সংগঠন' : 'Staff Org' }}</a>
                                    </li>
                                @endif
                                @if ($header_footer->is_history == 'yes')
                                    <li class="{{ request()->is('staf-history') ? 'active' : '' }}">
                                        <a
                                            href="{{ route('staf-history') }}">{{ session('lang', 'en') == 'bn' ? 'ইতিহাস' : 'History' }}</a>
                                    </li>
                                @endif

                                @if ($header_footer->is_contact == 'yes')
                                    <li>
                                        <a
                                            href="">{{ session('lang', 'en') == 'bn' ? 'যোগাযোগ' : 'Contact' }}</a>
                                        <ul class="submenu submenu-2">
                                            <div class="contact-scroll">

                                                @foreach ($contacts as $contact)
                                                    <li>
                                                        <a href="tel:{{ $contact->phone ?? '' }}"><span
                                                                class="font-weight-bold"
                                                                style="color: rgb(255, 238, 0)">
                                                                {{ $contact->title ?? '' }}</span>
                                                            <em>
                                                                @if ($contact->sub_title)
                                                                    ,({{ $contact->sub_title ?? '' }})
                                                                @endif
                                                            </em>

                                                            @if ($contact->phone)
                                                                <br>Mobile: <span class="phone" style="color:blue;">
                                                                    {{ $contact->phone }}
                                                                </span>
                                                            @endif

                                                            @if ($contact->tel)
                                                                <br>Mill Ext: {{ $contact->tel }}
                                                            @endif

                                                            @if ($contact->other)
                                                                ({{ $contact->other ?? '' }})
                                                            @endif
                                                            @if ($contact->civil_tel)
                                                                <br>Civil Tel : {{ $contact->civil_tel ?? '' }}
                                                            @endif
                                                        </a>

                                                    </li>
                                                @endforeach
                                            </div>

                                        </ul>
                                    </li>
                                @endif

                                @if (
                                    $header_footer->is_general_contact_info == 'yes' ||
                                        $header_footer->is_shuttle == 'yes' ||
                                        $header_footer->is_bus == 'yes')
                                    <li
                                        class="{{ request()->is('bus', 'mp-checkpost', 'shuttle-service', 'contact-info', 'helpline') ? 'active' : '' }}">
                                        <a
                                            href="#">{{ session('lang', 'en') == 'bn' ? 'অন্যান্য' : 'Others' }}</a>
                                        <ul class="submenu submenu-left">
                                            @if ($header_footer->is_bus == 'yes')
                                                <li class="{{ request()->is('bus') ? 'active' : '' }}"><a
                                                        href="{{ url('/bus') }}">{{ session('lang', 'en') == 'bn' ? 'বাস' : 'Bus' }}</a>
                                                </li>
                                            @endif
                                            @if ($header_footer->is_shuttle == 'yes')
                                                <li class="{{ request()->is('shuttle-service') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ url('/shuttle-service') }}">{{ session('lang', 'en') == 'bn' ? 'শাটল সার্ভিস' : 'Shuttle Service' }}</a>
                                                </li>
                                            @endif
                                            @if ($header_footer->is_general_contact_info == 'yes')
                                                <li class="{{ request()->is('contact-info') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ url('/contact-info') }}">{{ session('lang', 'en') == 'bn' ? 'সাধারণ যোগাযোগের তথ্য' : 'General Contact Info' }}</a>
                                                </li>
                                            @endif
                                            @if ($header_footer->gate_on_of_time == 'yes')
                                                <li class="{{ request()->is('gate-on-of-time') ? 'active' : '' }}">
                                                    <a
                                                        href="{{ url('/gate-on-of-time') }}">{{ session('lang', 'en') == 'bn' ? 'পকেট গেট অন/অফ এর সময়' : 'Pocket Gate On/Off Time' }}</a>
                                                </li>
                                            @endif

                                        </ul>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- main-menu-section ends -->


    <script>
        // Mobile Submenu Toggle Logic
        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = document.querySelectorAll('.main-menu > li');

            menuItems.forEach(item => {
                const link = item.querySelector('a');
                const submenu = item.querySelector('.submenu');

                if (link && submenu) {
                    link.addEventListener('click', function(e) {
                        if (window.innerWidth <= 991) { // Mobile/Tablet breakpoint
                            if (link.getAttribute('href') === '#' || link.getAttribute('href') ===
                                '') {
                                e.preventDefault();
                            }

                            // Toggle submenu
                            if (submenu.style.display === 'block') {
                                submenu.style.display = 'none';
                                // Remove active state if needed
                            } else {
                                submenu.style.display = 'block';
                            }
                        }
                    });
                }
            });
        });

        function toggleIcon(linkText) {
            var link = document.querySelector(`a:contains(${linkText})`); // Find the link by its text
            var icon = link.querySelector('i'); // Get any existing icon

            // If no icon exists, create one and append it to the link
            if (!icon) {
                icon = document.createElement('i');
                icon.classList.add('fa', 'fa-angle-down');
                link.appendChild(icon); // Append icon to the link
            } else {
                // Toggle between 'fa-angle-down' and 'fa-angle-up'
                if (icon.classList.contains('fa-angle-down')) {
                    icon.classList.remove('fa-angle-down');
                    icon.classList.add('fa-angle-up');
                } else {
                    icon.classList.remove('fa-angle-up');
                    icon.classList.add('fa-angle-down');
                }
            }
        }

        function toggleTutorial(submenuClass) {
            var submenu = document.querySelector(`.${submenuClass}`);
            var tutorialLink = submenu.previousElementSibling; // Get the Tutorial link

            // Check if the icon already exists
            var icon = tutorialLink.querySelector('i');

            // Toggle display of submenu
            if (submenu.style.display === 'block') {
                submenu.style.display = 'none';

                // Change the icon to 'fa-angle-down' when the submenu is hidden
                if (icon) {
                    icon.classList.remove('fa-angle-up');
                    icon.classList.add('fa-angle-down');
                }
            } else {
                submenu.style.display = 'block';

                // Add background color to the list items
                submenu.querySelectorAll('li').forEach(li => {
                    li.style.setProperty('background-color', '#f7bc28', 'important');
                });

                // Style the anchor tags inside the submenu
                submenu.querySelectorAll('li a').forEach(anchor => {
                    anchor.style.setProperty('color', 'red', 'important');
                    anchor.style.setProperty('margin-left', '2rem', 'important');
                    anchor.style.setProperty('background-color', 'transparent', 'important');
                });

                // If the icon doesn't exist, create it
                if (!icon) {
                    icon = document.createElement('i');
                    icon.classList.add('fa', 'fa-angle-up');
                    tutorialLink.appendChild(icon); // Append icon to the Tutorial link
                } else {
                    // If icon exists, just update it
                    icon.classList.remove('fa-angle-down');
                    icon.classList.add('fa-angle-up');
                }
            }
        }


        function updateDateTime() {
            // Get the current date and time
            const now = new Date();

            // Format the date: short day name (e.g., Sat), day number, short month name (e.g., Aug), year (e.g., 2024)
            const dateOptions = {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            const date = now.toLocaleDateString(undefined, dateOptions);

            // Format the time: HH:MM:SS (24-hour format)
            const time = now.toLocaleTimeString(undefined, {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            // Combine date and time
            const dateTime = `${date}, ${time}`;

            // Insert the formatted date and time into the <span> element
            document.getElementById('current-date-time').innerText = dateTime;
        }

        // Update the date and time every second
        setInterval(updateDateTime, 1000);

        // Initialize the date and time immediately
        updateDateTime();
    </script>
    <script>
        document.querySelectorAll('nav.main-menu-wrap ul.main-menu li.has-submenu').forEach(menuItem => {
            menuItem.addEventListener('mouseenter', function() {
                const submenu = this.querySelector('ul.submenu');

                if (submenu) {
                    const rect = submenu.getBoundingClientRect();
                    const isOverflowingRight = rect.right > window.innerWidth;

                    if (isOverflowingRight) {
                        submenu.classList.add('submenu-left');
                    } else {
                        submenu.classList.remove('submenu-left');
                    }
                }
            });
        });
    </script>
</header>
<!-- site-header closed -->
<!-- site-header closed -->
