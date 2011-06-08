<?php
function whois($domain) {
    if(preg_match('/[;\&\|\>\<]/', $domain)) exit;
    
     exec("whois " . escapeshellarg($domain), $output); 
     $result = implode("\n", $output);
    
    return (strpos($result, 'No match for domain') !== false);
}
?>