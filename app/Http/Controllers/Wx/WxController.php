<?php
namespace App\Http\Controllers\Wx;

use App\CodeResponse;
use App\Http\Controllers\Controller;

use App\Models\User\User;
use App\VerifyRequestInput;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;


class WxController extends Controller
{
    use VerifyRequestInput;
    protected $only;
    protected $except;


    public function __construct()
    {
        $option=[];
        if(!is_null($this->only)){
            $option['only']=$this->only;
        }

        if(!is_null($this->except)){

            $option['except']=$this->except;
        }
        $this->middleware('auth:wx',$option);
    }

    protected function codeReturn(array $codeResponse, $data=null, $info='')
    {
        list($errno, $errmsg) = $codeResponse;
        $ret = [
            'errno' => $errno,
            'errmsg' => $errmsg
        ];
        if (!is_null($data)) {
            if (is_array($data)) {
                $data = array_filter($data, function ($item) {
                    return $item !== null;
                });
            }
            $ret['data'] = $data;
        }
        return response()->json($ret);
    }

    protected function successpaginate($page, $list = null){
        return $this->success($this->paginate($page,$list));
    }

    /**
     * @param $page
     * @param null|array $list
     * @return array
     */
    protected function paginate($page,$list=null){
        if ($page instanceof LengthAwarePaginator){
            $total=$page->total();
            return [
                'total'=>$page->total(),
                'page'=>$total==0?0:$page->currentPage(),
                'limit'=>$page->perPage(),
                'pages'=>$total==0?0:$page->lastPage(),
                'list'=>$list?? $page->items()
            ];
        }elseif(is_array($page)) {
            $total=count($page);
            return [
                'total'=>$total,
                'page'=>$total==0?0:1,
                'limit'=>$total,
                'pages'=>$total==0?0:1,
                'list'=>$page

            ];
        }
        return $page;
    }

    protected function success($data=null)
    {
        return $this->codeReturn(CodeResponse::SUCCESS,$data);
    }

    protected function fail(array $codeResponse, $info='')
    {
        return $this->codeReturn($codeResponse, $info);
    }

    protected function failorSuccess($isSuccess,array $codeResponse=CodeResponse::FAIL,$data=null,$info='')
    {
        if($isSuccess){
            return $this->success($data);
        }
        return $this->fail($codeResponse,$info);
    }
    /**
     * @return User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function user()
    {
        return User::query()->where('id',1)->first();
    }

    /**
     * @return bool
     */
    public function isLogin(){
        return !is_null($this->user());
    }

    /**
     * @return mixed
     */
    public function userId()
    {
        return !empty($this->user()) ? $this->user()->getAuthIdentifier() : 0;
    }
}
