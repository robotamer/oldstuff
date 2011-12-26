<?php
/**
 * An open source application development framework for PHP 5.2 or newer
 *
 * @package      TaMeR
 * @author       Dennis T Kaplan
 * @copyright    Copyright (c) 2008 - 2010, Dennis T Kaplan
 * @license      http://tamer.pzzazz.net/license.html
 * @link         http://tamer.pzzazz.net
 */

/**
 * @subpackage  OpenSSL
 * @author      {@link http://www.karenandalex.com/php_stuff/classes/Openssl.php}
 * @todo		
 */

DEFINE("OPEN_SSL_CONF_PATH", '/etc/ssl/openssl.cnf');   // Point to your config file
DEFINE("OPEN_SSL_CERT_DAYS_VALID", 365);                // Number of days how long the certificate is valid
DEFINE("FILE_LOCATION", $_SERVER["DOCUMENT_ROOT"]."/tmp/");         // Location where to store the pem files.
DEFINE("HTML_LOCATION", "http://".$_SERVER["SERVER_NAME"]."/tmp/"); // Location where to store the pem files.
DEFINE("DEBUG", 1); // Show debug messages

class OpenSSL{

    var $certificate_resource_file; //the certificate in a file
    var $csr_resource_file;         //the csr in a file
    var $privatekey_resource_file;  //the private key in a file

    var $certificate_resource;      //the generated certificate
    var $csr_resource;              //the certificate signing request
    var $privatekey_resource;       //the private key

    var $certificate;               //the certificate
    var $crypttext;                 //the encrypted (= secure) text
    var $csr;                       //the csr
    var $dn;                        //the DN
    var $plaintext;                 //the decrypted (= unsecure) text
    var $ppkeypair;                 //the private and public key pair
    var $signature;                 //the signature

    var $config;                    //openssl config settings
    var $ekey;                      //ekey aka envelope key is set by encryption, required by decryption
                                    //randomly generated secret key and encrypted by public key
    var $privkeypass;               //password for private key
    var $random_filename;           //randomly generated filename

    function OpenSSL($isFile=0){
        $this->clear_debug_buffer();
        if($isFile) {
            $this->config = array("config" => OPEN_SSL_CONF_PATH);
        } else {
            // Configuration overrides.
            $this->config = array(
                "digest_alg" => "md5",
                "x509_extensions" => "v3_ca",
                "req_extensions" => "usr_cert",
                "private_key_bits" => 1024,
                "private_key_type" => OPENSSL_KEYTYPE_RSA,
                "encrypt_key" => true
            );
        }
        $this->debug("openssl");
    }

    function check_certificate_purpose($purpose) {
        //$this->clear_debug_buffer();
        $ok = openssl_x509_checkpurpose( $this->certificate_resource, $purpose);
        //$this->debug("check_certificate_purpose");
        return $ok;
    }

    function check_privatekey_match_certificate() {
        $this->clear_debug_buffer();
        $ok = openssl_x509_check_private_key ( $this->certificate_resource, $this->privatekey_resource );
        $this->debug("check_privatekey_match_certificate");
        return $ok;
    }

    function check_signature($plain=""){
        $this->clear_debug_buffer();
        if ($plain) $this->plaintext=$plain;
        $ok = openssl_verify($this->plaintext, $this->signature, $this->certificate_resource);
        $this->debug("check_signature");
        return $ok;
    }

    function clear_debug_buffer() {
        if(DEBUG) {
            while ($e = openssl_error_string());
        }
    }

    // Create a certificate signing request (CSR)
    function create_csr() {
        //$this->clear_debug_buffer();
        $this->csr = openssl_csr_new($this->dn, $this->ppkeypair, $this->config);
        //$this->debug("create_csr");
    }

    // Create a new private and public key pair
    function create_key_pair() {
        //$this->clear_debug_buffer();
        $this->ppkeypair = openssl_pkey_new($this->config);
        //$this->debug("create_key_pair");
    }

    // Create self-signed signed certificate. The certificate is valid for N days
    function create_self_signed_certificate($days=OPEN_SSL_CERT_DAYS_VALID) {
        //$this->clear_debug_buffer();
        $this->certificate = openssl_csr_sign($this->csr, null, $this->ppkeypair, $days, $this->config);
        //$this->debug("create_self_signed_certificate");
    }

    function create_signature($plain=""){
        $this->clear_debug_buffer();
        if ($plain) $this->plaintext=$plain;
        openssl_sign($this->plaintext, $this->signature, $this->privatekey_resource);
        $this->debug("create_signature");
    }

    function debug($location) {
        if(DEBUG) {
            // Show any errors that occurred here
            while (($e = openssl_error_string()) !== false) {
                echo $location . " -- ". $e . "<br />";
            }
        }
    }

    // Decrypt text for only 1 recipient
    function decrypt($crypt="", $ekey=""){
        $this->clear_debug_buffer();
        if ($crypt)$this->crypttext=$crypt;
        if ($ekey)$this->ekey=$ekey;
        openssl_open($this->crypttext, $this->plaintext, $this->ekey, $this->privatekey_resource);
        $this->debug("decrypt");
    }

    // Decrypt text using private key
    function decrypt_private($crypt=""){
        $this->clear_debug_buffer();
        if ($crypt)$this->crypttext=$crypt;
        openssl_private_decrypt ($this->crypttext, $this->plaintext, $this->privatekey_resource);
        $this->debug("decrypt_private");
    }

    // Decrypt text using public key
    function decrypt_public($crypt=""){
        $this->clear_debug_buffer();
        if ($crypt)$this->crypttext=$crypt;
        openssl_public_decrypt ($this->crypttext, $this->plaintext, $this->certificate_resource);
        $this->debug("decrypt_public");
    }

    function display_certificate_information($shortnames){
        $this->clear_debug_buffer();
        $arr = openssl_x509_parse ( $this->certificate_resource, $shortnames);
        $this->debug("display_certificate_information");
        return $arr;
    }

    // Encrypt text for only 1 recipient
    function encrypt($plain=""){
        $this->clear_debug_buffer();
        if ($plain) $this->plaintext=$plain;
        openssl_seal($this->plaintext, $this->crypttext, $ekey, array($this->certificate_resource));
        $this->ekey=$ekey[0];
        $this->debug("encrypt");
    }

    // Encrypt text using public key
    // The function openssl_public_encrypt is not intended for general encryption and decryption.
    // For that, you want openssl_seal() and openssl_open()
    // The maximum limit on the size of the string to be encrypted is 117 characters.
    function encrypt_public($plain=""){
        $this->clear_debug_buffer();
        if ($plain) $this->plaintext=$plain;
        openssl_public_encrypt ($this->plaintext, $this->crypttext, $this->certificate_resource);
        $this->debug("encrypt_public");
    }

    // Encrypt text using private key
    // The function openssl_private_encrypt is not intended for general encryption and decryption.
    // For that, you want openssl_seal() and openssl_open()
    // The maximum limit on the size of the string to be encrypted is 117 characters.
    function encrypt_private($plain=""){
        $this->clear_debug_buffer();
        if ($plain) $this->plaintext=$plain;
        openssl_private_encrypt ($this->plaintext, $this->crypttext, $this->privatekey_resource);
        $this->debug("encrypt_private");
    }

    // Export the certificate as a file (PEM encoded format)
    function export_certificate_to_file(){
        $this->clear_debug_buffer();
        // Create empty certificate file;
        $this->set_certificate_file();
        openssl_x509_export_to_file($this->certificate, FILE_LOCATION.$this->certificate_resource_file);
        $this->debug("export_certificate_to_file");
    }

    // Export the certificate as a string (PEM encoded format)
    function export_certificate_to_string(){
        $this->clear_debug_buffer();
        openssl_x509_export($this->certificate, $this->certificate_resource);
        $this->debug("export_certificate_to_string");
    }

    // Export the CSR as a file
    function export_csr_to_file(){
        $this->clear_debug_buffer();
        // Create empty csr file;
        $this->set_csr_file();
        openssl_csr_export_to_file($this->csr, FILE_LOCATION.$this->csr_resource_file);
        $this->debug("export_csr_to_file");
    }

    // Export the CSR as a string
    function export_csr_to_string(){
        $this->clear_debug_buffer();
        openssl_csr_export($this->csr, $this->csr_resource);
        $this->debug("export_csr_to_string");
    }

    // Export the private key certificate as a file (PEM encoded format)
    function export_privatekey_to_file(){
        //$this->clear_debug_buffer();
        // Create empty private key file;
        $this->set_privatekey_file();
        openssl_pkey_export_to_file($this->ppkeypair, FILE_LOCATION.$this->privatekey_resource_file);
        //$this->debug("export_privatekey_to_file");
    }

    // Export the private key certificate as a string (PEM encoded format)
    function export_privatekey_to_string(){
        //$this->clear_debug_buffer();
        openssl_pkey_export($this->ppkeypair, $this->privatekey_resource);
        //$this->debug("export_privatekey_to_string");
    }

    // Create random characters
    function generateRandomString($size) {
      srand( ( (double) microtime() ) * 1000000 );
      $string = '';
      $signs =  'abcdefghijklmnopqrstuvwxyz';
      $signs .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $signs .= '01234567890123456789012345';
      for( $i = 0; $i < $size; $i++ ){
        $string .= $signs{ rand( 0, ( strlen( $signs ) - 1 ) ) };
      }
      $this->random_filename = $string;
    }

    function get_certificate(){
        return $this->certificate_resource;
    }

    function get_certificate_file(){
        return $this->certificate_resource_file;
    }

    function get_crypt(){
        return $this->crypttext;
    }

    function get_csr(){
        return $this->csr_resource;
    }

    function get_csr_file(){
        return $this->csr_resource_file;
    }

    function get_ekey(){
        return $this->ekey;
    }

    function get_plain(){
        return $this->plaintext;
    }

    function get_privatekey(){
        return $this->privatekey_resource;
    }

    function get_privatekey_file(){
        return $this->privatekey_resource_file;
    }

    function get_privkeypass(){
        return $this->privkeypass;
    }

    function get_signature(){
        return $this->signature;
    }

    function load_certificate($cert) {
        $this->clear_debug_buffer();
        if(DEBUG) echo "Certificate loaded from =" .$cert . "<br />";
        if($this->certificate_resource = openssl_x509_read ($cert)){
            if(DEBUG) echo "Certificate loaded<br /><br />";
        } else {
            if(DEBUG) echo "Certificate not loaded <br /><br />";
        }
        $this->debug("load_certificate");
    }

    function load_privatekey($arr) {
        $this->clear_debug_buffer();
        if(DEBUG) echo "Source loaded from =" .$arr[0] . "<br />";
        if($this->privatekey_resource = openssl_pkey_get_private($arr)){
            if(DEBUG) echo "Private key loaded<br /><br />";
        } else {
            if(DEBUG) echo "Private key not loaded <br /><br />";
        }
        $this->debug("load_privatekey");
    }

    function readf($path){
        //return file contents
        $fp=fopen($path,"r");
        $ret=fread($fp,8192);
        fclose($fp);
        return $ret;
    }

    function set_certificate($cert){
        $this->certificate_resource=$cert;
    }

    // Certificate stored in file
    function set_certificate_file(){
        $this->certificate_resource_file="certificate_".$this->random_filename.".pem";
    }

    function set_crypttext($txt){
        $this->crypttext=$txt;
    }

    // CSR stored in file
    function set_csr_file(){
        $this->csr_resource_file="csr_".$this->random_filename.".pem";
    }

    function set_dn($countryName = "US",
                    $stateOrProvinceName = "California",
                    $localityName = "Los Angeles",
                    $organizationName = "cert.PzzAzz.net",
                    $organizationalUnitName = "Certification Services",
                    $commonName = "cert.PzzAzz.net CA",
                    $emailAddress = "cert@pzzazz.com"){

        $this->dn=Array(
            "countryName" => $countryName,
            "stateOrProvinceName" => $stateOrProvinceName,
            "localityName" => $localityName,
            "organizationName" => $organizationName,
            "organizationalUnitName" => $organizationalUnitName,
            "commonName" => $commonName,
            "emailAddress" => $emailAddress );
    }

    function set_ekey($ekey){
        $this->ekey=$ekey;
    }

    function set_plain($txt){
        $this->plaintext=$txt;
    }

    // Privatekey can be text or file path
    function set_privatekey($privatekey, $isFile=0, $key_password=""){
        $this->clear_debug_buffer();
        if ($key_password) $this->privkeypass=$key_password;
        if ($isFile)$privatekey=$this->readf($privatekey);
        $this->privatekey_resource=openssl_get_privatekey($privatekey, $this->privkeypass);
        $this->debug("set_privatekey");
    }

    // Privatekey stored in file
    function set_privatekey_file(){
        $this->privatekey_resource_file="privatekey_".$this->random_filename.".pem";
    }

    // Set password for private key
    function set_privkeypass($pass){
        $this->privkeypass=$pass;
    }

    function set_signature($signature){
        $this->signature=$signature;
    }
}

?>
