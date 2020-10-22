<?php
namespace GreenCheap\Docs\Controller;

use GreenCheap\Application as App;
use GreenCheap\Docs\Model\Category;
use GreenCheap\Docs\Model\Docs;
use GreenCheap\System\Service\StatusModelService;
use GreenCheap\User\Model\Role;

/**
 * Class DocsController
 * @package GreenCheap\Docs\Controller
 * @Access(admin=true)
 * @Route(name="admin")
 */
class DocsController
{
    /**
     * @param array $filter
     * @param int $page
     * @Route("/post" , name="post")
     * @Request({"filter":"array" , "page":"integer"})
     * @return array
     */
    public function indexAction( array $filter = [] , $page = 0 ):array
    {

        $categories = Category::query()->orderBy('priority' , 'asc')->get();

        return [
            '$view' => [
                'title' => __('Docs Index'),
                'name' => 'docs:views/admin/index.php'
            ],
            '$data' => [
                'config' => [
                    'filter' => (object) $filter,
                    'page' => (int) $page
                ],
                'statuses' => StatusModelService::getStatuses(),
                'roles' => Role::findAll(),
                'categories' => array_values($categories)
            ]
        ];
    }

    /**
     * @Route("/post/edit" , name="post/edit")
     * @Request({"id":"integer"})
     * @param int $id
     * @return array
     */
    public function editAction( int $id = 0 )
    {

        $categories = array_values(Category::findAll());

        if(!$categories){
            return App::abort(400 , __('Not Found Categories'));
        }

        if(!$query = Docs::where(compact('id'))->first()){
            if($id){
                return App::abort(404 , __('Not Found Docs'));
            }
            $query = Docs::create([
                'user_id' => App::user()->id,
                'date' => new \DateTime(),
                'category_id' => $categories[0]->id,
                'status' => StatusModelService::getStatus('STATUS_DRAFT')
            ]);

            $module = App::module('docs');
            $query->set('markdown', $module->config('posts.markdown_enabled'));
        }

        return [
            '$view' => [
                'title' => $query->id ? __('Edit Docs'):__('New Docs'),
                'name' => 'docs:views/admin/edit.php'
            ],
            '$data' => [
                'data' => [
                    'statuses' => StatusModelService::getStatuses(),
                    'categories' => $categories,
                ],
                'query' => $query
            ]
        ];
    }

    /**
     * @Access("system: access settings")
     */
    public function settingsAction()
    {
        return [
            '$view' => [
                'title' => __('Docs Settings'),
                'name'  => 'docs/admin/settings.php'
            ],
            '$data' => [
                'config' => App::module('docs')->config()
            ]
        ];
    }
}
