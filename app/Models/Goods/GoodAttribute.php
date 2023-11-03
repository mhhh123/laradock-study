<?php
namespace App\Models\Goods;


use App\Models\BaseModel;

/**
 * App\Models\Goods\GoodAttribute
 *
 * @method static \Illuminate\Database\Eloquent\Builder|GoodAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodAttribute query()
 * @mixin \Eloquent
 */
class GoodAttribute extends BaseModel
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

