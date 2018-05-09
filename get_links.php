<?php 

function host_without_www($host) 
{ 
    $out_host=$host; 
    if(substr($out_host, 0, min(4, strlen($out_host)))=='www.') 
        $out_host=substr($out_host, 4); 
    return $out_host; 
} 
function get_links_as_html($body_tmp, $inner, $external, $site_host=''){ 
    $return_arr=array(); 
    preg_match_all('/<a\s+***91;^>***93;*href***91;= ***93;+***91;"\'***93;?(\s****91;^ >"\'***93;*)***91; "\'***93;?/si',$body_tmp, $mas); 

    foreach($mas***91;1***93; as $i => $value) 
    { 
        $find_url=trim($value); 

        if($find_url***91;0***93;=='#' or preg_match('!^javascript:!', $find_url)) continue; 
         
        if(substr($find_url,0,7)=='http://' and strlen($find_url)>7) 
        { 
            $find_url_parsed=@parse_url($find_url); 
     
            $find_url_parsed***91;'host'***93;=trim($find_url_parsed***91;'host'***93;); 
            if(host_without_www($find_url_parsed***91;'host'***93;)!=host_without_www($site_host)) 
            {     
                $URL_out++; 
                if($external) $return_arr***91;***93;=$find_url; 
            } 
        } else if($inner) $return_arr***91;***93;=$find_url; 
    } 
    return $return_arr; 
} 
function _get_arr_links($url, $inner, $external) 
{ 
    $p=parse_url($url); 
    $site_host=$p***91;'host'***93;; 
    $body_tmp=file_get_contents($url); 
    return get_links_as_html($body_tmp, $inner, $external, $site_host); 
} 

?> 
<html> 
<head> 
<title>Get links</title> 
<meta http-equiv="Content-Language" content="ru"> 
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"> 
</head> 
<body>  
<form method=post> 
<input type="hidden" value="post"> 
  <table> 
     <tr><td align="right" colspan="2">&nbsp;</td></tr> 
     <tr> 
    <td align="right" valign="middle"><b>Enter url:</b></td> 
    <td valign="middle"><input size="30" rows="10" cols="50" type="text" name="url" /></td> 
     </tr> 
<tr> 
    <td align="right" valign="middle"><b>Or HTML-code:</b></td> 
    <td valign="middle"><textarea size="30" rows="10" cols="50" type="text" name="html"></textarea></td> 
 </tr> 
     <tr> 
        <td align="left" height="50" valign="middle" colspan="2"> 
            <div><input type="checkbox" name="inner" />Inner Links</div> 
            <div><input type="checkbox" name="external" checked />External Links</div> 
            <div><input type="submit" value=GET /></div> 
        </td> 
    </tr> 
  </table> 
</form> 
<?php  
    if(!empty($_POST)) 
    { 
            $inner=$_POST***91;'inner'***93;?true:false; 
            $external=$_POST***91;'external'***93;?true:false; 
             
            if(!empty($_POST***91;'url'***93;)){ 
                $url=trim($_POST***91;'url'***93;); 
                echo '<h1>'.$url.'</h1><br>'; 
                $arr1=_get_arr_links($url, $inner, $external); 
            } else if(!empty($_POST***91;'html'***93;)) $arr1=get_links_as_html(stripslashes($_POST***91;'html'***93;), $inner, $external); 
            echo implode('<br/>', $arr1).'<hr>'; 
            echo 'Total: '.count($arr1).'<br>'; 
  
    }     

?> 
</body> 
</html>