<?php
require($_SERVER['DOCUMENT_ROOT'].'/include/auth.inc.php');

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
    if(!empty($_GET))
    {
        if(empty($_GET['category']))
        {
            $output .= '<div class="panel red">';
            $output .= '<p>Es wurde keine Kategorie gesendet.</p>';
            $output .= '</div>';
        }
        else
        {
            if(preg_match('/[^a-z\_]/',$_GET['category']) == 0)
            {
                $allowed_categories = array('bulletin_board','pin');

                if(in_array($_GET['category'],$allowed_categories))
                {
                    if($_GET['category'] == $allowed_categories[0])
                    {
                        $showform = 1;
                        
                        $output .= '<h1>Pinnwand erstellen</h1>';

                        if(!empty($_GET['send']))
                        {
                            if(empty($_GET['bulletin_board_name']) || empty($_GET['token']))
                            {
                                $output .= '<div class="panel red">';
                                $output .= '<p>Es konnte keine Pinnwand angelegt werden.</p>';
                                $output .= '</div>';
                            }
                            else
                            {
                                if(preg_match('/[^a-zA-Z0-9]/',$_GET['token']) == 0)
                                {
                                    if($_SESSION['user_token'] == $_GET['token'])
                                    {
                                        if(preg_match('/[^a-zA-Z0-9öäüÖÄÜß\s\-\.]/',$_GET['bulletin_board_name']) == 0)
                                        {
                                            if(strlen($_GET['bulletin_board_name']) <= 20)
                                            {
                                                $images = array('bulletin_board_placeholder_red.svg','bulletin_board_placeholder_cyan.svg','bulletin_board_placeholder_orange.svg');

                                                $image_id = mt_rand(0,2);

                                                $image = $images[$image_id];

                                                $query = sprintf("
                                                INSERT INTO
                                                bulletin_board
                                                (bulletin_board_name,bulletin_board_img,bulletin_board_user_id)
                                                VALUES
                                                ('%s','%s','%s');",
                                                $sql->real_escape_string($_GET['bulletin_board_name']),
                                                $sql->real_escape_string($image),
                                                $sql->real_escape_string($_SESSION['user_id']));

                                                $sql->query($query);

                                                if($sql->affected_rows == 1)
                                                {
                                                    $output  = '<div class="panel cyan">';
                                                    $output .= '<p>Ihre Pinnwand wurde erfolgreich angelegt.</p>';
                                                    $output .= '</div>';

                                                    $showform = 0;

                                                    $returnto = 'http://'.$_SERVER['HTTP_HOST'].'/index.php';
                                                }
                                                else
                                                {
                                                    $output .= '<div class="panel red">';
                                                    $output .= '<p>Es konnte keine Pinnwand angelegt werden.</p>';
                                                    $output .= '</div>';
                                                }
                                            }
                                            else
                                            {
                                                $output .= '<div class="panel red">';
                                                $output .= '<p>Der Pinnwandname darf max. 20 Zeichen lang sein.</p>';
                                                $output .= '</div>';
                                            }
                                        }
                                        else
                                        {
                                            $output .= '<div class="panel red">';
                                            $output .= '<p>Verwenden Sie nur folgende Zeichen f&uuml;r den Pinnwandname: a-z, A-Z, 0-9, öäüÖÄÜß-.</p>';
                                            $output .= '</div>';
                                        }
                                    }
                                    else
                                    {
                                        $output .= '<div class="panel red">';
                                        $output .= '<p>Der gesendete Token ist nicht bekannt.</p>';
                                        $output .= '</div>';
                                    }
                                }
                                else
                                {
                                    $output .= '<div class="panel red">';
                                    $output .= '<p>Der Token ist fehlerhaft.</p>';
                                    $output .= '</div>';
                                }
                            }
                        }
                        
                        if($showform)
                        {
                            $output .= '<form action="add.php" method="get">';
                            $output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
                            $output .= '<h2>Name</h2>';
                            $output .= '<p><input onkeyup="'."chk_inputlength('bulletinboardname',20)".';" id="bulletinboardname" autofocus="true" class="input-default border border-white focus-border-orange" name="bulletin_board_name" placeholder="Name"/></p>';
                            $output .= '<input type="hidden" name="token" value="'.$_SESSION['user_token'].'"/>';
                            $output .= '<input type="hidden" name="send" value="1"/>';
                            $output .= '<p><button type="submit" class="block btn-default border border-orange orange hover-light-black focus-light-black">senden <i class="fas fa-arrow-right"></i></button></p>';
                            $output .= '</form>';
                        }
                    }
                    else if($_GET['category'] == $allowed_categories[1])
                    {
                        if(empty($_GET['bulletin_board']))
                        {
                            $output .= '<div class="panel red">';
                            $output .= '<p>Es wurde keine Pinnwand gew&auml;hlt.</p>';
                            $output .= '</div>'; 
                        }
                        else
                        {
                            if(preg_match('/[^0-9]/',$_GET['bulletin_board']) == 0)
                            {
                                $query = sprintf("
                                SELECT 1
                                FROM permission
                                WHERE permission_bulletin_board_id = '%s'
                                AND permission_user_id = '%s';",
                                $sql->real_escape_string($_GET['bulletin_board']),
                                $sql->real_escape_string($_SESSION['user_id']));

                                $result = $sql->query($query);

                                if($row = $result->fetch_array(MYSQLI_ASSOC))
                                {
                                    $showform = 1;

                                    $output .= '<h1>Pin erstellen</h1>';

                                    if(!empty($_GET['send']))
                                    {
                                        if(empty($_GET['pin_title']) || empty($_GET['pin_description']) || empty($_GET['token']))
                                        {
                                            $output .= '<div class="panel red">';
                                            $output .= '<p>Es konnte kein Pin erstellt werden.</p>';
                                            $output .= '</div>';
                                        }
                                        else
                                        {
                                            if(preg_match('/[^a-zA-Z0-9]/',$_GET['token']) == 0)
                                            {
                                                if($_SESSION['user_token'] == $_GET['token'])
                                                {
                                                    if(preg_match('/[^a-zA-Z0-9öäüÖÄÜß\s\.\-]/',$_GET['pin_title']) == 0)
                                                    {
                                                        if(strlen($_GET['pin_title']) <= 20)
                                                        {
                                                            $exit = 0;
                                            
                                                            if(!empty($_GET['pin_description']))
                                                            {
                                                                if(preg_match('/[^a-zA-Z0-9öäüÖÄÜß\s\.\,\!\?\-\r\n]/',$_GET['pin_description']) != 0)
                                                                {
                                                                    $output .= '<div class="panel red">';
                                                                    $output .= '<p>Verwenden Sie nur folgende Zeichen in ihrer Beschreibung: a-z, A-Z, 0-9, öäüÖÄÜß.,!?-</p>';
                                                                    $output .= '</div>';
                                            
                                                                    $exit = 1;
                                                                }
                                                                else if(strlen($_GET['pin_description']) > 200)
                                                                {
                                                                    $output .= '<div class="panel red">';
                                                                    $output .= '<p>Verwenden Sie max. 200 Zeichen in ihrer Beschreibung.</p>';
                                                                    $output .= '</div>';
                                            
                                                                    $exit = 1;
                                                                }
                                                                else
                                                                {
                                                                    $pin_description = $_GET['pin_description'];
                                                                }
                                                            }
                                                            else
                                                            {
                                                                $pin_description = '-';
                                                            }

                                                            if(!$exit)
                                                            {
                                                                $query = sprintf("
                                                                INSERT INTO
                                                                pin
                                                                (pin_title,pin_description,pin_bulletin_board_id,pin_user_id)
                                                                VALUES
                                                                ('%s','%s','%s','%s');",
                                                                $sql->real_escape_string($_GET['pin_title']),
                                                                $sql->real_escape_string($pin_description),
                                                                $sql->real_escape_string($_GET['bulletin_board']),
                                                                $sql->real_escape_string($_SESSION['user_id']));

                                                                $sql->query($query);

                                                                if($sql->affected_rows == 1)
                                                                {
                                                                    $output  = '<div class="panel cyan">';
                                                                    $output .= '<p>Ihr Pin wurde erfolgreich erstellt.</p>';
                                                                    $output .= '</div>';

                                                                    $showform = 0;

                                                                    $returnto = 'http://'.$_SERVER['HTTP_HOST'].'/bulletin_board.php?id='.$_GET['bulletin_board'];
                                                                }
                                                                else
                                                                {
                                                                    $output .= '<div class="panel red">';
                                                                    $output .= '<p>Es konnte kein Pin erstellt werden.</p>';
                                                                    $output .= '</div>';
                                                                }
                                                            }
                                                        }
                                                        else
                                                        {
                                                            $output .= '<div class="panel red">';
                                                            $output .= '<p>Verwenden Sie max. 20 Zeichen f&uuml;r ihren Titel.</p>';
                                                            $output .= '</div>';
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $output .= '<div class="panel red">';
                                                        $output .= '<p>Verwenden Sie nur folgende Zeichen in ihrem Titel: a-z, A-Z, 0-9, öäüÖÄÜß.-</p>';
                                                        $output .= '</div>';
                                                    }
                                                }
                                                else
                                                {
                                                    $output .= '<div class="panel red">';
                                                    $output .= '<p>Der gesendete Token ist nicht bekannt.</p>';
                                                    $output .= '</div>';
                                                }
                                            }
                                            else
                                            {
                                                $output .= '<div class="panel red">';
                                                $output .= '<p>Der Token ist fehlerhaft.</p>';
                                                $output .= '</div>';
                                            }
                                        }
                                    }

                                    if($showform)
                                    {
                                        $output .= '<form action="add.php" method="get">';
                                        $output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
                                        $output .= '<input type="hidden" name="bulletin_board" value="'.$_GET['bulletin_board'].'"/>';
                                        $output .= '<h2>Titel</h2>';
                                        $output .= '<p><input onkeyup="'."chk_inputlength('pintitle',20)".';" id="pintitle" autofocus="true" class="input-default border border-white focus-border-orange" name="pin_title" placeholder="Titel"/></p>';
                                        $output .= '<h2>Beschreibung</h2>';
                                        $output .= '<p><textarea onkeyup="'."chk_inputlength('pindescription',200)".';" id="pindescription" class="input-default border border-white focus-border-orange" name="pin_description" placeholder="Schreiben Sie etwas ..."></textarea></p>';
                                        $output .= '<input type="hidden" name="token" value="'.$_SESSION['user_token'].'"/>';
                                        $output .= '<input type="hidden" name="send" value="1"/>';
                                        $output .= '<p><button type="submit" class="block btn-default border border-orange orange hover-light-black focus-light-black">senden <i class="fas fa-arrow-right"></i></button></p>';
                                        $output .= '</form>';
                                    }
                                }
                                else
                                {
                                    $output .= '<div class="panel red">';
                                    $output .= '<p>Sie sind zur Durchf&uuml;hrung dieser Aktion nicht berechtigt.</p>';
                                    $output .= '</div>';
                                }
                            }
                            else
                            {
                                $output .= '<div class="panel red">';
                                $output .= '<p>Es konnte keine Pinnwand gefunden werden.</p>';
                                $output .= '</div>';
                            }
                        }
                    }
                }
                else
                {
                    $output .= '<div class="panel red">';
                    $output .= '<p>Die gew&auml;hlte Kategorie kann nicht bearbeitet werden.</p>';
                    $output .= '</div>';
                }
            }
            else
            {
                $output .= '<div class="panel red">';
                $output .= '<p>Die gew&auml;hlte Kategorie kann nicht bearbeitet werden.</p>';
                $output .= '</div>';
            }
        }
    }
    else
    {
        $output .= '<div class="panel red">';
        $output .= '<p>Es wurden keine Daten gesendet.</p>';
        $output .= '</div>';
    }

    if(!empty($returnto))
    {
        $output .= "<script>ch_location('".$returnto."',2);</script>";
    }
}
?>
<!DOCTYPE HTML>
<html lang="de">
    <head>
        <title>Pinhead Larry #Add</title>
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