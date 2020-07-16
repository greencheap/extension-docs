<?php
namespace GreenCheap\Docs\Model;

use GreenCheap\Application as App;
use GreenCheap\Database\ORM\Annotation\Entity;
use GreenCheap\Database\ORM\Annotation\HasMany;
use GreenCheap\System\Model\DataModelTrait;
use GreenCheap\System\Model\StatusModelTrait;
use GreenCheap\User\Model\AccessModelTrait;

/**
 * Class Category
 * @package GreenCheap\Docs\Model
 * @Entity(tableClass="@docs_category")
 */
class Category implements \JsonSerializable
{
    use CategoryModelTrait, DataModelTrait, AccessModelTrait, StatusModelTrait;

    /**
     * @Column(type="integer")
     * @Id
     */
    public $id;

    /**
     * @Column
     */
    public $title;

    /**
     * @Column
     */
    public $slug;

    /**
     * @Column(type="integer")
     */
    public $status;

    /**
     * @Column(type="integer")
     */
    public $priority = 999;

    /**
     * @HasMany(targetEntity="Post" , keyFrom="id" , keyTo="category_id")
     */
    public $posts;

    /**
     * @var array
     */
    protected static $properties = [
        'hasPost' => 'hasPost',
        'getPosts' => 'getPosts'
    ];

    /**
     * @return bool
     */
    public function hasPost():bool
    {
        if( $query = Post::where('category_id = ?' , [$this->id])->first() ){
            return true;
        }
        return false;
    }

    public function getPosts()
    {
        if($this->posts){
            $date = new \DateTime;
            $query = Post::where(['status = ?', 'date < ?' , 'category_id = ?'], [StatusModelTrait::getStatus('STATUS_PUBLISHED'), $date->format('Y-m-d h:m:s') , $this->id])->orderBy('priority' , 'asc')->get();
            return $query;
        }
        return false;
    }

    public static function getFirstPost()
    {
        $query = self::where(['status = ?'], [StatusModelTrait::getStatus('STATUS_PUBLISHED')])->where(function ($query) {
            return $query->where('roles IS NULL')->whereInSet('roles', App::user()->roles, false, 'OR');
        })->orderBy('priority' , 'asc')->first();
        $date = new \DateTime;
        $query = Post::where(['status = ?', 'date < ?'], [StatusModelTrait::getStatus('STATUS_PUBLISHED'), $date->format('Y-m-d h:m:s')])->orderBy('priority' , 'asc')->first();
        return $query->id;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
