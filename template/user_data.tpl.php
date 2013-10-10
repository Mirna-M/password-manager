<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" media="screen" href="css/user_data_style.css"/>
        <title>Password Manager</title>
        <script type='text/javascript' src='js/jquery-1.10.2.js'></script>
        <script type='text/javascript' src='js/script.js'></script>
    </head>
    <body>
        <div id=frame>
            <table id=storage>
                <tr id=storage_title>
                    <td>DATA FOR</td>
                    <td>USER NAME</td>
                    <td>USER PASSWORD</td>
                    <td>MODIFY</td>
                </tr>
                <?php foreach ($this->rows as $row): ?>
                <tr class="row" user-data-id="<?php echo $row['user_data_id']; ?>">
                    <td class="save_user_data_for">
                        <?php echo $row['user_data_for']; ?>
                    </td>
                    <td class="save_user_data_name">
                        <?php echo $row['user_data_name']; ?>  
                    </td>
                    <td class="save_user_data_password">
                        <?php echo $row['user_data_password']; ?>  
                    </td>
                    <td class="modify"><a class="edit" href="">Edit</a> | <a class="delete" href="">Delete</a></td>
                <tr class="input_row" user-data-id="<?php echo $row['user_data_id']; ?>">
                    <td>
                        <label for="user_data_for">Data for</label>
                        <input class="save_user_data_for" type="text" name="user_data_for" value="<?php echo $row['user_data_for']; ?>">
                    </td>
                    <td>
                        <label for="user_data_name">User name</label>
                        <input class="save_user_data_name" type="text" name="user_data_name" value="<?php echo $row['user_data_name']; ?>">
                    </td>
                    <td>
                        <label for="user_data_password">Password</label>
                        <input class="save_user_data_password" type="text" name="user_data_password" value="<?php echo $row['user_data_password']; ?>">
                    </td>
                    <td class="modify"><a class="save" href="">Save</a> | <a class="delete" href="">Delete</a></td>
                </tr>
                <?php endforeach; ?>
                <tr id="add_row">
                    <td>
                        <label for="user_data_for">Data for</label>
                        <input id="add_user_data_for" type="text" name="user_data_for" value="" placeholder="Data for...">
                    </td>
                    <td>
                        <label for="user_data_name">User name</label>
                        <input id="add_user_data_name" type="text" name="user_data_name" value="" placeholder="User name...">
                    </td>
                    <td>
                        <label for="user_data_password">Password</label>
                        <input id="add_user_data_password" type="text" name="user_data_password" value="" placeholder="Password...">
                    </td>
                    <td class="modify"><a id="add" href="">Add</a></td>
                </tr>
            </table>
        </div>
    </body>
</html>