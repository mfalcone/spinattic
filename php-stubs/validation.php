<?php

//sleep(3);

if (isset($_GET['action']))
{
    switch($_GET['action'])
    {
        case 'validateUsername':
            if (isset($_POST['username']) && $_POST['username'] == 'hardkito') echo 'BUSY';
            else echo 'AVAILABLE';
            break;
        
        case 'validateEmail':            
            if (isset($_POST['option']))
            {
                switch ($_POST['option'])
                {
                    case 'valid':
                        $re = '/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i';
	
                        if (isset($_POST['email']) && preg_match($re, $_POST['email'])) echo 'VALID';
                        else echo 'INVALID';
                        
                        break;
                        
                    case 'available':
                        $re = '/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i';
	
                        if (isset($_POST['email']) && $_POST['email'] == 'hardkito@yahoo.com.ar') echo 'BUSY';
                        else echo 'AVAILABLE';
                        
                        break;  
                        
                    default:
                        echo 'INVALID';            
                        break;    
                }
            } 
            
            break;
        
        
        default:
            echo 'BUSY';            
            break;
    }
}


?>
