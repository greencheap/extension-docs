<?php
namespace GreenCheap\Docs\Controller;

use GreenCheap\Application as App;
use GreenCheap\Docs\Model\Category;
use GreenCheap\Routing\Annotation\Request;
use GreenCheap\User\Annotation\Access;

/**
 * Class ApiDocsController
 * @package GreenCheap\Docs\Controller
 * @Access(admin=true)
 * @Route("/api" , name="admin/api")
 */
class ApiDocsController
{
    
    /**
     * @Route(methods="POST")
     * @Request({"category" : "array" , "id":"integer"} , csrf=true)
     * @param array $category
     * @param int $id
     */
    public function saveCategoryAction( array $category = [] , int $id = 0  )
    {
        if( !$query = Category::where(compact('id'))->first() ){
            if($id){
                return App::abort(404 , __('Not Found Category'));
            }
            $query = Category::create([
                'date' => new \DateTime()
            ]);
        }

        if(!$category['slug'] = App::filter($category['slug'] ?: $category['title'], 'slugify')){
            return App::abort(400, __('Invalid slug.'));
        }

        $query->save($category);
        return compact('query');
    }

    /**
     * @Route(methods="GET")
     * @Request({"id":"integer"} , csrf=true)
     * @param int $id
     * @return array
     */
    public function bulkCategoryDeleteAction( int $id = 0)
    {
        if( !$category = Category::find($id)){
            return App::abort(404 , __('Not Found Category'));
        }
        $category->delete();
        return [
            'msg' => true
        ];
    }
}
