            <div class="col-md-3 col-lg-3 col-xl-2 left-sidebar">
                <div class="widget left-widget">
                    <h3 class="widget-title"><i class="fa fa-bars"></i> Menu</h3>
                    <ul class="sidebar-menu">
                        <li>
                            <a href="#" class="mtitle">Def Person's List </a>
                            <ul class="sub-menu">
                                <li><a href="{{ url('/application/pending') }}/def">Pending</a></li>
                                <li><a href="{{ url('/application/hold') }}/def">Hold</a></li>
                                <li><a href="{{ url('/application/retake') }}/def">Re-take</a></li>
                                <li><a href="{{ route('verify_application', 'def') }}">Varified</a></li>
                                <li><a href="{{ url('/application/approved') }}/def">Approved</a></li>
                                <li><a href="{{ url('/application/delivered') }}/def">Delivered</a></li>
                                <li><a href="{{ url('/application/rejected') }}/def">Updated</a></li>
                                <li><a href="{{ url('/sticker/expired') }}/def">Expired</a></li>

                                <li><a href="{{ url('/def_payment_report/list') }}">Payment</a></li>

                                <li><a href="{{ url('/application/free_approved') }}/def">Free Approved</a></li>

                            </ul>
                        </li>
                        <li>
                            <a href="#" class="mtitle">Non Def Person's List</a>
                            <ul class="sub-menu">
                                <li><a href="{{ url('/application/pending') }}/non-def">Pending</a></li>
                                <li><a href="{{ url('/application/hold') }}/non-def">Hold</a></li>
                                <li><a href="{{ url('/application/retake') }}/non-def">Re-take</a></li>
                                <li><a href="{{ route('verify_application', 'non-def') }}">Varified</a></li>
                                <li><a href="{{ url('/application/approved') }}/non-def">Approved</a></li>
                                <li><a href="{{ url('/application/delivered') }}/non-def">Delivered</a></li>
                                <li><a href="{{ url('/application/rejected') }}/non-def">Updated</a></li>
                                <li><a href="{{ url('/sticker/expired') }}/non-def">Expired</a></li>

                                <li><a href="{{ url('/non_def_payment_report/list') }}">Payment</a></li>

                                <li><a href="{{ url('/application/free_approved') }}/ndef">Free Approved</a></li>

                            </ul>
                        </li>

                        <li>
                            <a href="#" class="mtitle">Transparent Application</a>
                            <ul class="sub-menu">
                                <li><a href="{{ url('/application/pending') }}/transparent">Pending</a></li>
                                <li><a href="{{ url('/application/hold') }}/transparent">Hold</a></li>
                                <li><a href="{{ url('/application/retake') }}/transparent">Re-take</a></li>
                                <li><a href="{{ url('/application/approved') }}/transparent">Approved</a></li>
                                <li><a href="{{ url('/application/delivered') }}/transparent">Delivered</a></li>
                                <li><a href="{{ url('/application/rejected') }}/transparent">Updated</a></li>
                                <li><a href="{{ url('/sticker/expired') }}/transparent">Expired</a></li>

                            </ul>
                        </li>
                        <li>
                            <a href="#" class="mtitle">Black Glass Application</a>
                            <ul class="sub-menu">
                                <li><a href="{{ url('/application/pending') }}/not-transparent">Pending</a></li>
                                <li><a href="{{ url('/application/hold') }}/not-transparent">Hold</a></li>
                                <li><a href="{{ url('/application/retake') }}/not-transparent">Re-take</a></li>
                                <li><a href="{{ url('/application/approved') }}/not-transparent">Approved</a></li>
                                <li><a href="{{ url('/application/delivered') }}/not-transparent">Delivered</a></li>
                                <li><a href="{{ url('/application/rejected') }}/not-transparent">Updated</a></li>
                                <li><a href="{{ url('/sticker/expired') }}/not-transparent">Expired</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ url('all-approved/application') }}" class="mtitle">Sticker Approved List</a>

                        </li>
                        <li>
                            <a href="#" class="mtitle">Dead Application List</a>
                            <ul class="sub-menu">
                                <li><a href="{{ url('/application/dead') }}/def">Def Dead Application</a></li>
                                <li><a href="{{ url('/application/dead') }}/non-def">Non-Def Dead Application</a></li>

                            </ul>
                        </li>
                        {{-- <li>
                                <a href="{{url('all-paid-recollect/application')}}" class="mtitle">Paid Recollected</a>
                                
                            </li>  --}}

                        <li>
                            <a href="{{ url('/all-delivery/application') }}" class="mtitle">Delivery List </a>

                        </li>

                        <li>
                            <a href="{{ url('/all-print/application') }}" class="mtitle">Sticker Print List </a>

                        </li>

                        <li>
                            <a href="{{ url('all-issued/application') }}" class="mtitle">Sticker Issued List</a>

                        </li>

                        <li>
                            <a href="{{ url('new-payment_report/list') }}" class="mtitle">New Payment List</a>

                        </li>
                        <li>
                            <a href="{{ url('payment_report/list') }}" class="mtitle">Payment Report List</a>

                        </li>

                        <li>
                            <a href="#" class="mtitle">E-type Sticker</a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('e_type_application.1', ['sticker_type' => 'e1']) }}">Minibus &
                                        Ealik upto 30 Seats-7500</a></li>
                                <li><a href="{{ route('e_type_application.2', ['sticker_type' => 'e2']) }}">Minibus &
                                        Ealik Uper 30 Seats-10000</a></li>
                                <li><a href="{{ route('e_type_application.3', ['sticker_type' => 'e3']) }}">Trust
                                        Transport Services (Vashantak to Kakoli)-7500</a></li>
                                <li><a href="{{ route('e_type_application.4', ['sticker_type' => 'e4']) }}">Trust
                                        Transport Services (Vashantak to Kakoli)-1000</a></li>
                                <li><a href="{{ route('e_type_application.5', ['sticker_type' => 'e5']) }}">Trust
                                        Transport Services-1000</a></li>
                                <li><a
                                        href="{{ route('e_type_application.6', ['sticker_type' => 'e6']) }}">Laguna-6000</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#" class="mtitle">MP DTE Directorate</a>
                            <ul class="sub-menu">
                                <li><a href="{{ url('/application/forwarded-list') }}">List for MP DTE Approval</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="mtitle">Sticker Report</a>
                            <ul class="sub-menu">
                                <li><a href="{{ url('/delivery-report') }}"> Reports sent to MP DTE Directorate</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="mtitle">Sticker Invoice</a>
                            <ul class="sub-menu">
                                <li><a href="{{ url('/invoice-list') }}">Invoice List</a></li>
                                <li><a href="{{ url('/invoice-report') }}">Invoice Report</a></li>
                                <li><a href="{{ url('/bank-deposit') }}">Bank Deposit </a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="mtitle">SMS Panel</a>
                            <ul class="sub-menu">
                                <li><a href="{{ url('/sms-panel') }}">SMS Panel</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                @if (Auth::check() && Auth::user()->role == 'super-admin')
                    <div class="widget left-widget">
                        <h3 class="widget-title">Website Menu</h3>
                        <ul class="sidebar-menu">

                            <li>
                                <a href="#" class="mtitle">Home Page</a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{{ route('home-page.index') }}"> List</a>
                                    </li>
                                    {{-- <li>
                                              <a href="{{ route('home-page.add')}}"> Add </a>
                                           </li> --}}
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="mtitle">Notice</a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{{ route('website_notice') }}"> All Notice</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('website_notice.add') }}"> Add Notice</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="mtitle">Header & Footer </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{{ route('header_footer') }}"> Header & Footer Contents </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="mtitle"> Slogan </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{{ route('slogan.add') }}"> Add Slogan </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="mtitle">Slider </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{{ route('slider.add') }}"> Add Slider </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('slider.list') }}"> Slider List </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="mtitle">Present Commander </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{{ route('present_commander.add') }}"> Add Commander Info </a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="#" class="mtitle">Ex Commander </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{{ route('excomd.add') }}"> Add Ex Commander Info </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('excomd.list') }}"> Ex Commander List </a>
                                    </li>
                                </ul>
                            </li>


                            <li>
                                <a href="#" class="mtitle">MIL OFFRS </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{{ route('major.add') }}"> Add MIL OFFRS </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('major.list') }}"> MIL OFFRS List</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="mtitle">Photo Gallery </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{{ route('photo_gallery.add') }}"> Add Photo </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('photo_gallery.list') }}"> Photo List</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="mtitle"> PDF Files </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{{ route('pdf_file.add') }}"> Add PDF File </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('pdf_file.list') }}"> PDF File List</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="mtitle"> Fixed Files </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{{ route('fixed_file.add') }}"> Add Fixed Files </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="mtitle"> Vehicle Type </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{{ route('vehicle-type.index') }}"> All Vehicle Types </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="mtitle"> Sticker Category </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{{ route('registration-number.index') }}"> All Sticker Categories
                                            registration Number </a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="#" class="mtitle"> Contact Number </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="{{ route('contact.add') }}"> Contact Number Add</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('contact.list') }}"> Contact Number List</a>
                                    </li>
                                </ul>
                            </li>


                        </ul>
                    </div>
                @endif


                <!--  <div class="widget left-widget">
                        <h3 class="widget-title"><i class="fa fa-bell"></i> Notice</h3>
                        <ul class="sidebar-menu">
                            <li><a href="{{ url('/tender-foot-over-bridge') }}"><i class="fa fa-external-link-alt"></i> Tender Foot-over Bridge</a></li>
                            <li><a href="{{ url('/tender-notice') }}"><i class="fa fa-external-link-alt"></i> Tender Notice</a></li>
                        </ul>
                    </div>

                    <div class="widget left-widget">
                        <h3 class="widget-title"><i class="fa fa-newspaper"></i> News & Events</h3>
                    </div>

                    <div class="widget left-widget">
                        <h3 class="widget-title"><i class="fa fa-gavel"></i> Policy</h3>
                        <ul class="sidebar-menu">
                            <li><a href="{{ url('/policy') }}"><i class="fa fa-external-link-alt"></i> Sticker Policy</a></li>
                            <li><a  href="http://stahqdhaka.org.bd/assets/pdf/graviyard-policy.pdf" target="_blank"><i class="fa fa-external-link-alt"></i> Graveyard Policy</a></li>
                        </ul>
                    </div> -->

                <!--   <div class="widget left-widget">
                        <h3 class="widget-title"><i class="fa fa-pen-square"></i> Forms</h3>
                        <ul class="sidebar-menu">
                            <li><a href="{{ asset('assets/pdf/-2033193580-SchoolCertificate_1.pdf') }}" target="_blank"><i class="fa fa-external-link-alt"></i> School Certificate Form</a></li>
                            <li><a href="{{ asset('assets/pdf/1727069855-JobCertificate_1.pdf') }}" target="_blank"><i class="fa fa-external-link-alt"></i> Job Certificate</a></li>
                            <li><a href="{{ asset('assets/pdf/240823335-HouseOwnerCertificate_1.pdf') }}" target="_blank"><i class="fa fa-external-link-alt"></i> House Owner/CEO Certificate</a></li>
                            <li><a href="{{ asset('assets/pdf/531393775-WardCommCertificate_1.pdf') }}" target="_blank"><i class="fa fa-external-link-alt"></i> Ward Commissioner Certificate</a></li>
                            <li><a href="{{ asset('assets/pdf/-902561939-ApplicationForm_1.pdf') }}" target="_blank"><i class="fa fa-external-link-alt"></i> Graveyard Application Form</a></li>
                            <li><a href="{{ asset('assets/pdf/1846078746-FreedomFighterCertificate_1.pdf') }}" target="_blank"><i class="fa fa-external-link-alt"></i> Freedom Fighter Certificate (Sample)</a></li>
                            <li><a href="{{ asset('assets/pdf/-452755646-DeathCertificate_1.pdf') }}" target="_blank"><i class="fa fa-external-link-alt"></i> Death Certificate (Sample)</a></li>
                        </ul>
                    </div> -->
            </div>

            <!-- <style media="screen">
              a.active-menu {
                background-color: #eee;
              }
            </style> -->
