<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Zipcode;
use App\Models\Timezone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ZipcodeReference;

class ZipcodeController extends Controller
{

    public function index(Request $request)
    {
      $searchTerm = $request->input('search', '');
        $zipcodes = ZipcodeReference::Search($searchTerm)->paginate(15);
        return view('portal.zipcodes.index', compact('zipcodes'));
    }
}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
//     public function create()
//     {
//     $timezones = Timezone::all();
//     $zipcode = Zipcode::first();

//     return view('portal.zipcodes.create', compact('timezones', 'zipcode'));
//     }

//     /**
//      * Store a newly created resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return \Illuminate\Http\Response
//      */
//     public function store(Request $request, Zipcode $zipcode)
//     {
//         $request->validate([
//             'zip_code' => 'required|string|unique:zip_codes,zip_code',
//             'city' => 'required|string|max:255',
//             'state' => 'required|string|max:255',
//             'population' => 'required|integer|min:0',
//             'density' => 'required|numeric|min:0',
//             'country' => 'required|string|max:255',
//             'timezone' => 'required|string|exists:timezones,timezone|max:255'
//         ]);

//         $zipcode->create([
//             'zip_code' => $request->zip_code,
//             'country' => $request->country,
//             'city' => $request->city,
//             'state' => $request->state,
//             'population' => $request->population,
//             'density' => $request->density,
//             'timezone' => $request->timezone,
//         ]);

//         return redirect()->route('zipcodes.index')->with('success', 'Zipcode created successfully');
//     }



//     /**
//      * Display the specified resource.
//      *
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     /*

// */
//     /**
//      * Show the form for editing the specified resource.
//      *
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function edit(Zipcode $zipcode)
//     {
//         $timezones=Timezone::all();
//         return view('portal.zipcodes.edit', compact('zipcode' , 'timezones'));
//     }

//     /**
//      * Update the specified resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */


// public function update(Request $request, Zipcode $zipcode)
// {
//     $request->validate([
//         'zip_code' => 'required|string|unique:zip_codes,zip_code,' . $zipcode->zip_code . ',zip_code',
//         'city' => 'nullable|string|max:255',
//         'state' => 'nullable|string|max:255',
//         'population' => 'nullable|integer|min:0',
//         'density' => 'nullable|numeric|min:0',
//         'country' => 'nullable|string|max:255',
//         'timezone' => 'nullable|string|exists:timezones,timezone|max:255',
//     ]);

//     DB::transaction(function () use ($request, $zipcode) {
//         if ($request->zip_code !== $zipcode->zip_code) {
//             $currentZipCode = $zipcode->zip_code;
//             DB::table('zip_codes')->insert([
//                 'zip_code' => $request->zip_code,
//                 'city' => $request->city,
//                 'state' => $request->state,
//                 'population' => $request->population,
//                 'density' => $request->density,
//                 'country' => $request->country,
//                 'timezone' => $request->timezone,
//                 'created_at' => now(),
//                 'updated_at' => now()
//             ]);

//             DB::table('provider_zip_codes')
//                 ->where('zip_code', $currentZipCode)
//                 ->update(['zip_code' => $request->zip_code]);

//             DB::table('zip_codes')
//                 ->where('zip_code', $currentZipCode)
//                 ->delete();

//             $zipcode->zip_code = $request->zip_code;
//         } else {
//             $zipcode->update([
//                 'zip_code' => $request->zip_code,
//                 'city' => $request->city,
//                 'state' => $request->state,
//                 'population' => $request->population,
//                 'density' => $request->density,
//                 'country' => $request->country,
//                 'timezone' => $request->timezone,
//             ]);
//         }
//     });
//     return redirect()->route('zipcodes.index')->with('success', 'Zipcode updated successfully');
// }


//     /**
//      * Remove the specified resource from storage.
//      *
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */


//      public function destroy($zip_code)
// {
//   $delete = Zipcode::where('zip_code', $zip_code->zip_code)->delete();
//   if($delete){
//         return redirect()->route('zipcodes.index')
//             ->with('success', 'Zipcode deleted successfully');
//   }
// }
// }
