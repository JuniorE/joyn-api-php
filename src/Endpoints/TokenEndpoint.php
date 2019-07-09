<?php
    /**
     * Created by PhpStorm.
     * User: JuniorE.
     * Date: 2019-06-23
     * Time: 18:29
     */

    namespace JuniorE\JoynApiClient;

    use JuniorE\JoynApiClient\Endpoints\Endpoint;
    use JuniorE\JoynApiClient\Resources\BaseResource;
    use JuniorE\JoynApiClient\Resources\TokenResource;

    class TokenEndpoint extends Endpoint
    {

        /**
         * TokenEndpoint constructor.
         * @param  JoynApiClient  $param
         */
        //public function __construct(JoynApiClient $param) {
        //    parent::__construct($param);
        //}

        /**
         * Get the object that is used by this API endpoint. Every API endpoint uses one type of object.
         *
         * @return void
         */
        protected function getResourceObject()
        {
            new TokenResource($this->client);
        }


        /**
         * Creates token.
         *
         * @param  array  $data  An array containing details on the payment.
         * @param  array  $filters
         *
         * @return TokenResource
         * @throws ApiException
         */
        public function create(array $data = [], array $filters = []): TokenResource
        {
            return $this->rest_create($data, $filters);
        }


    }
