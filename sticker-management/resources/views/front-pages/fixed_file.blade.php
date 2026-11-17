@extends('layouts.master')
@section('content')
    @include('layouts.header')
    @include('layouts.new_website.menubar')
    @include('layouts.new_website.buliten')

    <div class="widgets single-page-content-wrap">
        {{-- Removed container/row/col constraints so the file can take the full page --}}
        <div class="page-content" style="width: 100%;">

            <h3 class="page-title text-center py-3"> {{ $title }} </h3>

            <div class="entry-content">
                @if (!empty($fixed_file))
                    <div class="file-container"
                        style="width: 100%; height: 90vh; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                        @php
                            $extension = pathinfo($fixed_file, PATHINFO_EXTENSION);
                        @endphp

                        @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']))
                            <!-- For image files -->
                            <img src="{{ asset('assets/images/fixed_files/' . $fixed_file) }}" alt="image"
                                style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        @elseif ($extension === 'pdf')
                            <!-- For PDF files -->
                            <object data="{{ asset('assets/images/fixed_files/' . $fixed_file) }}" type="application/pdf"
                                style="width: 100%; height: 100%;">
                                <a href="{{ asset('assets/images/fixed_files/' . $fixed_file) }}" target="_blank">Click here
                                    to view the PDF</a>
                            </object>
                        @else
                            <!-- Default fallback for unsupported file types -->
                            <p>Unsupported file type: {{ $fixed_file }}</p>
                        @endif
                    </div>
                @else
                    <div style="text-align: center; font-size: 30px; color: red; padding: 100px 0;">
                        There are no file for view. !!!
                    </div>
                @endif
            </div>

        </div>
    </div>

@endsection
