<?php


namespace app\components\utils;

/**
 * @class Pagination 
 * @brief class to generate pagination pages.
 * @version : 1.0
 */
class Pagination
{

    /**
     * @brief Get Pagination details
     * @param int $totalRecords
     * @param int $pageOffset
     * @param int $pageSize
     * @return array $pages
     */
    public $pagesize;
    public $pageOffset;
    public $pageNumber;
    public $startTime;
    public $endTime;
    
    private $timeDelimiter;

    public function __construct($apiParams, $timeDelimiter)
    {
        $this->totalRecords = 0;

        $this->pageNumber = ($apiParams['page_number'] < 1) ? 1:$apiParams['page_number'];
        $this->pageSize = ($apiParams['page_size'] > 0) ? $apiParams['page_size'] : 0;
        $this->pageOffset = ($this->pageNumber > 0 && $this->pageSize > 0 ) ? ($this->pageNumber) * $this->pageSize : 0;
        $this->timeDelimiter = $timeDelimiter;
        
        $endTime = isset($apiParams['end_time']) ? $apiParams['end_time'] : self::getCurrentTime();
        $this->endTime = $this->removeDelimiter($this->timeDelimiter, $endTime);


        $startTime = isset($apiParams['start_time']) ? $apiParams['start_time'] : '';
        $this->startTime = $this->removeDelimiter($this->timeDelimiter, $startTime);

        $allRecords = (isset($apiParams['all_records']) && $apiParams['all_records'] == 1) ? true : false;
        if ($allRecords == true) {
            $this->pageSize = &$this->totalRecords;
        }

        
    }

    public function setPages($response)
    {
        $pages = [];
        if ($this->pageSize > 0 && $this->totalRecords > 0) {
            $lastPage = ceil($this->totalRecords / $this->pageSize);

            $currentPage = ceil($this->pageOffset / $this->pageSize);
            
            if ($currentPage >= $lastPage) {
                $pages['last_page'] = true;
            } else {
                $pages['last_page'] = false;
            }
            $pages['page_number'] = "$currentPage";
            $pages['max_length'] = $this->totalRecords;
        } else {
            $pages['last_page'] = true;
            $pages['page_number'] = $this->pageNumber;
            $pages['max_length'] = $this->totalRecords;
        }

        $pages['end_time'] = $this->addDelimiter($this->timeDelimiter, $this->endTime);
        return array_merge($response, $pages);
    }

    /**
     * @brief current time with milli seconds
     * @return current time with milli seconds
     */
    public static function getCurrentTime()
    {
        return date("Y-m-d H:i:s") . substr((string) microtime(), 1, 8);
    }

    private function removeDelimiter($timeDelimiter, $time)
    {
        return str_replace($timeDelimiter, ' ', $time);
    }

    private function addDelimiter($timeDelimiter, $time)
    {
        return str_replace(' ', $timeDelimiter, $time);
    }

}
