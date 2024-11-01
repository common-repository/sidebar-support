<?php namespace Sidebar_Support;

class OAuth2Client {
    public $database_prefix = "";
    public $api_base_url = "";
    public $authorize_url = "https://bitbucket.org/site/oauth2/authorize";
    public $token_url = "";
    public $token_info_url = "";

    public $client_id = "";
    public $client_secret = "";
    public $redirect_uri = "";
    public $access_token = "";
    public $refresh_token = "";

    public $access_token_expires_in = "";
    public $access_token_expires_at = "";

    public $sign_token_name = "access_token";
    public $decode_json = true;
    public $curl_time_out = 30;
    public $curl_connect_time_out = 30;
    public $curl_ssl_verifypeer = false;
    public $curl_header = array();
    
    public $curl_useragent = "";

    public $http_code = "";
    public $http_info = "";

    public function __construct($db_prefix, $client_id = false, $client_secret = false, $token_url, $redirect_uri = '', $user_agent = '') {
        $this->database_prefix = $db_prefix;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->token_url = $token_url;
        $this->redirect_uri = $redirect_uri;

        $this->curl_useragent = !empty($user_agent) ? $user_agent : 'OAuth/2 PHP Client; SlickRemix http://www.slickremix.com/';

    }
    /**
     * Build Authorize URL
     *
     * @param array $extras
     * @return string
     * @since
     */
    public function authorizeUrl($extras = array()) {
        $params = array(
            "client_id" => $this->client_id,
            "response_type" => "code"
        );

        if (count($extras))
            foreach ($extras as $k => $v)
                $params[$k] = $v;

        return $this->authorize_url . "?" . http_build_query($params);
    }
    /**
     * Authenticate
     *
     * @param $code
     * @return mixed|\StdClass
     * @throws \Exception
     * @since
     */
    public function authenticate() {

        $authenticated = $this->check_authentication();

        if($authenticated == false){
            $params = array(
                "client_id"     => $this->client_id,
                "client_secret" => $this->client_secret,
                "grant_type"    => "client_credentials",
            );

            $response = $this->request( $this->token_url, $params, "POST" );
            $response = $this->parseRequestResult( $response );

            if( ! $response || ! isset( $response->access_token ) ){
                echo ($response->error ? "The Authorization Service has returned: " . $response->error : 'The Authorization Service has returned empty response');
                return false;
            }

            if( isset( $response->access_token  ) ) $this->access_token            = $response->access_token;
            if( isset( $response->refresh_token ) ) $this->refresh_token           = $response->refresh_token;
            if( isset( $response->expires_in    ) ) $this->access_token_expires_in = $response->expires_in;

            // calculate when the access token expire
            $this->access_token_expires_at = time() + $response->expires_in;

            if(!empty($this->database_prefix)){
                update_option( $this->database_prefix.'-access', $this->access_token);
                update_option( $this->database_prefix.'-refresh', $this->refresh_token);
                update_option( $this->database_prefix.'-expires', $this->access_token_expires_at);
                
                return true;
            }
            echo "Please set a Database Prefix";
            return false;
        }
        //Authenticated from Check function
        return true;
    }
    /**
     * Check Authentication
     *
     * @return bool
     * @throws \Exception
     * @since
     */
    public function check_authentication() {
        $access_token = get_option($this->database_prefix.'-access');
        $refresh_token = get_option($this->database_prefix.'-refresh');
        $expires = get_option($this->database_prefix.'-expires');

        if ($access_token) {
            if ($refresh_token && time() > $expires) {
                // if yes, access_token has expired, then ask for a new one
                $response = $this->refreshToken($refresh_token);
                // if wrong response
                if (!isset($response->access_token) || !$response->access_token) {
                    echo "The Authorization Service has return an invalid response while requesting a new access token. given up!";
                    return false;
                }
                // set new access_token
                if( isset( $response->access_token  ) ) $this->access_token = $response->access_token;
                update_option( $this->database_prefix.'-access', $response->access_token);
                return true;
            }
            elseif($refresh_token && time() < $expires) {
                return true;
            }
        }
        return false;
    }
    /**
     * Format and sign an oauth for provider api
     *
     * @param $url
     * @param string $method
     * @param array $parameters
     * @return mixed|null
     * @since
     */
    public function api($url, $method = "GET", $parameters = array()) {
        if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
            $url = $this->api_base_url . $url;
        }

        $parameters[$this->sign_token_name] = $this->access_token;
        $response = null;

        switch ($method) {
            case 'GET'  :
                $response = $this->request($url, $parameters, "GET");
                break;
            case 'POST' :
                $response = $this->request($url, $parameters, "POST");
                break;
        }

        if ($response && $this->decode_json) {
            $response = json_decode($response);
        }

        return $response;
    }
    /**
     * GET wrapper for provider apis request
     *
     * @param $url
     * @param array $parameters
     * @return mixed|null
     * @since
     */
    function get($url, $parameters = array()) {
        return $this->api($url, 'GET', $parameters);
    }
    /**
     * POST wrapper for provider apis request
     *
     * @param $url
     * @param array $parameters
     * @return mixed|null
     * @since
     */
    function post($url, $parameters = array()) {
        return $this->api($url, 'POST', $parameters);
    }

    /**
     * Get Token Info
     *
     * @param $accesstoken
     * @return mixed|\StdClass
     * @since
     */
    public function tokenInfo($params) {
        $params['access_token'] = get_option($this->database_prefix.'-access');
        $response = $this->request($this->token_info_url, $params);
    }
    /**
     * Use Refresh Token to make Request
     *
     * @param array $parameters
     * @return mixed|\StdClass
     * @since
     */
    public function refreshToken($refresh_token) {
        $params = array(
            "client_id" => $this->client_id,
            "client_secret" => $this->client_secret,
            "grant_type" => "refresh_token",
            "refresh_token" => $refresh_token
        );
        $response = $this->request($this->token_url, $params, "POST");
        return $this->parseRequestResult( $response );
    }
    /**
     * Make Curl Request
     *
     * @param $url
     * @param bool $params
     * @param string $type
     * @return mixed
     * @since
     */
    private function request($url, $params = false, $type = "GET") {
        if ($type == "GET") {
            $url = $url . (strpos($url, '?') ? '&' : '?') . http_build_query($params);
        }

        $this->http_info = array();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_time_out);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->curl_useragent);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->curl_connect_time_out);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->curl_ssl_verifypeer);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->curl_header);

        if ($type == "POST") {
            curl_setopt($ch, CURLOPT_POST, 1);
            if ($params) curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        $response = curl_exec($ch);

        $this->http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->http_info = array_merge($this->http_info, curl_getinfo($ch));

        curl_close($ch);

        return $response;
    }

    /**
     * Parse Request
     *
     * @param $result
     * @return \Sidebar_Support\StdClass
     * @since
     */
    private function parseRequestResult($result )
    {
        if( json_decode( $result ) ) return json_decode( $result );

        parse_str( $result, $ouput );

        $result = new \StdClass();

        foreach( $ouput as $k => $v )
            $result->$k = $v;

        return $result;
    }
}
