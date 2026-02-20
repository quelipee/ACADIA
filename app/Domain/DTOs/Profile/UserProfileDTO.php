<?php

namespace App\Domain\DTOs\Profile;

use Carbon\Carbon;

class UserProfileDTO
{
    public function __construct(
        public int $idUsuario,
        public string $nome,
        public string $ru,
        public string $login,
        public string $email,
        public ?string $imagem,
        public ?string $ultimoLogin,
        public string $cpf,
        public ?string $dataNascimento,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            idUsuario: (int) $data['X-IdUsuario'],
            nome: $data['X-Nome'],
            ru: $data['X-RU'],
            login: $data['X-Login'],
            email: $data['X-Email'],
            imagem: $data['X-Imagem'] ?? null,
            ultimoLogin: isset($data['X-UltimoLogin'])
                ? Carbon::parse($data['X-UltimoLogin'])->toISOString()
                : null,
            cpf: $data['X-Cpf'],
            dataNascimento: isset($data['X-DataNascimento'])
                ? Carbon::parse($data['X-DataNascimento'])
                : null,
        );
    }

    public function toArray(): array
    {
        return [
            'idUsuario' => $this->idUsuario,
            'nome' => $this->nome,
            'ru' => $this->ru,
            'login' => $this->login,
            'email' => $this->email,
            'imagem' => $this->imagem,
            'ultimoLogin' => $this->ultimoLogin,
            'cpf' => $this->cpf,
            'dataNascimento' => $this->dataNascimento,
        ];
    }
}
