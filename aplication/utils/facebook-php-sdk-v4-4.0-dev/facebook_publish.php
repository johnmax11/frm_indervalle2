<?php 
namespace MiProyecto{
    error_reporting(E_ALL);
    ini_set('error_reporting', E_ERROR | ~E_WARNING);
    ini_set('display_errors',1);
    // Define the root directoy
    define( 'ROOT', dirname( __FILE__ ) . '/' );
    // Autoload the required files
    require_once( ROOT . '/autoload.php' );
    // include required files form Facebook SDK

    require_once( $_SERVER['DOCUMENT_ROOT'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/src/Facebook/HttpClients/FacebookHttpable.php' );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/src/Facebook/HttpClients/FacebookCurl.php' );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/src/Facebook/FacebookCanvasLoginHelper.php' );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/src/Facebook/HttpClients/FacebookCurlHttpClient.php' );

    require_once( $_SERVER['DOCUMENT_ROOT'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/src/Facebook/Entities/AccessToken.php' );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/src/Facebook/Entities/SignedRequest.php' );

    require_once( $_SERVER['DOCUMENT_ROOT'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/src/Facebook/FacebookSession.php' );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/src/Facebook/FacebookRedirectLoginHelper.php' );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/src/Facebook/FacebookRequest.php' );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/src/Facebook/FacebookResponse.php' );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/src/Facebook/FacebookSDKException.php' );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/src/Facebook/FacebookRequestException.php' );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/src/Facebook/FacebookOtherException.php' );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/src/Facebook/FacebookAuthorizationException.php' );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/src/Facebook/GraphUser.php' );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/src/Facebook/GraphObject.php' );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/src/Facebook/GraphSessionInfo.php' );
    
    require_once( $_SERVER['DOCUMENT_ROOT'].'/aplication/utils/facebook-php-sdk-v4-4.0-dev/src/Facebook/Entities/AccessToken.php' );

    use Facebook\FacebookSession;
    use Facebook\FacebookCanvasLoginHelper;
    use Facebook\FacebookRedirectLoginHelper;
    use Facebook\FacebookRequest;
    use Facebook\FacebookResponse;
    use Facebook\FacebookSDKException;
    use Facebook\FacebookRequestException;
    use Facebook\FacebookAuthorizationException;
    use Facebook\GraphObject;
    use Facebook\GraphUser;
    
    use Facebook\Entities\AccessToken;
    
    
    class facebook_publish{
        private $_session = null;
        private $_token_long = null;
        function __construct($token_long) {
            $this->_token_long = $token_long;
            // init app with app id (APPID) and secret (SECRET)
            FacebookSession::setDefaultApplication('1497602370514291','5fb3e6e763d8bd404518641eb5ee77c4');
            /****/
            $this->connect_facebook();
        }
        
        /*****/
        public function connect_facebook(){
            // login helper with redirect_uri
            $helper = new FacebookCanvasLoginHelper();
            try {
              $this->_session = new FacebookSession($this->_token_long);
              $accessToken = $this->_session->getAccessToken();
              //echo '--><pre>'.print_r($accessToken,true).'</pre>';  
              $obj_info = $this->_session->getSessionInfo();
              $obj_at = new AccessToken($this->_token_long);
              //echo '<pre>'.print_r($obj_at->getInfo("1497602370514291", "5fb3e6e763d8bd404518641eb5ee77c4"),true).'</pre>';
              $obj_da = $obj_at->get_expires_at();
              
              $longLivedAccessToken = $accessToken->extend();
            } catch(FacebookRequestException $ex) {
              // When Facebook returns an error
                echo "Exception occured, code: " . $ex->getCode();
                echo " with message: " . $ex->getMessage();
            } catch(\Exception $ex) {
              // When validation fails or other local issues
            }
        }
        
        /****/
        public function publish_message($name,$caption,$msj,$array_f){
            if ($this->_session) {
                try {
                    $request = new FacebookRequest(
                      $this->_session,
                      'GET',
                      '/me/permissions'
                    );
                    $response = $request->execute();
                    $graphObject = $response->getGraphObject();
                    // make api call
                    $response = (new FacebookRequest(
                        $this->_session,
                        'POST', 
                        '/me/photos', 
                        array(
                            'name' => $name,
                            'caption' => $caption,
                            'source' => class_exists('\CurlFile', false) ? new \CURLFile($array_f) : "@{$array_f[0]}",
                            /*'link' => 'http://vamels.com/',*/
                            'message' => $msj
                        )
                      ))->execute()->getGraphObject()->asArray();
                } catch(FacebookRequestException $e) {
                  echo "Exception occured, code: " . $e->getCode();
                  echo " with message: " . $e->getMessage();
                }
            }
        }
        
        /****/
        public function get_new_token_long($longLivedAccessToken){
            try{
                /**************************************************/
                try {
                  // Get a code from a long-lived access token
                  $code = AccessToken::getCodeFromAccessToken($longLivedAccessToken);
                } catch(FacebookSDKException $e) {
                  echo 'Error getting code: ' . $e->getMessage();
                  exit;
                }

                try {
                  // Get a new long-lived access token from the code
                  $newLongLivedAccessToken = AccessToken::getAccessTokenFromCode($code);
                } catch(FacebookSDKException $e) {
                  echo 'Error getting a new long-lived access token: ' . $e->getMessage();
                  exit;
                }
                //$this->_session = new FacebookSession($newLongLivedAccessToken);
                return $newLongLivedAccessToken;
            } catch (Exception $ex) {
                return false;
            }
        }
        
        /****/
        public function get_expires_token(){
            try{
                $obj_at = new AccessToken($this->_token_long);
                /**generamos la info del token*/
                $obj_at->getInfo("1497602370514291", "5fb3e6e763d8bd404518641eb5ee77c4");
                /**return fecha en format int*/
                return  date_format($obj_at->get_expires_at(),"Ymd");
            } catch (Exception $ex) {
                return false;
            }
        }
    }
}

 

