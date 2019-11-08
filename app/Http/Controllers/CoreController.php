<?php

namespace App\Http\Controllers;

use App\Core;
use App\ChryslerSurvey;
use App\FordSurvey;
use App\MazdaSurvey;
use App\Account;
use Illuminate\Http\Request;

class CoreController extends Controller
{
  protected $str_hand = "";
  protected $str_tail = false;

  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $user = auth()->user()->role;
    return view('admin.core.index', ['role' => $user]);
  }

  public function create(Request $request)
  {
    return view('admin/core/create');
  }

  public function addgeneric(Request $request)
  {
    $core = Core::create([
      'account_id' => $request->account_id,
      'xyzposition' => $request->xyzposition,
      'gxxorlsa' => $request->gxxorlsa,
      'hardware' => $request->hardwarecode,
      'software' => $request->softwarecode,
      'description' => $request->description
    ]);

    $core->title = $core->hardware
      . ' | '
      . $core->software
      . ' | '
      . $core->description
      . ' | '
      . $core->gxxorlsa
      . " | 'Stock S'"
      . $core->id;

    $core->save();

    return view('admin.printcore', [
      'id' => $core->id,
      'hardware' => $core->hardware,
      'software' => $core->software,
      'description' => $core->description,
      'xyzposition' => $core->xyzposition
    ]);
  }

  public function addcore(Request $request)
  {
    if ($request->ford_id !== null && $request->ford_id >= 0) {
      $ford = FordSurvey::where('id', $request->ford_id)->get()->first();
      $core = Core::create([
        'account_id' => $request->account_id,
        'xyzposition' => $request->xyzposition,
        'gxxorlsa' => $request->gxxorlsa,
        'hardware' => $ford->hardware,
        'catchcode' => $ford->catch_word,
        'partnumber' => $ford->part_number,
        'year' => $ford->model_year,
        'vehicle' => $ford->vehicle,
        'engine' => $ford->engine,
        'publish' => 1,
      ]);

      $core->title = $core->hardware
        . ' | '
        . $core->catchcode
        . ' | '
        . $core->partnumber
        . ' | '
        . $core->gxxorlsa
        . " | 'Stock S'"
        . $core->id;

      $core->save();

      return view('admin.print', [
        'id' => $core->id,
        'hardware' => $core->hardware,
        'software' => '',
        'description' => '',
        'xyzposition' => $core->xyzposition,
      ]);
    } elseif ($request->chrysler_id !== null && $request->chrysler_id >= 0) {
      $chrysler = ChryslerSurvey::where('id', $request->chrysler_id)->get()->first();
      $core = Core::create([
        'account_id' => $request->account_id,
        'xyzposition' => $request->xyzposition,
        'gxxorlsa' => $request->gxxorlsa,
        'controller' => $chrysler->controller,
        'moduletype' => $chrysler->module_type,
        'hardware' => $chrysler->hardware_pn,
        'software' => $chrysler->software_pn,
        'oldsoftware' => $chrysler->older_software_pn,
        'year' => $chrysler->year,
        'body' => $chrysler->body,
        'engine' => $chrysler->engine,
        'description' => $chrysler->software_description,
        'publish' => 1,
      ]);

      $core->title = $core->moduletype
        . ' | '
        . $core->hardware
        . ' | '
        . $core->software
        . ' | '
        . $core->oldsoftware
        . ' | '
        . $core->gxxorlsa
        . " | 'Stock S'"
        . $core->id;

      $core->save();

      return view('admin.print', [
        'id' => $core->id,
        'hardware' => $core->hardware,
        'software' => $core->software,
        'description' => $core->description,
        'xyzposition' => $core->xyzposition,
      ]);
    } elseif ($request->mazda_id !== null && $request->mazda_id >= 0) {
      $mazda = MazdaSurvey::where('id', $request->mazda_id)->get()->first();
      $core = Core::create([
        'account_id' => $request->account_id,
        'xyzposition' => $request->xyzposition,
        'gxxorlsa' => $request->gxxorlsa,
        'hardware' => $mazda->hardware,
        '_18881_pn' => $mazda->_18881_pn,
        'catch' => $mazda->catch,
        '_12a650' => $mazda->_12a650,
        'epc_pn' => $mazda->epc_pn,
        'mazda_model' => $mazda->mazda_model,
        'year' => $mazda->year,
        'engine' => $mazda->engine,
        'trans' => $mazda->trans,
        'emissions' => $mazda->emissions,
        'sec' => $mazda->sec,
        'description' => $mazda->description,
        'publish' => 1,
      ]);

      $core->title = $core->hardware
        . ' | '
        . $core->_18881_pn
        . ' | '
        . $core->_12a650
        . ' | '
        . $core->epc_pn
        . ' | '
        . $core->gxxorlsa
        . " | 'Stock S'"
        . $core->id;

      $core->save();

      return view('admin.print', [
        'id' => $core->id,
        'hardware' => $core->hardware,
        'software' => '',
        'description' => $core->description,
        'xyzposition' => $core->xyzposition,
      ]);
    }

    return back()->with('success', 'failed');
  }

  public function partmatches(Request $request)
  {
    $searchTerm = request('q');

    if ($searchTerm === null) {
      return back()->with('success', 'Please input terms for search');
    }

    $chryslers = ChryslerSurvey::search([
      'controller',
      'module_type',
      'hardware_pn',
      'software_pn',
      'older_software_pn',
      'year',
      'body',
      'engine',
      'software_description'
    ], $searchTerm)->get();

    foreach ($chryslers as $chrysler) {
      $core = Core::select('xyzposition')
        ->where([
          'hardware' => $chrysler['hardware_pn'],
        ])
        ->orderBy('id', 'desc')
        ->get();
      if (count($core)) {
        $chrysler['xyzposition'] = $core[0]->xyzposition;
      }
    }

    $fords = FordSurvey::search([
      'hardware',
      'catch_word',
      'part_number',
      'model_year',
      'vehicle',
      'engine'
    ], $searchTerm)->get();

    foreach ($fords as $ford) {
      $core = Core::select('xyzposition')
        ->where([
          'hardware' => $ford['hardware'],
        ])
        ->orderBy('id', 'desc')
        ->get();
      if (count($core)) {
        $ford['xyzposition'] = $core[0]->xyzposition;
      }
    }

    $mazdas = MazdaSurvey::search([
      'hardware',
      '_18881_pn',
      'catch',
      '_12a650',
      'epc_pn',
      'mazda_model',
      'year',
      'engine',
      'trans',
      'emissions',
      'sec',
      'description',
      'vin_ex'
    ], $searchTerm)->get();

    foreach ($mazdas as $mazda) {
      $core = Core::select('xyzposition')
        ->where([
          'hardware' => $mazda['hardware'],
        ])
        ->orderBy('id', 'desc')
        ->get();
      if (count($core)) {
        $mazda['xyzposition'] = $core[0]->xyzposition;
      }
    }

    return view('admin/core/misc/partmatches', [
      'chryslers' => $chryslers,
      'fords' => $fords,
      'mazdas' => $mazdas,
      'accounthnumber' => $request->accounthnumber,
      'gxxorlsa' => $request->gxxorlsa,
      // 'xyzposition' => request('xyzposition'),
    ]);
  }

  public function bulkcheckcore()
  {
    return view('admin/core/bulkcheckcore');
  }

  public function bulkaddaccounts()
  {
    return view('admin/core/bulkaddaccounts', ['bulkinfos' => []]);
  }

  public function strtok_l($tok, $str = null)
  {
    if ($str !== null) {
      $this->str_hand = $str;
    }

    if ($this->str_hand === "") {

      if ($this->str_tail === true) {
        $this->str_tail = false;
        return "";
      }
      return false;
    }

    $pos = \strpos($this->str_hand, $tok);

    if ($pos === false) {
      $tok = $this->str_hand;
      $this->str_hand = "";
    } else {
      $this->str_tail = true;

      if ($pos <= 0) {
        $tok = "";
        $pos = 0;
      } else {
        $tok = \substr($this->str_hand, 0, $pos);
      }
      $this->str_hand = \substr($this->str_hand, $pos + 1);
    }

    return $tok;
  }

  public function setpublish()
  {
    $id = request('id');

    $core = Core::findOrfail($id);
    $core->publish = request('publish');
    $core->save();

    return response()->json($core);
  }

  public function dataTableCore()
  {
    $Core = Core::get();
    $array = [];
    foreach ($Core as $Cor) {
      $data = array(
        'id' => $Cor->id,
        'account_id' => $Cor->account_id,
        'title' => $Cor->title,
        'hardware' => $Cor->hardware,
        'software' => $Cor->software,
        'xyzposition' => $Cor->xyzposition,
        'gxxorlsa' => $Cor->gxxorlsa,
        'description' => $Cor->description,
      );
      array_push($array, $data);
    }
    return json_encode($array);
  }
}
