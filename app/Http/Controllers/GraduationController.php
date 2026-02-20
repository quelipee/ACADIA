<?php

namespace App\Http\Controllers;

use App\Application\Services\ResolverAssessmentService;
use App\Domain\Contracts\Academic\DisciplinaClientInterface;
use App\Domain\Contracts\Academic\GraduationClientInterface;
use App\Domain\Enums\ExamActivityType;
use App\Infrastructure\Http\Session\FaculdadeSession;
use Inertia\Inertia;

class GraduationController extends Controller
{
    public function __construct(
        protected GraduationClientInterface $graduation,
        protected FaculdadeSession $session,
        protected DisciplinaClientInterface $subject,
        protected ResolverAssessmentService $resolver
    )
    {}

    public function list_all_graduation() {
        $graduationData = $this->graduation->getInfoListGraduation();

        return Inertia::render('Dashboard', ['graduation' => $graduationData,
        'name' => $this->session->getHeaders()['X-Nome']]);
    }

    public function subjects(int $idUsuarioCurso, int $idCurso) {
        $disciplina  = $this->subject->courseDiscipline($idUsuarioCurso, $idCurso);

        return Inertia::render('Graduation/Subjects', [
            'subjects' => $disciplina
        ]);
    }

    public function activities(int $id, int $idSalaVirtualOfertaAproveitamento, ExamActivityType $type) {
        $activities = $this->resolver->get_all_activities($id, $idSalaVirtualOfertaAproveitamento, $type);

        return Inertia::render('Graduation/Activities', [
            'activities' => $activities
        ]);
    }
}
