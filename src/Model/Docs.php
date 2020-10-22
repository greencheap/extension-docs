<?php
namespace GreenCheap\Docs\Model;

use GreenCheap\Application as App;
use GreenCheap\Database\ORM\Annotation\BelongsTo;
use GreenCheap\Database\ORM\Annotation\Entity;
use GreenCheap\System\Model\DataModelTrait;
use GreenCheap\System\Model\StatusModelTrait;
use GreenCheap\Docs\Model\Category;

/**
 * Class Docs
 * @package GreenCheap\Docs\Model
 * @Entity(tableClass="@docs_post")
 */
class Docs implements \JsonSerializable
{
    use DocsModelTrait , DataModelTrait , StatusModelTrait;

    /**
     * @Column(type="integer")
     * @Id
     */
    public $id;

    /**
     * @Column(type="integer")
     */
    public $user_id;

    /**
     * @Column(type="integer")
     */
    public $category_id;

    /**
     * @Column
     */
    public $title;

    /**
     * @Column
     */
    public $slug;

    /**
     * @Column(type="datetime")
     */
    public $date;

    /**
     * @Column(type="datetime")
     */
    public $modified;

    /**
     * @Column(type="text")
     */
    public $content;

    /**
     * @Column(type="integer")
     */
    public $priority = 999;

    /**
     * @BelongsTo(targetEntity="GreenCheap\User\Model\User" , keyFrom="user_id")
     */
    public $user;

    /**
     * @BelongsTo(targetEntity="Category" , keyFrom="category_id")
     */
    public $category;

    /**
     * @var array
     */
    protected static $properties = [
        'category_name' => 'getCategory',
        'published' => 'isPublished',
        'accessible' => 'isAccessible'
    ];

    /**
     * @return string|void
     */
    public function getCategory()
    {
        if($this->category){
            return $this->category->title;
        }
        return;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->status === self::getStatus('STATUS_PUBLISHED') && $this->date < new \DateTime;
    }

    /**
     * @return bool
     */
    public function isAccessible()
    {
        return $this->isPublished();
    }

    public function hasAccess()
    {
        $query = Category::where(['status = ?' , 'id = ?'], [StatusModelTrait::getStatus('STATUS_PUBLISHED') , $this->category_id])->where(function ($query) {
            return $query->where('roles IS NULL')->whereInSet('roles', App::user()->roles, false, 'OR');
        })->first();

        if($query){
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $data = [
            'url' => App::url('@docs/id', ['id' => $this->id ?: 0], 'base')
        ];


        return $this->toArray($data);

    }
}
