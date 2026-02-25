<?php

namespace App\Http\Controllers\Lounge;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cities;
use App\Models\Lounge;
use App\Models\LoungeImage;
use App\Models\LoungeTime;
use App\Models\LoungeMaintenanceTime;
use App\Models\Franchise;
use Validator;
use Session;
use DataTables;

class LoungeController extends Controller
{
    function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->middleware('permission:lounge-list', ['only' => ['view', 'load_table']]);
        $this->middleware('permission:lounge-add', ['only' => ['create', 'insert']]);
        $this->middleware('permission:lounge-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:lounge-delete', ['only' => ['delete']]);
    }

    public function createSlug(Request $request)
    {
        $slug = str_slug($request->lounge_name);
        $allSlugs = $this->checkSlug($slug);
        if (! $allSlugs->contains('lounge_slug', $slug)){
            return response()->json(['slug' => $slug]);
        }
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('lounge_slug', $newSlug)) {
                return response()->json(['slug' => $newSlug]);
            }
        }
        throw new \Exception('Can not create a unique slug');
    }

    protected function checkSlug($slug)
    {
        return Lounge::select("lounge_slug")->where("lounge_slug", 'like', $slug.'%')->get();
    }

    public function pupload()
    {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // Settings
        //$targetDir        = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
        $folderName         = '';
        $targetDir          = public_path('/uploads/tlounge/').'/'.$folderName;
        $cleanupTargetDir   = true; // Remove old files
        $maxFileAge         = 5 * 3600; // Temp file age in seconds
        @set_time_limit(5 * 60);

        // Get parameters
        $chunk              = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks             = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
        $fileName           = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

        // Clean the fileName for security reasons
        $fileName           = preg_replace('/[^\w\._]+/', '_', $fileName);
        $fileOriginalName   = str_replace('_',' ',$fileName);

        // Make sure the fileName is unique but only if chunking is disabled
        if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
            $ext            = strrpos($fileName, '.');
            $fileName_a     = substr($fileName, 0, $ext);
            $fileName_b     = substr($fileName, $ext);

            $count = 1;
            while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
                $count++;

            $fileName       = $fileName_a . '_' . $count . $fileName_b;
        }

        $fileName           = $fileName;
        $filePath           = $targetDir . DIRECTORY_SEPARATOR . $fileName;

        // Create target dir
        if (!file_exists($targetDir))
            @mkdir($targetDir);

        // Remove old temp files
        if ($cleanupTargetDir) {
            if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
                while (($file = readdir($dir)) !== false) {
                    $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                    // Remove temp file if it is older than the max age and is not the current file
                    if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
                        @unlink($tmpfilePath);
                    }
                }
                closedir($dir);
            } else {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }
        }

        // Look for the content type header
        if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
            $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

        if (isset($_SERVER["CONTENT_TYPE"]))
            $contentType = $_SERVER["CONTENT_TYPE"];

        // Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
        if (strpos($contentType, "multipart") !== false) {
            if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                // Open temp file
                $out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
                if ($out) {
                    // Read binary input stream and append it to temp file
                    $in = @fopen($_FILES['file']['tmp_name'], "rb");

                    if ($in) {
                        while ($buff = fread($in, 4096))
                            fwrite($out, $buff);
                    } else
                        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                    @fclose($in);
                    @fclose($out);
                    @unlink($_FILES['file']['tmp_name']);
                } else
                    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            } else
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
        } else {
            // Open temp file
            $out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
            if ($out) {
                // Read binary input stream and append it to temp file
                $in = @fopen("php://input", "rb");

                if ($in) {
                    while ($buff = fread($in, 4096))
                        fwrite($out, $buff);
                } else
                    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

                @fclose($in);
                @fclose($out);
            } else
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        // Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {
            // Strip the temp .part suffix off
            rename("{$filePath}.part", $filePath);
        }

        //$_SESSION['deal_timages'][] = $folderName.'/'.$fileName;
        //$_SESSION['pimage_image'][] = $fileName;
        //array_filter($_SESSION['pimage_image']);
    }

    public function create()
    {
        $cityDetail = Cities::where(['states_id'=>'12', 'cities_status'=>'1'])->get();
        return view("admin.lounge.create", compact('cityDetail'));
    }

    public function insert(Request $request)
    {
        $lmtimeArray = [];
        $dayArray = array('MON','TUE','WED','THU','FRI','SAT','SUN');
        $lastOrder = Lounge::orderBy("lounge_order", "DESC")->first();
        //print_r($request->all()); die;
        $validator = Validator::make($request->all(),[
            "lounge_name" => "required",
            "lounge_slug" => "required",
            //"lounge_email" => "required|email",
            //"lounge_mobile" => "required|regex:/^\+?[0-9]{10,15}$/",
            "lounge_max_person" => "required",
            "lounge_address" => "required",
            "lounge_area" => "required",
            "lounge_google_map" => "required",
            "cities_id" => "required|integer|min:1",
            'lounge_unit' => 'required|not_in:0',
            'lounge_ownership' => 'required|not_in:0',
            "lounge_agreement_start_date" => "required",
            "lounge_agreement_end_date" => "required",
            'lounge_gst_invoice' => 'required|not_in:0',
            'lounge_franchise_status' => 'required|not_in:0',
            'lounge_plateform_fee' => 'required|not_in:0',
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            if ($request->hasFile('lounge_image')) {
                $image = $request->file('lounge_image');
                $filename = "IMG-" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/lounge/'), $filename);
            } else {
                $filename = "";
            }

            $data                                       = new Lounge();
            $data->lounge_name                          = $request->lounge_name;
            $data->lounge_slug                          = $request->lounge_slug;
            $data->lounge_email                         = $request->lounge_email;
            $data->lounge_mobile                        = $request->lounge_mobile;
            $data->lounge_image                         = $filename;
            $data->lounge_short_desc                    = addslashes($request->lounge_short_desc);
            $data->lounge_includes                      = $request->lounge_includes;
            $data->lounge_amenities                     = $request->lounge_amenities;
            $data->lounge_max_person                    = $request->lounge_max_person;
            $data->lounge_address                       = $request->lounge_address;
            $data->lounge_area                          = $request->lounge_area;
            $data->cities_id                            = $request->cities_id;
            $data->lounge_google_map                    = $request->lounge_google_map;
            $data->lounge_desc                          = $request->lounge_desc;
            $data->lounge_rules                         = $request->lounge_rules;
            $data->lounge_unit                          = $request->lounge_unit;
            $data->lounge_ownership                     = $request->lounge_ownership;
            $data->lounge_agreement_start_date          = date('Y-m-d', strtotime($request->lounge_agreement_start_date));
            $data->lounge_agreement_end_date            = date('Y-m-d', strtotime($request->lounge_agreement_end_date));
            $data->lounge_gst_invoice                   = $request->lounge_gst_invoice;
            $data->lounge_franchise_status              = $request->lounge_franchise_status;
            $data->lounge_plateform_fee                 = $request->lounge_plateform_fee;
            $data->lounge_meta_title                    = $request->lounge_meta_title;
            $data->lounge_meta_keyword                  = $request->lounge_meta_keyword;
            $data->lounge_meta_desc                     = $request->lounge_meta_desc;
            $data->lounge_canonical                     = $request->lounge_canonical;
            $data->lounge_order                         = (!empty($lastOrder)) ? $lastOrder->lounge_order + 1 : 1;
            $data->lounge_status                        = '1';
            $data->created_at                           = date('Y-m-d H:i:s');
            if ($data->save()) {
                $lastOrder                              = LoungeImage::orderBy("limage_order", "DESC")->first();
                $lastOrder                              = (!empty($lastOrder)) ? $lastOrder->limage_order + 1 : 1;
                if ($request->uploader_count > 0) { for ($i=0; $i < $request->uploader_count; $i++) {
                    $key                                = 'uploader_' . $i . '_name';
                    $limage                             = preg_replace('/[^\w\._]+/', '_', $request->$key);
                    //echo '<pre>'; echo $_FILES['course_file'][$key]; exit();
                    @copy(public_path('/uploads/tlounge/'.$limage), public_path('/uploads/tlounge/'.$limage));
                    @unlink(public_path('/uploads/tlounge/'.$limage));

                    $limageArray[]                      = [
                        'lounge_id'                     => $data->lounge_id,
                        'limage_image'                  => $limage,
                        'limage_order'                  => $lastOrder,
                        'created_at'                    => date('Y-m-d H:i:s'),
                    ];
                    $lastOrder++;
                }
                    if (count($limageArray) > 0) {
                        LoungeImage::insert($limageArray);
                    }
                }

                if (is_array($dayArray) && count($dayArray) > 0){ foreach ($dayArray as $day) {
                    if(is_array($_POST["lounge_open_hour"][$day]) && count($_POST["lounge_open_hour"][$day]) > 0){
                        $lastOrder                      = LoungeTime::orderBy("ltime_order", "DESC")->first();
                        $lastOrder                      = (!empty($lastOrder)) ? $lastOrder->ltime_order + 1 : 1;
                        foreach ($_POST["lounge_open_hour"][$day] as $key => $opentTme){ if ($_POST["lounge_open_hour"][$day][$key] > 0) {
                            $ltimeArray[]               = [
                                'lounge_id'             => $data->lounge_id,
                                'ltime_day'             => $day,
                                'ltime_open_hour'       => $_POST["lounge_open_hour"][$day][$key],
                                'ltime_open_time'       => $_POST["lounge_open_time"][$day][$key],
                                'ltime_open_ap'         => $_POST["lounge_open_ap"][$day][$key],
                                'ltime_close_hour'      => $_POST["lounge_close_hour"][$day][$key],
                                'ltime_close_time'      => $_POST["lounge_close_time"][$day][$key],
                                'ltime_close_ap'        => $_POST["lounge_close_ap"][$day][$key],
                                'ltime_text'            => $_POST["lounge_text"][$day][$key],
                                'ltime_order'           => $lastOrder,
                                'ltime_status'          => ($_POST["lounge_day"][$day][$key] == $day) ? '1' : '0',
                                'created_at'            => date('Y-m-d H:i:s'),
                            ];
                        } }
                        if (count($ltimeArray) > 0) {
                            LoungeTime::insert($ltimeArray);
                        }
                    }
                } }

                if ($request->lounge_maintenance_open_date!="") {
                    $lmtimeArray[]                      = [
                        'lounge_id'                     => $data->lounge_id,
                        'lmtime_open_date'              => date('Y-m-d', strtotime($request->lounge_maintenance_open_date)),
                        'lmtime_open_hour'              => $_POST["lounge_maintenance_open_hour"],
                        'lmtime_open_time'              => $_POST["lounge_maintenance_open_time"],
                        'lmtime_open_ap'                => $_POST["lounge_maintenance_open_ap"],
                        'lmtime_close_hour'             => $_POST["lounge_maintenance_close_hour"],
                        'lmtime_close_time'             => $_POST["lounge_maintenance_close_time"],
                        'lmtime_close_ap'               => $_POST["lounge_maintenance_close_ap"],
                        'is_fullday_close'              => ($_POST["is_fullday_close"] == '1') ? '1' : '0',
                        'created_at'                    => date('Y-m-d H:i:s')
                    ];
                }
                if (count($lmtimeArray) > 0) {
                    LoungeMaintenanceTime::insert($lmtimeArray);
                }

                Session::flash('successMsg', 'Lounge details added successfully');
                return ["redirect_url" => "lounge-add"];
            }
        }
    }

    public function edit($id)
    {
        $loungeDetail = Lounge::find($id);
        $cityDetail = Cities::where(['states_id'=>'12', 'cities_status'=>'1'])->get();
        $limageDetail = LoungeImage::where('lounge_id', $loungeDetail->lounge_id)->orderBy('limage_order')->get(['limage_id', 'limage_image'])->toArray();
        $ltimeDetail = LoungeTime::where('lounge_id', $loungeDetail->lounge_id)->orderBy('ltime_order')->get()->toArray();
        $lmtimeDetail = LoungeMaintenanceTime::withTrashed()->where('lounge_id', $loungeDetail->lounge_id)->orderBy('created_at', 'DESC')->get()->toArray();
        return view("admin.lounge.edit", compact('loungeDetail', 'cityDetail', 'limageDetail', 'ltimeDetail', 'lmtimeDetail'));
    }

    public function update(Request $request)
    {
        $lmtimeArray = [];
        $dayArray = array('MON','TUE','WED','THU','FRI','SAT','SUN');
        $data = Lounge::find($request->lounge_id);
        $validator = Validator::make($request->all(),[
            "lounge_name" => "required",
            "lounge_slug" => "required",
            //"lounge_email" => "required|email",
            //"lounge_mobile" => "required|regex:/^\+?[0-9]{10,15}$/",
            "lounge_max_person" => "required",
            "lounge_address" => "required",
            "lounge_area" => "required",
            "lounge_google_map" => "required",
            "cities_id" => "required|integer|min:1",
            'lounge_unit' => 'required|not_in:0',
            'lounge_ownership' => 'required|not_in:0',
            "lounge_agreement_start_date" => "required",
            "lounge_agreement_end_date" => "required",
            'lounge_gst_invoice' => 'required|not_in:0',
            'lounge_franchise_status' => 'required|not_in:0',
            'lounge_plateform_fee' => 'required|not_in:0',
        ]);

        if ($validator->fails()) {
            return ['status' => 'validation-error', 'data' => $validator->errors()];
        } else {
            if ($request->hasfile('lounge_image')) {
                //echo 'Hello'; exit();
                if ($data->lounge_image!='' && file_exists(public_path('/uploads/lounge/'.$data->lounge_image))) {
                    @unlink(public_path('/uploads/lounge/'.$data->lounge_image));
                }
                $image = $request->file('lounge_image');
                $filename = "IMG-" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path().'/uploads/lounge/', $filename);
            } else {
                $filename = $request->old_image;
            }
            
            $data->lounge_name                          = $request->lounge_name;
            $data->lounge_slug                          = $request->lounge_slug;
            $data->lounge_email                         = $request->lounge_email;
            $data->lounge_mobile                        = $request->lounge_mobile;
            $data->lounge_image                         = $filename;
            $data->lounge_short_desc                    = addslashes($request->lounge_short_desc);
            $data->lounge_includes                      = $request->lounge_includes;
            $data->lounge_amenities                     = $request->lounge_amenities;
            $data->lounge_max_person                    = $request->lounge_max_person;
            $data->lounge_address                       = $request->lounge_address;
            $data->lounge_area                          = $request->lounge_area;
            $data->cities_id                            = $request->cities_id;
            $data->lounge_google_map                    = $request->lounge_google_map;
            $data->lounge_desc                          = $request->lounge_desc;
            $data->lounge_rules                         = $request->lounge_rules;
            $data->lounge_unit                          = $request->lounge_unit;
            $data->lounge_ownership                     = $request->lounge_ownership;
            $data->lounge_agreement_start_date          = date('Y-m-d', strtotime($request->lounge_agreement_start_date));
            $data->lounge_agreement_end_date            = date('Y-m-d', strtotime($request->lounge_agreement_end_date));
            $data->lounge_gst_invoice                   = $request->lounge_gst_invoice;
            $data->lounge_franchise_status              = $request->lounge_franchise_status;
            $data->lounge_plateform_fee                 = $request->lounge_plateform_fee;
            $data->lounge_meta_title                    = $request->lounge_meta_title;
            $data->lounge_meta_keyword                  = $request->lounge_meta_keyword;
            $data->lounge_meta_desc                     = $request->lounge_meta_desc;
            $data->lounge_canonical                     = $request->lounge_canonical;
            $data->updated_at                           = date('Y-m-d H:i:s');
            if ($data->save()) {
                $lastOrder                              = LoungeImage::orderBy("limage_order", "DESC")->first();
                $lastOrder                              = (!empty($lastOrder)) ? $lastOrder->limage_order + 1 : 1;
                if ($request->uploader_count > 0) { for ($i=0; $i < $request->uploader_count; $i++) {
                    $key                                = 'uploader_' . $i . '_name';
                    $limage                             = preg_replace('/[^\w\._]+/', '_', $request->$key);
                    //echo '<pre>'; echo $_FILES['course_file'][$key]; exit();
                    @copy(public_path('/uploads/tlounge/'.$limage), public_path('/uploads/lounge/'.$limage));
                    @unlink(public_path('/uploads/tlounge/'.$limage));
                    $limagenew                          = time() . '_' .  $limage;
                    rename(public_path('/uploads/lounge/'.$limage), public_path('/uploads/lounge/'.$limagenew));

                    $limageArray[]                      = [
                        'lounge_id'                     => $data->lounge_id,
                        'limage_image'                  => $limagenew,
                        'limage_order'                  => $lastOrder,
                        'created_at'                    => date('Y-m-d H:i:s'),
                    ];
                    $lastOrder++;
                }
                    if (count($limageArray) > 0) {
                        LoungeImage::insert($limageArray);
                    }
                }

                $flag = 0;
                if(array_key_exists('ilounge_open_hour', $request->all())) {
                    $flag = 1;
                }

                if ($flag > 0) {
                    if (is_array($dayArray) && count($dayArray) > 0){ foreach ($dayArray as $day) {
                        if(is_array($_POST["ilounge_open_hour"][$day]) && count($_POST["ilounge_open_hour"][$day])){
                            $lastOrder                  = LoungeTime::orderBy("ltime_order", "DESC")->first();
                            $lastOrder                  = (!empty($lastOrder)) ? $lastOrder->ltime_order + 1 : 1;
                            foreach ($_POST["ilounge_open_hour"][$day] as $key => $opentTme){
                                LoungeTime::where('ltime_id', $key)->update([
                                    'lounge_id'         => $data->lounge_id,
                                    'ltime_day'         => $day,
                                    'ltime_open_hour'   => $_POST["ilounge_open_hour"][$day][$key],
                                    'ltime_open_time'   => $_POST["ilounge_open_time"][$day][$key],
                                    'ltime_open_ap'     => $_POST["ilounge_open_ap"][$day][$key],
                                    'ltime_close_hour'  => $_POST["ilounge_close_hour"][$day][$key],
                                    'ltime_close_time'  => $_POST["ilounge_close_time"][$day][$key],
                                    'ltime_close_ap'    => $_POST["ilounge_close_ap"][$day][$key],
                                    'ltime_text'        => $_POST["ilounge_text"][$day][$key],
                                    'ltime_status'      => ($_POST["ilounge_day"][$day][$key] == $day) ? '1' : '0'
                                ]);
                            }
                        }
                    } }
                }

                $flag = 0;
                if(array_key_exists('lounge_open_hour', $request->all())) {
                    $flag = 1;
                }
                if ($flag > 0) {
                    if (is_array($dayArray) && count($dayArray) > 0){ foreach ($dayArray as $day) {
                        if(isset($_POST["lounge_open_hour"][$day]) && count($_POST["lounge_open_hour"][$day]) > 0){
                            $lastOrder                  = LoungeTime::orderBy("ltime_order", "DESC")->first();
                            $lastOrder                  = (!empty($lastOrder)) ? $lastOrder->ltime_order + 1 : 1;
                            foreach ($_POST["lounge_open_hour"][$day] as $key => $opentTme){ if ($_POST["lounge_open_hour"][$day][$key] > 0) {
                                LoungeTime::create([
                                    'lounge_id'         => $data->lounge_id,
                                    'ltime_day'         => $day,
                                    'ltime_open_hour'   => $_POST["lounge_open_hour"][$day][$key],
                                    'ltime_open_time'   => $_POST["lounge_open_time"][$day][$key],
                                    'ltime_open_ap'     => $_POST["lounge_open_ap"][$day][$key],
                                    'ltime_close_hour'  => $_POST["lounge_close_hour"][$day][$key],
                                    'ltime_close_time'  => $_POST["lounge_close_time"][$day][$key],
                                    'ltime_close_ap'    => $_POST["lounge_close_ap"][$day][$key],
                                    'ltime_text'        => $_POST["lounge_text"][$day][$key],
                                    'ltime_order'       => $lastOrder,
                                    'ltime_status'      => ($_POST["lounge_day"][$day][$key] == $day) ? '1' : '0',
                                    'created_at'        => date('Y-m-d H:i:s'),
                                ]);
                            } }
                        }
                    } }
                }

                if ($request->lounge_maintenance_open_date!="") {
                    $lmtimeArray[]                      = [
                        'lounge_id'                     => $data->lounge_id,
                        'lmtime_open_date'              => date('Y-m-d', strtotime($request->lounge_maintenance_open_date)),
                        'lmtime_open_hour'              => $_POST["lounge_maintenance_open_hour"],
                        'lmtime_open_time'              => $_POST["lounge_maintenance_open_time"],
                        'lmtime_open_ap'                => $_POST["lounge_maintenance_open_ap"],
                        'lmtime_close_hour'             => $_POST["lounge_maintenance_close_hour"],
                        'lmtime_close_time'             => $_POST["lounge_maintenance_close_time"],
                        'lmtime_close_ap'               => $_POST["lounge_maintenance_close_ap"],
                        'is_fullday_close'              => (isset($_POST["is_fullday_close"]) && $_POST["is_fullday_close"] == '1') ? '1' : '0',
                        'created_at'                    => date('Y-m-d H:i:s')
                    ];
                }
                if (count($lmtimeArray) > 0) {
                    LoungeMaintenanceTime::insert($lmtimeArray);
                }

                Session::flash('successMsg', 'Lounge details updated successfully');
                return ["redirect_url" => "lounge-add"];
            }
        }
    }

    public function view()
    {
        return view("admin.lounge.list");
    }

    public function load_table(Request $request)
    {
        // Logged in user
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        if ($role == 'Super Admin') {
            $loungeDetail = Lounge::orderBy("lounge_order")->get();
        } else {
            $franchise = Franchise::where('franchise_id', $user->franchise_id)->first();
            $loungeId = [];
            if ($franchise && $franchise->lounge_id) {
                $loungeId = explode(',', $franchise->lounge_id); // convert CSV to array
            }
            $loungeDetail = Lounge::whereIn('lounge_id', $loungeId)->orderBy("lounge_order")->get();
        }
        return DataTables::of($loungeDetail)
            ->editColumn("checkbox", function ($lounge){
                return '<input type="checkbox" name="check[]" id="check[]" value="'.$lounge->lounge_id.'" class="custom-checkbox check_class" />';
            })
            ->editColumn("title", function ($lounge){
                return $lounge->lounge_name;
            })
            ->editColumn("sdate", function ($lounge){
                return date('d-m-Y', strtotime($lounge->lounge_agreement_start_date));
            })
            ->editColumn("edate", function ($lounge){
                return date('d-m-Y', strtotime($lounge->lounge_agreement_end_date));
            })
            ->editColumn("date", function ($lounge){
                return date('d-m-Y h:i A', strtotime($lounge->created_at));
            })
            ->editColumn("status", function ($lounge){
                if ($lounge->lounge_status == '1') {
                    return '<span id="td_status_'.$lounge->lounge_id.'"><a href="javascript:void(0)" onclick="change_status('.$lounge->lounge_id.', 0)" ><div class="label label-table label-success">Active</div></a></span>';
                } else {
                    return '<span id="td_status_'.$lounge->lounge_id.'"><a href="javascript:void(0)" onclick="change_status('.$lounge->lounge_id.', 1)" ><div class="label label-table label-danger">Inactive</div></a></span>';
                }
            })
            ->editColumn("book_status", function ($lounge){
                if ($lounge->lounge_book_status == '1') {
                    return '<span id="td_book_status_'.$lounge->lounge_id.'"><a href="javascript:void(0)" onclick="change_book_status('.$lounge->lounge_id.', 0)" ><div class="label label-table label-success">Yes</div></a></span>';
                } else {
                    return '<span id="td_book_status_'.$lounge->lounge_id.'"><a href="javascript:void(0)" onclick="change_book_status('.$lounge->lounge_id.', 1)" ><div class="label label-table label-danger">No</div></a></span>';
                }
            })
            ->editColumn("maintenance_status", function ($lounge){
                if ($lounge->lounge_maintenance_status == '1') {
                    return '<span id="td_maintenance_status_'.$lounge->lounge_id.'"><a href="javascript:void(0)" onclick="change_maintenance_status('.$lounge->lounge_id.', 0)" ><div class="label label-table label-success">On</div></a></span>';
                } else {
                    return '<span id="td_maintenance_status_'.$lounge->lounge_id.'"><a href="javascript:void(0)" onclick="change_maintenance_status('.$lounge->lounge_id.', 1)" ><div class="label label-table label-danger">Off</div></a></span>';
                }
            })
            ->editColumn("action", function ($lounge){
                $action = "";
                if (auth()->user()->can('lounge-edit')) {
                    $action.= '<a href="'.route("lounge-edit", ['id' => $lounge->lounge_id]).'" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="fa fa-pencil text-inverse"></i> </a>';
                }
                if (auth()->user()->can('lounge-delete')) {
                    $action.= '<a href="javascript:void(0)" data-toggle="tooltip" onclick="deleteSingal(' . $lounge->lounge_id . ');" data-placement="top" title="Delete"> <i class="fa fa-trash text-danger"></i> </a>';
                }
                return $action;
            })
            ->setRowClass(function () {
                return 'row1';
            })
            ->setRowAttr([
                "data-id" => function ($lounge) {
                    return $lounge->lounge_id;
                }
            ])
            ->rawColumns(["checkbox", "status", "book_status", "maintenance_status", "action"])
            ->make(true);
    }

    public function change_status(Request $request)
    {
        if (!$request->ajax())
        {
            exit('No direct script access allowed');
        }
        if (!empty($request->all()))
        {
            Lounge::where("lounge_id", $request->lounge_id)->update(["lounge_status" => $request->status]);
            if ($request->status == 1) {
                echo 'Status Activate successfully';
            } else if ($request->status == 0){
                echo 'Status Inactivate successfully';
            }
        }
    }

    public function change_book_status(Request $request)
    {
        if (!$request->ajax())
        {
            exit('No direct script access allowed');
        }
        if (!empty($request->all()))
        {
            Lounge::where("lounge_id", $request->lounge_id)->update(["lounge_book_status" => $request->status]);
            if ($request->status == 1) {
                echo 'Status Activate successfully';
            } else if ($request->status == 0){
                echo 'Status Inactivate successfully';
            }
        }
    }

    public function change_maintenance_status(Request $request)
    {
        if (!$request->ajax())
        {
            exit('No direct script access allowed');
        }
        if (!empty($request->all()))
        {
            Lounge::where("lounge_id", $request->lounge_id)->update(["lounge_maintenance_status" => $request->status]);
            if ($request->status == 1) {
                echo 'Status Activate successfully';
            } else if ($request->status == 0){
                echo 'Status Inactivate successfully';
            }
        }
    }

    public function update_order(Request $request)
    {
        //print_r($request->order); exit();
        foreach ($request->order as $order) {
            Lounge::where("lounge_id", $order["lounge_id"])->update(["lounge_order" => $order["position"]]);
        }
        echo 'Lounge order changed successfully.';
    }

    public function delete(Request $request)
    {
        Lounge::where("lounge_id", $request->lounge_id)->delete();
    }

    public function deleteLTime(Request $request)
    {
        LoungeTime::where("ltime_id", $request->ltime_id)->delete();
    }

    public function deleteLMTime(Request $request)
    {
        LoungeMaintenanceTime::where("lmtime_id", $request->lmtime_id)->delete();
    }

    public function remove_image(Request $request)
    {
        $image = LoungeImage::find($request->limage_id);
        if ($image->limage_image!='' && file_exists(public_path('/uploads/lounge/'.$image->limage_image))) {
            @unlink(public_path('/uploads/lounge/'.$image->limage_image));
        }
        LoungeImage::where("limage_id", $request->limage_id)->delete();
    }
}