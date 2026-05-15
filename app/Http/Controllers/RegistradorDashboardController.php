<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Persona;
use App\Models\Actividad;
use App\Models\Institucion;
// Asegúrate de importar tu modelo de Ficha si se llama diferente
use App\Models\FichaBeneficiario; 

class RegistradorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $area = $user->area_intervencion_id;

        // LÓGICA DE FILTRO: 
        // Si el área es 'M6', busca todos los que empiecen con 'M6' (M6A, M6B...), 
        // si no, busca exacto.
        $filtroArea = function ($query) use ($area) {
            if ($area === 'M6') {
                $query->where('area_intervencion_id', 'LIKE', 'M6%');
            } else {
                $query->where('area_intervencion_id', $area);
            }
        };

        // 1. OBTENER CONTADORES (Filtrados por Área)
        $totalPersonas = Persona::where($filtroArea)->count();
        $totalActividades = Actividad::where($filtroArea)->count();
        
        // Asumiendo que FichaBeneficiario tiene relación con Persona o Area
        // Si no tienes FichaBeneficiario modelo, usa DB::table('ficha_beneficiario')->...
        try {
            $totalFichas = \Illuminate\Support\Facades\DB::table('ficha_beneficiario')->count(); 
        } catch (\Exception $e) {
            $totalFichas = 0;
        }

        // Instituciones suelen ser globales, pero si quieres filtrar, aplica lógica similar
        $totalInstituciones = Institucion::count();

        // 2. DATOS PARA GRÁFICO: Personas por Género (En su área)
        $personasPorGenero = Persona::where($filtroArea)
            ->select('genero', DB::raw('count(*) as total'))
            ->groupBy('genero')
            ->get();

        $chartLabels = [];
        $chartData = [];
        
        foreach ($personasPorGenero as $p) {
            $label = $p->genero == 'M' ? 'Masculino' : ($p->genero == 'F' ? 'Femenino' : 'Otro');
            $chartLabels[] = $label;
            $chartData[] = $p->total;
        }

        return view('registrador.dashboard', compact(
            'totalPersonas',
            'totalActividades',
            'totalFichas',
            'totalInstituciones',
            'chartLabels',
            'chartData'
        ));
    }
}