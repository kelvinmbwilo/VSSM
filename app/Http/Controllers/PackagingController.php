<?php

namespace App\Http\Controllers;

use App\Manufacture;
use App\PackagingInformation;
use App\Recipient;
use App\RecipientPackage;
use App\SystemSettings;
use App\TransportMode;
use App\User;
use App\Vaccine;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Milon\Barcode\DNS1D;

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
        $barcode = new DNS1D();
        $translation = array(
            "english" => array(
                "title" => "Vaccines Visibility System",
                "voucher_title" => "Ministry Of Health",
                "dispatch_date" => "Dispatch Date",
                "entered_by" => "Entered By :",
                "issued_to" => "Issued To :",
                "transport_mode1" => "Transport Mode :",
                "product" => "Products",
                "manufacture" => "Manufacturer",
                "lot_number" => "Batch Number",
                "expired_date" => "Expiration Date",
                "doses" => "Doses",
                "vials" => "Vials",
                "t_price" => "T. Price",
                "total_price" => "Total Price :",
                "issued" => "Issued By",
                "name_signature" => "Name & Designation:",
                "sign" => "Signature:",
                "receiver" => "Received By"
            ),
            "spanish" => array(
                "title" => "Sistema de Visibilidad de Vacunas",
                "voucher_title" => "Ministerio de Salud",
                "dispatch_date" => "Fecha de Despacho",
                "entered_by" => "Anotado por :",
                "issued_to" => "Enviado a :",
                "transport_mode1" => "Modo de Transporte :",
                "product" => "Producto",
                "manufacture" => "Fabricante",
                "lot_number" => "Número de Lote",
                "expired_date" => "Fecha de Caducación",
                "doses" => "Dosis",
                "vials" => "Frascos",
                "t_price" => "Precio T.",
                "total_price" => "Precio Total :",
                "issued" => "Despachado por",
                "name_signature" => "Nombre y Designación:",
                "sign" => "Firma:",
                "receiver" => "Recibido Por",
            ),
        );
        $system_settings = SystemSettings::where('id','!=',"0")->first();
        $lanKey = $system_settings->language;
        $main_currency = $system_settings->main_currency;
        $translate = ($lanKey == "enUS")? $translation['english'] : $translation['spanish'];


        $package = RecipientPackage::where('voucher_number',$id)->first();
        $transport = TransportMode::find($package->transport_mode_id);
        $issued_to = Recipient::find($package->recipient_id);
        $user = User::find($package->sending_user);
        $html = "<div style='width: 710px; font-family: \"Droid Sans\",\"Helvetica Neue\",Helvetica,Arial,sans-serif;'>";
        $html .= "<table style='width: 710px; font-family: \"Droid Sans\",\"Helvetica Neue\",Helvetica,Arial,sans-serif;'>";
        $html .= '<tr>';
        $html .= '<td><img src="'.asset('img/logo1.jpg').'" style="height: 100px;width: 100px"></td>';
        $html .= '<td>';
        $html .= '<h3 style="text-align: center">'. $translate['title'] .'</h3>';
        $html .= '<h4 style="text-align: center">'. $translate['voucher_title'].'</h4>';
        $html .= '</td>';
        $html .= '<td style="text-align: right"><img src="'.asset('img/logo.png').'" style="height: 100px;width: 100px"></td>';
        $html .= '</tr>';
        $html .= "</table>";

        //Barcode Image
        $html .= "<div style='margin-left: 35%'>";
        $html .= '<div>'.$barcode->getBarcodeHTML($id, "C128",2,53).'</div>';
        $html .= "<div style='margin-left: 15%'>".$id ."</div>";
        $html .= "</div>";

        // Dispatch Date
        $html .= "<div style='text-align: center'>";
        $html .= "<h4>". $translate['dispatch_date'] ." ".$package->date_sent ."</h4>";
        $html .= "</div>";

        $html .= "<table style='width: 710px; margin-top: 30px;margin-bottom: 20px'>";
        $html .= '<tr>';
        $html .= '<td>'. $translate['entered_by'] .' '. $user->first_name .' '.$user->last_name.'</td>';
        $html .= '<td>'. $translate['issued_to'] .' '. $issued_to->name .'</td>';
        $html .= '<td>'. $translate['transport_mode1'] .' '. $transport->name  .'</td>';
        $html .= '</tr>';
        $html .= "</table>";

        $html .= "<table style='width: 710px;border-collapse: collapse;' border='1px'>";
        $html .= '<tr style="background-color: #ADFF2F; font-size: 12px">';
        $html .= '<th>Sr</th>';
        $html .= '<th>'. $translate['product'] .'</th>';
        $html .= '<th>'. $translate['manufacture'] .'</th>';
        $html .= '<th>'. $translate['lot_number'] .'</th>';
        $html .= '<th>'. $translate['expired_date'] .'</th>';
        $html .= '<th>'. $translate['doses'] .'</th>';
        $html .= '<th>'. $translate['vials'] .'</th>';
        $html .= '<th>'. $translate['t_price'] .'</th>';
        $html .= '</tr>';
        $i = 1;
        $total_price = 0;
        foreach ($package->items as $val){
            $vaccine = Vaccine::find($val->vaccine_id);
            $packaging = PackagingInformation::find($val->packaging_id);
            $manufacture = Manufacture::find($packaging->manufacture_id);
            $html .= '<tr style="font-size: 12px">';
            $html .= '<td style="text-align: center">'.$i.'</td>';
            $html .= '<td style="text-align: center">'.$vaccine->name .'</td>';
            $html .= '<td style="text-align: center">'.$manufacture->name.'</td>';
            $html .= '<td style="text-align: center">'.$val->batch_number .'</td>';
            $html .= '<td style="text-align: center">'.$val->expiry_date .'</td>';
            $html .= '<td style="text-align: center">'.$val->amount .'</td>';
            $html .= '<td style="text-align: center">'.round($val->amount / $packaging->dose_per_vial, 0, PHP_ROUND_HALF_DOWN).'</td>';
            $html .= '<td style="text-align: center">'.$main_currency."  ".$val->amount * $val->unit_price.'</td>';
            $html .= '</tr>';
            $total_price += ($val->amount * $val->unit_price);
        }
        $html .= "</table>";

        $html .= "<div><h4>".$main_currency."  ". $translate['total_price'] ." ".$total_price."</h4></div>";
        $html .= "<table style='width: 710px;'>";
        $html .= '<tr>';
        $html .= '<td style="width: 30%; font-size: 12px"">';
        $html .= '<div>'. $translate['issued'] .'</div>';
        $html .= '<div>'. $translate['name_signature'] .' _________</div>';
        $html .= '<div>'. $translate['sign'] .' ________</div>';
        $html .= '</td>';
        $html .= '<td style="width: 40%"></td>';
        $html .= '<td style="width: 30%; font-size: 12px">';
        $html .= '<div>'. $translate['receiver'] .'</div>';
        $html .= '<div>'. $translate['name_signature'] .' ___________</div>';
        $html .= '<div>'. $translate['sign'] .' ___________</div>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= "</table>";
        $html .= "</div>";
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        return $pdf->stream();
//        return view('dispatch_voucher', [
//            'name' => 'James',
//            'second_name'=> 'Mbwilo'
//        ]);
    }

}
