<?php

namespace App\Console\Commands;

use App\Domain\Contracts\Academic\DisciplinaClientInterface;
use App\Domain\Contracts\FaculdadeClientInterface;
use App\Domain\Contracts\Academic\GraduationClientInterface;
use App\Domain\Contracts\Academic\SubjectClientInterface;
use App\Domain\Contracts\Assessment\ExamClientInterface;
use App\Domain\Enums\ExamActivityType;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(
        protected FaculdadeClientInterface $faculdade,
        protected GraduationClientInterface $graduation,
        protected DisciplinaClientInterface $subject,
        protected ExamClientInterface $exam,
    )
    {
        return parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->faculdade->signInAuthenticated();
        $graduation = $this->graduation->getInfoListGraduation();
        $subject  = $this->subject->courseDiscipline($graduation[2]->idUsuarioCurso,$graduation[2]->idCurso);
        $activities = $this->exam->listStudentActivity($subject[0]->id,$subject[0]->idSalaVirtualOfertaAtual, ExamActivityType::MISTA);
        $eita = $this->exam->fetchFormattedQuestion($activities[1]->id, $activities[1]->status);
        dd($eita);
    }
}
