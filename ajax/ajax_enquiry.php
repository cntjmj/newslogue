<?php
    

    include "../config.php";
    

    if(@$_REQUEST["send"] == "SUBMIT" && $_POST['msg'] == "")
    {
        $fullname = $_POST['fullname'];
        $fromEmailAddr = $_POST['emailaddress'];
        $toEmailAddr = "service@newslogue.com";
        $enquirycontent = $_POST['enquirycontent'];

        $html = 
                '
                <table width="600" cellspacing="0" cellpadding="0" border="0" style="border-collapse: collapse;">
                    <tr>
                        <td>
                            <div style="margin-bottom:20px;">
                                Sender Name : '.$fullname.'<br/>
                                Sender E-mail : '. $fromEmailAddr .'
                                <hr>
                            </div>                            
                            <div></div>
                            <div style="margin-top: 30px;"><b>Inquiry from '.$fullname.'</b></div>
                            <div style="margin-top: 20px;font:14px arial, sans-serif;"><pre>'.$enquirycontent.'</pre></div>
                        </td>
                    </tr>
                </table>
                ';                

                //$mailer->AddAddress($email, $firstName. " ".$lastName);
                $mailer->AddAddress($toEmailAddr);                
                
                $mailer->SetFrom($fromEmailAddr, $fullname);
                //$mailer->AddReplyTo($employerUsername, $firstName. " ".$lastName);
                
                $mailer->Subject = "Inquiry";
                $mailer->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
                $mailer->MsgHTML($html);
                $result= $mailer->Send();


                    // $loginRstArray = $user->AddDetails($_PO
        if($result)        
            echo "true<==>Mail has been successfully sent!"; 
        else
            echo "false<==>Mail has not been sent!";  
    }
            


?>


