<?php
namespace App\Models\Goods;


use App\Models\BaseModel;

/**
 * App\Models\Goods\issue
 *
 * @property int $id
 * @property string|null $question 问题标题
 * @property string|null $answer 问题答案
 * @property \Illuminate\Support\Carbon|null $add_time 创建时间
 * @property \Illuminate\Support\Carbon|null $update_time 更新时间
 * @property bool|null $deleted 逻辑删除
 * @method static \Illuminate\Database\Eloquent\Builder|issue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|issue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|issue query()
 * @method static \Illuminate\Database\Eloquent\Builder|issue whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|issue whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|issue whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|issue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|issue whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|issue whereUpdateTime($value)
 * @mixin \Eloquent
 */
class issue extends BaseModel
{
    protected $table = 'issue';

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
