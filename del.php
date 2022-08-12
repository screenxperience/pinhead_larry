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
    if(!empty($_GET))
    {
        if(empty($_GET['post_id']))
        {
            $output .= '<div class="container">';
            $output .= '<div class="panel black">';
            $output .= '<div class="panel red">';
            $output .= '<p>Es wurde keine ID gesendet.</p>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
        }
        else
        {
            if(preg_match('/[^0-9]/',$_GET['post_id']) == 0)
            {
                $query = sprintf("
                DELETE
                FROM post
                WHERE post_id = '%s';",
                $sql->real_escape_string($_GET['post_id'])); 
                
                $sql->query($query);

                if($sql->affected_rows == 1)
                {
                    $output .= '<div class="container">';
                    $output .= '<div class="panel black">';
                    $output .= '<div class="panel cyan">';
                    $output .= '<p>Der Post wurde erfolgreich gel&ouml;scht.</p>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                }
                else
                {
                    $output .= '<div class="container">';
                    $output .= '<div class="panel black">';
                    $output .= '<div class="panel red">';
                    $output .= '<p>Es konnte kein Post gel&ouml;scht werden.</p>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                }
            }
            else
            {
                $output .= '<div class="container">';
                $output .= '<div class="panel black">';
                $output .= '<div class="panel red">';
                $output .= '<p>Die ID besteht nur aus Zahlen.</p>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';
            }
        }
    }
    else
    {
        $output .= '<div class="container">';
        $output .= '<div class="panel black">';
        $output .= '<div class="panel red">';
        $output .= '<p>Es wurden keine Daten gesendet.</p>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
    }

    $returnto = 'http://'.$_SERVER['HTTP_HOST'].'/index.php';

    $output .= "<script>ch_location('".$returnto."',2);</script>"; 
}
?>
<!DOCTYPE HTML>
<html lang="de">
    <head>
        <title>Pinhead Larry #Del</title>
        <?php
        require($_SERVER['DOCUMENT_ROOT'].'/include/head.inc.php');
        ?>
    </head>
    <body>
        <div class="app-container">
        <?php
        if(!empty($output))
        {
            echo $output;
        }
        ?>
        </div>
    </body>
</html>