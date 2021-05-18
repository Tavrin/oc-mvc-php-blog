<?php


namespace Core\utils;


use Core\database\Repository;

class Paginator
{
    /**
     * @param array $content
     * @param int $currentPage
     * @param int $limit
     * @return array|null
     */
    public function paginateArray(array $content, int $currentPage, int $limit): ?array
    {
        $itemsToKeep = [];
        $content['pages'] = intval(ceil(count($content['items']) / $limit));
        $content['actualPage'] = $currentPage;
        if ($content['actualPage'] > $content['pages'] || $content['actualPage'] < 1) {
            $content['actualPage'] = 1;
        }
        $firstItem = ($content['actualPage'] * $limit) - $limit;
        for ($i = $firstItem; $i < $firstItem + $limit; $i++) {
            if (isset($content['items'][$i])) {
                $itemsToKeep[] = $content['items'][$i];
            }
        }

        return $itemsToKeep;
    }

    public function paginate(Repository $repository, int $currentPage, int $limit, string $column = null, string $order = null, string $row = null, string $criteria = null): ?array
    {
        $offset = ($currentPage * $limit) - $limit;
        $itemsCount = $repository->count()[0];
        isset($row, $criteria) ? $items = $repository->findBy($row, $criteria, $column, $order, $limit, $offset) : $items = $repository->findAll($column, $order, $limit, $offset);
        $content['pages'] = intval(ceil($itemsCount / $limit));
        $content['actualPage'] = $currentPage;
        $content['items'] = $items;

        return $content;
    }
}