<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\InscripcionEst;
use App\Models\Curso;
use App\Models\Paralelo;
use App\Models\AnioAcademico;
use App\Models\Nota;
use App\Models\Asistencia;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EstudianteController extends Controller
{
    public function index(Request $request)
    {
        $cursos = Curso::where('estado', true)->orderBy('grado')->get();
        $paralelos = Paralelo::where('estado', true)->get();
        $anioActual = anio_actual();
        
        $query = Estudiante::query();
        
        // Cargar inscripciones del año actual
        if ($anioActual) {
            $query->with(['inscripciones' => function($q) use ($anioActual) {
                $q->where('anio_academico_id', $anioActual->id)
                  ->where('estado', 'activo');
            }]);
        }
        
        // Filtros
        if ($request->filled('curso_id')) {
            $query->whereHas('inscripciones', function($q) use ($request) {
                $q->where('estado', 'activo')
                  ->whereHas('paralelo', function($sq) use ($request) {
                      $sq->where('curso_id', $request->curso_id);
                  });
            });
        }
        
        if ($request->filled('paralelo_id')) {
            $query->whereHas('inscripciones', function($q) use ($request) {
                $q->where('estado', 'activo')
                  ->where('paralelo_id', $request->paralelo_id);
            });
        }
        
        if ($request->has('sin_inscribir')) {
            $query->whereDoesntHave('inscripciones', function($q) use ($anioActual) {
                $q->where('anio_academico_id', $anioActual->id)
                  ->where('estado', 'activo');
            });
        }
        
        $estudiantes = $query->orderBy('apellido_paterno')->get();
        
        return view('admin.estudiantes.index', compact('cursos', 'paralelos', 'estudiantes'));
    }

    public function create()
    {
        return view('admin.estudiantes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombres' => 'required|string|max:50',
            'apellido_paterno' => 'required|string|max:40',
            'apellido_materno' => 'required|string|max:40',
            'ci' => 'required|string|max:20|unique:estudiantes,ci',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|in:M,F',
            'direccion' => 'nullable|string|max:200',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:estudiantes,email',
            'password' => 'nullable|string|min:6',
        ]);

        DB::transaction(function () use ($validated, $request) {
            // Generar codigo de estudiante
            $ultimoEstudiante = Estudiante::latest('id')->first();
            $numero = $ultimoEstudiante ? $ultimoEstudiante->id + 1 : 1;
            $validated['codigo_estudiante'] = 'EST' . str_pad($numero, 6, '0', STR_PAD_LEFT);
            $validated['rude'] = 'RUDE' . str_pad($numero, 8, '0', STR_PAD_LEFT);

            // Crear usuario si tiene email
            $userId = null;
            if (!empty($validated['email'])) {
                $request->validate(['password' => 'required|string|min:6']);
                
                $estudianteRole = Role::where('nombre', 'estudiante')->first();
                $user = User::create([
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role_id' => $estudianteRole->id,
                ]);
                $userId = $user->id;
            }

            $validated['user_id'] = $userId;
            $estudiante = Estudiante::create($validated);

            if ($userId) {
                $user = User::find($userId);
                $user->update([
                    'userable_type' => Estudiante::class,
                    'userable_id' => $estudiante->id,
                ]);
            }
        });

        return redirect()->route('admin.estudiantes')->with('success', 'Estudiante creado exitosamente');
    }

    public function edit($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        return view('admin.estudiantes.edit', compact('estudiante'));
    }

    public function update(Request $request, $id)
    {
        $estudiante = Estudiante::findOrFail($id);
        
        $validated = $request->validate([
            'nombres' => 'required|string|max:50',
            'apellido_paterno' => 'required|string|max:40',
            'apellido_materno' => 'required|string|max:40',
            'ci' => 'required|string|max:20|unique:estudiantes,ci,' . $id,
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|in:M,F',
            'direccion' => 'nullable|string|max:200',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:estudiantes,email,' . $id,
        ]);

        $estudiante->update($validated);

        return redirect()->route('admin.estudiantes')->with('success', 'Estudiante actualizado exitosamente');
    }

    public function destroy($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        $estudiante->delete();

        return redirect()->route('admin.estudiantes')->with('success', 'Estudiante eliminado exitosamente');
    }

    public function getInscripcionData($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        $cursos = Curso::where('estado', true)->with('paralelos')->get();
        
        return view('admin.estudiantes.inscribir', compact('estudiante', 'cursos'));
    }

    public function inscribir(Request $request)
    {
        $validated = $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'paralelo_id' => 'required|exists:paralelos,id',
        ]);

        $anioActual = anio_actual();
        $paralelo = Paralelo::findOrFail($validated['paralelo_id']);
        
        // Verificar capacidad
        if ($paralelo->inscritos >= $paralelo->cupo_maximo) {
            return back()->with('error', 'El paralelo ha alcanzado su capacidad maxima.');
        }

        // Verificar si ya esta inscrito
        $existe = InscripcionEst::where('estudiante_id', $validated['estudiante_id'])
            ->where('anio_academico_id', $anioActual->id)
            ->where('estado', 'activo')
            ->exists();

        if ($existe) {
            return back()->with('error', 'El estudiante ya esta inscrito en este anio academico.');
        }

        DB::transaction(function () use ($validated, $anioActual, $paralelo) {
            // Generar codigo de inscripcion
            $ultimaInscripcion = InscripcionEst::latest('id')->first();
            $numero = $ultimaInscripcion ? $ultimaInscripcion->id + 1 : 1;
            $codigo = 'INS-' . date('Y') . '-' . str_pad($numero, 6, '0', STR_PAD_LEFT);

            InscripcionEst::create([
                'codigo' => $codigo,
                'estudiante_id' => $validated['estudiante_id'],
                'paralelo_id' => $validated['paralelo_id'],
                'anio_academico_id' => $anioActual->id,
                'fecha_inscripcion' => now(),
                'estado' => 'activo',
            ]);

            // Actualizar contador de inscritos
            $paralelo->increment('inscritos');
        });

        return redirect()->route('admin.estudiantes')->with('success', 'Estudiante inscrito exitosamente');
    }

    public function getNotas($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        $anioActual = anio_actual();
        
        if (!$anioActual) {
            return '<div class="alert alert-warning">No hay un anio academico activo.</div>';
        }

        $inscripcion = InscripcionEst::where('estudiante_id', $id)
            ->where('anio_academico_id', $anioActual->id)
            ->where('estado', 'activo')
            ->with(['paralelo.curso.materias.area'])
            ->first();
        
        if (!$inscripcion) {
            return '<div class="alert alert-warning">Estudiante no inscrito en el anio actual.</div>';
        }

        // Obtener todas las materias del curso
        $materias = $inscripcion->paralelo->curso->materias->sortBy('area.nombre');

        // Obtener todas las notas del estudiante para este anio
        $notasCollection = Nota::where('inscripcion_id', $inscripcion->id)
            ->get();

        // Estructurar datos para la vista
        $boletin = [];
        foreach ($materias as $materia) {
            $notasMateria = $notasCollection->where('materia_id', $materia->id);
            $boletin[$materia->id] = [
                'materia' => $materia,
                1 => $notasMateria->firstWhere('trimestre', '1'),
                2 => $notasMateria->firstWhere('trimestre', '2'),
                3 => $notasMateria->firstWhere('trimestre', '3'),
            ];
            
            // Calc promedio anual
            $suma = 0;
            $count = 0;
            for($t=1; $t<=3; $t++) {
                if(isset($boletin[$materia->id][$t])) {
                    $suma += $boletin[$materia->id][$t]->nota_final;
                    $count++;
                }
            }
            $boletin[$materia->id]['promedio_anual'] = $count > 0 ? round($suma/3) : 0;
            $boletin[$materia->id]['literal'] = $this->numeroALiteral($boletin[$materia->id]['promedio_anual']);
        }

        return view('admin.estudiantes.notas', compact('estudiante', 'inscripcion', 'boletin'));
    }

    public function descargarBoletin($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        $anioActual = anio_actual();
        
        if (!$anioActual) {
            return back()->with('error', 'No hay anio academico activo');
        }

        $inscripcion = InscripcionEst::where('estudiante_id', $id)
            ->where('anio_academico_id', $anioActual->id)
            ->where('estado', 'activo')
            ->with(['paralelo.curso.materias.area'])
            ->firstOrFail();

        $materias = $inscripcion->paralelo->curso->materias->sortBy('area.nombre');
        $notasCollection = Nota::where('inscripcion_id', $inscripcion->id)->get();

        $boletin = [];
        foreach ($materias as $materia) {
            $notasMateria = $notasCollection->where('materia_id', $materia->id);
            $boletin[$materia->id] = [
                'materia' => $materia,
                1 => $notasMateria->firstWhere('trimestre', '1'),
                2 => $notasMateria->firstWhere('trimestre', '2'),
                3 => $notasMateria->firstWhere('trimestre', '3'),
            ];
            
            $suma = 0;
            $count = 0;
            for($t=1; $t<=3; $t++) {
                if(isset($boletin[$materia->id][$t])) {
                    $suma += $boletin[$materia->id][$t]->nota_final;
                    $count++;
                }
            }
            $boletin[$materia->id]['promedio_anual'] = $count > 0 ? round($suma/3) : 0;
            $boletin[$materia->id]['literal'] = $this->numeroALiteral($boletin[$materia->id]['promedio_anual']);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.estudiantes.boletin-pdf', compact('estudiante', 'inscripcion', 'boletin'));
        $pdf->setPaper('letter', 'landscape');
        
        return $pdf->download('Libreta-' . $estudiante->codigo_estudiante . '.pdf');
    }

    private function numeroALiteral($numero)
    {
        $numero = intval($numero);
        if ($numero == 0) return 'CERO';
        
        $unidades = ['', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
        $decenas = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $diez_veinte = ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];

        if ($numero < 10) return $unidades[$numero];
        
        if ($numero >= 10 && $numero < 20) {
            return $diez_veinte[$numero - 10];
        }

        if ($numero >= 20 && $numero < 100) {
            $decena = floor($numero / 10);
            $unidad = $numero % 10;
            
            if ($unidad == 0) return $decenas[$decena];
            
            if ($decena == 2) return 'VEINTI' . $unidades[$unidad];
            
            return $decenas[$decena] . ' Y ' . $unidades[$unidad];
        }

        if ($numero == 100) return 'CIEN';

        return 'NUMERO INVALIDO';
    }

    public function getAsistencia($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        $anioActual = anio_actual();

        if (!$anioActual) {
            return '<div class="alert alert-warning">No hay un anio academico activo.</div>';
        }
        
        $inscripcion = InscripcionEst::where('estudiante_id', $id)
            ->where('anio_academico_id', $anioActual->id)
            ->where('estado', 'activo')
            ->first();
        
        if (!$inscripcion) {
            return '<div class="alert alert-warning">Estudiante no inscrito en el anio actual.</div>';
        }

        $asistencias = Asistencia::where('inscripcion_id', $inscripcion->id)
            ->with(['registradoPor', 'asignacion.materia'])
            ->orderBy('fecha', 'desc')
            ->get();

        return view('admin.estudiantes.asistencia', compact('estudiante', 'asistencias'));
    }

    public function getParaleloCapacidad($id)
    {
        $paralelo = Paralelo::findOrFail($id);
        
        return response()->json([
            'cupo_maximo' => $paralelo->cupo_maximo,
            'inscritos' => $paralelo->inscritos,
            'disponible' => $paralelo->cupo_maximo - $paralelo->inscritos
        ]);
    }

    public function descargarAsistenciaIndividual($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        $anioActual = anio_actual();

        if (!$anioActual) {
            return back()->with('error', 'No hay anio academico activo');
        }

        $inscripcion = InscripcionEst::where('estudiante_id', $id)
            ->where('anio_academico_id', $anioActual->id)
            ->where('estado', 'activo')
            ->firstOrFail();

        $asistencias = Asistencia::where('inscripcion_id', $inscripcion->id)
            ->with(['asignacion.materia', 'registradoPor'])
            ->orderBy('fecha', 'desc')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reportes.pdf.asistencia_individual', compact('estudiante', 'asistencias', 'anioActual'));
        $pdf->setPaper('letter', 'portrait');

        return $pdf->download('Asistencia-' . $estudiante->codigo_estudiante . '.pdf');
    }

    public function descargarAsistenciaGeneral(Request $request)
    {
        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'paralelo_id' => 'required|exists:paralelos,id',
        ]);

        $curso = Curso::findOrFail($request->curso_id);
        $paralelo = Paralelo::findOrFail($request->paralelo_id);
        $anioActual = anio_actual();

        $estudiantes = Estudiante::whereHas('inscripciones', function ($q) use ($request, $anioActual) {
            $q->where('paralelo_id', $request->paralelo_id)
              ->where('anio_academico_id', $anioActual->id)
              ->where('estado', 'activo');
        })
        ->with(['inscripciones' => function($q) use ($anioActual, $request) {
            $q->where('anio_academico_id', $anioActual->id)
              ->where('paralelo_id', $request->paralelo_id)
              ->with('asistencias.asignacion.materia');
        }])
        ->orderBy('apellido_paterno')
        ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reportes.pdf.asistencia_general', compact('estudiantes', 'curso', 'paralelo', 'anioActual'));
        $pdf->setPaper('letter', 'landscape');

        return $pdf->download('Asistencia-General-' . $curso->nombre . '-' . $paralelo->nombre . '.pdf');
    }

    public function descargarNotasGeneral(Request $request)
    {
        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'paralelo_id' => 'required|exists:paralelos,id',
        ]);

        $curso = Curso::findOrFail($request->curso_id);
        $paralelo = Paralelo::findOrFail($request->paralelo_id);
        $anioActual = anio_actual();

        $materias = $curso->materias->sortBy('id');

        $estudiantes = Estudiante::whereHas('inscripciones', function ($q) use ($request, $anioActual) {
             $q->where('paralelo_id', $request->paralelo_id)
               ->where('anio_academico_id', $anioActual->id)
               ->where('estado', 'activo');
        })
        ->with(['inscripciones' => function ($q) use ($anioActual, $request) {
            $q->where('anio_academico_id', $anioActual->id)
                ->where('paralelo_id', $request->paralelo_id)
                ->with('notas');
        }])
        ->orderBy('apellido_paterno')
        ->get();

        $data = [];
        foreach ($estudiantes as $est) {
            $inscripcion = $est->inscripciones->first();
            $notasEst = [];
            
            foreach ($materias as $mat) {
                $notasMateria = $inscripcion->notas->where('materia_id', $mat->id);
                $promedio = $notasMateria->filter(fn($n) => in_array($n->trimestre, [1,2,3]))->avg('nota_final');
                $notasEst[$mat->id] = round($promedio ?? 0);
            }
            $data[] = [
                'estudiante' => $est,
                'notas' => $notasEst
            ];
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reportes.pdf.notas_general', compact('data', 'curso', 'paralelo', 'materias', 'anioActual'));
        $pdf->setPaper('legal', 'landscape');

        return $pdf->download('Notas-General-' . $curso->nombre . '-' . $paralelo->nombre . '.pdf');
    }

    public function tutores($estudiante_id)
    {
        $estudiante = Estudiante::with('tutores')->findOrFail($estudiante_id);
        $tutoresDisponibles = \App\Models\Tutor::where('estado', true)
            ->orderBy('apellido_paterno')
            ->get();
        
        return view('admin.estudiantes.tutores', compact('estudiante', 'tutoresDisponibles'));
    }

    public function attachTutor(Request $request, $estudiante_id)
    {
        $request->validate([
            'tutor_id' => 'required|exists:tutores,id',
            'tipo_parentesco' => 'required|string|max:50',
            'es_principal' => 'boolean'
        ]);

        $estudiante = Estudiante::findOrFail($estudiante_id);
        
        // Verificar si ya existe la relacion
        $existe = $estudiante->tutores()->where('tutor_id', $request->tutor_id)->exists();
        if ($existe) {
            return redirect()->back()->with('error', 'Este tutor ya esta asignado al estudiante.');
        }

        $estudiante->tutores()->attach($request->tutor_id, [
            'tipo_parentesco' => $request->tipo_parentesco,
            'es_principal' => $request->has('es_principal')
        ]);

        return redirect()->route('admin.estudiantes.tutores', $estudiante_id)->with('success');
    }

    public function detachTutor($parentesco)
    {
        $parentescoModel = \App\Models\Parentesco::findOrFail($parentesco);
        $estudiante_id = $parentescoModel->estudiante_id;
        $parentescoModel->delete();

        return redirect()->route('admin.estudiantes.tutores', $estudiante_id)->with('success');
    }
}
