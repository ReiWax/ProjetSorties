<?php
namespace App\Data;

class SearchData
{

    /**
     * @var null|string
     */
    public $name;
    
    /**
     * @var null|datetime
     */
    public $dateTimeStartAt;

      /**
     * @var null|datetime
     */
    public $dateLimitRegistrationAt;

    /**
     * @var null|boolean
     */
    public $eventIsOrganizer;

     /**
     * @var null|boolean
     */
    public $eventIsRegistered;

      /**
     * @var null|boolean
     */
    public $eventIsNotRegistered;

      /**
     * @var null|boolean
     */
    public $eventFinished;
    
}