<?php

namespace App\Http\Controllers;

use App\Teams;
use App\User;
use App\Role;
use App\Rank;
use App\TeamSetting;
use App\Account;
use App\PatchNotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function GuzzleHttp\json_encode;
use Lcobucci\JWT\Claim\Validatable;
use Hash;

class SettingsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function create()
	{
		$Roles = Role::get();
		$Ranks = Rank::get();
		return view('auth/settings')->with(['Roles' => $Roles, 'Ranks' => $Ranks]);
	}

	public function changepassword()
	{
		return view('auth/changepassword');
	}

	public function updatePassword(Request $request)
	{		
		$request->validate([
			'old_password' => 'required',
			'password' => 'required',
			'confirm_password' => 'required|same:password',
		]);

		$current_password = Auth::User()->password;           
		if(Hash::check($request->old_password, $current_password))
		{
		  $user_id = Auth::User()->id;                       
		  $obj_user = User::find($user_id);
		  $obj_user->password = Hash::make($request->password);
		  $obj_user->save();
		  return redirect()->back()->with('success', 'Password updated successfully.'); 
		}
		else
		{
		  return redirect()->back()->with('error', 'Please enter correct old password.');
		} 
		return view('auth/changepassword');
	}

	public function teamsettings()
	{
		$user = auth()->user();
		$userName = $user->first_name . ' ' . $user->last_name;
		return view('admin/teamsettings')->with(['UserName' => $userName]);
	}

	public function getFreeUsers()
	{
		$user = auth()->user();
		$senior = TeamSetting::groupBy('senior')->pluck('senior');
		$junior = TeamSetting::where('junior', '!=', NULL)->groupBy('junior')->pluck('junior');
		$a1 = json_decode($senior, true);
		$a2 = json_decode($junior, true);
		$users = array_merge_recursive($a1, $a2);
		return $Users = User::whereNotIn('id', $users)->where('role', 2)->get();
		// return json_encode($Users); die;
	}

	public function getTeam()
	{
		$user = auth()->user();
		$TeamArr = [];
		$TeamCount = 1;
		$senior = TeamSetting::groupBy('senior')->where('team_leader', $user->id)->pluck('senior');
		foreach ($senior as $seniorId) {
			$sData = User::find($seniorId);
			$jData[$TeamCount] = [];
			$sData['juniors'] = [];
			// array_push($TeamArr, $sData);
			$junior = TeamSetting::where('junior', '!=', NULL)->where('team_leader', $user->id)->where('senior', $seniorId)->groupBy('junior')->pluck('junior');
			foreach ($junior as $juniorId) {
				$jUser = User::find($juniorId);
				if($jUser)
					array_push($jData[$TeamCount], $jUser);
			}
			$sData['juniors'] = $jData[$TeamCount];
			array_push($TeamArr, $sData);
			$TeamCount = $TeamCount + 1;
		}
		return $TeamArr;
	}

	public function ChangeTeamUserStatus(Request $request)
	{
		// $request->user_id = 6;
		// $request->mode = 3;
		// $request->senior_id = 4;
		if (isset($request->id) && $request->id != '') {
			$user = auth()->user();
			$teamLeader = $user->id;
			$user = $request->id;
			if ($request->mode == 1) {
				TeamSetting::where('junior', $user)->orWhere('senior', $user)->delete();
				return 'true';
			} else if ($request->mode == 2) {
				TeamSetting::where('junior', $user)->orWhere('senior', $user)->delete();
				$team = new TeamSetting();
				$team->team_leader = $teamLeader;
				$team->senior = $user;
				$team->save();
				return 'true';
			} else if ($request->mode == 3) {
				if (isset($request->senior_id) && $request->senior_id != '')
					$senior_id = $request->senior_id;
				else
					return false;
				TeamSetting::where('junior', $user)->orWhere('senior', $user)->delete();
				$team = new TeamSetting();
				$team->team_leader = $teamLeader;
				$team->senior = $senior_id;
				$team->junior = $user;
				$team->save();
				return 'true';
			} else {
				return 'false';
			}
		} else {
			return 'false';
		}
	}

	public function update(Request $request)
	{ 
		$user = auth()->user();
		if($user->username != $request->username){
			$request->validate([
				'username' => ['required', 'string', 'max:255', 'unique:users'],
			]);
			Account::where('primaryaccountmanager', $user->username)->update(['primaryaccountmanager' => $request->username]);
			Account::where('secondaryaccountmanager', $user->username)->update(['primaryaccountmanager' => $request->username]);
			Account::where('whomadethesale', $user->username)->update(['whomadethesale' => $request->username]);
			Account::where('secondarysale', $user->username)->update(['secondarysale' => $request->username]);
			$user->username = $request->username;
		}
		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		if($user->email != $request->email){
			$request->validate([
				'email' => ['required', 'string', 'max:255', 'unique:users'],
			]);
			$user->email = $request->email;
		} 
		$user->role = $request->role;
		$user->team = $request->team;
		$user->rank = $request->rank;
		$user->phone = $request->phone;
		$user->extension = $request->extension;
		$user->creditcard = $request->creditcard;
		$user->save();
		// upddate user role
		$user->roles()->detach();
		$user->roles()->attach($request->role);
		return back()->with('success', 'The profile information ');
	}

	public function teamstore(Request $request)
	{
		$team = new Teams([
			'username' => auth()->user()->username,
			'rank' => auth()->user()->rank,
			'teammateusername' => $request->input('teammateusername'),
			'teammaterank' => $request->input('rank')
		]);

		$team->save();

		$users = User::all();

		return view('auth/settings', [
			'users' => $users
		]);
	}

	public function mystore(Request $request)
	{
		$user = Auth::user();
		$user->rank = $request->input('myrank');
		$user->save();

		$users = User::all();

		return view('auth/settings', [
			'users' => $users
		]);
	}

	public function patchNotes(){
		$user = Auth::user()->email;
		return view('admin/patch_notes', [
			'email' => $user
		]);
	}

	public function CreatePatchNotes(Request $request)
	{
		$note = new PatchNotes();
		$note->title = $request->title;
		$note->content = $request->content;
		$note->save();
        if ($note) {
            $PatchNotes = PatchNotes::orderBy('id', 'desc')->get();
            return response()->json([
                'IsSuccess' => true,
                'Message' => 'Successfully added record.',
                'TotalCount' => 1,
                'Data' => $PatchNotes
            ], 200);
        } else {
            return response()->json([
                'IsSuccess' => false,
                'Message' => 'Something went wrong.',
                'TotalCount' => 0,
                'Data' => null
            ], 200);
        }
	}

	public function GetPatchNotes(Request $request)
	{
		$PatchNotes = PatchNotes::orderBy('id', 'desc')->get();
        if ($PatchNotes) {
            return response()->json([
                'IsSuccess' => true,
                'Message' => 'Successfully get records.',
                'TotalCount' => 1,
                'Data' => $PatchNotes
            ], 200);
        } else {
            return response()->json([
                'IsSuccess' => false,
                'Message' => 'Something went wrong.',
                'TotalCount' => 0,
                'Data' => null
            ], 200);
        }
	}

}
