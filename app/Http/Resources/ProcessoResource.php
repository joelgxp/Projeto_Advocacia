<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProcessoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'numero_processo' => $this->numero_processo,
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'status_color' => $this->status?->color(),
            'data_abertura' => $this->data_abertura?->format('d/m/Y'),
            'data_peticao' => $this->data_peticao?->format('d/m/Y'),
            'data_audiencia' => $this->data_audiencia?->format('d/m/Y'),
            'observacoes' => $this->observacoes,
            'cliente' => new ClienteResource($this->whenLoaded('cliente')),
            'advogado' => new AdvogadoResource($this->whenLoaded('advogado')),
            'vara' => new VaraResource($this->whenLoaded('vara')),
            'especialidade' => new EspecialidadeResource($this->whenLoaded('especialidade')),
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i'),
        ];
    }
}

