<?php
session_start();

session_regenerate_id();

if(!empty($_SESSION['user_login']))
{
    header('location:http://'.$_SERVER['HTTP_HOST'].'/index.php');
    exit;
}

require($_SERVER['DOCUMENT_ROOT'].'/include/config.inc.php');

$output = '';

$sql = mysqli_connect($app_sqlhost,$app_sqluser,$app_sqlpasswd,$app_sqldb);

if(!$sql)
{
    $output .= '<div class="panel red">';
    $output .= '<p>Es konnte keine Datenbankverbindung hergestellt werden.</p>';
    $output .= '</div>';
}
else
{
    $showform = 1;

    $output .= '<h1>Loggen Sie sich ein</h1>';

    if(!empty($_POST))
    {
        if(empty($_POST['user_uid']) || empty($_POST['user_password']))
        {
            $output .= '<div class="panel red">';
            $output .= '<p>Login nicht erfolgreich.</p>';
            $output .= '</div>';
        }
        else
        {
            if(preg_match('/[^a-zA-Z0-9]/',$_POST['user_uid']) == 0)
            {
                if(strlen($_POST['user_password']) >= 10)
                {
                    $query = sprintf("
                    SELECT user_id,user_password,user_salt
                    FROM user
                    WHERE user_uid = '%s';",
                    $sql->real_escape_string($_POST['user_uid']));

                    $result = $sql->query($query);

                    if($row = $result->fetch_array(MYSQLI_ASSOC))
                    {
                        require($_SERVER['DOCUMENT_ROOT'].'/include/strhash.inc.php');

                        $post_password = strhash($row['user_salt'].$_POST['user_password']);

                        if($row['user_password'] == $post_password)
                        {
                            require($_SERVER['DOCUMENT_ROOT'].'/include/randomstr.inc.php');

                            session_start();

                            $user_token = randomstr(10);

                            $_SESSION = array('user_login' => true,'user_id' => $row['user_id'],'user_token' => $user_token);

                            header('location:http://'.$_SERVER['HTTP_HOST'].'/index.php');
                            exit;
                        }
                        else
                        {
                            $output .= '<div class="panel red">';
                            $output .= '<p>Login nicht erfolgreich.</p>';
                            $output .= '</div>';
                        }
                    }
                    else
                    {
                        $output .= '<div class="panel red">';
                        $output .= '<p>Login nicht erfolgreich.</p>';
                        $output .= '</div>';
                    }
                }
                else
                {
                    $output .= '<div class="panel red">';
                    $output .= '<p>Ihr Passwort ist mind. 10 Zeichen lang.</p>';
                    $output .= '</div>';
                }
            }
            else
            {
                $output .= '<div class="panel red">';
                $output .= '<p>Die UID besteht nur aus folgenden Zeichen a-z, A-Z, 0-9.</p>';
                $output .= '</div>';
            }
        }
    }

    if($showform)
    {
        $output .= '<form action="login.php" method="post">';
        $output .= '<h2>UID</h2>';
        $output .= '<p><input autofocus="true" class="input-default border border-white focus-border-orange" name="user_uid" placeholder="UID"/></p>';
        $output .= '<h2>Passwort</h2>';
        $output .= '<p><input class="input-default border border-white focus-border-orange" name="user_password" placeholder="Passwort"/></p>';
        $output .= '<p><button type="submit" class="block btn-default border border-orange orange hover-light-black focus-light-black">einloggen <i class="fas fa-arrow-right"></i></button></p>';
        $output .= '</form>';
    }
}
?>
<!DOCTYPE HTML>
<html lang="de">
    <head>
        <title>Pinhead Larry #Login</title>
        <?php
        require($_SERVER['DOCUMENT_ROOT'].'/include/head.inc.php');
        ?>
    </head>
    <body>
        <div class="app-container container">
            <div class="center-container" style="margin-top:10vh;margin-bottom:10vh;max-width:500px;">
                <div class="container black">
                <?php
                if(!empty($output))
                {
                    echo $output;
                }
                ?>
                </div>
            </div>
        </div>
    </body>
</html>