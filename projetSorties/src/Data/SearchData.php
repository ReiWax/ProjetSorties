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
    public $dateFinishAt;

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