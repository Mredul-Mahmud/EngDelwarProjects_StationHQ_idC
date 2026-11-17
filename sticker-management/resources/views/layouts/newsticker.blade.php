<section class="p3" style="background-color: #666666">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-md-12 {{ $highLightNotice ? 'col-md-10' : '' }}" style=""> 
                <marquee style="font-size: 1.5rem; font-weight: bold;" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();" class="bulletin">
                    <ul class="d-flex justify-content-center align-items-center mb-0">
                        @foreach ($notices as $index => $notice)
                            @if ($notice->status == 1)
                                <li class="me-5" style="color: {{ $index % 2 == 0 ? '#64df1c' : '#00B050' }}; font-size: 2rem;">
                                    @if ($notice->link_active == 1)
                                        @if ($notice->link_active == 1 && $notice->slug)
                                          <a href="{{ route('notice_details_slug', ['slug' => $notice->slug]) }}" style="color: inherit; text-decoration: none;">{{ $notice->title }}</a>
                                      @else
                                          {{ $notice->title }}
                                      @endif
                                    @else
                                        {{ $notice->title }}
                                    @endif
                                    @if (!$loop->last) 
                                       
                                        <span class="p-2" style="color: #df631c;">*</span> <!-- Asterisk separator -->
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    
                </marquee>
            </div>
        </div>
    </div>
</section>