<?php

/**
 * @author V.Jelev
 *
 * Class AuthorizeNetComponent
 */
class AuthorizeNetComponent
{
    const API_LOGIN_ID = '4Sr92HsYA';
    const TRANSACTION_KEY = '6MnjF982TzWhJ496';
    const VERSION = '3.1';
    const POST_URL = 'https://test.authorize.net/gateway/transact.dll';
    // const HOST = 'apitest.authorize.net';
    // const API_PATH = '/xml/v1/request.api';

    public $response = [];

    public function chargeCard($data)
    {
        $post_values = $this->generateCorrectDataForAuthorize($data);

        $post_string = "";
        foreach ($post_values as $key => $value) {
            $post_string .= "$key=" . urlencode($value) . "&";
        }

        $post_string = rtrim($post_string, "& ");

        $request = curl_init(self::POST_URL);
        curl_setopt($request, CURLOPT_HEADER, 0);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($request, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
        $post_response = curl_exec($request);
        curl_close($request);

        $response_array = explode($post_values["x_delim_char"], $post_response);

        if ($response_array[0] == 1 || $response_array[0] == 4) {

            $this->response['status'] = $response_array[0];
            $this->response['message'] = $response_array[3];
            // $this->tranzactionID = $response_array[6];
            // $this->authorizationCode = $response_array[4];

//            print_r($this->response);
        } else {
            $this->response['status'] = $response_array[0];
            $this->response['message'] = $response_array[3];

//            print_r($this->response);

        }
        return $this->response;

    }

    public function generateCorrectDataForAuthorize($data)
    {
        return [
            "x_cust_id"           => $data['user_id'],
            "x_first_name"        => $data['fname'],
            "x_last_name"	      => $data['lname'],
            "x_phone"             => $data['phone'],
            "x_email"             => $data['email'],
            "x_address"		      => $data['address'],
            "x_city"			  => $data['city'],
            "x_country"           => $data['country'],
            "x_zip"				  => $data['zip'],
            "x_card_num"	      => $data['card_number'],
            "x_exp_date"	      => $data['exp_date'],
            "x_amount"		      => $data['total_price'],
            "x_login"			  => self::API_LOGIN_ID,
            "x_tran_key"	      => self::TRANSACTION_KEY,
            "x_version"		      => self::VERSION,
            "x_method"		      => "CC",
            "x_delim_data"	      => "TRUE",
            "x_delim_char"	      => "|",
            "x_type"			  => "AUTH_ONLY",
            "x_description"	      => "Endlessly Organic Transaction",
            "x_relay_response"    => "FALSE",
        ];
    }

}
