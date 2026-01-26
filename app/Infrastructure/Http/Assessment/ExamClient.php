<?php 

namespace App\Infrastructure\Http\Assessment;

use App\Concerns\Assessment\HasAssessmentType;
use App\Concerns\Assessment\HasQuestionFormatting;
use App\Domain\Contracts\Assessment\ExamClientInterface;
use App\Domain\Enums\ExamActivityType;
use InvalidArgumentException;

class ExamClient implements ExamClientInterface{
    use HasAssessmentType, HasQuestionFormatting;
    
    public function name() : string {
        return ExamActivityType::MISTA->value;
    }

    public function listStudentActivity(int $idSalaVirtual, int $idSalaVirtualOferta, ExamActivityType $value) : array {
        if(!isset($value) || $value->value !== $this->name()) {
            throw new InvalidArgumentException('Tipo invalido!!');
        }
        return $this->hasAssessmentType($idSalaVirtual, $idSalaVirtualOferta, $value->value);
    }

    public function confirmStartAssessment(string $cIdAvaliacao, int $try) : void {
        $endpoint = str_replace(
            '{try}', $try+1,
            config('faculdade.endpoints.confirm_start_assessment')
        ); 

        $this->http->client()->get($endpoint,[
            'ap' => 'false',
            'cIdAvaliacao' => $cIdAvaliacao,
            'cache' => random_int(1000000000000, 9999999999999)
        ]);
    }

    public function listAllQuestion(string $id) : array {
        return $this->hasQuestionAllList($id);
    }
}