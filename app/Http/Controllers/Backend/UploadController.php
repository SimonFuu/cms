<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2018/11/11
 * Time: 10:29 AM
 * https://www.fushupeng.com
 * contact@fushupeng.com
 */

namespace App\Http\Controllers\Backend;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UploadController extends BackendController
{
    protected $relativeAvatarPath = '';
    protected $storeAvatarPath = '';
    public function ueUpload(Request $request)
    {
        if ($request -> has('action')) {
            switch ($request -> action) {
                case 'config':
                    $res = $this -> ueConfig();
                    break;
                case 'upload_image':
                    $res = $this -> saveFile($request, 'up_file');
                    if ($res) {
                        $res = json_encode([
                            'state' => 'SUCCESS',
                            'url' => $res,
                        ]);
                    } else {
                        $res = json_encode(['state' => 'FAILED']);
                    }
                    break;
                case 'upload_file':
                    $res = $this -> saveFile($request, 'up_file', 'files', $request->file('up_file')->getClientOriginalExtension());
                    if ($res) {
                        $res = json_encode([
                            'state' => 'SUCCESS',
                            'url' => $res,
                        ]);
                    } else {
                        $res = json_encode(['state' => 'FAILED']);
                    }
                    break;
                case 'upload_video':
                    $res = $this -> saveFile($request, 'up_file', 'videos');
                    if ($res) {
                        $res = json_encode([
                            'state' => 'SUCCESS',
                            'url' => $res,
                        ]);
                    } else {
                        $res = json_encode(['state' => 'FAILED']);
                    }
                    break;
                default:
                    $res = '{"state": "FAILED"}';
                    break;
            }
        } else {
            $res = '{"state": "FAILED"}';
        }
        return $res;
    }

    private function ueConfig()
    {
        return json_decode(
            preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(storage_path('app/private') . '/config.json')), true);
    }

    public function thumbnail(Request $request)
    {
        $res = $this -> saveFile($request, 'thumbnail-container');
        if ($res) {
            return $this -> response(['url' => $res]);
        } else {
            return $this -> response([], 500, config('msg.common.server.error'));
        }
    }
    private function saveFile(Request $request, $filed = '', $type = 'images', $originalExtension = false)
    {
        $this -> createPath($type);
        if ($request->hasFile($filed)) {
            if ($request->file($filed)->isValid()){
                switch ($filed) {
                    case 'up_file':
                        if ($type == 'files') {
                            $fileName = uniqid('download_');
                        } else {
                            $fileName = uniqid('ue_upload_');
                        }
                        break;
                    case 'thumbnail-container':
                        $fileName = uniqid('thumbnail_');
                        break;
                    case 'resource-container':
                        $fileName = uniqid('video_');
                        break;
                    case 'attachmentContainer':
                        $fileName = uniqid('attachment_');
                        break;
                    case 'file':
                        $fileName = uniqid('im_');
                        break;
                    case 'itemContainer':
                        $fileName = uniqid('download_');
                        break;
                    default:
                        $fileName = uniqid();
                        break;
                }
                $extension = $request->$filed->extension();
                if ($originalExtension) {
                    $file = $fileName . '.' . ($originalExtension == $extension ? $extension : $originalExtension);
                } else {
                    $file = $fileName . '.' . $extension;
                }
                $request->$filed->move($this -> storeAvatarPath, $file);
                $url = $this -> relativeAvatarPath . $file;
                return $url;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function createPath($type = 'images')
    {
        $basePath = base_path('public');
        $today = date('Ymd', time());
        $this -> relativeAvatarPath = '/upload/' . $type . '/' . $today . '/';
        $this -> storeAvatarPath = $basePath . $this -> relativeAvatarPath;
        if (!is_dir($this -> storeAvatarPath)) {
            mkdir($this -> storeAvatarPath, 0777, true);
        }
    }

    public function software(Request $request)
    {
        $res = $this -> saveFile($request, 'itemContainer', 'download', $request->file('itemContainer')->getClientOriginalExtension());
        if ($res) {
            return $this -> response(['url' => $res ,'size' => $this -> getReadableSize(File::size(public_path() . $res))]);
        } else {
            return $this -> response([], 500, config('msg.common.server.error'));
        }
    }

    private function getReadableSize($size)
    {
        $KB = 1024;
        $MB = 1024 * 1024;
        $GB = 1024 * 1024 * 1024;
        $TB = 1024 * 1024 * 1024;
        $PB = 1024 * 1024 * 1024 * 1024;
        switch ($size) {
            case $size < $KB:
                $sizeStr =  $size . 'bytes';
                break;
            case $size < $MB:
                $sizeStr = number_format($size / $KB, 2) . 'KB';
                break;
            case $size < $GB:
                $sizeStr = number_format($size / $MB, 2) . 'MB';
                break;
            case $size < $TB:
                $sizeStr = number_format($size / $GB, 2) . 'GB';
                break;
            case $size < $PB:
                $sizeStr = number_format($size / $TB, 2) . 'TB';
                break;
            default:
                $sizeStr = number_format($size / $PB, 2) . 'PB';
                break;
        }
        return $sizeStr;
    }
}