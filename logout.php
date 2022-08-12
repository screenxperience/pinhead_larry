<?php
require($_SERVER['DOCUMENT_ROOT'].'/include/auth.inc.php');

$output = '';

if(!empty($_GET))
{
    if(empty($_GET['token']))
    {
        $output .= '<div class="container">';
        $output .= '<div class="panel black">';
        $output .= '<div class="panel red">';
        $output .= '<p>Sie konnten nicht ausgeloggt werden.</p>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
    }
    else
    {
        if(preg_match('/[^a-zA-Z0-9]/',$_GET['token']) == 0)
        {
            if($_GET['token'] == $_SESSION['user_token'])
            {
                session_destroy();

                $output .= '<div class="container">';
                $output .= '<div class="panel black">';
                $output .= '<div class="panel cyan">';
                $output .= '<p>Sie wurden erfolgreich ausgeloggt.</p>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';
            }
            else
            {
                $output .= '<div class="container">';
                $output .= '<div class="panel black">';
                $output .= '<div class="panel red">';
                $output .= '<p>Sie konnten nicht ausgeloggt werden.</p>';
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
            $output .= '<p>Sie konnten nicht ausgeloggt werden.</p>';
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
    $output .= '<p>Sie konnten nicht ausgeloggt werden.</p>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
}
?>
<!DOCTYPE HTML>
<html lang="de">
    <head>
        <title>Pinhead Larry #Logout</title>
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