<?php


namespace YOYOW\Utils;


class HttpClient
{
    static private $instance;
    private $url;

    private $rpcType = 'database';
    const RPC_TYPE_DATABASE = 'database';
    const RPC_TYPE_BROADCAST = 'network_broadcast';
    const RPC_TYPE_HISTORY = 'history';

    public $status;
    public $error;
    public $raw_response;
    public $response;
    static $id = 0;

    private function __clone(){}

    static public function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setRpcType($rpcType) {
        $this->rpcType = $rpcType;
        return $this;
    }

    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    public function __call($method, $params)
    {
        $this->status       = null;
        $this->error        = null;
        $this->raw_response = null;
        $this->response     = null;

        $params = array_values($params);
        HttpClient::$id++;
        $params = array($this->rpcType, $method, $params);

        $request = json_encode(array(
            'id'     => HttpClient::$id,
            'method' => "call",
            'jsonrpc' => "2.0",
            'params' => $params,
        ), JSON_UNESCAPED_UNICODE);

        if ($method == 'broadcast_transaction_with_callback') {
            var_dump($request);
        }

        $curl  = curl_init();
        $options = array(
            CURLOPT_URL            => $this->url,
            CURLOPT_HTTPHEADER     => array('Accept: application/json;charset=utf-8','Content-Type: application/json; charset=utf-8'),
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => $request,
            CURLOPT_USERAGENT      => "Mozilla/5.0 (iPhone; CPU iPhone OS 13_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => 0
        );
        curl_setopt_array($curl, $options);


        $this->raw_response = curl_exec($curl);
        $this->response     = json_decode($this->raw_response, true);
        $this->status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($curl);

        curl_close($curl);

        if (!empty($curl_error)) {
            $this->error = $curl_error;
        }
        if (array_key_exists("error", $this->response) && $this->response['error']) {
            $this->error = $this->response['error']['message'];
        } elseif ($this->status != 200) {
            switch ($this->status) {
                case 400:
                    $this->error = 'HTTP_BAD_REQUEST';
                    break;
                case 401:
                    $this->error = 'HTTP_UNAUTHORIZED';
                    break;
                case 403:
                    $this->error = 'HTTP_FORBIDDEN';
                    break;
                case 404:
                    $this->error = 'HTTP_NOT_FOUND';
                    break;
            }
        }

        if ($this->error) {
            return false;
        }
        return $this->response['result'];
    }
}