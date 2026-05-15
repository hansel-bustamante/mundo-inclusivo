<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Institucion;
use App\Models\Actividad;
use App\Models\Persona;

class CoordinadorDashboardController extends Controller
{
    public function index()
    {
        // 1. KPI Cards (Contadores)
        $totalInstituciones = Institucion::count();
        $totalActividades = Actividad::count();
        $totalBeneficiarios = Persona::count();
        
        // Simulación para 'Seguimiento' si no tienes modelo aún, 
        // o usa tu modelo real: App\Models\Seguimiento::count();
        $totalSeguimientos = 0; 
        try {
            $totalSeguimientos = DB::table('seguimiento')->count(); 
        } catch (\Exception $e) { $totalSeguimientos = 0; }

        // 2. DATOS PARA GRÁFICO: Instituciones por Tipo 
        // (O Actividades por Área, según prefieras. Aquí uso Instituciones)
        $institucionesData = Institucion::select('tipo', DB::raw('count(*) as total'))
            ->groupBy('tipo')
            ->get();

        $chartLabels = [];
        $chartData = [];

        foreach ($institucionesData as $inst) {
            $chartLabels[] = $inst->tipo; // Ej: Publica, Privada, ONG
            $chartData[] = $inst->total;
        }

        return view('coordinador.dashboard', compact(
            'totalInstituciones',
            'totalActividades',
            'totalBeneficiarios',
            'totalSeguimientos',
            'chartLabels',
            'chartData'
        ));
    }
}