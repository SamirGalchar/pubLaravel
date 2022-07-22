<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserDataTable;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePasswordRequest;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Library\Globalfunction;
use Illuminate\Http\Request;

use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    
    function __construct() {
        $this->user = New User();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserDataTable $dataTable) {
        return $dataTable->render('admin.user.listing');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $action = 'edit';
        $html = $this->user->getPageHTMLForUserEdit($id,$action);
        $formParam = array('actionUrl'=>route('admin.users.update',$id),'cancelUrl'=>route('admin.users.index'),'sbtName'=>'btnpage','action'=>$action);
        $htmlContent = Globalfunction::getAdminFormHTML(ucfirst($action).' User',$html,$formParam);
        return view('admin.user.form',compact('htmlContent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id){
        
        /*if($request->hasFile('user_image')){            
            $image =  time().'.'.$request->user_image->extension();
            $request->user_image->move(public_path('uploads/user-img'), $image);
            if(!empty($request->old_user_image) && file_exists(public_path('uploads/user-img/').$request->old_user_image)){
                unlink(public_path('uploads/user-img/').$request->old_user_image);
            }
        } else{
            $image = $request->old_user_image;
        }*/
        

        $formData = ["name"=>$request->name,'email'=>$request->email,'phone'=>$request->phone,'isActive'=>$request->isActive];

        $user = User::find($id);
        $user->update($formData);
        session()->flash('success',__('User Updated successfully'));

        return redirect()->route('admin.users.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(['success'=>'User deleted successfully.']);
    }

    public function profile(){
        $action = 'edit';
        $user_id = Auth::user()->id;
        $html = $this->user->getProfileHtml($user_id,$action);
        $formParam = array('actionUrl'=>route('admin.saveprofile'),'cancelUrl'=>route('admin.dashboard'),'sbtName'=>'btnpage','action'=>$action);
        $htmlContent = Globalfunction::getAdminFormHTML(ucfirst($action).' Profile',$html,$formParam);
        return view('admin.user.profile',compact('htmlContent'));
    }

    public function saveprofile(UpdateProfileRequest $request){
        
        $formData = ['name'=>$request->name,'email'=>$request->email];
        $user_id = Auth::user()->id;
        $admin = User::find($user_id);
        $admin->update($formData);
        session()->flash('success',__('Your Profile updated successfully'));

        return redirect()->route('admin.dashboard');
    }

    public function changePassword(){
        $action = 'edit';
        $html=$this->user->changePassHTML($action);
        $formParam = array('actionUrl'=>route('admin.saveChangepassword'),'cancelUrl'=>route('admin.dashboard'),'sbtName'=>'btnChangePass','action'=>$action);
        $htmlContent = Globalfunction::getAdminFormHTML('Change Password',$html,$formParam);
        return view('admin.user.change_pass',compact('htmlContent'));
    }

    public function saveChangepassword(UpdatePasswordRequest $request){
        
        $admin_id = Auth::user()->id;
        $admin = User::find($admin_id);
        
        $updVal=array("password"=>bcrypt($request->newPassword));
        $admin->update($updVal);
        session()->flash('success',__('Password changed successfully'));

        return redirect()->route('admin.dashboard');
    }

    public function checkPassword(Request $request){
        $admin_id = Auth::user()->id;
        $admin = User::where('id',$admin_id)->where('role','admin')->first();
        
        if(Hash::check($request->oldPassword,$admin->password)){
            echo "true";
            exit;
        }
        else{
            echo "false";
            exit;
        }
    }
}
