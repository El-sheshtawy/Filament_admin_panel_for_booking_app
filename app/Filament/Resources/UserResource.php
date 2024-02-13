<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
//                Forms\Components\Select::make('role_id')
//                    ->label('Role in system')
//                    ->relationship('role', 'name')
//                    ->required()
//                    ->native(false),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('password')
                    ->required()
                    ->password()
                    ->revealable()
                    ->maxLength(255)
                    ->rule(Password::default())
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),

//                Forms\Components\TextInput::make('phone_number')
//                    ->tel()
//                    ->maxLength(255),
//
//                Forms\Components\TextInput::make('photo')
//                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('role.name')
                    ->label('System Role'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->label('Membership Date')
                    ->description(fn ($record) => $record->created_at->diffInDays(Carbon::now()) . ' days')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('display_name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('photo')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
        ->recordUrl(function ($record) {
                return Pages\ViewUser::getUrl([$record->id]);
            })
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        Role::ROLE_ADMINISTRATOR => 'Administrator',
                        Role::ROLE_OWNER         => 'Property Owner',
                        Role::ROLE_USER          => 'Simple User',
                    ])
                    ->attribute('role_id'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
//                    Tables\Actions\EditAction::make(),
//                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('changePassword')
                        ->action(function (User $record, array $data): void {
                            $record->update([
                                'password' => Hash::make($data['new_password']),
                            ]);

                            Notification::make()
                                ->title('Password changed successfully.')
                                ->success()
                                ->send();

                        })
                        ->form([
                            Forms\Components\TextInput::make('new_password')
                                ->label('New Password')
                                ->required()
                                ->password()
                                ->revealable()
                                ->maxLength(255)
                                ->rule(Password::default())
                                ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                                ->dehydrated(fn (?string $state): bool => filled($state)),

                            Forms\Components\TextInput::make('new_password_confirmation')
                                ->label('Confirm New Password')
                                ->required()
                                ->password()
                                ->revealable()
                                ->maxLength(255)
                                ->rule(Password::default())
                                ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                                ->dehydrated(fn (?string $state): bool => filled($state))
                                ->rule('required', fn($get) => ! ! $get('new_password'))
                                ->same('new_password'),
                        ])
                        ->icon('heroicon-o-key')
                        ->visible(fn (User $record): bool => $record->role_id === Role::ROLE_ADMINISTRATOR),
                    Tables\Actions\Action::make('deactivate')
                        ->color('danger')
                        ->icon('heroicon-o-trash')
                        ->action(function (User $record) {
                            $record->delete();

                            Notification::make()
                                ->title('User has been deactivated successfully.')
                                ->success()
                                ->send();
                        })
                        ->visible(fn (User $record): bool => $record->role_id === Role::ROLE_ADMINISTRATOR),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
