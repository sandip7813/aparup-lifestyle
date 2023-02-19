<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Medias;

use Hash;
use Auth;
use Validator;
use Image;
use Illuminate\Support\Facades\File; 

class MyAccountController extends Controller
{
    public function updateProfile() {
        return view('admin.myaccount.update-profile');
    }

    public function updateProfileSubmit(Request $request){
        $response = [];

        $response['status'] = '';

        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required',
            ]);

            $validator_errors = implode('<br>', $validator->errors()->all());
    
            if ($validator->fails()) {
                return response()->json(['status' => 'failed', 'error' => ['message' => $validator_errors]]);
            }

            $user = Auth::user();
            $user->name = $request->full_name ?? null;
            $user->user_bio = $request->short_bio ?? null;
            $user->save();

            //+++++++++++++++++++++++++++ STORE & CROP IMAGES :: Start +++++++++++++++++++++++++++//
            if($request->hasFile('profile_picture')) {
                $usersDir = 'images/users/';

                //------------- DELETE EXISTING IMAGES :: Start -------------//
                self::delete_profile_picture();
                //------------- DELETE EXISTING IMAGES :: End -------------//
                
                $profile_picture = Image::make($request->file('profile_picture'));

                $profilePictureName = time() . '-' . uniqid() . '.' . $request->file('profile_picture')->getClientOriginalExtension();

                //------------- MAIN PROFILE PICTURE UPLOAD :: Start -------------//
                $destinationPath = public_path( $usersDir . 'main/' );
                $profile_picture->save($destinationPath . $profilePictureName);
                //------------- MAIN PROFILE PICTURE UPLOAD :: End -------------//

                //------------- 300 x 300 PROFILE PICTURE UPLOAD :: Start -------------//
                $destinationPathThumbnail = public_path( $usersDir . '300x300/' );
                $profile_picture->resize(300, 300);
                $profile_picture->save($destinationPathThumbnail . $profilePictureName);
                //------------- 300 x 300 PROFILE PICTURE UPLOAD :: End -------------//

                Medias::create([
                    'user_id' => Auth::user()->id,
                    'media_type' => 'user_profile',
                    'source_uuid' => $user->uuid,
                    'name' => $profilePictureName,
                    'is_active' => 1
                ]);
            }
            //+++++++++++++++++++++++++++ STORE & CROP IMAGES :: End +++++++++++++++++++++++++++//

            $response['status'] = 'success';
        } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'failed', 'error' => ['message' => $e->getMessage()], 'e' => $e]);
        }

        return response()->json($response);
    }

    public function deleteProfilePicture(){
        $response = [];

        $response['status'] = '';

        try {
            self::delete_profile_picture();

            $response['status'] = 'success';
        } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'failed', 'error' => ['message' => $e->getMessage()], 'e' => $e]);
        }

        return response()->json($response);
    }

    public function changePassword() {
        return view('admin.myaccount.change-password');
    }

    public function changePasswordSubmit(Request $request) {
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with('error', 'Your current password does not match.');
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            // Current password and new password same
            return redirect()->back()->with('error', 'New Password cannot be same as your current password.');
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:8|confirmed',
        ]);

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with('success', 'Password successfully changed!');
    }

    private static function delete_profile_picture(){
        $user = Auth::user();

        $usersDir = 'images/users/';

        $existingProfilePicture = $user->profile_picture->name ?? null;

        if( !is_null($existingProfilePicture) ){
            File::delete( $usersDir . 'main/' . $existingProfilePicture );
            File::delete( $usersDir . '300x300/' . $existingProfilePicture );

            $user->profile_picture->delete();
        }
    }

}
