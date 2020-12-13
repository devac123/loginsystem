<?php 

include $_SERVER['DOCUMENT_ROOT'].'/App/class/functions.php';
include $_SERVER['DOCUMENT_ROOT'].'/App/class/db.php';
include $_SERVER['DOCUMENT_ROOT'].'/App/class/auth.php';




function authCheck($data)
{
    $ret = "";
    $dataObj = json_decode($data);
   

    $db = new db();
    $sqlData = $db->query('SELECT * FROM tb_sessions WHERE device_code = ? and token = ? and session_valid_untill > (SELECT UTC_TIMESTAMP()) and is_deleted = 0',$dataObj->deviceFP,$dataObj->authToken )->fetchAll();
    $db->close();

    if(count($sqlData) == 1)
    {
        

        $db = new db();
        $sqlData = $db->query('update tb_sessions set session_valid_untill =DATE_ADD(UTC_TIMESTAMP(), INTERVAL 30 MINUTE) where id = '.$sqlData[0]['id'].';' )->affectedRows();
        $db->close();

        $ret = 'Authenticated';
    }
    else
    {
        $ret = 'Failed';
    }

return  $ret;



}

function LoginCheck($req_data)
{

    $retObj = new stdClass();//inti return object


    $ret = "";
    $reqObj = json_decode($req_data);

    $headerObj = $reqObj->Header;
    $dataObj = $reqObj->Data;



    $username  = $dataObj->username;
    $password  = $dataObj->password;
    $pass_saltedhash="";//Provided by User


    $userid="";//Saved in DB
    $salt      =""; //Saved in DB
    $saved_saltedhash ="";//Saved in DB
    $user_locked_untill="";//Saved in DB
    $faild_login_count= "";
    
    
    

    $db = new db();
    $sqlData = $db->query('select * from tb_users where user_name = ? and is_active =1',$username )->fetchAll();
    $db->close();

    if(count($sqlData) == 1)
    {
        $userid= $sqlData[0]["id"];
        $salt      = $sqlData[0]["salt"];
        $saved_saltedhash = $sqlData[0]["salted_hash"];
        $user_locked_untill= $sqlData[0]["locked_untill"];
        $failed_login_count= $sqlData[0]["failed_login_count"];
        $pass_saltedhash = createHash($salt . $password);

        
        if(gmdate('d/M/Y h:i:s') > date('d/M/Y h:i:s',strtotime($user_locked_untill)) )
        {

            if($saved_saltedhash == $pass_saltedhash)
            {
                $ret ="Hurrey ! Login ";
                //reset failed login count
                $db = new db();
                $sqlData = $db->query('update tb_users set failed_login_count =0  where id = '. $userid.';' )->affectedRows();
                $db->close();
                
                $token = randomString(64);
                //Check if deviceFP exist in any session or not
                $db = new db();
                $sqlData = $db->query('select * from tb_sessions where user_id_fk = '. $userid.' and device_code = ?;', $headerObj->deviceFP )->fetchAll();
                $db->close();

               if(count($sqlData) ==0)
               {
                   //Create Session Here
                   $db = new db();
                   $sqlquery = "INSERT INTO tb_sessions(`user_id_fk`,`device_code`,`token`,`session_valid_untill`,`is_remembered`,`is_deleted`)VALUES(?,?,?,DATE_ADD(UTC_TIMESTAMP(), INTERVAL 30 MINUTE),0,0);";
                   $sqlData = $db->query($sqlquery,$userid,$headerObj->deviceFP,$token)->affectedRows();
                   $db->close();

                   //$ret ="Login Success ! Session Created";

                   $retObj->response = "Success";
                   $retObj->action = "Session Created";
                   $retObj->data = "";
                   
               }
               else if(count($sqlData) ==1)
               {
                   $session_id = $sqlData[0]["id"];
                   //update session here
                   $db = new db();
                   
                   $sqlData = $db->query('update tb_sessions set token ="'.$token.'", session_valid_untill =DATE_ADD(UTC_TIMESTAMP(), INTERVAL 30 MINUTE) where id = '. $session_id .';')->affectedRows();
                   $db->close();

                   //$ret ="Login Success ! Session Updated";

                   $retObj->response = "Success";
                   $retObj->action = "Session Updated";
                   $retObj->data = "";
                   

               }

            }
            else
            {
               
                if($failed_login_count >= 4)
                {
                    
                    //Diabling User Account
                    $db = new db();
                    $sqlData = $db->query('update tb_users set locked_untill = DATE_ADD(UTC_TIMESTAMP(), INTERVAL 1 DAY) , failed_login_count = 0 where id = '. $userid.';' )->affectedRows();
                    $db->close();

                   // $ret = "Wrong Passowrd: Your account is Disabled";

                   $retObj->response = "Failed";
                   $retObj->action = "Account Disabled";
                   $retObj->data = "";
                   
                
                }
                else
                {
                    //Incrementing Failed Login Counter
                    $db = new db();
                    $sqlData = $db->query('update tb_users set failed_login_count = '. ($failed_login_count+1)  .' where id = '. $userid.';' )->affectedRows();
                    $db->close();

                   // $ret = "Wrong Passowrd: Failed Login attempts ". ($failed_login_count+1);

                    $retObj->response = "Failed";
                    $retObj->action = "Failed Attempt Number:". ($failed_login_count+1);
                    $retObj->data = "";
                }

                
            }

        }
        else
        {
            //$ret = "User Temprary Blocked";

                    $retObj->response = "Failed";
                    $retObj->action = "User is Blocked";
                    $retObj->data = "";
        }


       
        
        
    }
    else
    {
        //No User | Multiple Match  | User Not active
       //$ret = "No User Found or not active";
        $retObj->response = "Failed";
        $retObj->action = "No User Found";
        $retObj->data = "";
    }


   

    return  json_encode( $retObj);
}


if (isset($_POST['authCheck'])) 
{
        echo authCheck($_POST['authCheck']);
}
else if (isset($_POST['LoginCheck'])) 
{
        echo LoginCheck($_POST['LoginCheck']);
}
else
{
    echo "Error: No such function in controller";
}

?>
