<?php
    /**
     * Created by PhpStorm.
     * User: JuniorE.
     * Date: 2019-06-23
     * Time: 19:52
     */

    namespace JuniorE\JoynApiClient\Resources;

    use JuniorE\JoynApiClient\JoynApiClient;

    class ResourceFactory
    {
        /**
         * Create resource object from Api result
         *
         * @param  object  $apiResult
         * @param  BaseResource  $resource
         *
         * @return BaseResource
         */
        public static function createFromApiResult($apiResult, BaseResource $resource): BaseResource
        {
            foreach ($apiResult as $property => $value) {
                $resource->{$property} = $value;
            }
            return $resource;
        }
    }
