                @extends('layouts.master')
                @section('content')
                    @include('layouts.header')
                    @include('layouts.newsticker')

                     <div class="widgets single-page-content-wrap section-padding">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-8"> <!-- 80% approx of container -->
                                    <div class="card d-flex flex-column m-2 w-100">
                                        <div class="card-body flex-grow-1 d-flex flex-column">
                                            <div class="widget left-widget flex-grow-1 d-flex flex-column">
                                                <h3 class="widget-title"><i class="fa fa-gavel"></i> Notice</h3>
                                                <ul class="widget-menu flex-grow-1">
                                                    @foreach ($pdf_files as $pdf_file)
                                                        @if ($pdf_file->type == 'notice')
                                                            <li>
                                                                <a style="font-size: 20px"
                                                                    href="{{ asset('assets/pdf_files/' . $pdf_file->file) }}"
                                                                    target="_blank"><i style="font-size: 20px" class="fa fa-external-link-alt"></i>
                                                                    {{ $pdf_file->name }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endsection
