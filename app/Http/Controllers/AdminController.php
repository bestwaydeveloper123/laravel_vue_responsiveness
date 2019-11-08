<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
	public function check(Request $request)
	{
		$password = $request->password;
		$useof = $request->useof;
		if ($this->verifyReg($password)) {
			if ($useof === 'register') {
				return redirect('register');
			} else {
				return back();
			}
		} else if ($this->verifyReport($password)) {
			if ($useof === 'adminer') {
				return redirect('adminer');
			} else if ($useof === 'programmer') {
				return redirect('admin/points/programmer');
			} else {
				return back();
			}
		}
		return back()->with('error', 'incorrect password');
	}

	protected function verifyReg($password)
	{
		return $password === 'CharlesPark!';
	}

	protected function verifyReport($password)
	{
		return $password === 'CharlesParkReportMe!';
	}
}
