@extends($theme.'layouts.login_register')
@section('title',$page_title)

@section('content')
    @include($theme.'auth.verifyImage')
    <section class="login-signup-page pt-0 pb-0 min-vh-100 h-100">
        <div class="container-fluid h-100">
            <div class="row min-vh-100">
                <div class="col-md-6 p-0">
                    <div class="login-signup-thums h-100">
                        <div class="content-area">
                            <div class="logo-area mb-30">
                                <a href="{{url('/')}}">
                                    <img class="logo"
                                         src="{{getFile(basicControl()->dark_logo_driver,basicControl()->dark_logo)}}" alt="...">
                                </a>
                            </div>
                            <div class="middle-content">
                                <h3 class="section-title">Admin Fee verification here!</h3>
                                <p>We ensure account security with a quick and simple admin fee verification process. Stay protected, stay connected.</p>
                            </div>
                            @include($theme.'auth.socialIcon')
                        </div>
                    </div>
                </div>
                <div class="col-md-6 p-0 d-flex justify-content-center flex-column">
                    <div class="login-signup-form">
                        <form action="{{ route('user.mailVerify') }}" method="post">
                            @csrf
                            <div class="section-header">
                                <h3>Admin Fee Verification Here!</h3>
                                <div
                                    class="description">Your exchange account is under verification, We want to make sure you paid the admin fee before accessing the TBC exchange. Try to login again after an hour or 3 hours.</div>
                            </div>
                            <!-- <div class="row g-4">
                                <div class="col-12">
                                    <input type="text" name="code" class="form-control"
                                           id="exampleInputEmail1"
                                           placeholder="TBC009 wallet username.">
                                    @error('code')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div> -->

                            <button type="submit" class="cmn-btn mt-30 w-100">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
