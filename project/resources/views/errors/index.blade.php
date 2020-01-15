<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Oops! Page Not Found.</title>

	<!-- Google font -->
	<link href="https://fonts.googleapis.com/css?family=Cabin:400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:900" rel="stylesheet">

	<!-- Custom stlylesheet -->
	<link type="text/css" rel="stylesheet" href="{{ asset('css/style.css') }}" />

</head>

<body>

	<div id="notfound">
		<div class="notfound">
			<div class="notfound-404">
				<h3>Oops! Page not found</h3>
				<h1><span>4</span><span>0</span><span>4</span></h1>
			</div>
			<h2>we are sorry, but the page you requested was not found</h2>
			<div id="countdown"></div>
		</div>
	</div>

</body>

</html>

<?php header( "refresh:4;url=/" ); ?>

<script type="text/javascript">
	var timeleft = 3;
	var downloadTimer = setInterval(function(){
	document.getElementById("countdown").innerHTML = "Redirecting in " +timeleft + " seconds..";
	timeleft -= 1;
	if(timeleft < 0){
		clearInterval(downloadTimer);
		document.getElementById("countdown").innerHTML = "Loading.."
	}
	}, 1000);
</script>
