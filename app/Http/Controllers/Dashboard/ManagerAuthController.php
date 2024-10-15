<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignInRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class ManagerAuthController extends Controller
{
   public function postSignIn(SignInRequest $request)
   {
      $user = User::whereHas('roles', function (Builder $query) {
         $query->whereIn('role_id', [2, 3]);
      })->where('username', $request->input('username'))->first();

      if ($user && Hash::check($request->input('password'), $user->password)) {
         $data = [
            'status' => 'success',
            'message' => __('You have been successfully authenticated.'),
            'username' => $user->username
         ];
         return response($data, 200);
      } elseif (!$user) {
         $data = [
            'status' => 'failed',
            'message' => __('User is not Authorize')
         ];
         return response($data, 401);
      } else {
         $data = [
            'status' => 'failed',
            'message' => __('Username or Password incorrect.')
         ];
         return response($data, 401);
      }
   }
}
