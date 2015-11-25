<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Plan;
use App\Enterprise;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$pdf = \App::make('dompdf.wrapper');
        //$pdf->loadHTML('<h1>Test</h1>');
        
        //return $pdf->stream();

        /*$title = "Hola mundo desde el controlador";
        $pdf = \PDF::loadView('report.invoice', compact('title'));

        return $pdf->download('invoice.pdf');*/

        /*\Excel::create('report', function($excel) {
            $excel->sheet('hoja_'.date('d-m-Y'), function($sheet) {
                $sheet->mergeCells('A1:E1');
                $sheet->row(1, array(
                    'test1', 'test2'
                ));
            });
        })->download('xls');*/

        // or
        //->export('xls');
    }

    public function planes(Request $request){
        $planes = Plan::orderBy('nombre')->get();
        $enterprises = new Enterprise;

        $order = $request->input('order') == 'asc' ? 'ASC':'DESC';
        
        if($request->input('sort') == 'razon_social'){
            $enterprises = Enterprise::orderBy('razon_social',$order);
        }
        elseif ($request->input('sort') == 'created_at') {
            $enterprises = Enterprise::orderBy('created_at',$order);
        }
        elseif ($request->input('sort') == 'plan') {
            $enterprises = Enterprise::select('enterprises.*','planes.created_at AS created_date','planes.id AS plan_id')
                            ->leftJoin('planes', 'planes.id', '=', 'enterprises.plan_id')
                            ->orderBy('planes.nombre', $order);
        }
		elseif ($request->input('sort') == 'totals') {
            $enterprises = Enterprise::select('enterprises.*','planes.created_at AS created_date',
												'planes.id AS plan_id', \DB::raw('SUM(sale_orders.total) as total_sales'))
                            ->leftJoin('planes', 'planes.id', '=', 'enterprises.plan_id')
                            ->leftJoin('sale_orders', 'sale_orders.enterprise_id', '=', 'sale_orders.id')
                            ->orderBy('total_sales', $order);
        }

        //Filters
        $filtros = array();
        if($request->input('tipo_plan')){
            $enterprises = $enterprises->where('plan_id', $request->input('tipo_plan'));
            $filtros['tipo_plan'] = $request->input('tipo_plan');
        }
		if($request->input('fecha_inic') != '' && $request->input('fecha_fin') != ''){
			$inic_arr = explode('/', $request->input('fecha_inic'));
			$inic = $inic_arr[2]."-".$inic_arr[1]."-".$inic_arr[0]." 00:00:00";
			$fin_arr = explode('/', $request->input('fecha_fin'));
			$fin = $fin_arr[2]."-".$fin_arr[1]."-".$fin_arr[0]." 11:59:59";
			$enterprises = $enterprises->whereBetween('created_at', [$inic, $fin]);
			$filtros['fecha_inic'] = $request->input('fecha_inic');
			$filtros['fecha_fin'] = $request->input('fecha_fin');
		}elseif($request->input('fecha_inic') != '' && $request->input('fecha_fin') == ''){
			$inic_arr = explode('/', $request->input('fecha_inic'));
			$inic = $inic_arr[2]."-".$inic_arr[1]."-".$inic_arr[0]." 00:00:00";
			$enterprises = $enterprises->where('created_at', '>', $inic);
			$filtros['fecha_fin'] = $request->input('fecha_fin');
		}elseif($request->input('fecha_inic') == '' && $request->input('fecha_fin') != ''){
			$fin_arr = explode('/', $request->input('fecha_fin'));
			$fin = $fin_arr[2]."-".$fin_arr[1]."-".$fin_arr[0]." 11:59:59";
			$enterprises = $enterprises->where('created_at', '<', $fin);
			$filtros['fecha_inic'] = $request->input('fecha_inic');
		}

        $enterprises = $enterprises->paginate(10);

        $order_colunm = $order=='ASC' ? 'desc':'asc';

        $param_nombre = array_merge(['sort'=>'razon_social','order'=>$order_colunm], $filtros);
        $param_date = array_merge(['sort'=>'created_at','order'=>$order_colunm], $filtros);
        $param_plan = array_merge(['sort'=>'plan','order'=>$order_colunm], $filtros);
        $param_total = array_merge(['sort'=>'totals','order'=>$order_colunm], $filtros);

        return view('report.index', compact('enterprises','order_colunm',
                                            'planes','filtros',
                                            'param_nombre','param_date',
                                            'param_plan','param_total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}