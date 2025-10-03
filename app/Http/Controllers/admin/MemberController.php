<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\UserStory;
use Hash;
use Auth;
use Alert;

class MemberController extends Controller
{
   public function memberList(Request $request){

      $user = Auth::user();
      $title = $user->name . " :: List Member";
      $label = "Member List";
      $data = Member::all();
      return view('admin.member.index', compact('data', 'title', 'label'));
   }
   public function blockMember(Request $request){

      $member_id=$request->id;
        $dataMember=Member::findOrFail($member_id);
        if($dataMember->status > 0){
            $data = array(
                'status' =>0 ,
            );
            $msg="Member is now Blocked successfully...!";
        }else{
            $user=Auth::user();
            $data = array(
                'status' =>1 ,
            );
            $msg="Member is now Unblocked successfully...!";
        }
        Member::where('id',$member_id)->update($data);
        toast($msg,'success');
        return back();
    
   }
   public function postStory(Request $request){
      
      $user = Auth::user();
      $title = $user->name . " :: List Story";
      $label = "Story List";
      $data = UserStory::all();
        return view('admin.story.index', compact('data', 'title', 'label'));
   }
   public function publishStory(Request $request){
      $story_id=$request->id;
        $dataUserStory=UserStory::findOrFail($story_id);
        if($dataUserStory->status > 0){
            $data = array(
                'status' =>0 ,
            );
            $msg="UserStory is now Blocked successfully...!";
        }else{
            $user=Auth::user();
            $data = array(
                'status' =>1 ,
            );
            $msg="User Story is now Unblocked successfully...!";
        }
        UserStory::where('id',$story_id)->update($data);
        toast($msg,'success');
        return back();
   }
   public function deleteStory($id)
    {
        $UserStory = UserStory::findOrFail($id);
        $UserStory->delete();
        toast('User Story deleted successfully!','success');
        return redirect()->route('story.list');
    }
}
