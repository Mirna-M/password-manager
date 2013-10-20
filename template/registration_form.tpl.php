<html>
    <head>
        <link rel="stylesheet" type="text/css" media="screen" href="css/register_style.css"/>
        <title>Password Manager - Register</title>
        <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div class="page_wrraper">
            <div id="register_wrraper">
                <form action="" method="POST">
                    <div class="align_right">
                        <a id="login" href="index.php?where=login">Login</a>
                    </div>
                    <label for="user_name">Username: </label>
                    <input id="first_line" type="text" name="user_name" placeholder="Username...">
                    <label for="password">Password: </label>
                    <input type="password" name="password" placeholder="Password..."><br>
                    <label for="reenter_password">Retype password: </label>
                    <input type="password" name="reenter_password" placeholder="Retype password..."><br>
                    <input id="button" type="submit" value="Register!">
                </form>
            </div>
                <?php
                if(isset($this->message)) {
                ?>
                    <div class="warning_image">
                    <img src="images/warning_blue_high_contrast.png" >
                    </div>
                    <div class="message">
                        <?php
                        echo $this->message;
                        ?>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </body>
</html>

