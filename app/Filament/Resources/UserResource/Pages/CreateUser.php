<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Role;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    public function getSubheading(): Htmlable|string|null
    {
        return 'This form will create an administrator user';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['role_id'] = Role::ROLE_ADMINISTRATOR;

        return $data;
    }
}
