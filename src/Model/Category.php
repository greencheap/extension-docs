<?php
namespace GreenCheap\Docs\Model;

use GreenCheap\Database\ORM\Annotation\Entity;
use GreenCheap\Database\ORM\Annotation\HasMany;
use GreenCheap\System\Model\DataModelTrait;
use GreenCheap\User\Model\AccessModelTrait;

/**
 * Class Category
 * @package GreenCheap\Docs\Model
 * @Entity(tableClass="@docs_category")
 */
class Category implements \JsonSerializable
{
    use CategoryModelTrait,DataModelTrait,AccessModelTrait;

    const STATUS_UNPUBLISHED = 0;
    const STATUS_PUBLISHED = 1;

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
     * @HasMany(targetEntity="Post" , keyFrom="category_id" , orderBy={"priority":"ASC"})
     */
    public $posts;

    /**
     * @var array
     */
    protected static $_properties = [];

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
