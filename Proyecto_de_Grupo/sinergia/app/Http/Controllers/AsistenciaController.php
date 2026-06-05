<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Socio;
use App\Models\Clase;
use App\Models\Inscripcion;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    public function index()
    {
        $asistencias = Asistencia::with('socio', 'clase')->get();
        return view('asistencias.index', compact('asistencias'));
    }

    public function create()
    {
        $socios = Socio::all();
        $clases = Clase::all();
        return view('asistencias.create', compact('socios', 'clases'));
    }

    // -----------------------------
    // VALIDACIÓN CENTRAL
    // -----------------------------
    private function validarAsistencia(Request $request)
    {
        // 1. Membresía vigente
        $inscripcionVigente = Inscripcion::where('SocioID', $request->SocioID)
            ->where('FechaVencimiento', '>=', date('Y-m-d'))
            ->first();

        if (!$inscripcionVigente) {
            return 'El socio no tiene una membresía vigente.';
        }

        // 2. Clase
        $clase = Clase::findOrFail($request->ClaseID);

        // 3. Día de clase
        $diaActual = Carbon::parse($request->FechaEntrada)
            ->locale('es')
            ->isoFormat('dddd');

        if (strpos(strtolower($clase->Dias), strtolower($diaActual)) === false) {
            return 'No hay clase asignada para este día.';
        }

        // 4. Horario clase
        [$inicio, $fin] = explode('-', $clase->Horario);

        $horaInicio = Carbon::createFromFormat('H:i', trim($inicio));
        $horaFin = Carbon::createFromFormat('H:i', trim($fin));

        $entrada = Carbon::parse(str_replace('T', ' ', $request->FechaEntrada));
        $horaEntrada = Carbon::createFromFormat('H:i', $entrada->format('H:i'));

        // validar entrada
        if (!$horaEntrada->between($horaInicio, $horaFin)) {
            return 'No puedes registrar asistencia fuera del horario de la clase.';
        }

        // validar salida
        if ($request->FechaSalida) {

            $salida = Carbon::parse(str_replace('T', ' ', $request->FechaSalida));
            $horaSalida = Carbon::createFromFormat('H:i', $salida->format('H:i'));

            if ($horaSalida->gt($horaFin)) {
                return 'La hora de salida no puede ser mayor al fin de la clase.';
            }

            if ($horaSalida->lt($horaEntrada)) {
                return 'La hora de salida no puede ser menor a la entrada.';
            }
        }

        return null;
    }

 
    private function existeDuplicado($request, $excludeId = null)
    {
        $fecha = Carbon::parse($request->FechaEntrada)->toDateString();

        $query = Asistencia::where('SocioID', $request->SocioID)
            ->where('ClaseID', $request->ClaseID)
            ->whereDate('FechaEntrada', $fecha);

        if ($excludeId) {
            $query->where('AsistenciaID', '!=', $excludeId);
        }

        return $query->exists();
    }

    // -----------------------------
    // STORE
    // -----------------------------
    public function store(Request $request)
    {
        $request->validate([
            'SocioID' => 'required',
            'ClaseID' => 'required',
            'FechaEntrada' => 'required',
            'FechaSalida' => 'nullable'
        ]);

        if ($error = $this->validarAsistencia($request)) {
            return redirect()->back()
                ->withInput()
                ->with('error', $error);
        }

        if ($this->existeDuplicado($request)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Este socio ya tiene asistencia registrada en esta clase.');
        }

        Asistencia::create([
            'SocioID' => $request->SocioID,
            'ClaseID' => $request->ClaseID,
            'FechaEntrada' => str_replace('T', ' ', $request->FechaEntrada),
            'FechaSalida' => $request->FechaSalida
                ? str_replace('T', ' ', $request->FechaSalida)
                : null
        ]);

        return redirect()->route('asistencias.index')
            ->with('success', 'Asistencia registrada correctamente');
    }

    // -----------------------------
    // EDIT
    // -----------------------------
    public function edit($id)
    {
        $asistencia = Asistencia::findOrFail($id);
        $socios = Socio::all();
        $clases = Clase::all();

        return view('asistencias.edit', compact('asistencia', 'socios', 'clases'));
    }

   
    public function update(Request $request, $id)
    {
        $request->validate([
            'SocioID' => 'required',
            'ClaseID' => 'required',
            'FechaEntrada' => 'required',
            'FechaSalida' => 'nullable'
        ]);

        if ($error = $this->validarAsistencia($request)) {
            return redirect()->back()
                ->withInput()
                ->with('error', $error);
        }

        if ($this->existeDuplicado($request, $id)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Este socio ya tiene asistencia registrada en esta clase.');
        }

        $asistencia = Asistencia::findOrFail($id);

        $asistencia->update([
            'SocioID' => $request->SocioID,
            'ClaseID' => $request->ClaseID,
            'FechaEntrada' => str_replace('T', ' ', $request->FechaEntrada),
            'FechaSalida' => $request->FechaSalida
                ? str_replace('T', ' ', $request->FechaSalida)
                : null
        ]);

        return redirect()->route('asistencias.index')
            ->with('success', 'Asistencia actualizada correctamente');
    }

   
    public function destroy($id)
    {
        $asistencia = Asistencia::findOrFail($id);
        $asistencia->delete();

        return redirect()->route('asistencias.index')
            ->with('success', 'Asistencia eliminada correctamente');
    }
}