// A $( document ).ready() block.
$( document ).ready(function() 
{
    consolelog( "Document Ready!" );
    //AuthCheck();

    $.when(AuthCheck()).done(function(a1){
       
        if(window.location.pathname.indexOf('/Auth/login.php')==0 )
        {
            consolelog('after authchek loginpage');
            
        }
        try
        {
            Startup();
        }
        catch(err)
        {
            consolelog('No Startup Function Detected');
        }

    });

    

});

