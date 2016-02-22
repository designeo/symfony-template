<?php

namespace AppBundle\Twig;

class Date extends \Twig_Extension
{
    const NAME = 'app__date';

    public function getFilters()
    {
        return array(
            'dateWithYear' => new \Twig_SimpleFilter('dateWithYear', [$this, 'date']),
            'datetime' => new \Twig_SimpleFilter('datetime', [$this, 'datetime']),
            'dateWithoutYear' => new \Twig_SimpleFilter('dateWithoutYear', [$this, 'simpleDate']),
            'weekday' => new \Twig_SimpleFilter('weekday', [$this, 'weekday']),
            'dateId' => new \Twig_SimpleFilter('dateId', [$this, 'dateId']),
        );
    }

    /**
     * @param $date
     *
     * @return string
     */
    public function datetime($date)
    {
        return $this->processDate($date, 'd.m.Y H:i');
    }

    /**
     * @param $date
     *
     * @return string
     */
    public function date($date)
    {
        return $this->processDate($date, 'd.m.Y');
    }

    /**
     * @param $date
     *
     * @return string
     */
    public function simpleDate($date)
    {
        return $this->processDate($date, 'j.n.');
    }

    /**
     * @param $date
     *
     * @return string
     */
    public function weekday($date)
    {
        return $this->processDate($date, 'D');
    }

    public function dateId($date)
    {
        return $this->processDate($date, 'Y-m-d');
    }

    /**
     * @param $date
     *
     * @param $format
     *
     * @return \DateTime|null
     */
    protected function processDate($date, $format)
    {
        if (is_null($date)) {
            return null;
        }

        if (is_string($date)) {
            $date = new \DateTime($date);
        }

        if (!$date instanceof \DateTime) {
            $dateObject = new \DateTime();
            $dateObject->setTimestamp(intval($date));

            $date = $dateObject;
        }

        return is_null($date) ? null : $date->format($format);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }
}