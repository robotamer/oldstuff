<?php
/**
 * @author   : Dennis T Kaplan
 *
 * @version  : 1.0
 * Date      : June 20, 2008
 * Function  : tokenCheck()
 * Purpose   : Check POST
 *
 * @access  public
 * @return  string
 **/
function tokenCheck($redirect = NULL) {
    if ( ! empty($_POST)) {
        if (empty($_POST['token']) OR empty($_SESSION['token']) OR ($_POST['token'] !== $_SESSION['token'])) {
            Log::set("token didn't match", 'Hacker');
            unset($_SESSION['token'], $_POST['token']);
            header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
            exit;
        }else{
            unset($_SESSION['token'], $_POST['token']);
            return TRUE;
        }
    }else{
        return $_SESSION['token'] = randString(10);
    }
}
?>
