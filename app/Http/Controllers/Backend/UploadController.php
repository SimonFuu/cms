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
                    $res = $this -> saveFile($request, 'up_file', 'attachments');
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
                        $fileName = uniqid('ue_upload_');
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
}