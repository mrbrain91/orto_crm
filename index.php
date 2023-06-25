<?php
include('settings.php');
include('bot_lib.php');
if(login($connect)){
	$text = login($connect);
}





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>ortopharm</title>
</head>
<body style="background-color: #2B3643;">
    
    <section class="login-form">
        <span style="color:red;">
		<?php
		    if (isset($text)) {
				echo $text;
				}
		?>		  
		</span>
        <img src="icons/logo_or.png" alt="logo" class="img_logo">
        <form action="index.php" method="POST">
            <div class="mb-3">
              <input type="text" name="username" placeholder="Логин" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
               <input type="password" name="pass" placeholder="Парол" class="form-control" id="exampleInputPassword1">
            </div>
            <button type="submit" name="submit_log" class="btn btn-primary login_btn">Войти</button>
        </form>

    </section>
</body>
</html>
