@extends("layouts.app")
@section('title', $pagesDetail->page_meta_title ?? DEFAULT_META_TITLE)
@section('keywords', $pagesDetail->page_meta_keyword ?? DEFAULT_META_KEYWORD)
@section('description', $pagesDetail->page_meta_desc ?? DEFAULT_META_DESCRIPTION)
@section('canonical', 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?? '')
@section("content")
    <!-- Breadcrumb -->
    <div class="breadcrumb breadcrumb-list mb-0">
        <span class="primary-right-round"></span>
        <div class="container">
            <h1 class="text-white">{{ $pagesDetail->page_title ?? '' }}</h1>
            <ul>
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>{{ $pagesDetail->page_title ?? '' }}</li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12 offset-sm-12 offset-md-1 col-md-10 col-lg-10">
                    <div class="ask-questions">
                        <div class="faq-info">
                            <div class="accordion" id="accordionExample">
                                <!-- FAQ Item -->
                                @if(is_array($faqDetail) && count($faqDetail) > 0)
                                    @for($f=0; $f < count($faqDetail); $f++)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $f+1 }}">
                                                <a href="javascript:;" class="accordion-button {{ ($f == 0) ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapse{{ $f+1 }}" aria-expanded="{{ ($f == 0) ? 'true' : 'false' }}" aria-controls="collapse{{ $f+1 }}">
                                                    {{ $faqDetail[$f]['faq_title'] }}
                                                </a>
                                            </h2>
                                            <div id="collapse{{ $f+1 }}" class="accordion-collapse collapse {{ ($f == 0) ? 'show' : '' }}" aria-labelledby="heading{{ $f+1 }}" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="accordion-content">
                                                        {!! $faqDetail[$f]['faq_desc'] !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                @endif
                                <!-- /FAQ Item -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection
@section('page-js')
    <script type="text/javascript">
        $(document).ready(function () {
            AOS.init({
                duration:1200,
                once:true
            });
        });
    </script>
@endsection