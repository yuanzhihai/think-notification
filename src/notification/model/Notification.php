<?php

namespace yzh52521\notification\model;

use think\model\Collection;
use think\Model;

/**
 * Class Notification
 * @package yzh52521\notification\model
 *
 * @property integer $id
 * @property string $type
 * @property string $data
 * @property string $read_time
 */
class Notification extends Model
{

    protected $type = [
        'read_time' => 'datetime',
        'data'      => 'array',
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function markAsRead()
    {
        if ( is_null($this->getData('read_time')) ) {
            $this->save(['read_time' => time()]);
        }
    }

    /**
     * 转换数据集为数据集对象
     * @access public
     * @param array|Collection $collection 数据集
     * @param string|null $resultSetType 数据集类
     * @return Collection
     */
    public function toCollection(iterable $collection = [], string $resultSetType = null): Collection
    {
        return new NotificationCollection($collection);
    }
}
