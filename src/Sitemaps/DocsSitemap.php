<?php

namespace GreenCheap\Docs\Sitemaps;

use GreenCheap\Application as App;
use GreenCheap\Docs\Model\Docs;
use GreenCheap\Seo\SitemapInterface;
use GreenCheap\Seo\Sitemaps;
use GreenCheap\System\Model\StatusModelTrait;

class DocsSitemap implements SitemapInterface
{

    /**
     * @param int $page
     * @return array
     */
    public function getData($page = 0)
    {
        $data = [];
        if($page == 0){
            for ($i = 1; $i <= $this->getPages(); $i++){
                $data[] = [
                    "url" => [
                        "loc" => App::url('@sitemap/page', ["sitemap" => "docs", "page" => $i], 0),
                    ],
                ];
            }

            krsort($data);
        }else{
            foreach ($this->getDocs($page) as $doc) {
                $data[] = [
                    "url" => [
                        "loc" => App::url('@docs/id', ['id' => $doc->id ?: 0], 0),
                        "lastmod" => $doc->date->format(Sitemaps::getLastModFormat()),
                    ],
                ];
            }
        }

        return $data;
    }

    /**
     * @param $page
     * @return mixed
     */
    protected function getDocs($page): mixed
    {
        $page = $page == 0 ? 1 : $page;

        $query = Docs::where(['status = :status', 'date < :date'], ['status' => StatusModelTrait::getStatus('STATUS_PUBLISHED'), 'date' => new \DateTime]);

        $limit = Sitemaps::getPerLimit();

        $count = $query->count('id');
        $total = ceil($count / $limit);
        $page = max(1, min($total, $page));

        $query->offset(($page - 1) * $limit)->limit($limit)->orderBy('date', 'DESC');
        return $query->get();
    }

    /**
     * @return mixed
     */
    protected function getPages(): mixed
    {
        $query = Docs::where(['status = :status', 'date < :date'], ['status' => StatusModelTrait::getStatus('STATUS_PUBLISHED'), 'date' => new \DateTime]);

        return $query->count();
    }
}
