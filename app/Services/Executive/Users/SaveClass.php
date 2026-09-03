<?php

namespace App\Services\Executive\Users;

use App\Models\User;
use App\Models\UserRole;
// use App\Jobs\MailJob;
use App\Http\Resources\UserResource;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class SaveClass
{
    public function store($request){
        $data = User::create(array_merge($request->all(), ['password' => bcrypt('dost7!@#$%'),'is_new' => 1 ,'role' => 'Staff','avatar' =>'avatar.jpg']));
        $data->profile()->create($request->all());
        $data->myroles()->create($request->all());
        // MailJob::dispatch($data);
        return [
            'data' => new UserResource($data),
            'message' => 'User creation was successful!', 
            'info' => "You've successfully created an account for the user."
        ];
    }

    public function update($request){
        $data = User::with('profile.suffix')->where('id',$request->id)->first();
        $data->update($request->all());

        return [
            'data' => new UserResource($data),
            'message' => 'User update was successful!', 
            'info' => "You've successfully updated the selected user."
        ];
    }

    public function avatar($request){
        $user = User::with('profile')->findOrFail($request->id);

        $manager = new ImageManager(new Driver());
        $encoded = $manager->read($request->file('image'))->cover(300, 300)->toWebp(80);

        $path = 'oneportal/avatars/' . $user->username . '.webp';
        Storage::disk('s3')->put($path, (string) $encoded);

        $user->profile->avatar = $path;
        $user->profile->save();

        return [
            'data' => new UserResource($user),
            'message' => 'Profile picture updated successfully.',
            'info' => "The user's profile image has been changed to the new photo."
        ];
    }

    public function role($request){
        if($request->type == 'remove'){
            $data = UserRole::find($request->id);
            $data->removed_by = \Auth::user()->id;
            $data->removed_at = now();
            $data->is_active = 0;
            $data->save();
            $id = $request->id;
        }else{
            $data = UserRole::where('user_id', $request->id)->where('role_id', $request->role_id)->first();
            if(!$data){
                $data = new UserRole;
                $data->role_id = $request->role_id;
                $data->user_id = $request->id;
            }
            $data->added_by = \Auth::user()->id;
            $data->removed_by = null;
            $data->removed_at = null;
            $data->is_active = 1;
            $data->save();
            $id = $data->id;
        }

        $data = UserRole::with('role:id,name','added:id','added.profile:user_id,firstname,middlename,lastname,suffix_id','removed:id','removed.profile:user_id,firstname,middlename,lastname,suffix_id')->where('id',$id)->first();
        return [
            'data' => new RoleResource($data),
            'message' => 'User role remove was successful!', 
            'info' => "You've successfully updated the selected user."
        ];
    }
}
