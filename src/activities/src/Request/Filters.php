<?php

namespace App\Request;

class Filters
{
    private $filters = [];

    public function __construct(array $filters)
    {
        $this->parseFilters($filters);
    }

    public function addFilter(string $filter, $value)
    {
        $this->filters[$filter] = $value;
    }

    private function parseFilters(array $filters)
    {
        foreach ($filters as $name => $value) {
            if (false !== strpos($name, 'date')) {
                $date = \DateTime::createFromFormat('Y-m-d', $value);
                $date->setTime(0, 0, 0);
                $this->addFilter($name, $date);
            } else {
                $this->addFilter($name, $value);
            }
        }
    }

    public function getFilters(): array
    {
        return $this->filters;
    }
}
