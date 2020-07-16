<?php

namespace GreenCheap\Docs\Controller;

use GreenCheap\Docs\Model\Category;
use GreenCheap\Docs\Model\Post;
use GreenCheap\System\Model\StatusModelTrait;
use GreenCheap\Application as App;

class SiteController
{
    /**
     * @Route("/")
     * @Route("/{slug}" , name="slug") 
     */
    public function indexAction(string $slug = '')
    {
        $date = new \DateTime;
        $categories = Category::where(['status = ?'], [StatusModelTrait::getStatus('STATUS_PUBLISHED')])->where(function ($query) {
            return $query->where('roles IS NULL')->whereInSet('roles', App::user()->roles, false, 'OR');
        })->orderBy('priority' , 'asc')->related('posts');

        $query = Post::where(['status = ?', 'date < ?'], [StatusModelTrait::getStatus('STATUS_PUBLISHED'), $date->format('Y-m-d h:m:s')]);
        if ($slug) {
            $query->where('slug = ?', [$slug]);
        } else {
            $query->where('id = ?', [Category::getFirstPost()]);
        }

        $doc = $query->related('user')->first();

        if(!$doc || !$doc->hasAccess()){
            return App::abort(404 , __('Not Found Document'));
        }
       
        $doc->content = App::content()->applyPlugins($doc->content, ['markdown' => true]);
        $doc->links = $this->doMarkdownLinks($doc->content);
        $description = $doc->get('v.og:description');
        if (!$description) {
            $description = strip_tags($doc->content);
            $description = rtrim(mb_substr($description, 0, 150), " \t\n\r\0\x0B.,") . '...';
        }

        return [
            '$view' => [
                'title' => $doc->title,
                'name' => 'docs/index.php',
                'og:type' => 'article',
                'article:author' => $doc->user->name,
                'og:title' => $doc->get('v.og:title') ?: $doc->title,
                'og:description' => $description,
                'og:keywords' => $doc->get('v.og:keyword'),
            ],
            'doc' => $doc,
            'categories' => $categories->get()
        ];
    }

    protected function doMarkdownLinks($s) {
        $regex = '/<h1?2?3?4?5?6? id="(.*)">(.*)<\/h1?2?3?4?5?6?>/';
        preg_match_all($regex , $s , $match);
        $data = [];
        foreach($match[2] as $key => $value){
            $data[] = ['name' => $value , 'src' => $match[1][$key]];
        }
        return $data;
    }
}
