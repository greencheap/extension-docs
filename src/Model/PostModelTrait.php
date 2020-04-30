<?php

namespace GreenCheap\Docs\Model;

use GreenCheap\Database\ORM\Annotation\Saving;
use GreenCheap\Database\ORM\ModelTrait;

trait PostModelTrait
{
    use ModelTrait;

    /**
     * @Saving
     * @param $event
     * @param Post $post
     */
    public static function saving($event , Post $post)
    {

        $data = $post;

        $i  = 2;
        $a = 2;
        $id = $data->id;

        $data->modified = new \DateTime();

        while (self::where('title = ?', [$data->title])->where(function ($query) use ($id) {
            if ($id) {
                $query->where('id <> ?', [$id]);
            }
        })->first()) {
            $data->title = preg_replace('/-\d+$/', '', $data->title).'-'.$a++;
        }

        while (self::where('slug = ?', [$data->slug])->where(function ($query) use ($id) {
            if ($id) {
                $query->where('id <> ?', [$id]);
            }
        })->first()) {
            $data->slug = preg_replace('/-\d+$/', '', $data->slug).'-'.$i++;
        }

    }
}
