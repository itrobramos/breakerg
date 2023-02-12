<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Client;
use App\Models\InvoiceConfiguration;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Auth;
use App\Exports\SalesExport;
use App\Exports\SalesDetailsExport;
use App\Models\Credit;
use App\Models\Movement;
use Maatwebsite\Excel\Facades\Excel;
use DB;

use Charles\CFDI\CFDI;
use Charles\CFDI\Node\Emisor;
use Charles\CFDI\Node\Receptor;
use Charles\CFDI\Node\Concepto;
use Charles\CFDI\Node\Impuesto\Traslado;

use CfdiUtils;
use CfdiUtils\Cleaner\Cleaner;
use CfdiUtils\Nodes\XmlNodeUtils;
use PhpCfdi\CfdiToPdf\CfdiDataBuilder;
use PhpCfdi\CfdiToPdf\Converter;
use PhpCfdi\CfdiToPdf\Builders\Html2PdfBuilder;

class SaleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $objects = Sale::orderBy('date', 'desc')->get();
        $clients = Client::orderBy('name')->get();
        return view('sales.index', compact('objects', 'clients'));
    }

    public function indexPost(Request $request)
    {
        $fechaInicio = $request->FechaInicio;
        $fechaFin = $request->FechaFin;
        $clientId = $request->clientId;
        $folio = $request->Folio;

        $objects = Sale::orderBy('date', 'desc');
        $clients = Client::orderBy('name')->get();

        if (isset($request->FechaInicio)) {
            $objects->where('date', '>=', $fechaInicio);
        }

        if (isset($request->FechaFin)) {
            $objects->where('date', '<=', $fechaFin);
        }

        if (isset($request->clientId)) {
            $objects->where('clientId',  $clientId);
        }

        if (isset($request->Folio)) {
            $objects->where('folio',  $folio);
        }

        $objects = $objects->get();

        $Parameters = [
            "FechaInicio" => $fechaInicio,
            "FechaFin" => $fechaFin,
            "ClientId" => $clientId,
            "Folio" => $folio
        ];


        return view('sales.index', compact('objects', 'clients', 'Parameters'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $types = ProductType::orderBy('name')->get();

        return view('sales.create', compact('clients', 'products', 'types'));
    }

    public function store(Request $request)
    {

        \DB::beginTransaction();
        try {

            $object = new Sale();
            $object->clientId = $request->clientId;
            $object->date = $request->date;
            $object->total = 0;
            $object->folio = $request->folio;
            $object->userId = Auth::user()->id;

            $object->save();
            $Total = 0;

            //Guardado de imagen
            if ($request->hasFile('file')) {

                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension(); // getting image extension
                $filename = time() . '.' . $extension;
                $file->move('salesimg/', $filename);
                $object->imageUrl = "salesimg/" . $filename;
            }
            
            if (isset($request->product)) {
                foreach ($request->product as $product) {

                    $SaleDetail = new SaleDetail();
                    $SaleDetail->saleId = $object->id;
                    $SaleDetail->productId = $product['productId'];
                    $SaleDetail->quantity = $product['quantity'];
                    $SaleDetail->price = $product['unitPrice'];

                    $SaleDetail->save();

                    $Total = $Total +  ($SaleDetail->price   * $SaleDetail->quantity);

                    //Actualizamos existencias del producto

                    $Producto = Product::find($SaleDetail->productId);
                    $Producto->stock = $Producto->stock - $SaleDetail->quantity;
                    $Producto->save();
                }

                $object->total = $Total;
                $object->save();
            }

            if($request->paymentType == "Credito"){
                $Client = Client::find($request->clientId);

                $credit = new Credit();
                $credit->saleId = $object->id;
                $credit->clientId = $request->clientId;
                $credit->initialPayment = $request->PagoInicial;
                $credit->credit = $request->montoCredito;
                $credit->total = $request->TotalVenta;

                $credit->currentCredit = $request->montoCredito;
                $credit->beginDate = date("Y-m-d");
                $credit->endDate = date('Y-m-d', strtotime("+" . $Client->days . " days"));
                $credit->save();

           
                $Movement = new Movement();
                $Movement->clientId = $request->clientId;
                $Movement->payment = $request->montoCredito;
                $Movement->previosDebt = $Client->creditAmount - $Client->availableCredit;
                $Movement->newDebt = $Client->creditAmount - $Client->availableCredit + $request->montoCredito;
                $Movement->type = 2; // 1 Abono 2 Cargo
                $Movement->saleId =  $object->id;
                $Movement->date = $request->date;
                $Movement->save();
           
                $Client->availableCredit = $Client->availableCredit -  $request->montoCredito;
                $Client->save();

            }
            \DB::commit();
            return redirect('sales/add')->with('success', 'Venta creada correctamente.');
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect('sales/add')->with('danger', 'Error al crear la venta.');
        }
    }

    


    public function show($id)
    {
        $object = Sale::findOrFail($id);
        $details = SaleDetail::where('saleId', $id)->get();

        return view('sales.show', compact('object', 'details'));
    }


    public function edit($id)
    {
        $object = Sale::findOrFail($id);
        return view('sales.edit', compact('object'));
    }


    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $object = Sale::findOrFail($id);
        $object->name = $request->name;
        $object->save();

        return redirect('sales')->with('success', 'Editado correctamente.');
    }


    public function destroy($id)
    {
        Sale::destroy($id);
        return redirect('sales')->with('success', 'Eliminado correctamente.');
    }

    public function export(Request $request)
    {

        $fechaInicio = $request->FechaInicio;
        $fechaFin = $request->FechaFin;
        $clientId = $request->clientId;
        $folio = $request->Folio;

        return Excel::download(new SalesExport($fechaInicio, $fechaFin, $clientId, $folio), 'Ventas.xlsx');
    }

    public function products()
    {
        $objects = DB::select("SELECT p.name product, cr.endDate,sd.quantity, sd.price, c.name client, s.date, s.folio
                    FROM sales s INNER JOIN sale_details sd ON s.id = sd.saleId
                                 INNER JOIN products p ON p.id = sd.productId
                                 INNER JOIN clients c ON c.id = s.clientId
                                 LEFT JOIN credits cr ON s.id = cr.saleId
                    ");

        $clients = Client::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('sales.products', compact('objects', 'clients', 'products'));
    }

    public function productsPost(Request $request)
    {

        $fechaInicio = $request->FechaInicio;
        $fechaFin = $request->FechaFin;
        $clientId = $request->clientId;
        $productId = $request->productId;
        $folio = $request->Folio;

        $query = "SELECT p.name product, cr.endDate, sd.quantity, sd.price, c.name client, s.date, s.folio
        FROM sales s INNER JOIN sale_details sd ON s.id = sd.saleId
                     INNER JOIN products p ON p.id = sd.productId
                     INNER JOIN clients c ON c.id = s.clientId
                     LEFT JOIN credits cr ON s.id = cr.saleId
                     WHERE 1 = 1 ";


        if (isset($fechaInicio)) {
            $query = $query . " AND  DATE(s.date) >= '" . $fechaInicio . "'";
        }

        if (isset($fechaFin)) {
            $query = $query . " AND  DATE(s.date) <= '" . $fechaFin . "'";
        }

        if (isset($clientId)) {
            $query = $query . " AND  s.clientId = " . $clientId;
        }

        if (isset($productId)) {
            $query = $query . " AND  sd.productId = " . $productId;
        }

        if (isset($folio)) {
            $query = $query . " AND  s.folio = " . $folio;
        }


        $objects = DB::select($query);

        $clients = Client::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        $Parameters = [
            "FechaInicio" => $fechaInicio,
            "FechaFin" => $fechaFin,
            "ClientId" => $clientId,
            "ProductId" => $productId,
            "Folio" => $folio
        ];

        return view('sales.products', compact('objects', 'clients', 'products', 'Parameters'));
    }


    public function productsExport(Request $request)
    {
        $fechaInicio = $request->FechaInicio;
        $fechaFin = $request->FechaFin;
        $clientId = $request->clientId;
        $productId = $request->productId;
        $folio = $request->Folio;


        return Excel::download(new SalesDetailsExport($fechaInicio, $fechaFin, $clientId, $productId, $folio), 'VentasDetallado.xlsx');
    }

    public function timbrar(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'rfc' => 'required',
        ]);


        $sale = Sale::findOrFail($request->id);

        $config = InvoiceConfiguration::first();

        //TODO: Validar que existan los archivos
         $cer = file_get_contents(storage_path('app/'.$config->cerPEM));
         $key = file_get_contents(storage_path('app/'.$config->keyPEM));
        // $cer = file_get_contents('publicCer.pem');
        // $key = file_get_contents('publicKey.pem');



        $rfcReceptor = $request->rfc;
        $serie = 'A';
        $folio = 'A0101';
        $folio = 'A0102';
        $formaPago = '01';
        $noCertificado = $config->noCertificado;
        $condicionesPago = '';
        $subtotal = $sale->subtotal;
        $total = $sale->total;
        $moneda = 'MXN';
        $tipoCambio = '1';
        $tipoComprobante = 'I';
        $metodoPago = 'PUE';
        $lugarExpedicion = $config->lugarExpedicion;

        $fecha = date('Y-m-d\Th:m:s');
        //armado de CFDI
        $cfdi = new CFDI([
            'Fecha' => $fecha,
            'Folio' => $folio,
            'FormaPago' => $formaPago, // TODO: Revisar si fijo o dinamico     http://omawww.sat.gob.mx/tramitesyservicios/Paginas/documentos/catPagos.xls
            'LugarExpedicion' => $lugarExpedicion, 
            'MetodoPago' => $metodoPago,
            'Moneda' => $moneda,
            'SubTotal' => $subtotal,
            'TipoDeComprobante' => $tipoComprobante, 
            'Total' => $total,
            'Descuento' => '0.00',
        ],$cer, $key);

        $cfdi->add(new Emisor([
            'Rfc' => $config->RFC, 
            'Nombre' => $config->razonSocial,
            'RegimenFiscal' => $config->regimenFiscal,
        ]));

        $cfdi->add(new Receptor([
            'Rfc' => $request->rfc,
            // 'Nombre' => 'Roberto Ramos',
            'UsoCFDI' => 'G03',//Gastos en general	//https://catalogosat.mx/UsoCFDI/
        ]));

        $concepto = new Concepto([
            'ClaveProdServ' => '90101501', //90101501 Restaurantes // http://pys.sat.gob.mx/PyS/SugerenciasVF.pdf
            // 'NoIdentificacion' => 'UT421511',//?????
            'Cantidad' => '1.000000',
            'ClaveUnidad' => 'E48', ///Unidades específicas de la industria (varias)	// http://pys.sat.gob.mx/PyS/catUnidades.aspx
            // 'Unidad' => 'NA',
            'Descripcion' => 'Mercancía en general',
            'ValorUnitario' => $sale->total,
            'Importe' => $sale->total,
            'Descuento' => '0.00',
        ]);

        $concepto->add(new Traslado([
            'Impuesto' => '002', //IVA
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.160000',
            'Importe' => $sale->total / 1.16,
            'Base' => $sale->subtotal ///TODO: revisar que es base
        ]));
        
        $cfdi->add($concepto);

        $cfdi->add(new Traslado([
            'Impuesto' => '002',
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.160000',
            'Importe' => $sale->total * .16,
        ],[], [
            'TotalImpuestosTrasladados' => $sale->total * .16,
        ]));


        
        $xml = $cfdi->getXml();
        //dd($xml);
        //Eliminamos datos del sello
        $doc = new \DOMDocument();
        $doc->loadXML($xml);
        $node = $doc->getElementsByTagName('Comprobante')[0];
        $node->removeAttribute('Sello');
        $node->removeAttribute('Certificado');
        $xml =  $doc->saveXML();
        //Preparamos timbrado
        $ws = "https://cfdi33-pruebas.buzoncfdi.mx:1443/Timbrado.asmx?wsdl";/*<- Esta ruta es para el servicio de pruebas, para pasar a productivo cambiar por https://timbracfdi33.mx:1443/Timbrado.asmx*/
        $response = '';
        
        dd($xml);
        $base64Comprobante = $xml;
        $base64Comprobante = base64_encode($base64Comprobante);
        
        try {
           
            $params = array();
            /*Nombre del usuario integrador asignado, para efecto de pruebas utilizaremos 'mvpNUXmQfK8=' <- Este usuario es para el servicio de pruebas, para pasar a productivo cambiar por el que le asignarán posteriormente*/
            $params['usuarioIntegrador'] = 'mvpNUXmQfK8='; //Sacarlo de BD o de .env
            /* Comprobante en base 64*/
            $params['xmlComprobanteBase64'] = $base64Comprobante;
            /*Id del comprobante, deberá ser un identificador único, para efecto del ejemplo se utilizará un numero aleatorio*/
            $params['idComprobante'] = rand(5, 999999);//Id de la tabla

            $context = stream_context_create(
                array(
                    'ssl' => array(
                        // set some SSL/TLS specific options
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => false
                    ),
                    'http' => array(
                        'user_agent' => 'PHPSoapClient'
                    )
                ) 
            );

            $options =array();
            $options['stream_context'] = $context;
            // $options['cache_wsdl']= WSDL_CACHE_MEMORY;
            $options['trace']= true;

            libxml_disable_entity_loader(false);
            echo "SoapClient";
    
            $client = new \SoapClient($ws,$options);
            echo "__soapCall";
            $response = $client->__soapCall('TimbraCFDI', array('parameters' => $params));
        }catch (\SoapFault $fault)
        {
            echo "SOAPFault: ".$fault->faultcode."-".$fault->faultstring."\n";
        }

        /*Obtenemos resultado del response*/
        // echo "resultado";
        //echo $response;
        $tipoExcepcion = $response->TimbraCFDIResult->anyType[0];
        $numeroExcepcion = $response->TimbraCFDIResult->anyType[1];
        $descripcionResultado = $response->TimbraCFDIResult->anyType[2];
        $xmlTimbrado = $response->TimbraCFDIResult->anyType[3];
        $codigoQr = $response->TimbraCFDIResult->anyType[4];
        $cadenaOriginal = $response->TimbraCFDIResult->anyType[5];
        $errorInterno = $response->TimbraCFDIResult->anyType[6];
        $mensajeInterno = $response->TimbraCFDIResult->anyType[7];
        $detalleError = $response->TimbraCFDIResult->anyType[8];

        if($xmlTimbrado != ''){
           //Guardar archivos
            /*Guardamos comprobante timbrado*/
            $fullpath = storage_path() .'/app/invoices/'.date('Y').'/' .date('m');
            $relativepath = '/app/invoices/'.date('Y').'/' .date('m');
            if (!file_exists($fullpath)) {
                \File::makeDirectory($fullpath, 0777, true, true);
            }

            file_put_contents($fullpath .'/'. $sale->id . '.xml', $xmlTimbrado);

            /*Guardamos codigo qr*/
            file_put_contents($fullpath .'/'.  $sale->id . '.jpg', $codigoQr);

            print_r("Timbrado exitoso");

            $invoice = new Invoice();
            $invoice->saleId = $sale->id;
            $invoice->xml = $relativepath . '/' . $sale->id . '.xml';
            $invoice->pdf = $relativepath . '/' . $sale->id . '.pdf';
            $invoice->qr = $relativepath . '/' . $sale->id . '.jpg';
            $invoice->cadenaOriginal = '';
            $invoice->status = 1;

            $invoice->clientId = Auth::user()->clientId;
            $invoice->rfcReceptor = $rfcReceptor;
            $invoice->serie = $serie;
            $invoice->folio = $folio;
            $invoice->fecha = $fecha;
            $invoice->formaPago = $formaPago;
            $invoice->noCertificado = $noCertificado;
            $invoice->condicionesDePago = $condicionesPago;
            $invoice->subtotal = $subtotal;
            $invoice->total = $total;
            $invoice->descuento = 0;
            $invoice->moneda = $moneda;
            $invoice->tipoCambio = $tipoCambio;
            $invoice->tipoDeComprobante = $tipoComprobante;
            $invoice->metodoPago = $metodoPago;
            $invoice->lugarExpedicion = $lugarExpedicion;

            //obtenemos el uuid
            $doc = new \DOMDocument();
            $doc->loadXML($xmlTimbrado);
            $node = $doc->getElementsByTagName('TimbreFiscalDigital')[0];
            $uuid = $node->getAttribute('UUID');
            $invoice->uuid = $uuid;
            $invoice->save();


            //TODO: Envio de email
            //Generación de PDF

            //$cfdifile = $invoice->xml;
            //$xml = file_get_contents($cfdifile);
            $xml = $xmlTimbrado;
            // clean cfdi
            $xml = \CfdiUtils\Cleaner\Cleaner::staticClean($xml);


            // create the main node structure
            $comprobante = \CfdiUtils\Nodes\XmlNodeUtils::nodeFromXmlString($xml);

            // create the CfdiData object, it contains all the required information
            $cfdiData = (new \PhpCfdi\CfdiToPdf\CfdiDataBuilder())
                ->build($comprobante);

            // create the converter
            $converter = new \PhpCfdi\CfdiToPdf\Converter(
                new \PhpCfdi\CfdiToPdf\Builders\Html2PdfBuilder()
            );

            // create the invoice as output.pdf
            $converter->createPdfAs($cfdiData, $fullpath .'/'. $sale->id . '.pdf');

    
            //FIN generación de PDF

            return redirect('sales')->with('success','Factura creada correctamente.');


        }else{
            echo "[".$tipoExcepcion." ---- ".$numeroExcepcion." ------ ".$descripcionResultado."  ei=".$errorInterno."    mi=".$mensajeInterno."]" ;
            die;
        }

    }
}
