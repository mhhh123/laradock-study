<?php
namespace App\Models\Goods;


use App\Models\BaseModel;

/**
 * App\Models\Goods\Brand
 *
 * @property int $id
 * @property string $name 品牌商名称
 * @property string $desc 品牌商简介
 * @property string $pic_url 品牌商页的品牌商图片
 * @property int|null $sort_order
 * @property float|null $floor_price 品牌商的商品低价，仅用于页面展示
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand query()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereFloorPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand wherePicUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereUpdateTime($value)
 * @mixin \Eloquent
 */
class Brand extends BaseModel
{
    protected $table='brand';

    /**
     * @var array
     */


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */


    /**
     * The attributes that should  be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'floor_price'=>'float'
    ];


}

