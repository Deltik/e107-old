<?php
/*
+---------------------------------------------------------------+
|        e107 website system
|        /classes/mail.php
|
|        �Steve Dunstan 2001-2002
|        http://e107.org
|        jalist@e107.org
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

function sendemail($send_to, $subject, $message,$format="plain"){
        global $pref;
        $send_to = "<".$send_to.">";
        $headers = "From: \"".$pref['siteadmin']."\" <".$pref['siteadminemail']."> \n";
        $headers .= "Reply-To: ".$pref['siteadmin']." <".$pref['siteadminemail'].">\n";
        $headers .= "Return-Path: <".$pref['siteadminemail'].">\n";
        $headers .= "X-Sender: ".$pref['siteadminemail']."\n";
        $headers .= "X-Mailer: Microsoft Outlook Express 6.00.2720.3000\n";
        $headers .= "X-MimeOLE: Produced By e107 website system\n";
        $headers .= "X-Priority: 3\n";
        $headers .= "Content-transfer-encoding: 8bit\nDate: " . date('r', time()) . "\n";
        $headers .= "MIME-Version: 1.0\n";
        if($format == "html"){
        $headers .= "Content-Type: text/html; charset=".CHARSET."\n";
        }else{
        $headers .= "Content-Type: text/plain; charset=".CHARSET."\n";
        }



        if($pref['smtp_enable']){
                require_once(e_HANDLER."smtp.php");
                if(smtpmail($send_to, $subject, $message, $headers)){
                        return TRUE;
                }else{
                        return FALSE;
                }
        }else{
                $headers .= "Return-Path: <".$pref['siteadminemail'].">\n";
                if(@mail($send_to, $subject, $message, $headers)){
                        return TRUE;
                }else{
                        return FALSE;
                }
        }

}

?>