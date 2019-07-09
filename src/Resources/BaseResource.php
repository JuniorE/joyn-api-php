<?php
    /**
     * Created by PhpStorm.
     * User: JuniorE.
     * Date: 2019-06-23
     * Time: 19:48
     */

    namespace JuniorE\JoynApiClient\Resources;

    use JuniorE\JoynApiClient\JoynApiClient;

    abstract class BaseResource
    {
        /**
         * @var JoynApiClient
         */
        protected $client;

        /**
         * @param $client
         */
        public function __construct(JoynApiClient $client)
        {
            $this->client = $client;
        }
    }
