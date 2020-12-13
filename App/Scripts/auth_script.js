function AuthCheck()
{
    authtoken = GetToken();
    deviceFP = GetDeviceFP();
    

    consolelog( "AuthToken:" + authtoken+"  DeviceFP:"+ deviceFP );    

    dataObj = {};
    dataObj['authToken'] = authtoken;
    dataObj['deviceFP'] = deviceFP;


    return $.ajax({
        url: '/App/Controller/auth_controller.php',
        type: 'post',
        data: { "authCheck": JSON.stringify(dataObj) },
        success: function(response) 
        {
            // Authenticated | Failed | Error
            res = response.replace(/(\r\n|\n|\r)/gm, "");
            consolelog("Ajax Success:" + res); 
            if(res!="Authenticated")
            {
                if(window.location.pathname.indexOf('Auth/') == -1 )
                {
                    //Inject Path Query String
                    window.location.assign("/Auth/login.php");
                }
               
            }
            else if(res=="Authenticated")
            {
                if(window.location.pathname.indexOf('Auth/') >=0 )
                {
                    window.location.assign("/index.php");
                }
                
            }

           
               
        },
        error:function(response){
            consolelog("Ajax Error:" + JSON.stringify(response)); 
            
        }
       });
    
}

function GetToken()
{
    authtoken =  getCookie("authtoken");
    if(authtoken == null)
    {
        authtoken="";
    }
return authtoken;
}

  
function GetDeviceFP()
{
   deviceFP = getCookie("deviceFP");
    if(deviceFP == null)
    {
        deviceFP= genrateString(64);
        setCookie("deviceFP",deviceFP,9999);
        
    }
return deviceFP;
}
