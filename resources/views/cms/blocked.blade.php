
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Notes - Admin | Blocked</title>

	<!-- Google font -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Chango" rel="stylesheet">

	<!-- Custom stlylesheet -->
<link type="text/css" rel="stylesheet" href="{{asset('blocked/style.css')}}" />

</head>

<body>
	<div id="notfound">
		<div class="notfound">
			<div>
				<div class="notfound-404">
					<h1>!</h1>
				</div>
				<h2>Account<br>Blocked</h2>
			</div>
            <p>Your account has been blocked, you don't have access to Notes CMS system <a href="{{route('cms.student.login_view')}}">Back to Login page</a></p>
		</div>
	</div>
</body>

</html>
