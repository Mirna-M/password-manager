<html>
    <head>
        <title>Password Manager - Register</title>
    </head>
    <body>
        <form action="" method="POST">
            <label for="user_name">Nickname: </label>
            <input type="text" name="user_name">
            <label for="password">Password: </label>
            <input type="password" name="password">
            <label for="reenter_password">Retype password: </label>
            <input type="password" name="reenter_password">
            <input type="submit" value="Register!">
        </form>
        <?php
        if(isset($this->message)) {
            echo $this->message;
        }
        ?>
    </body>
</html>

