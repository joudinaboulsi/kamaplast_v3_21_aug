@extends('frontend.layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 no-padding">
            <div class="form-wrapper">

                <div class="about-content-text text-center">
                    <h3>Sign In</h3>
                </div>

                <div class="my-form">
                    <div class="col-md-6 hidden-xs hidden-sm no-padding" style="background: url(/images/account.jpg); background-position: center; background-size: cover;">
                        <!-- <div id="carousel-id" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carousel-id" data-slide-to="0" class=""></li>
                                <li data-target="#carousel-id" data-slide-to="1" class=""></li>
                                <li data-target="#carousel-id" data-slide-to="2" class="active"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="item">
                                    <img src="https://cdn2.iconfinder.com/data/icons/thesquid-ink-40-free-flat-icon-pack/64/envelope-2-128.png" class="img-responsive center-block" alt="">
                                    <div class="container">
                                        <div class="carousel-caption">
                                            <p>display properly due to web browser security rules.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <img src="https://cdn2.iconfinder.com/data/icons/thesquid-ink-40-free-flat-icon-pack/64/cup-128.png" class="img-responsive center-block" alt="">
                                    <div class="container">
                                        <div class="carousel-caption">
                                            <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="item active">
                                    <img src="https://cdn2.iconfinder.com/data/icons/thesquid-ink-40-free-flat-icon-pack/64/headphone-big-128.png" class="img-responsive center-block" alt="">
                                    <div class="container">
                                        <div class="carousel-caption">
                                            <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <div class="col-md-6 col-xs-12 form-bg">
                        <div class="main-form">

                            <form class="logForm m-t" method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                                @csrf

                                <div class="form-group">
                                    <label>Email address</label>
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="Email Address" required>
                                     @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong class="text-danger">{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password" required>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <p><a title="Recover your forgotten password" href="{{ url('/password/reset') }}">Forgot your password? </a></p>
                                </div>

                                <button type="submit" class="btn btn-stroke thin btn-dark" value="Sign In"> Sign In </button>
                            </form>

                            <hr>

                            <a href="/register" class="btn btn-stroke dark thin">Don't have an account?</a>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


@endsection