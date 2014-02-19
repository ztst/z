<?
    namespace Znaika\FrontendBundle\Helper;

    /*
     * (c) digitorum.ru / Pavel Ladygin
     */
    class Odnoklassniki
    {
        protected $connectionData = array();

        protected $authUrl = 'http://www.odnoklassniki.ru/oauth/authorize';

        protected $tokenUrl = 'http://api.odnoklassniki.ru/oauth/token.do?';

        protected $apiUrl = 'http://api.odnoklassniki.ru/fb.do?';

        protected $redirectUrl = '';

        protected $token = array();

        public function __construct($connectionData = array())
        {
            $this->connectionData = $connectionData;
        }

        public function setRedirectUrl($url = '')
        {
            $this->redirectUrl = $url;
        }

        public function getLoginUrl($scope = array())
        {
            return $this->authUrl . '?'
            . http_build_query(
                array(
                    'client_id'     => $this->connectionData['client_id'],
                    'response_type' => 'code',
                    'redirect_uri'  => $this->redirectUrl,
                    'scope'         => implode(';', $scope)
                )
            );
        }

        public function error($array)
        {
            throw new \Exception($array['error'] . ':' . (isset($array['error_description']) ? $array['error_description'] : ''));
        }

        public function setToken($token)
        {
            if (is_string($token))
            {
                $token = json_decode($token, true);
            }
            $this->token = $token;
        }

        public function getTokenStr()
        {
            return json_encode($this->token);
        }

        public function getToken($code = '')
        {
            if ($code)
            {
                $this->token = $this->sendRequest(
                                    $this->tokenUrl,
                                        array(
                                            'code'          => $code,
                                            'redirect_uri'  => $this->redirectUrl,
                                            'grant_type'    => 'authorization_code',
                                            'client_id'     => $this->connectionData['client_id'],
                                            'client_secret' => $this->connectionData['client_secret']
                                        )
                );
                if (isset($this->token['error']))
                {
                    $this->error($this->token);
                }
                else
                {
                    $this->token['expires'] = time() + 30 * 60; // Маркер доступа имеет ограниченное время существования - 30 минут
                }
            }
        }

        public function refreshToken()
        {
            $this->token = $this->sendRequest(
                                $this->tokenUrl,
                                    array(
                                        'refresh_token' => $this->token['refresh_token'],
                                        'grant_type'    => 'refresh_token',
                                        'client_id'     => $this->connectionData['client_id'],
                                        'client_secret' => $this->connectionData['client_secret']
                                    )
            );
            if (isset($this->token['error']))
            {
                $this->error($this->token);
            }
        }

        public function getAccessToken()
        {
            if (isset($this->token['access_token']))
            {
                if (isset($this->token['expires']) && $this->token['expires'] < time())
                {
                    $this->refreshToken();
                }

                return $this->token['access_token'];
            }

            return false;
        }

        public function api($action = '', $parameters = array(), $method = 'POST')
        {
            $accessToken = $this->getAccessToken();
            $paramsArray = array(
                'application_key=' . $this->connectionData['application_key'],
                'method=' . $action
            );
            foreach ($parameters as $k => $v)
            {
                $paramsArray[] = $k . '=' . urlencode($v);
            }
            sort($paramsArray);
            $sig           = md5(
                implode("", $paramsArray)
                . md5(
                    $accessToken
                    . $this->connectionData['client_secret']
                )
            );
            $paramsArray[] = 'access_token=' . $accessToken;
            $paramsArray[] = 'sig=' . $sig;
            sort($paramsArray);

            return $this->sendRequest(
                        $this->apiUrl,
                            implode("&", $paramsArray),
                            $method
            );
        }

        protected function sendRequest($url = '', $params = array(), $method = 'POST')
        {
            if (is_array($params))
            {
                $params = http_build_query($params);
            }
            $ch = curl_init();
            if ($method == 'GET')
            {
                $url .= $params;
            }
            else
            {
                if ($method == 'POST')
                {
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                }
            }
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $result = curl_exec($ch);
            curl_close($ch);

            return json_decode($result, true);
        }
    }
