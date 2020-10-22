<?php

namespace GreenCheap\Docs\Controller;

use GreenCheap\Docs\Model\Category;
use GreenCheap\Docs\Model\Docs;
use GreenCheap\System\Model\StatusModelTrait;
use GreenCheap\Application as App;

class SiteController
{
    /**
     * @Route("/")
     * @Route("/{id}" , name="id")
     * @Captcha(verify="true")
     */
    public function indexAction(string $id = '')
    {
        $categories = Category::where(['status = :status'], ['status' => StatusModelTrait::getStatus('STATUS_PUBLISHED')])->where(function ($query) {
            return $query->where('roles IS NULL')->whereInSet('roles', App::user()->roles, false, 'OR');
        })->orderBy('priority' , 'asc')->related('posts');

        $query = Docs::where(['status = :status', 'date < :date'], ['status' => StatusModelTrait::getStatus('STATUS_PUBLISHED'), 'date' => new \DateTime]);
        if ($id) {
            $query->where('id = :id', ['id' => $id]);
        } else {
            if(!$getFirstDocs = Category::getFirstPost()){
                return App::abort(404 , __('Not Found Docs'));
            }
            $query->where('id = :id', ['id' => $getFirstDocs]);
        }
        $query->related('user');
        $post = $query->first();

        if(!$post || !$post->hasAccess()){
            return App::abort(404 , __('Not Found Document'));
        }

        $post->content = App::content()->applyPlugins($post->content, ['post' => $post, 'markdown' => $post->get('markdown')]);

        $post->links = $this->doMarkdownLinks($post->content);
        $description = $post->get('meta.og:description');

        if (!$description) {
            $description = strip_tags($post->content);
            $description = rtrim(mb_substr($description, 0, 150), " \t\n\r\0\x0B.,") . '...';
        }

        return [
            '$view' => [
                'title' => $post->title,
                'name' => 'docs/index.php',
                'og:type' => 'article',
                'article:published_time' => $post->date->format(\DateTime::ATOM),
                'article:modified_time' => $post->modified->format(\DateTime::ATOM),
                'article:author' => $post->user->name,
                'og:title' => $post->get('meta.og:title') ?: $post->title,
                'og:description' => $description,
                'og:image' =>  $post->get('image.src') ? App::url()->getStatic($post->get('image.src'), [], 0) : false
            ],
            'post' => $post,
            'categories' => $categories->get()
        ];
    }

    protected function doMarkdownLinks($s) 
    {
        $regex = '/<h1?2?3?4?5?6? id="(.*)">(.*)<\/h1?2?3?4?5?6?>/';
        preg_match_all($regex , $s , $match);
        $data = [];
        foreach($match[2] as $key => $value){
            $data[] = ['name' => $value , 'src' => $match[1][$key]];
        }
        return $data;
    }
}
