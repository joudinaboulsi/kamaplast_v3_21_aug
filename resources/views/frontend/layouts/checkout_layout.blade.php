 <!DOCTYPE html>
<html>
<head>
	<title>@yield('title')</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}" />

	<!-- Bootstrap core CSS -->
    <link href="/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Buttons CSS -->
    <link href="/css/btn.css" rel="stylesheet">
    <!-- sweetalert CSS -->
    <link href="/cms/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <!-- Datepicker CSS -->
    <link href="/cms/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <!-- Checkout css -->
    <link href="/css/checkout.css" rel="stylesheet">
    <!-- include pace script for automatic web page progress bar  -->

    <link href="../cms/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">
    
    <script src="/js/jquery/jquery-2.1.3.min.js"></script>
    
    <meta name="google-site-verification" content="ZCINyClFDq7YfixMVeL1nxg6Zg_FYjzmOCA1n5WmREU" />
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-Z77PXEPEPL"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-Z77PXEPEPL');
    </script>
    
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '725247858715469');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=725247858715469&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->

    <meta name="facebook-domain-verification" content="9oqdkwjbwfhw6a2jlbvscr00gdxrvu" />
</head>

<body>

    @yield('content')

    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/bootstrap/js/bootstrap.min.js"></script>

    <!-- Sweet Alert -->
    <script src="/cms/js/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Date picker -->
    <script src="/cms/js/plugins/datapicker/bootstrap-datepicker.js"></script>

    <script src="/js/checkout/apply_promo_code.js"></script>
    <script src="/js/checkout/shipping_address.js"></script>

</body>

</html>
