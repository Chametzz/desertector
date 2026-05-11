<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Verificar que el usuario tenga perfil de tutor
        if (!$user->isTutor()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $tutor = $user->person->tutor;
        if (!$tutor) {
            abort(404, 'Perfil de tutor no encontrado.');
        }

        // Obtener estudiantes activos del tutor (solo inscritos)
        $students = Student::with(['person', 'major'])
            ->where('tutor_id', $tutor->id)
            ->where('is_active', true)
            ->where('status', 'enrolled')
            ->withCount([
                'absences as total_unjustified_absences' => function ($query) {
                    $query->where('is_justified', false);
                },
                'incidents',
                'observations'
            ])
            // Subconsulta para obtener el máximo nivel de riesgo de los incidentes
            ->withMax('incidents', 'risk_level')
            ->get();

        // Preparar datos para la vista
        $studentsData = [];
        $riskCounts = ['Alto' => 0, 'Medio' => 0, 'Bajo' => 0];

        foreach ($students as $student) {
            $maxRisk = $student->incidents_max_risk_level ?? 1; // Si no hay incidentes, riesgo bajo
            $riskLabel = match ($maxRisk) {
                3 => 'Alto',
                2 => 'Medio',
                default => 'Bajo',
            };
            $riskClass = match ($maxRisk) {
                3 => 'bg-red-100 text-red-800',
                2 => 'bg-yellow-100 text-yellow-800',
                default => 'bg-green-100 text-green-800',
            };

            $studentsData[] = [
                'id' => $student->id,
                'full_name' => $student->person->full_name,
                'control_number' => $student->control_number,
                'major' => $student->major->name ?? 'Sin carrera',
                'status' => $student->status,
                'gpa' => $student->gpa,
                'unjustified_absences' => $student->total_unjustified_absences,
                'incidents_count' => $student->incidents_count,
                'observations_count' => $student->observations_count,
                'risk_label' => $riskLabel,
                'risk_class' => $riskClass,
            ];

            $riskCounts[$riskLabel]++;
        }

        // Ordenar: Alto -> Medio -> Bajo, y dentro del mismo nivel por GPA ascendente (peores primero)
        usort($studentsData, function ($a, $b) {
            $riskOrder = ['Alto' => 1, 'Medio' => 2, 'Bajo' => 3];
            if ($riskOrder[$a['risk_label']] != $riskOrder[$b['risk_label']]) {
                return $riskOrder[$a['risk_label']] <=> $riskOrder[$b['risk_label']];
            }
            return $a['gpa'] <=> $b['gpa'];
        });

        $totalStudents = count($studentsData);
        $riskPercentages = [];
        foreach ($riskCounts as $level => $count) {
            $riskPercentages[$level] = $totalStudents > 0 ? round(($count / $totalStudents) * 100, 1) : 0;
        }

        // Calcular promedio de GPA por nivel de riesgo (agregado especial)
        $avgGpaByRisk = [];
        $gpaSum = ['Alto' => 0, 'Medio' => 0, 'Bajo' => 0];
        $gpaCount = ['Alto' => 0, 'Medio' => 0, 'Bajo' => 0];
        foreach ($studentsData as $student) {
            $gpaSum[$student['risk_label']] += $student['gpa'];
            $gpaCount[$student['risk_label']]++;
        }
        foreach ($gpaSum as $level => $sum) {
            $avgGpaByRisk[$level] = $gpaCount[$level] > 0 ? round($sum / $gpaCount[$level], 2) : 0;
        }

        return view('analysis.index', compact('studentsData', 'riskCounts', 'riskPercentages', 'totalStudents', 'avgGpaByRisk'));
    }
}
