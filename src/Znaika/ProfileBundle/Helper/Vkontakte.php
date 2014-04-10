<?
    namespace Znaika\ProfileBundle\Helper;

    use Symfony\Component\DependencyInjection\ContainerInterface;

    class Vkontakte
    {
        const HTTPS_OAUTH_VK_COM_ACCESS_TOKEN   = 'https://oauth.vk.com/access_token';
        const HTTPS_API_VK_COM_METHOD_USERS_GET = 'https://api.vk.com/method/users.get';
        const HTTP_OAUTH_VK_COM_AUTHORIZE = 'http://oauth.vk.com/authorize';
        /**
         * @var ContainerInterface
         */
        protected $container;

        public function __construct(ContainerInterface $container)
        {
            $this->container = $container;
        }

        public function getLoginUrl()
        {
            $url    = self::HTTP_OAUTH_VK_COM_AUTHORIZE;
            $params = array(
                'client_id'     => $this->container->getParameter('vk_client_id'),
                'redirect_uri'  => $this->container->getParameter('vk_redirect_uri'),
                'response_type' => 'code'
            );

            return $url . '?' . urldecode(http_build_query($params));
        }

        public function getUserInfo($code)
        {
            $response = null;

            $clientId     = $this->container->getParameter('vk_client_id');
            $redirectUri  = $this->container->getParameter('vk_redirect_uri');
            $clientSecret = $this->container->getParameter('vk_client_secret');

            $params = array(
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'code'          => $code,
                'redirect_uri'  => $redirectUri

            );

            $token = self::vkCurl(self::HTTPS_OAUTH_VK_COM_ACCESS_TOKEN, $params);

            if (isset($token['access_token']))
            {

                $params = array(
                    'uids'         => $token['user_id'],
                    'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
                    'access_token' => $token['access_token']
                );

                $userInfo = self::vkCurl(self::HTTPS_API_VK_COM_METHOD_USERS_GET, $params);
                $response = (array)$userInfo["response"]["0"];
            }

            return $response;
        }

        public function vkCurl($url, $params)
        {
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_URL, $url . '?' . urldecode(http_build_query($params)));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

            $res = (array)json_decode(curl_exec($curl));
            curl_close($curl);

            return $res;
        }
    }