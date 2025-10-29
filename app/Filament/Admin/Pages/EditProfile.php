<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.admin.pages.edit-profile';

    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        $this->schema->fill([
            'avatar' => auth()->user()->avatar,
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
        ]);
    }

    public function schema(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Profil')
                    ->description('Update informasi profil Anda')
                    ->schema([
                        FileUpload::make('avatar')
                            ->label('Photo Profile')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper()
                            ->directory('avatars')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Upload foto profile Anda (max 2MB). Format: JPG, PNG, atau WebP'),
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(table: 'users', ignorable: auth()->user()),
                    ]),

                Section::make('Ubah Password')
                    ->description('Kosongkan jika tidak ingin mengubah password')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Password Saat Ini')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->requiredWith('new_password')
                            ->rule(function () {
                                return function (string $attribute, $value, \Closure $fail) {
                                    if (filled($value) && !Hash::check($value, auth()->user()->password)) {
                                        $fail('Password saat ini tidak sesuai.');
                                    }
                                };
                            }),
                        TextInput::make('new_password')
                            ->label('Password Baru')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->rule(Password::default())
                            ->requiredWith('new_password_confirmation')
                            ->same('new_password_confirmation'),
                        TextInput::make('new_password_confirmation')
                            ->label('Konfirmasi Password Baru')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->requiredWith('new_password'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->schema->getState();

        $user = auth()->user();

        // Handle avatar upload
        if (isset($data['avatar']) && $data['avatar'] !== $user->avatar) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $data['avatar'];
        }

        // Update name and email
        $user->name = $data['name'];
        $user->email = $data['email'];

        // Update password if provided
        if (!empty($data['new_password'])) {
            $user->password = Hash::make($data['new_password']);
        }

        $user->save();

        // Clear password fields after save
        $this->schema->fill([
            'avatar' => $user->avatar,
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => null,
            'new_password' => null,
            'new_password_confirmation' => null,
        ]);

        Notification::make()
            ->success()
            ->title('Profil berhasil diperbarui')
            ->body('Informasi profil Anda telah berhasil diperbarui.')
            ->send();
    }

    public function getTitle(): string
    {
        return 'Edit Profile';
    }

    public function getHeading(): string
    {
        return 'Edit Profile';
    }
}
