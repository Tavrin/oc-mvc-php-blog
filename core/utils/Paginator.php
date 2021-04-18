<?php


namespace Core\utils;


class Paginator
{
    /**
     * @param array $content
     * @param int $currentPage
     * @param int $limit
     * @return array|null
     */
    public static function paginate(array $content, int $currentPage, int $limit): ?array
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
}