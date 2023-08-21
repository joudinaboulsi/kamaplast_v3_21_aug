<!DOCTYPE html>
<html>

<head>

   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title>NEOCOMZ CMS | Login</title>

   <link href="/cms/css/bootstrap.min.css" rel="stylesheet">
   <link href="/cms/font-awesome/css/font-awesome.css" rel="stylesheet">

   <link href="/cms/css/animate.css" rel="stylesheet">
   <link href="/cms/css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

   <div class="middle-box text-center loginscreen animated fadeInDown">
       <div>
           <div>
              <!--  <h1 class="logo-name"><img width="70%;" src=""></h1> -->
           </div>
           <h3>Welcome to NEOCOMZ CMS</h3>
           <p>This platform that allows you to manage your e-commerce</p>
           <p>Login in using your credentials.<br><br></p>

<!--          <form class="m-t" method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
 -->          <form class="form-horizontal" role="form" method="POST" action="{{ route('admin_login_path') }}"  aria-label="{{ __('Login') }}">
                  @csrf

                  <div class="form-group">
                          <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="Email Address" required>

                          @if ($errors->has('email'))
                              <span class="invalid-feedback" role="alert">
                                  <strong class="text-danger">{{ $errors->first('email') }}</strong>
                              </span>
                          @endif
                  </div>

                  <div class="form-group">
                          <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password" required>

                          @if ($errors->has('password'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('password') }}</strong>
                              </span>
                          @endif
                  </div>

                  <div class="form-group row mb-0">
                      <div class="col-md-12">
                          <button type="submit" class="btn btn-primary block full-width m-b">
                              {{ __('Login') }}
                          </button>
                      </div>
                  </div>
              </form>
                
           <p class="m-t"> <small><a target="_blank" href="https://www.webneoo.com"> Webneoo </a> Neocomz - All rights reserved &copy; 2019</small> </p>
       </div>
   </div>

   <!-- Mainly scripts -->
   <script src="cms/js/jquery-2.1.1.js"></script>
   <script src="cms/js/bootstrap.min.js"></script>

</body>

</html>