<?php

    namespace JuniorE\JoynApiClient;

    use GuzzleHttp\Client;
    use GuzzleHttp\ClientInterface;
    use GuzzleHttp\Exception\GuzzleException;
    use GuzzleHttp\Psr7\Request;
    use Psr\Http\Message\ResponseInterface;

    class JoynApiClient
    {
        // Build your next great package.
        /**
         * Version of our client.
         */
        const CLIENT_VERSION = '0.0.1';
        /**
         * Endpoint of the remote API.
         */
        const API_ENDPOINT = 'https://api-v2.acc.joyn.be/api/v2';
        /**
         * Version of the remote API.
         */
        const API_VERSION = 'v1';
        /**
         * HTTP Methods
         */
        const HTTP_GET    = 'GET';
        const HTTP_POST   = 'POST';
        const HTTP_DELETE = 'DELETE';
        const HTTP_PATCH  = 'PATCH';
        /**
         * HTTP status codes
         */
        const HTTP_NO_CONTENT = 204;
        /**
         * Default response timeout (in seconds).
         */
        const TIMEOUT = 10;
        /**
         * @var ClientInterface
         */
        protected $httpClient;
        /**
         * @var string
         */
        protected $apiEndpoint = self::API_ENDPOINT;

        public $tokens;

        /**
         * @var string
         */
        protected $apiKey;

        /**
         * True if an OAuth access token is set as API key.
         *
         * @var bool
         */
        protected $oauthAccess;
        /**
         * @var array
         */
        protected $versionStrings = [];
        /**
         * @var int
         */
        protected $lastHttpResponseStatusCode;

        public function __construct(ClientInterface $httpClient = null)
        {
            $this->httpClient = $httpClient ?:
                new Client([
                    \GuzzleHttp\RequestOptions::VERIFY  => \Composer\CaBundle\CaBundle::getBundledCaBundlePath(),
                    \GuzzleHttp\RequestOptions::TIMEOUT => self::TIMEOUT,
                ]);

            $this->initializeEndpoints();

            $this->addVersionString('Joyn/' . self::CLIENT_VERSION);
            $this->addVersionString('PHP/' . PHP_VERSION);
            $this->addVersionString('Guzzle/' . ClientInterface::VERSION);
        }

        public function initializeEndpoints(): void
        {
            $this->tokens = new TokenEndpoint($this);
        }

        public function addVersionString(string $versionString)
        {
            $this->versionStrings[] = str_replace([' ', "\t", "\n", "\r"], '-', $versionString);
            return $this;
        }


        /**
         * @param  string  $url
         *
         * @return JoynApiClient
         */
        public function setApiEndpoint($url): JoynApiClient
        {
            $this->apiEndpoint = rtrim(trim($url), '/');
            return $this;
        }

        /**
         * @return string
         */
        public function getApiEndpoint(): string
        {
            return $this->apiEndpoint;
        }

        /**
         * @param  string  $apiKey  The JOYN API key, starting with 'test_' or 'live_'
         *
         * @return JoynApiClient
         */
        public function setApiKey($apiKey): JoynApiClient
        {
            $apiKey = trim($apiKey);
            $this->apiKey = $apiKey;
            $this->oauthAccess = false;
            return $this;
        }

        /**
         * @param  string  $accessToken
         *
         * @return JoynApiClient
         */
        public function setAccessToken($accessToken): JoynApiClient
        {
            $accessToken = trim($accessToken);
            $this->apiKey = $accessToken;
            $this->oauthAccess = true;
            return $this;
        }

        public function performHttpCall($httpMethod, $apiMethod, $httpBody = null)
        {
            $url = $this->apiEndpoint . '/' . $apiMethod;
            return $this->performHttpCallToFullUrl($httpMethod, $url, $httpBody);
        }

        /**
         * Returns null if no API key has been set yet.
         *
         * @return bool|null
         */
        public function usesOAuth(): ?bool
        {
            return $this->oauthAccess;
        }

        public function performHttpCallToFullUrl($httpMethod, $url, $httpBody = null)
        {
            if (empty($this->apiKey)) {
                throw new ApiException('You have not set an API key or OAuth access token. Please use setApiKey() to set the API key.');
            }

            $headers = [
                'Content-Type'  => 'application/json',
                'Authorization' => "Bearer {$this->apiKey}",
            ];

            $request = new Request($httpMethod, $url, $headers, $httpBody);

            try {
                $response = $this->httpClient->send($request, ['https_errors' => false]);
            } catch (GuzzleException $e) {
                throw ApiException::createFromGuzzleException($e);
            }
            if ( !$response) {
                throw new ApiException('Did not receive API response.');
            }
            return $this->parseResponseBody($response);
        }

        /**
         * Parse the PSR-7 Response body
         *
         * @param  ResponseInterface  $response
         * @return \stdClass|null
         * @throws ApiException
         */
        private function parseResponseBody(ResponseInterface $response)
        {
            $body = (string)$response->getBody();
            if (empty($body)) {
                if ($response->getStatusCode() === self::HTTP_NO_CONTENT) {
                    return null;
                }
                throw new ApiException('No response body found.');
            }
            $object = @json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ApiException("Unable to decode Joyn response: '{$body}'.");
            }
            if ($response->getStatusCode() >= 400) {
                throw ApiException::createFromResponse($response);
            }
            return $object;
        }


    }
