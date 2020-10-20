<?php


namespace App;


use SoapClient;

class SendSms
{
    // sms panel information
    private const USERNAME = "mohsenalak2986";
    private const PASSWORD = "mohsen2986";
    private const FROM_NUMBER = "+9810000385";
    private const PATTERN_CODE = "nf8qgolnte";
    private $client ;

    public function __construct(){
        $this->client = new SoapClient("http://ippanel.com/class/sms/wsdlservice/server.php?wsdl");
    }
    // note remember thar the number and input_data is array
    /*
     * return @string
     */
    public function sendSmsToNumber($number , $input_data): string {
        return $this->client->sendPatternSms(self::FROM_NUMBER,$number,self::USERNAME,self::PASSWORD,self::PATTERN_CODE,$input_data);
    }
}
