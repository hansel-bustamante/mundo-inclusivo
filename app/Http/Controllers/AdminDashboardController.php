<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\AreaIntervencion;
use App\Models\Institucion;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Obtener Contadores Totales
        $totalUsuarios = Usuario::count();
        $totalAreas = AreaIntervencion::count();
        $totalInstituciones = Institucion::count();

        // 2. Preparar datos para el Gráfico: Usuarios por Área
        // Agrupamos usuarios por area_intervencion_id y contamos
        $usuariosPorArea = Usuario::select('area_intervencion_id', DB::raw('count(*) as total'))
            ->groupBy('area_intervencion_id')
            ->with('areaIntervencion') // Carga ansiosa para obtener el nombre del área
            ->get();

        // 3. Formatear datos para Chart.js (Arrays separados para etiquetas y valores)
        $chartLabels = [];
        $chartData = [];

        foreach ($usuariosPorArea as $registro) {
            // Si el área es null, ponemos 'Sin Asignar', si no, el nombre del área
            $chartLabels[] = $registro->areaIntervencion ? $registro->areaIntervencion->nombre_area : 'Sin Área';
            $chartData[] = $registro->total;
        }

        // 4. Retornar la vista con todos los datos
        return view('admin.dashboard', compact(
            'totalUsuarios',
            'totalAreas',
            'totalInstituciones',
            'chartLabels',
            'chartData'
        ));
    }
}