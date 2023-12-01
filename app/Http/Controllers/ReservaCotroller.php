<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservaCotroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filtro = array();
        $filtroOr = array();
        if ($request->get('id')) {
            array_push($filtro, ['reservas.id', $request->get('id')]);
        }

        if ($request->get('fecha')) {
            array_push($filtro, ['reservas.fecha_llegada', $request->get('fecha')]);
        }

        if ($request->get('nombre')) {
            $nombre = $request->get('nombre');
            array_push($filtroOr, ['clientes.nombre', 'like', "%$nombre%"]);
            array_push($filtroOr, ['clientes.documento', 'like', "%$nombre%"]);
        }
        $reservas = Reserva::join('clientes', 'clientes.id', '=', 'reservas.clientes_id')
                    ->select('reservas.*', 'clientes.nombre', "clientes.documento")
                    ->selectRaw("(reservas.fecha_llegada + reservas.cantidad_noche * INTERVAL '1 day') as fecha_salida")
                    ->where($filtro)
                    ->orWhere($filtroOr)
                    ->orderBy('reservas.id', 'desc')
                    ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Lista de todas las reservas',
            'data' => $reservas
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $req = $request->all();
        $validator = Validator::make($req, [
            'nombre' => 'required',
            'documento' => 'required',
            'numero_personas' => 'required|numeric',
            'fecha_llegada' => 'required',
            'cantidad_noche' => 'required|numeric',
            'valor_reserva' => 'required|numeric'
        ], [
            'required' => 'El :attribute es requerido.',
            'numeric' => 'El :attribute debe ser numérico.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Devuelve la reserva',
                'data' => $validator->messages()
            ], 400);
        }
        $dataClient = array('nombre' => $req['nombre'], 'documento' => $req['documento']);
        $client = Cliente::where('documento', $dataClient['documento'])->first();
        if ($client == NULL) {
            $client = Cliente::create($dataClient);
        }
        $dataReserva = array(
            'numero_personas' => $req['numero_personas'],
            'fecha_llegada' => $req['fecha_llegada'],
            'cantidad_noche' => $req['cantidad_noche'],
            'valor_reserva' => $req['valor_reserva'],
            'clientes_id' => $client->id
        );

        $reservas = Reserva::create($dataReserva);
        return response()->json([
            'status' => 'success',
            'message' => 'La reserva se creo',
            'data' => $reservas
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reserva $reserva)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reserva $reserva)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reserva $reserva)
    {
        $req = $request->all();
        $validator = Validator::make($req, [
            'numero_personas' => 'required|numeric',
            'fecha_llegada' => 'required',
            'cantidad_noche' => 'required|numeric',
            'valor_reserva' => 'required|numeric',
        ], [
            'required' => 'El :attribute es requerido.',
            'numeric' => 'El :attribute debe ser numérico.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Devuelve la reserva',
                'data' => $validator->messages()
            ], 400);
        }
        $reserva->update($req);
        return response()->json([
            'status' => 'success',
            'message' => 'La reserva se actualizo',
            'data' => $reserva
        ], 200);
    }

    /**
     * Actuliza el estado de una reserva
     */
    public function editEstadoReserva(Request $request, Reserva $reserva)
    {
        $req = $request->all();
        $validator = Validator::make($req, [
            'estado_reserva' => 'required'
        ], [
            'required' => 'El :attribute es requerido.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Devuelve la reserva',
                'data' => $validator->messages()
            ], 400);
        }
        $reserva->update($req);
        return response()->json([
            'status' => 'success',
            'message' => 'La reserva se creo',
            'data' => $reserva
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reserva $reserva)
    {
        //
    }
}
