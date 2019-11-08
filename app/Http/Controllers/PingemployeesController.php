<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pingemployees;
use App\User;

class PingemployeesController extends Controller
{

  public function GetPingemployees()
  {
    $userid = auth()->user()->id;
    $Users = User::whereNotIn('id', [$userid])->get();
    return response()->json([
      'IsSuccess' => true,
      'Message' => 'Successfully geting users.',
      'TotalCount' => 0,
      'Data' => array('Users' => $Users)
    ], 200);
  }

  public function PostPingemployees(Request $request)
  {
    $username = auth()->user()->username;
    $Pingemployees = new Pingemployees;
    $Pingemployees->user_name = $username;
    $Pingemployees->send_to = $request->send_to;
    $Pingemployees->account_id = $request->account_id;
    $Pingemployees->note = $request->note;
    $Pingemployees->save();
    if ($Pingemployees) {
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Successfully added ping.',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong.',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
  }

  public function GetMyPing()
  {
    $username = auth()->user()->username;
    if ($username) {
      $Pingemployees = Pingemployees::where('send_to', $username)->orderBy('id', 'desc')->get();
      $PingemployeesCount = Pingemployees::where('send_to', $username)->where('read', 0)->count();
      $html = '<table class="table text-center">';
      $html .= '<thead>
                            <tr>
                            <th>From User</th>
                            <th>Note</th>
                            <th>Date</th>
                            <th>Action</th>
                            </tr>
                        </thead><tbody>';
      foreach ($Pingemployees as $Pingemployee) {
        $alink = url("accounts") .'/'. $Pingemployee->account_id . '/edit';
        $html .= '<tr>
          <td>' . $Pingemployee->user_name . '</td>
          <td>' . $Pingemployee->note . '</td>
          <td>' . $Pingemployee->created_at . '</td>
          <td><a href="'.$alink.'" class="btn btn-primary active" role="button" aria-pressed="true">Go To Account<a></td>
          </tr>';
      }
      $html .= '</tbody></table>';
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Successfully geting ping.',
        'TotalCount' => 0,
        'Data' => array('Pingemployees' => $html, 'Count' => $PingemployeesCount)
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong.',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
  }

  public function ReadMyPing()
  {
    $username = auth()->user()->username;
    $Pingemployees = Pingemployees::where('send_to', $username)->where('read', 0)->update(['read' => 1]);
    if ($Pingemployees) {
      return response()->json([
        'IsSuccess' => true,
        'Message' => 'Successfully read ping.',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    } else {
      return response()->json([
        'IsSuccess' => false,
        'Message' => 'Something went wrong.',
        'TotalCount' => 0,
        'Data' => []
      ], 200);
    }
  }
}
