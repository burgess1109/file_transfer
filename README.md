# file_transfer Laravel curl檔案下載及轉移

起因：因專案需求進行大量資料下載及轉移

# 安裝方式

1. 切換報專案目錄下，執行 composer require burgess1109/file_transfer:* 

2. 至 config/app.php 'providers'內加入 Burgess\FileTransfer\FileTransferServiceProvider::class,


# 環境參數(.env)

使用Storage ftp 上傳時需做下列設定  

FILE_HOST：file server IP

FILE_USERNAME：file server 帳號

FILE_PASSWORD：file server 密碼

FILE_ROOT：上傳目錄

config/filesystems.php 

 1.修正default參數,讓其撈取環境參數
 
 'default' => 'ftp',
 
 2.'disks'內增加ftp disk, 讓Storage支援FTP
 
 'ftp' => [
            
            'driver'   => 'ftp',
            
            'host' => env('FILE_HOST', 'localhost'),
            
            'username' => env('FILE_USERNAME', '預設帳號'),
            
            'password' => env('FILE_PASSWORD', '預設密碼'),

            // Optional FTP Settings...
            
            'port' => 21,
            
            'root' => '',
        ],


# 測試頁面

提供測試頁面 YourIP/test

# 使用方式

可參考 packages/filetransfer/src/TestController.php

使用package 

use Burgess\FileTransfer;

1. CURL下載

 $file = new FileTransfer\FileTransferController();
 
 $down_path = 'http://ooo.xxx'; //下載路徑
 
 $result=$file->download($down_path); //下載
 
 
2. 檔案轉移

 $directory='路徑';

 $file = new FileTransfer\FileTransferController();
 
 $down_path = 'http://ooo.xxx'; //下載路徑
 
 $upload_path='transfer'; //上傳路徑
 
 $is_local = true; //是否在本地端儲存
 
 $result=$file->transfer($down_path,$upload_path,$is_local); //轉移
 

CURL 或 檔案轉移 $is_local設定為ture, 會在本地端產生"temp_file"資料夾儲存下載下來的檔案
