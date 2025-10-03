<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Auth;
use Alert;

class AdvertisementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $title = $user->name . " :: Advertisement";
        $label = "Advertisement List";

        if ($user->hasRole('Super Admin')) {
            // Super Admin sees all categories
            $advertisementss = Advertisement::orderBy('created_at', 'desc')->get();
        } elseif ($user->hasRole('Vendor')) {
            // Vendor: See their own categories + staff categories
            $staffIds = User::where('created_by', $user->id)->pluck('id')->toArray(); // Get staff IDs under this vendor
            $advertisementss = Advertisement::whereIn('created_by', array_merge([$user->id], $staffIds))
                        ->orderBy('created_at', 'desc')
                        ->get();
        } else {
            // Staff: See only their own categories
            $advertisementss = Advertisement::where('created_by', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->get();
        }


        return view('admin.advertisement.index', compact('advertisementss', 'title', 'label'));
    }

    public function create()
    {
        $user = Auth::user();
        $title = $user->name . " :: Advertisement";
        $label = "Add Advertisement";
        return view('admin.advertisement.create', compact('title', 'label'));
    }

    public function store(Request $request)
    {
            // Validate input
             $validator = Validator::make($request->all(), [
            'title' => 'required',
            'image' => 'nullable|image',
            'link' => 'nullable|url',
            'position' => 'required',
            'is_active' => 'boolean',
            'company_name' => 'nullable|string',
            'company_email' => 'nullable|email',
            'company_phone' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);


        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput(); // Keep old input
        }

        //path for image
        $path_load = config('url.public_path');
        if ($request->hasFile('image')) {
            $image1 = $request->file('image');
            $image = "image".rand(100, 999) . time() . '.' . $image1->getClientOriginalExtension();
            $destinationPath = $path_load . 'advertisement/';
            $image1->move($destinationPath, $image);
        } else {
            $image = "";
        }
        // Create Advertisement
        $data = Advertisement::create([
            'title' => $request->title,
            'company_name'=>$request->company_name ?? NULL,
            'company_email'=>$request->company_email ?? NULL,
            'company_phone'=>$request->company_phone ?? NULL,
            'image' => $image,
            'link' => $request->link ?? NULL,
            'position' => $request->position ?? NULL,
            'is_active' => $request->is_active ?? 1,
            'start_date' => $request->start_date ?? NULL,
            'end_date' => $request->end_date ?? NULL,
            'created_by' => Auth::id(),
        ]);

        if($data){

            toast('Advertisement created successfully.', 'success');
            return redirect()->route('advertisement.index');

        }else{

            toast('Advertisement creation fail Something found error', 'error');
            return redirect()->route('advertisement.index');
        }
    }
    public function edit($id){
        $user = Auth::user();
        $title = $user->name . " :: Advertisement";
        $label = "Add Advertisement";
        $data=Advertisement::findOrFail($id);
        return view('admin.advertisement.edit', compact('title', 'label','data'));
    }
    public function update(Request $request, $id)
    {
            // Validate input
             $validator = Validator::make($request->all(), [
            'title' => 'required',
            'image' => 'nullable|image',
            'link' => 'nullable|url',
            'position' => 'required',
            'is_active' => 'boolean',
            'company_name' => 'nullable|string',
            'company_email' => 'nullable|email',
            'company_phone' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);


        if ($validator->fails()) {
            $errors = implode('<br>', $validator->messages()->all());
            Alert::html('Validation Error!', $errors, 'error');
            return redirect()->back()->withInput(); // Keep old input
        }

        //path for image
        $path_load = config('url.public_path');
        if ($request->hasFile('image')) {
            $image1 = $request->file('image');
            $image = "image".rand(100, 999) . time() . '.' . $image1->getClientOriginalExtension();
            $destinationPath = $path_load . 'advertisement/';
            $image1->move($destinationPath, $image);
        } else {
            $data=Advertisement::where('id',$id)->first();
            $image = $data->image;
        }
        // Create Advertisement
        $data = Advertisement::where('id', $id)->update([
            'title' => $request->title,
            'company_name'=>$request->company_name ?? NULL,
            'company_email'=>$request->company_email ?? NULL,
            'company_phone'=>$request->company_phone ?? NULL,
            'image' => $image,
            'link' => $request->link ?? NULL,
            'position' => $request->position ?? NULL,
            'is_active' => $request->is_active ?? 1,
            'start_date' => $request->start_date ?? NULL,
            'end_date' => $request->end_date ?? NULL,
            'created_by' => Auth::id(),
        ]);

        if($data){

            toast('Advertisement updated successfully.', 'success');
            return redirect()->route('advertisement.index');

        }else{

            toast('Advertisement updation fail Something found error', 'error');
            return redirect()->route('advertisement.index');
        }
    }
    public function publishAdvertisement(Request $request){
        $advertisement_id=$request->id;
        $dataBlog=Advertisement::findOrFail($advertisement_id);
        if($dataBlog->is_active > 0){
            $data = array(
                'is_active' =>0 ,
            );
            $msg="Advertisement is now unpublished successfully...!";
        }else{
            $user=Auth::user();
            $data = array(
                'is_active' =>1 ,
            );
            $msg="Advertisement is now published successfully...!";
        }
        Advertisement::where('id',$advertisement_id)->update($data);
        toast($msg,'success');
        return back();
    }
    public function destroy($id)
    {
        $advertisement = Advertisement::findOrFail($id);
        // Define the folder path
        $folderPath = public_path('admin/uploads/advertisement/');

        // Retrieve the image filename from the database
        $data = Advertisement::where('id', $id)->pluck('image')->first();

        if ($data) {
            $filePath = $folderPath . $data;

            // Check if file exists and delete it
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            // Remove the image reference from the database
            Advertisement::where('id', $id)->update(['image' => null]);

        }
        $advertisement->delete();
        toast('Advertisement deleted successfully!','success');
        return redirect()->route('advertisement.index');
    }

}
