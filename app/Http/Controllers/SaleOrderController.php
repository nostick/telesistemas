<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\SaleOrder;
use App\OrderProduct;

class SaleOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sale_order.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$this->validate($request, SaleOrder::$rules);
		$data = array_merge($request->all(), 
									array(
										'fecha_emision' => date('d-m-Y'),
										'nro_orden' => '000001',
										'enterprise_id' => Auth::user()->enterprise[0]->id
									));
		$order = SaleOrder::create($data);
	
		foreach ($request->input('products') as $p) {
			$producto = new OrderProduct;
			$producto->nombre = $p['nombre'];
			$producto->cantidad = $p['cantidad'];
			$producto->monto = $p['monto'];
			$producto->total = $p['total'];
			$producto->sale_order_id = $order->id;
			$producto->save();
		}
		
        //echo "<pre>"; print_r($request->all()); echo "</pre>";
		return redirect()
				->route('sale-point.orden-venta.create')
				->with('message', '<div class="alert alert-success" style="margin-top:15px">Orden creada con Éxito</div>');
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
