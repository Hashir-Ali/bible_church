<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Utils\ChurchUtil;
use App\Models\ChurchBanner;
use File;

class ChurchController extends Controller
{
    protected $churchUtil;
    public function __construct(ChurchUtil $churchUtil)
    {
        $this->churchUtil = $churchUtil;
    }

    public function churchList(Request $request){
        $search = Null;
        if($request->search)
        {
            $search = $request->search;
        }
        $churches = DB::table('churches')->where('church_name', 'like', '%'.$search.'%')->orderBy('updated_at', 'desc')->get();
        
        return response([
            'success' => true,
            'message' => 'success',
            'data' =>$churches,
        ], config('constants.validResponse.statusCode'));
    }

    /* Church Banner List */
    public function churchBannerList(Request $request)
    {
        $ChurchBanner = ChurchBanner::where('church_id', auth()->user()->church_id)->orderByRaw('ISNULL(serial_number), serial_number ASC')->get();
        
        $response = [];
        foreach($ChurchBanner as $banner){
            
            $existImage = store_banner_path(). $banner->banner_image;
            if (File::exists($existImage)) {
                $banner->banner_image = banner_public_path().$banner->banner_image;
            }
            $bannerArr = [
                'church_name' => church_name_helper($banner->church_id),
                'banner_image'    => $banner->banner_image,
                'serial_number'    => $banner->serial_number
            ];
            array_push($response, $bannerArr);
        }
     
        return response([
            'success' => true,
            'message' => 'success',
            'data' =>$response,
        ], config('constants.validResponse.statusCode'));
    }
}