<html>
    <head>
        <title>Password Manager - Login</title>
    </head>
    <body>
        <form action="" method="POST">
            <label for="user_name">Username: </label>
            <input type="text" name="user_name">
            <label for="password">Password: </label>
            <input type="password" name="password">
            <input type="submit" value="Login!">
        </form>
        <a href="index.php?where=register">Register!</a>
        <?php
        if(isset($this->message)) {
            echo $this->message;
        }
        ?>
    </body>
</html>
