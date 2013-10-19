<html>
    <head>
        <link rel="stylesheet" type="text/css" media="screen" href="css/login_style.css"/>
        <title>Password Manager - Login</title>
    </head>
    <body>
        <div class="page_wrraper">
            <div id="login_wrraper">
                <div class="align_right">
                    <a id="register" href="index.php?where=register">Register</a>
                </div>
                <form action="" method="POST">
                    <label for="user_name">Username: </label>
                    <input id="first_line" type="text" name="user_name" placeholder="Username...">
                    <label for="password">Password: </label>
                    <input type="password" name="password" placeholder="Password...">
                    <input id="button" type="submit" value="Login!">
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
    </body>
</html>
