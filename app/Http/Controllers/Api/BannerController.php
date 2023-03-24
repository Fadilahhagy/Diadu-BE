<?php

namespace App\Http\Controllers\Api;

use App\Models\Banner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\BannerResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public function index(){
        $result = Banner::simplePaginate(5);
        return new BannerResource(true, 'List Data Banner', $result);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name'      => 'required',
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'url'       => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/banners', $image->hashName());

        $data = Banner::create([
            'name'      => $request->name,
            'image'     => $image->hashName(),
            'url'       => $image->hashName(),
        ]);
        return new BannerResource(true, 'Data Banner Berhasil Ditambahkan!', $data);
    }

    public function show($id){
        $data = Banner::find($id);
        return new BannerResource(true, 'Detail Data Banner!', $data);
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = Banner::find($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/banners', $image->hashName());
            Storage::delete('public/banners/'.basename($data->image));
            $data->update([
                'name'     => $request->name,
                'image'     => $image->hashName(),
                'url'   => $image->hashName(),
            ]);

        } else {
            $data->update([
                'name'     => $request->name,
            ]);
        }
        return new BannerResource(true, 'Data Banner Berhasil Diubah!', $data);
    }

    public function destroy($id){
        $banner = Banner::find($id);
        Storage::delete('public/banners/'.basename($banner->image));
        $banner->delete();
        return new BannerResource(true, 'Data Banner Berhasil Dihapus!', null);
    }
}
