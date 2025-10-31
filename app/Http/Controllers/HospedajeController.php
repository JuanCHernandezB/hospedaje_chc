<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospedaje;
use Illuminate\Support\Facades\Storage;

class HospedajeController extends Controller
{
    /**
     * Mostrar lista de solicitudes de hospedaje
     */
    public function index()
    {
        $hospedajes = Hospedaje::latest()->paginate(10);
        return view('hospedaje.index', compact('hospedajes'));
    }

    /**
     * Mostrar formulario de creación (modal incluido en index)
     */
    public function create()
    {
        return view('hospedaje.create'); // Aquí crearás hospedaje/create.blade.php
    }

    /**
     * Guardar nueva solicitud
     */
    public function store(Request $request)
{
    $request->validate([
    'Fecha_Hospedaje' => 'required|date',
    'Nombre_Solicitante' => 'required|string|max:255',
    'Apellido_Solicitante' => 'required|string|max:255',
    'Documento_Solicitante' => 'required|numeric',
    'Carta_Solicitud' => 'required|file|mimes:pdf',
    'Foto_Evidencia' => 'required|image',
    'tiene_acompanante' => 'required|in:si,no',
    'Nombre_Acompanante' => $request->tiene_acompanante === 'si' ? 'required|string|max:255' : 'nullable|string|max:255',
    'Apellido_Acompanante' => $request->tiene_acompanante === 'si' ? 'required|string|max:255' : 'nullable|string|max:255',
    'Documento_Acompanante' => $request->tiene_acompanante === 'si' ? 'required|numeric' : 'nullable|numeric',
]);

    $hospedaje = new Hospedaje();
    $hospedaje->Fecha_Hospedaje = $request->Fecha_Hospedaje;
    $hospedaje->Nombre_Solicitante = $request->Nombre_Solicitante;
    $hospedaje->Apellido_Solicitante = $request->Apellido_Solicitante;
    $hospedaje->Documento_Solicitante = $request->Documento_Solicitante;

    // Acompañante solo si eligió "si"
    if ($request->tiene_acompanante === 'si') {
        $hospedaje->Nombre_Acompanante = $request->Nombre_Acompanante;
        $hospedaje->Apellido_Acompanante = $request->Apellido_Acompanante;
        $hospedaje->Documento_Acompanante = $request->Documento_Acompanante;
    } else {
        $hospedaje->Nombre_Acompanante = null;
        $hospedaje->Apellido_Acompanante = null;
        $hospedaje->Documento_Acompanante = null;
    }

    // Guardar archivos
 if ($request->hasFile('Carta_Solicitud')) {
    $cartaPath = $request->file('Carta_Solicitud')->store('cartas', 'public');
    $hospedaje->Carta_Solicitud = $cartaPath;
}

if ($request->hasFile('Foto_Evidencia')) {
    $fotoPath = $request->file('Foto_Evidencia')->store('fotos', 'public');
    $hospedaje->Foto_Evidencia = $fotoPath;
}

    $hospedaje->save();

    return redirect()->back()->with('success', 'Hospedaje creado correctamente');
}


public function edit(Hospedaje $hospedaje)
{
    return view('hospedaje.edit', compact('hospedaje'));
}

public function update(Request $request, Hospedaje $hospedaje)
{
    $request->validate([
        'Fecha_Hospedaje' => 'required|date',
        'Nombre_Solicitante' => 'required|string|max:100',
        'Apellido_Solicitante' => 'required|string|max:100',
        'Documento_Solicitante' => 'required|numeric',
        'Nombre_Acompanante' => 'nullable|string|max:100',
        'Apellido_Acompanante' => 'nullable|string|max:100',
        'Documento_Acompanante' => 'nullable|numeric',
        'Carta_Solicitud' => 'nullable|mimes:pdf|max:2048',
        'Foto_Evidencia' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $hospedaje->fill($request->except(['Carta_Solicitud', 'Foto_Evidencia']));

    if ($request->hasFile('Carta_Solicitud')) {
        if ($hospedaje->Carta_Solicitud && Storage::disk('public')->exists($hospedaje->Carta_Solicitud)) {
            Storage::disk('public')->delete($hospedaje->Carta_Solicitud);
        }
        $hospedaje->Carta_Solicitud = $request->file('Carta_Solicitud')->store('cartas', 'public');
    }

    if ($request->hasFile('Foto_Evidencia')) {
        if ($hospedaje->Foto_Evidencia && Storage::disk('public')->exists($hospedaje->Foto_Evidencia)) {
            Storage::disk('public')->delete($hospedaje->Foto_Evidencia);
        }
        $hospedaje->Foto_Evidencia = $request->file('Foto_Evidencia')->store('fotos', 'public');
    }

    $hospedaje->save();

    return redirect()->route('hospedaje.index')->with('success', 'Solicitud actualizada correctamente.');
}



    /**
     * Eliminar una solicitud
     */
    public function destroy($id)
    {
        $hospedaje = Hospedaje::findOrFail($id);

        if ($hospedaje->Carta_Solicitud && Storage::disk('public')->exists($hospedaje->Carta_Solicitud)) {
            Storage::disk('public')->delete($hospedaje->Carta_Solicitud);
        }

        if ($hospedaje->Foto_Evidencia && Storage::disk('public')->exists($hospedaje->Foto_Evidencia)) {
            Storage::disk('public')->delete($hospedaje->Foto_Evidencia);
        }

        $hospedaje->delete();

        return redirect()->route('hospedaje.index')->with('success', 'Solicitud eliminada correctamente.');
    }

     /**
     * Accion de Buscar en el Index
     */

        public function buscar(Request $request)
    {
        $query = $request->input('q');
        
        $hospedajes = Hospedaje::where('Nombre_Solicitante', 'LIKE', "%{$query}%")
            ->orWhere('Apellido_Solicitante', 'LIKE', "%{$query}%")
            ->orWhere('Documento_Solicitante', 'LIKE', "%{$query}%")
            ->orderBy('id_Solicitante', 'desc')
            ->get();

        return response()->json($hospedajes);
    }

     /**
     * Accion de Visualizar los Documentos
     */

    public function verCarta($id)
    {
        $h = Hospedaje::findOrFail($id);

        if (!$h->Carta_Solicitud || !Storage::disk('public')->exists($h->Carta_Solicitud)) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->file(storage_path('app/public/' . $h->Carta_Solicitud));
    }

    public function verFoto($id)
    {
        $h = Hospedaje::findOrFail($id);

        if (!$h->Foto_Evidencia || !Storage::disk('public')->exists($h->Foto_Evidencia)) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->file(storage_path('app/public/' . $h->Foto_Evidencia));
    }

}
