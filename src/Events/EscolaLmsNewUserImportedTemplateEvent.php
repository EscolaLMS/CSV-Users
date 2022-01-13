<?php

namespace EscolaLms\CsvUsers\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EscolaLmsNewUserImportedTemplateEvent
{
    use Dispatchable, SerializesModels;

    private Authenticatable $user;

    public function __construct(Authenticatable $user)
    {
        $this->user = $user;
    }

    public function getUser(): Authenticatable
    {
        return $this->user;
    }
}
