<?php

namespace Burgess\FileTransfer;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Burgess\FileTransfer;

class TestController extends Controller
{
    public function index(){

        $file = new FileTransfer\FileTransferController();
        $down_path='https://s1.yimg.com/rz/d/yahoo_frontpage_zh-Hant-TW_s_f_p_bestfit_frontpage_2x.png';
        $upload_path='transfer';

        //Âà²¾
        $result=$file->transfer($down_path,$upload_path);
        print_r($result);

        //curl¤U¸ü
        $result=$file->download($down_path);
        print_r($result);
    }
}
