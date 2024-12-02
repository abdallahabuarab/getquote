<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Models\Request as CustomerRequest;
use App\Http\Controllers\Controller;

class RequestPanelController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search', '');

        $requests = CustomerRequest::query()
        ->search($searchTerm)
        ->orderBy('request_id', 'DESC')
        ->paginate(10);



        return view('portal.requests.index', compact('requests'));
    }

}
