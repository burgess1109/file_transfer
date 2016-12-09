<?php

namespace Burgess\FileTransfer;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use File;
use Storage;

class FileTransferController extends Controller
{
    public $down_path = "";//下載路徑
    public $upload_path = "";//上傳路徑
    public $local_path = "temp_file";//本地暫存路徑

    /**
 * 檔案轉移
 * @param $down_path 下載路徑
 * @param $upload_path 上傳路徑
 * @param bool|true $is_local 是否在本地端儲存
 * @return array
 */
    public function transfer($down_path,$upload_path,$is_local=true)
    {
        if(empty($down_path)) return array('result'=>false,'message'=>'參數錯誤');


        $tmp_arr = explode('/', $down_path);
        $file_name = $tmp_arr[count($tmp_arr) - 1];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//CURLOPT_RETURNTRANSFER為true的話，curl只會將結果傳回，並不會輸出在畫面上
        curl_setopt($ch, CURLOPT_URL, $down_path);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //HTTPS要加(2 代表除了要檢查 SSL 憑證內的 common name 是否存在外，也驗證是否符合伺服器的主機名稱)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //HTTPS要加(TRUE 是要驗證伺服器憑證)
        $result = curl_exec($ch);
        if (!$result) return array('result'=>false,'message'=>$down_path." 下載失敗");
        curl_close($ch);

        if($is_local){
            if (!is_dir($this->local_path)) {
                $result = mkdir($this->local_path, 0777, true);
                if (!$result) return array('result'=>false,'message'=>$this->local_path . '創建失敗');
            }
            file_put_contents('./'.$this->local_path.'/'.$file_name, $result);//將檔案存到設定的路徑中
        }

        $result = Storage::disk('ftp')->put($upload_path.'/'.$file_name, $result);
        if (!$result) return array('result'=>false,'message'=>$down_path." 檔案轉移失敗");

        return array('result'=>true,'message'=>$down_path." 檔案轉移成功");

    }

    /**
     * 檔案轉移
     * @param $down_path 下載路徑
     * @return array
     */
    public function download($down_path)
    {
        if(empty($down_path)) return array('result'=>false,'message'=>'參數錯誤');

        $tmp_arr = explode('/', $down_path);
        $file_name = $tmp_arr[count($tmp_arr) - 1];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//CURLOPT_RETURNTRANSFER為true的話，curl只會將結果傳回，並不會輸出在畫面上
        curl_setopt($ch, CURLOPT_URL, $down_path);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //HTTPS要加(2 代表除了要檢查 SSL 憑證內的 common name 是否存在外，也驗證是否符合伺服器的主機名稱)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //HTTPS要加(TRUE 是要驗證伺服器憑證)
        $result = curl_exec($ch);
        if (!$result) return array('result'=>false,'message'=>$down_path." 下載失敗");
        curl_close($ch);

        if (!is_dir($this->local_path)) {
            $result = mkdir($this->local_path, 0777, true);
            if (!$result) return array('result'=>false,'message'=>$this->local_path . '創建失敗');
        }

        $result = file_put_contents('./'.$this->local_path.'/'.$file_name, $result);//將檔案存到設定的路徑中

        if (!$result) return array('result'=>false,'message'=>$down_path." 檔案下載失敗");

        return array('result'=>true,'message'=>$down_path." 檔案下載成功");

    }
}
