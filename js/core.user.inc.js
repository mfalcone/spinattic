CoreActions.init({
    route : {
        home:'index.php',

        validateUsername: 'php-stubs/validation.php?action=validateUsername',
        validateEmail: 'php-stubs/validation.php?action=validateEmail'
    }
});               


function setErrorOnField(elem)
{
    jQuery(elem).css('border-color', '#D62327');
}

function unsetErrorOnField(elem)
{
    jQuery(elem).css('border-color', '#D4D4D4');
}

jQuery(document).ready(function()
{
        jQuery( "#tabs" ).tabs();

        jQuery( "#username" ).keyup(function()
        {
            var value = jQuery(this).val();                                    
            
            var $msg1 = jQuery(this).next('.validation').children('.msg1');
            var $msg2 = jQuery(this).next('.validation').children('.msg2');
            var $this = this;

            if (value.length < 3)
            {
                $msg1.removeClass('ok');

                if (value.length != 0)  
                {    
                    $msg1.addClass('error');
                    $msg1.fadeIn(0); 
                    
                    setErrorOnField($this);
                }
                else
                {
                    unsetErrorOnField($this);
                    $msg1.fadeOut(0);                                            
                }    

                $msg2.fadeOut(0);
            }  
            else
            {
                $msg1.removeClass('error');
                $msg1.addClass('ok');                                        
                $msg1.fadeIn(0);  
                
                unsetErrorOnField($this);

                /*availability*/
                CoreActions.validateUsername('&username='+value, function(response)
                {
                    if (response !== null) 
                    {
                        console.log(response);                                                

                        if (response == "AVAILABLE")  
                        {  
                            $msg2.removeClass('error');
                            $msg2.addClass('ok');
                            $msg2.fadeIn(0); 
                        }
                        else if (response == "BUSY")  
                        {
                            $msg2.removeClass('ok');
                            $msg2.addClass('error');
                            $msg2.fadeIn(0); 
                            
                            setErrorOnField($this);
                        }    

                        stop_loader(); 

                        return false;
                    }

                });
            }    
        });
        
        jQuery( "#email" ).focus(function()
        {
            var $msg1 = jQuery(this).next('.validation').children('.msg1');
            var $msg2 = jQuery(this).next('.validation').children('.msg2');
            
            $msg1.fadeOut(0);
            $msg2.fadeOut(0);
            
            unsetErrorOnField(this);
        });
        
        jQuery( "#email" ).blur(function()
        {
            var value = jQuery(this).val();
            
            var $msg1 = jQuery(this).next('.validation').children('.msg1');
            var $msg2 = jQuery(this).next('.validation').children('.msg2');
            var $this = this;

            if (value.length < 6)
            {
                $msg1.removeClass('ok');

                if (value.length != 0)  
                {    
                    $msg1.addClass('error');
                    $msg1.fadeIn(0); 
                    
                    setErrorOnField($this);
                }
                else
                {
                    $msg1.fadeOut(0);                                            
                }    

                $msg2.fadeOut(0);
            }  
            else
            {
                /*check valid*/
                CoreActions.validateEmail('&option=valid&email='+value, function(response)
                {
                    if (response !== null) 
                    {
                        console.log(response);                                                

                        if (response == "VALID")  
                        {  
                            $msg1.removeClass('error');
                            $msg1.addClass('ok');                                        
                            $msg1.fadeIn(0);  
                            
                            /*availability*/
                            CoreActions.validateEmail('&option=available&email='+value, function(response)
                            {
                                if (response !== null) 
                                {
                                    console.log(response);                                                

                                    if (response == "AVAILABLE")  
                                    {  
                                        $msg2.removeClass('error');
                                        $msg2.addClass('ok');
                                        $msg2.fadeIn(0); 
                                    }
                                    else if (response == "BUSY")  
                                    {
                                        $msg2.removeClass('ok');
                                        $msg2.addClass('error');
                                        $msg2.fadeIn(0); 
                                        
                                        setErrorOnField($this);
                                    }    

                                    stop_loader(); 

                                    return false;
                                }
                            });
                        }
                        else if (response == "INVALID")  
                        {
                            $msg1.removeClass('ok');
                            $msg1.addClass('error');
                            $msg1.fadeIn(0); 
                            
                            setErrorOnField($this);
                        }    

                        stop_loader(); 

                        return false;
                    }
                });
            }    
        });
        
        jQuery( "#password" ).blur(function()
        {
            var value = jQuery(this).val();                                    
            
            var $msg1 = jQuery(this).next('.validation').children('.msg1');                        

            if (value.length < 6)
            {
                $msg1.removeClass('ok');

                if (value.length != 0)  
                {    
                    $msg1.addClass('error');
                    $msg1.fadeIn(0); 
                    
                    setErrorOnField(this);
                }
                else
                {
                    unsetErrorOnField(this);
                    $msg1.fadeOut(0);                                            
                }    
            }  
            else
            {
                $msg1.removeClass('error');
                $msg1.addClass('ok');                                        
                $msg1.fadeIn(0);  
                
                unsetErrorOnField(this);
            }    
        });
        
        jQuery( "#repeat-password" ).keyup(function()
        {
            var value      = jQuery(this).val();                                    
            var pass_value = jQuery('#password').val();
            
            var $msg1 = jQuery(this).next('.validation').children('.msg1');                        

          /*  if (value.length == 0)  
            {   
                $msg1.fadeOut(0); 
                unsetErrorOnField(this);
                
                return false;
            }
            */
            if ( value != pass_value)
            {
                $msg1.removeClass('ok');
                $msg1.addClass('error');
                $msg1.fadeIn(0); 

                setErrorOnField(this);
            }  
            else
            {
                $msg1.removeClass('error');
                $msg1.addClass('ok');                                        
                $msg1.fadeIn(0);  
                
                unsetErrorOnField(this);
            }    
        });
});