<?php
namespace App\Models\Goods;


use App\Models\BaseModel;

/**
 * App\Models\Goods\GoodsAttribute
 *
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsAttribute query()
 * @mixin \Eloquent
 */
class GoodsAttribute extends BaseModel
{


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
        'deleted'=>'boolean',

    ];


}

