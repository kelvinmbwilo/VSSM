<?php

namespace App\Http\Controllers;

use App\PackagingInformation;
use App\Recipient;
use App\RecipientPackage;
use App\TransportMode;
use App\User;
use App\Vaccine;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class PackagingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return PackagingInformation::where('status','!=','deleted')->with('vaccine','manufacture')->get();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        if(count(PackagingInformation::where('GTIN',$request->input("GTIN"))->get()) != 0){

        }else{
            $item = new PackagingInformation;
            $item->vaccine_id         = $request->input("vaccine_id");
            $item->GTIN               = $request->input("GTIN");
            $item->dose_per_vial      = $request->input("dose_per_vial");
            $item->vials_per_box      = $request->input("vials_per_box");
            $item->cm_per_dose        = $request->input("cm_per_dose");
            $item->manufacture_id     = $request->input("manufacture_id");
            $item->commercial_name    = $request->has("commercial_name")?$request->input("commercial_name"):"";
            $item->status               = "active";
            $item->save();
            return $item->load('vaccine','manufacture');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request,$id)
    {
        $item = PackagingInformation::find($id);
        $item->vaccine_id     = $request->input("vaccine_id");
        $item->GTIN     = $request->input("GTIN");
        $item->dose_per_vial     = $request->input("dose_per_vial");
        $item->vials_per_box     = $request->input("vials_per_box");
        $item->cm_per_dose     = $request->input("cm_per_dose");
        $item->manufacture_id     = $request->input("manufacture_id");
        $item->commercial_name    = $request->has("commercial_name")?$request->input("commercial_name"):"";
        $item->status               = $request->input('status');
        $item->save();
        return $item->load('vaccine','manufacture');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $item = PackagingInformation::find($id);
        $item->status = "deleted";
        $item->save();
    }

    public function to_pdf($id){
        $package = RecipientPackage::where('voucher_number',$id)->first();
        $transport = TransportMode::find($package->transport_mode_id);
        $issued_to = Recipient::find($package->recipient_id);
        $user = User::find($package->sending_user);
        $html = "<table style='width: 710px;'>";
        $html .= '<tr>';
        $html .= '<td><img src="'.asset('img/logo1.jpg').'" style="height: 100px;width: 100px"></td>';
        $html .= '<td>';
        $html .= '<h3 style="text-align: center">Vaccines Visibility System</h3>';
        $html .= '<h4 style="text-align: center">Ministry Of Health, Government of Nicaragua</h4>';
        $html .= '</td>';
        $html .= '<td style="text-align: right"><img src="'.asset('img/logo.jpg').'" style="height: 100px;width: 100px"></td>';
        $html .= '</tr>';
        $html .= "</table>";

        $html .= "<div style='text-align: center'>";
        $html .= "<img src='https://www.barcodesinc.com/generator/image.php?code=". $id ."&style=197&type=C128B&width=187&height=100&xres=1&font=3'>";
        $html .= "</div>";

        $html .= "<table style='width: 710px; margin-top: 30px'>";
        $html .= '<tr>';
        $html .= '<td>Entered By: '. $user->first_name .' '.$user->last_name.'</td>';
        $html .= '<td>Issued To: '. $issued_to->name .'</td>';
        $html .= '<td>Transport Mode: '. $transport->name  .'</td>';
        $html .= '</tr>';
        $html .= "</table>";

        $html .= "<table style='width: 710px;border-collapse: collapse;' border='1px'>";
        $html .= '<tr style="background-color: #ADFF2F">';
        $html .= '<th>Product</th>';
        $html .= '<th>Manufacturer</th>';
        $html .= '<th>Batch Number</th>';
        $html .= '<th>Expiration Date</th>';
        $html .= '<th>Doses</th>';
        $html .= '<th>Vials</th>';
        $html .= '<th>T. Price</th>';
        $html .= '</tr>';
        $i = 1;
        foreach ($package->items as $val){
            $vaccine = Vaccine::find($val->vaccine_id);
            $packaging = PackagingInformation::find($val->packaging_id);
            $html .= '<tr>';
            $html .= '<td>'.$i.'</td>';
            $html .= '<td>'.$vaccine->name .'</td>';
            $html .= '<td></td>';
            $html .= '<td>'.$val->batch_number .'</td>';
            $html .= '<td>'.$val->expiry_date .'</td>';
            $html .= '<td>'.$val->amount .'</td>';
            $html .= '<td></td>';
            $html .= '<td></td>';
            $html .= '</tr>';
        }
        $html .= "</table>";
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        return $pdf->stream();
//        return view('dispatch_voucher', [
//            'name' => 'James',
//            'second_name'=> 'Mbwilo'
//        ]);
    }

}
