<?php
require($_SERVER['DOCUMENT_ROOT'].'/include/auth.inc.php');

require($_SERVER['DOCUMENT_ROOT'].'/include/config.inc.php');

$output = '';

$sql = mysqli_connect($app_sqlhost,$app_sqluser,$app_sqlpasswd,$app_sqldb);

if(!$sql)
{
    $output .= '<div class="container">';
    $output .= '<div class="panel black">';
    $output .= '<div class="panel red">';
    $output .= '<p>Es konnte keine Datenbankverbindung hergestellt werden.</p>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
}
else
{
    $query = sprintf("
    SELECT user_uid
    FROM user
    WHERE user_id = '%s';",
    $sql->real_escape_string($_SESSION['user_id']));

    $result = $sql->query($query);

    if($row = $result->fetch_array(MYSQLI_ASSOC))
    {
        $output .= '<div class="container">';
        $output .= '<div class="flex-container flex-wrap flex-base">';
        $output .= '<div class="col-s12 col-m6 col-l6">';
        $output .= '<h1>'.$row['user_uid'].'</h1>';
        $output .= '</div>';
        $output .= '<div class="col-s12 col-m6 col-l6 text-right">';
        $output .= '<a class="col-s4 btn-default border border-black black hover-light-black hover-border-orange focus-light-black focus-border-orange" href="add.php?category=bulletin_board"><i class="fas fa-plus"></i></a>';
        $output .= '<a class="col-s4 btn-default border border-black black hover-light-black hover-border-orange focus-light-black focus-border-orange" href="changepassword.php"><i class="fas fa-key"></i></a>';
        $output .= '<a class="col-s4 btn-default border border-black black hover-light-black hover-border-orange focus-light-black focus-border-orange" href="logout.php?token='.$_SESSION['user_token'].'"><i class="fas fa-sign-out-alt"></i></a>';
        $output .= '</div>';
        $output .= '</div>';
        
        $query = sprintf("
        SELECT bulletin_board_id,bulletin_board_name,bulletin_board_img
        FROM bulletin_board
        INNER JOIN persmission ON permission_user_id = 
        WHERE bulletin_board_user = '%s';",
        $sql->real_escape_string('%"'.$_SESSION['user_id'].'"%'));

        $result = $sql->query($query);

        $amount_g = mysqli_num_rows($result);

        if($amount_g > 0)
        {
            $output .= '<h2>Ihre Pinnw&auml;nde ( '.$amount_g.' )</h2>';
            $output .= '</div>';
            $output .= '<div class="flex-container flex-wrap">';

            while($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $output .= '<div class="margin">';
                $output .= '<div class="img-container square-300 display-container">';
                $output .= '<a href="bulletin_board.php?id='.$row['bulletin_board_id'].'"><img class="block" src="/images/'.$row['bulletin_board_img'].'"/></a>';
                $output .= '<div class="display-bottom-left block container black">';
                $output .= '<p>'.$row['bulletin_board_name'].'</p>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';
            }

            $output .= '</div>';
        }
        else
        {
            $output .= '</div>';
        }
    }
    else
    {
        session_destroy();

        $output .= '<div class="container">';
        $output .= '<div class="panel black">';
        $output .= '<div class="panel red">';
        $output .= '<p>Es konnte kein Account gefunden.</p>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
    }
}
?>
<!DOCTYPE HTML>
<html lang="de">
    <head>
        <title>Pinhead Larry #Home</title>
        <?php
        require($_SERVER['DOCUMENT_ROOT'].'/include/head.inc.php');
        ?>
    </head>
    <body>
        <div class="app-container scroll-container">
            <?php
            if(!empty($output))
            {
                echo $output;
            }
            ?>
        </div>
    </body>
</html>