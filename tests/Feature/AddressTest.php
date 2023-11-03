<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use DatabaseTransactions;
    public function testList()
    {
        $response=$this->get('wx/address/list', $this->getAuthHeader());
        dd($response->getOriginalContent());

    }
    public function debugTest(){
        $id='123456';
        $date=now();
        return phpinfo();
    }



}
