<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;




/**
 * App\Models\User\User
 *
 * @property int $id
 * @property string $username 用户名称
 * @property string $password 用户密码
 * @property int $gender 性别：0 未知， 1男， 1 女
 * @property string|null $birthday 生日
 * @property string|null $last_login_time 最近一次登录时间
 * @property string $last_login_ip 最近一次登录IP地址
 * @property int|null $user_level 0 普通用户，1 VIP用户，2 高级VIP用户
 * @property string $nickname 用户昵称或网络名称
 * @property string $mobile 用户手机号码
 * @property string $avatar 用户头像图片
 * @property string $weixin_openid 微信登录openid
 * @property string $session_key 微信登录会话KEY
 * @property int $status 0 可用, 1 禁用, 2 注销
 * @property string|null $add_time 创建时间
 * @property string|null $update_time 更新时间
 * @property int|null $deleted 逻辑删除
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSessionKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereWeixinOpenid($value)
 * @mixin \Eloquent
 */
 class User extends Authenticatable implements JWTSubject
 {
     const CREATED_AT = 'add_time';
     const UPDATED_AT = 'update_time';


     protected $table = 'user';


     use HasApiTokens, HasFactory,Authorizable;
     use Notifiable;

     /**
      * The attributes that are mass assignable.
      *
      * @var array<int, string>
      */
     protected $fillable = [
         'name',
         'email',
         'password',
         'api_token'
     ];

     /**
      * The attributes that should be hidden for serialization.
      *
      * @var array<int, string>
      */
     protected $hidden = [
         'password',
         'deleted',
         'remember_token',
         'api_token'
     ];

     protected $casts = [
         'deleted' => 'boolean'
     ];


//    public static function booted()
//    {
//        static::casing(function ($user){
//            echo 'casing1'.PHP_EOL;
//            return true;
//        });
//        static::casing(function ($user){
//            echo 'casing2'.PHP_EOL;
//            return true;
//        });
//        static::casing(function ($user){
//            echo 'cased1'.PHP_EOL;
//            return true;
//        });
//        static::casing(function ($user){
//            echo 'case2'.PHP_EOL;
//            return true;
//        });
//    }

    public function routeNotificationForEasySms($driver, $notification = null)
    {
        return $this->mobile;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
        ];
    }
}


    // Rest omitted for brevity


