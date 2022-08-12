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
        if(empty($_GET['id']))
        {
            $output .= '<div class="container">';
            $output .= '<div class="panel black">';
            $output .= '<div class="panel red">';
            $output .= '<p>Es konnte keine Pinnwand angezeigt werden.</p>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
        }
        else
        {
            if(preg_match('/[^0-9]/',$_GET['id']) == 0)
            {
                $query = sprintf("
                SELECT bulletin_board_name
                FROM bulletin_board
                WHERE bulletin_board_id = '%s';",
                $sql->real_escape_string($_GET['id']));

                $result = $sql->query($query);

                if($row = $result->fetch_array(MYSQLI_ASSOC))
                {
                    $output .= '<div class="container">';
                    $output .= '<div class="flex-container flex-wrap flex-base">';
                    $output .= '<div class="col-s12 col-m6 col-l6">';
                    $output .= '<h1>'.$row['bulletin_board_name'].'</h1>';
                    $output .= '</div>';
                    $output .= '<div class="col-s12 col-m6 col-l6 text-right">';
                    $output .= '<a class="col-s3 btn-default border border-black black hover-light-black hover-border-orange focus-light-black focus-border-orange" href="index.php"><i class="fas fa-home"></i></a>';
                    $output .= '<a class="col-s3 btn-default border border-black black hover-light-black hover-border-orange focus-light-black focus-border-orange" href="add.php?category=pin&bulletin_board='.$_GET['id'].'"><i class="fas fa-plus"></i></a>';
                    $output .= '<a class="col-s3 btn-default border border-black black hover-light-black hover-border-orange focus-light-black focus-border-orange" href="edit.php?category=bulletin_board&id='.$_GET['id'].'"><i class="fas fa-edit"></i></a>';
                    $output .= '<a class="col-s3 btn-default border border-black black hover-light-black hover-border-orange focus-light-black focus-border-orange" href="logout.php?token='.$_SESSION['user_token'].'"><i class="fas fa-sign-out-alt"></i></a>';
                    $output .= '</div>';
                    $output .= '</div>';

                    $query = sprintf("
                    SELECT pin_id,pin_title,pin_description
                    FROM pin
                    WHERE pin_bulletin_board_id = '%s';",
                    $sql->real_escape_string($_GET['id']));
        
                    $result = $sql->query($query);
        
                    $amount_g = mysqli_num_rows($result);
        
                    if($amount_g > 0)
                    {
                        $i = 0;
        
                        $rotations = array('rotate-2','rotate-15','rotate-1','rotate-05','rotate05','rotate1','rotate15','rotate2');
            
                        $output .= '<h2>Pins ( '.$amount_g.' )</h2>';
                        $output .= '</div>';
            
                        $output .= '<div class="flex-container flex-wrap">';
            
                        while($row = $result->fetch_array(MYSQLI_ASSOC))
                        {
                            $rotation_id = mt_rand(0,count($rotations)-1);
            
                            if($i == 0)
                            {
                                $color = 'red';
                                $focus = 'focus-border-red';

                                $i++;
                            }
                            else if($i == 1)
                            {
                                $color = 'cyan';
                                $focus = 'focus-border-cyan';

                                $i++;
                            }
                            else if($i == 2)
                            {
                                $color = 'orange';
                                $focus = 'focus-border-orange';
            
                                $i = 0;
                            }
            
                            $output .= '<div class="margin">';
                            $output .= '<div class="container scroll-container hover-scale105 '.$color.' '.$rotations[$rotation_id].' square-300 display-container">';
                            $output .= '<p><a class="large '.$focus.'" href="del.php?category=pin&id='.$row['pin_id'].'&token='.$_SESSION['user_token'].'"><i class="fas fa-times"></i></a></p>';
                            $output .= '<form action="change.php" method="post"/>';
                            $output .= '<input type="hidden" name="category" value="pin"/>';
                            $output .= '<input type="hidden" name="id" value="'.$row['pin_id'].'"/>';
                            $output .= '<p><input onkeyup="'."chk_inputlength('pintitle".$row['pin_id']."',20)".';" id="pintitle'.$row['pin_id'].'" name="pin_title" type="text" class="large input-default '.$color.' '.$focus.'" style="padding:0;" value="'.$row['pin_title'].'"/></p>';
                            $output .= '<p><textarea onkeyup="'."chk_inputlength('pindescription".$row['pin_id']."',200)".';" id="pindescription'.$row['pin_id'].'" name="pin_description" class="input-default '.$color.' '.$focus.'" style="padding:0;resize:none;">'.$row['pin_description'].'</textarea></p>';
                            $output .= '</form>';
                            $output .= '</div>';
                            $output .= '</div>';
                        }
            
                        $output .= '</div>';
                    }
                    else
                    {
                        $output .= '<div class="panel black">';
                        $output .= '<div class="panel red">';
                        $output .= '<p>Es wurden keine Pins gefunden.</p>';
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
                    $output .= '<p>Es konnte keine Pinnwand angezeigt werden.</p>';
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
                $output .= '<p>Es konnte keine Pinnwand angezeigt werden.</p>';
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
        $output .= '<p>Es konnte keine Pinnwand angezeigt werden.</p>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
    }
}
?>
<!DOCTYPE HTML>
<html lang="de">
    <head>
        <title>Pinhead Larry #Bulletin Board</title>
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