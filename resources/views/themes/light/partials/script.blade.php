<script src="{{ asset($themeTrue . 'js/jquery-3.6.1.min.js') }}"></script>
<script src="{{ asset('assets/global/js/notiflix-aio-3.2.6.min.js') }}"></script>
<script src="{{ asset($themeTrue . 'js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/global/js/axios.min.js') }}"></script>

@stack('js-lib')
<script src="{{ asset($themeTrue . 'js/owl.carousel.min.js') }}"></script>
<script src="{{ asset($themeTrue . 'js/swiper-bundle.min.js') }}"></script>
<script src="{{ asset($themeTrue . 'js/slick.min.js') }}"></script>
<script src="{{ asset($themeTrue . 'js/select2.min.js') }}"></script>
<script src="{{ asset($themeTrue . 'js/nouislider.min.js') }}"></script>
<script src="{{ asset($themeTrue . 'js/parallax-scroll.js') }}"></script>

<script src="{{ asset($themeTrue . 'js/main.js') }}"></script>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/66c25dd8146b7af4a43bd0b1/1i5jjt6b0';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
@stack('script')

@php
    $routeName = \Illuminate\Support\Facades\Route::currentRouteName();
@endphp
@if (
    !in_array($routeName, [
        'login',
        'register',
        'password.confirm',
        'password.email',
        'password.request',
        'password.reset',
    ]))
    @if ($errors->any())
        @php
            $collection = collect($errors->all());
            $errors = $collection->unique();
        @endphp
        <script>
            "use strict";
            @foreach ($errors as $error)
                Notiflix.Notify.failure("{{ trans($error) }}");
            @endforeach
        </script>
    @endif
@endif
