<?php
    /**
     * Created by PhpStorm.
     * User: JuniorE.
     * Date: 2019-06-23
     * Time: 19:55
     */

    namespace JuniorE\JoynApiClient\Endpoints;


    use JuniorE\JoynApiClient\ApiException;
    use JuniorE\JoynApiClient\JoynApiClient;
    use JuniorE\JoynApiClient\Resources\BaseCollection;
    use JuniorE\JoynApiClient\Resources\BaseResource;
    use JuniorE\JoynApiClient\Resources\ResourceFactory;
    use JuniorE\JoynApiClient\Resources\TokenResource;

    abstract class Endpoint
    {
        const REST_CREATE = JoynApiClient::HTTP_POST;
        const REST_UPDATE = JoynApiClient::HTTP_PATCH;
        const REST_READ   = JoynApiClient::HTTP_GET;
        const REST_LIST   = JoynApiClient::HTTP_GET;
        const REST_DELETE = JoynApiClient::HTTP_DELETE;
        /**
         * @var JoynApiClient
         */
        protected $client;
        /**
         * @var string
         */
        protected $resourcePath;
        /**
         * @var string|null
         */
        protected $parentId;

        /**
         * @param  JoynApiClient  $api
         */
        public function __construct(JoynApiClient $api)
        {
            $this->client = $api;
        }

        /**
         * @param  array  $filters
         * @return string
         */
        protected function buildQueryString(array $filters)
        {
            if (empty($filters)) {
                return '';
            }
            foreach ($filters as $key => $value) {
                if ($value === true) {
                    $filters[$key] = 'true';
                }
                if ($value === false) {
                    $filters[$key] = 'false';
                }
            }
            return '?' . http_build_query($filters, '', '&');
        }

        /**
         * @param  array  $body
         * @param  array  $filters
         * @return BaseResource
         * @throws ApiException
         */
        protected function rest_create(array $body, array $filters): BaseResource
        {
            $result = $this->client->performHttpCall(
                self::REST_CREATE,
                $this->getResourcePath(),
                $this->parseRequestBody($body)
            );
            return ResourceFactory::createFromApiResult($result, new TokenResource($this->client));
        }

        /**
         * Get the object that is used by this API endpoint. Every API endpoint uses one type of object.
         *
         * @return BaseResource
         */
        abstract protected function getResourceObject();

        /**
         * @param  string  $resourcePath
         */
        public function setResourcePath($resourcePath): void
        {
            $this->resourcePath = strtolower($resourcePath);
        }

        /**
         * @return string
         * @throws ApiException
         */
        public function getResourcePath(): string
        {
            return $this->resourcePath;
        }

        /**
         * @param  array  $body
         * @return null|string
         * @throws ApiException
         */
        protected function parseRequestBody(array $body): ?string
        {
            if (empty($body)) {
                return null;
            }
            try {
                $encoded = \GuzzleHttp\json_encode($body);
            } catch (\InvalidArgumentException $e) {
                throw new ApiException("Error encoding parameters into JSON: '" . $e->getMessage() . "'.");
            }
            return $encoded;
        }
    }
