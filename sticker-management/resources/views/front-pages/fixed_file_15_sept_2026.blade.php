@extends('layouts.master')
@section('content')
    @include('layouts.header')
    @include('layouts.new_website.menubar')
    @include('layouts.new_website.buliten')

        <div class="widgets single-page-content-wrap section-padding">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <!-- sidebar -->
                    {{-- @include('layouts.sidebar') --}}
                    <!-- page-content -->
                    <div class="col-lg-9 col-md-8 page-content">
                        <h3 class="page-title"> {{ $title }} </h3>

                        <div class="entry-content ">
                            @if (!empty($fixed_file))
                                <!-- <iframe src="{{asset('assets/images/fixed_files/'.$fixed_file)}}"></iframe> -->
                                <!-- <object data="{{asset('assets/images/fixed_files/'.$fixed_file)}}"  class="w-100 h-100" 
                                style="object-fit: cover;"></object> -->

                                <div class="file-container" style="width: 100%; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                                    @php
                                        $extension = pathinfo($fixed_file, PATHINFO_EXTENSION);
                                    @endphp
                                
                                    @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']))
                                        <!-- For image files -->
                                        <img src="{{ asset('assets/images/fixed_files/'.$fixed_file) }}" 
                                             alt="image" 
                                             class="w-100 h-100" 
                                             style="object-fit: contain; object-position: center;">
                                        
                                    @elseif ($extension === 'pdf')
                                        <!-- For PDF files -->
                                        <object data="{{ asset('assets/images/fixed_files/'.$fixed_file) }}" 
                                                type="application/pdf" 
                                                class="w-100 h-100" 
                                                style="object-fit: contain; object-position: center;">
                                            <a href="{{ asset('assets/images/fixed_files/'.$fixed_file) }}" target="_blank">Click here to view the PDF</a>
                                        </object>                     
                                    
                                
                                    @else
                                        <!-- Default fallback for unsupported file types -->
                                        <p>Unsupported file type: {{ $fixed_file }}</p>
                                    @endif
                                </div>
                            @else 
                                <div style="text-align: center;font-size: 30px;color: red;">
                                   There are no file for view. !!!
                                </div>    
                            @endif
                            
                        </div>                     
                    </div>
                </div>
            </div>        
        </div>

@endsection
