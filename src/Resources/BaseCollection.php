<?php
    /**
     * Created by PhpStorm.
     * User: JuniorE.
     * Date: 2019-06-23
     * Time: 19:49
     */

    namespace JuniorE\JoynApiClient\Resources;


    abstract class BaseCollection extends \ArrayObject
    {
        /**
         * Total number of retrieved objects.
         *
         * @var int
         */
        public $count;
        /**
         * @var \stdClass
         */
        public $_links;

        /**
         * @param  int  $count
         * @param  \stdClass  $_links
         */
        public function __construct($count, $_links)
        {
            $this->count = $count;
            $this->_links = $_links;
        }

        /**
         * @return string|null
         */
        abstract public function getCollectionResourceName();
    }
