<?php
namespace App\Models\Goods;


use App\Models\BaseModel;

/**
 * App\Models\Goods\GoodsProduct
 *
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsProduct query()
 * @mixin \Eloquent
 */
class GoodsProduct extends BaseModel
{
    protected $table = 'goods_product';

    /**
     * @var array
     */
    protected $fillable = [


    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should  be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deleted' => 'boolean',

    ];
}
