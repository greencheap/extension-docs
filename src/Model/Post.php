<?php
namespace GreenCheap\Docs\Model;

use GreenCheap\Database\ORM\Annotation\BelongsTo;
use GreenCheap\Database\ORM\Annotation\Entity;
use GreenCheap\System\Model\DataModelTrait;

/**
 * Class Post
 * @package GreenCheap\Docs\Model
 * @Entity(tableClass="@docs_post")
 */
class Post implements \JsonSerializable
{
    use PostModelTrait , DataModelTrait;

    const STATUS_UNPUBLISHED = 0;
    const STATUS_PUBLISHED = 1;

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
     * @Column(type="integer")
     */
    public $status;

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
     * @var array
     */
    protected static $_properties = [

    ];

    /**
     * @return array
     */
    public static function getStatuses():array
    {
        return [
            self::STATUS_UNPUBLISHED => __('UnPublished'),
            self::STATUS_PUBLISHED => __('Published')
        ];
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
