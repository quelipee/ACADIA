<?php

namespace App\Http\Controllers;

use App\Application\Services\ResolverAssessmentService;
use App\Domain\Contracts\Academic\DisciplinaClientInterface;
use App\Domain\Contracts\Academic\GraduationClientInterface;
use App\Domain\Enums\AiProvider;
use App\Domain\Enums\ExamActivityType;
use App\Infrastructure\Http\Session\FaculdadeSession;
use Illuminate\Http\Request;
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
    ]);
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
            'activities' => $activities,
            'idSalaVirtualOfertaAproveitamento' => $idSalaVirtualOfertaAproveitamento
        ]);
    }

    public function activity_attempts(Request $request) {
        $data = $this->resolver->attempts($request['data']['cID']);

        return Inertia::render('Graduation/Activityattempts',[
            'attempts' => $data
        ]);
    }

    public function answer_activity(Request $request) {
        $data = $this->resolver->formattedQuestions($request->toArray());
        return Inertia::render('Graduation/Activities/ActivityQuestion', [
            'questions' => $data,
            'idSalaVirtualOfertaAproveitamento' => $request['idSalaVirtualOfertaAproveitamento'],
            'idSalaVirtual' => $request['data']['idSalaVirtual'],
            'typeExam' => $request['data']['nomeClassificacaoTipo']
        ]);
    }

    public function selected_alternative(Request $request, int $idQuestaoAlternativa) {
        $this->resolver->resolver($request->toArray(), $idQuestaoAlternativa);
    }

    public function handleFormSubmit(Request $request)
    {
        $this->resolver->submit_activity($request->toArray());

        return redirect()->route('activities', [
            'id' => $request['idSalaVirtual'],
            'idSalaVirtual' => $request['idSalaVirtualOfertaAproveitamento'],
            'type' => $request['typeExam'],
        ]);
    }

    public function answer_activity_ai(Request $data) {
        $provider = AiProvider::from($data['ai']);
        $this->resolver->resolverAI($data->toArray(), $provider);
    }

    public function answer_key(Request $request) {
        $answer_key = $this->resolver->get_answer_key_list($request->toArray());

        return Inertia::render('Graduation/AnswerKey/AnswerKey', [
            'questoes' => $answer_key
        ]);
    }
}
