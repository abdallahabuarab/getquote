<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Reject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RejectsController extends Controller
{
    public function index(Request $request)
    {
        // Search functionality
        $search = $request->query('search');

        $rejects = Reject::when($search, function ($query, $search) {
                $query->where('reject_zipcode', 'like', "%{$search}%")
                      ->orWhere('reject_ip_country', 'like', "%{$search}%")
                      ->orWhere('reject_ip_city', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('portal.rejects.index', compact('rejects'));
        }
}
