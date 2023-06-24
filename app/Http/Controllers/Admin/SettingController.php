<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Contracts\SettingContract;
use Illuminate\Http\JsonResponse;
use DB;

class SettingController extends Controller
{
    // public function __construct(private SettingContract $service)
    // {
        
    // }
    public function index()
    {
        $setting = Setting::all();
        return view('admin.setting.index', compact('setting'));
    }

    public function getListPagination()
    {
        $search = request()->get('search') ? strtolower(request()->get('search')) : null;
        $limit = request()->input('length') ? request()->input('length') : 0;
        
        return Setting::paginate($limit)->toArray();
    }

    public function getList(Request $request): JsonResponse
    {
        $data = $this->getListPagination();

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($data['total']),
            "recordsFiltered" => intval($data['total']),
            "data"            => $data['data']
        );

        return response()->json($json_data, 200); 
    }

    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'tahun' => 'required|unique:tbl_setting',
            'survei_ke' => 'required',
            'status' => 'required',
        ]);

        try {
            $create = Setting::create([
                'tahun' => $request['tahun'],
                'survei_ke' => $request['survei_ke'],
                'status' => $request['status']
            ]);

            return response()->json([
                'data' => $create,
                'message' => 'Sukses menambah setting'
            ], 201);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateStatus(Request $request, $id) 
    {
        $data = Setting::findOrFail($id);
        // dd($data['status']);


        if($data->status == '0') {
            $cek_aktif = DB::table('tbl_setting')->where('status', '1')->count();
            if($cek_aktif > 0 == true)
            {
                return response()->json([
                    'message' => 'Status aktif masih ada, silahkan nonaktifkan terlebih dahulu'
                ], 422);
            } else {
                $data->update(['status' => '1']);

                return response()->json([
                    'message' => 'Sukses mengubah setting menjadi aktif'
                ], 201);
            }
            
        } else if($data->status == '1') {
            $data->update(['status' => '0']);

            return response()->json([
                'message' => 'Sukses mengubah setting menjadi nonaktif'
            ], 201);
        }else {
            return response()->json([
                'message' => 'Terjadi kesalahan'
            ], 500);
        }
    
    }

    public function updateSurvei(Request $request, $id) 
    {
        $data = Setting::findOrFail($id);
        // dd($data['status']);

        try {
            $data->update(['survei_ke' => $request->survei_ke]);

            return response()->json([
                'message' => 'Sukses mengubah survei'
            ], 201);
        } catch (\Exception $e) {
            throw $e;
        }
    
    }

    public function destroy($id) {
        $data = Setting::findOrFail($id);

        try {
            $data->delete();

            return response()->json([
                'message' => 'Sukses menghapus survei'
            ], 201);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
