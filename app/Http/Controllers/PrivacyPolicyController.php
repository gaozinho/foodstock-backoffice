<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\LgpdTerm;


class PrivacyPolicyController extends Controller
{

    public function show(Request $request)
    {
        $lgpd = LgpdTerm::where("publishing_date", "<", date("Y-m-d H:m:s"))->orderBy("publishing_date", "desc")->first();

        return view('policy', [
            'policy' => $lgpd->term,
        ]);
    }
}
