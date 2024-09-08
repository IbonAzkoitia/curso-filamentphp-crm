<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Contactos';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Info')
                        ->columns(2)
                        ->schema([
                            Forms\Components\FileUpload::make('image')
                                ->label('Foto')
                                ->directory('contacts')
                                ->preserveFilenames()
                                ->avatar()
                                ->imageEditor()
                                ->circleCropper()
                                ->maxSize(1024)
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('first_name')
                                ->label('Nombre'),
                            Forms\Components\TextInput::make('last_name')
                                ->label('Apellidos'),
                            Forms\Components\TextInput::make('email')
                                ->email(),
                            Forms\Components\TextInput::make('phone')
                                ->label('Teléfono')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('description')
                                ->label('Descripción'),
                            Forms\Components\TextInput::make('job_title')
                                ->label('Puesto'),
                            // Forms\Components\Select::make('account_id')
                            //     ->label('Empresa')
                            //     ->relationship('account', 'name'),
                            Forms\Components\Select::make('source_id')
                                ->label('Fuente del Contacto')
                                ->relationship('source', 'name'),
                        ])
                        ->columnSpan(2),
                    Wizard\Step::make('Social media')
                        ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('url_linkedin')
                                ->label('LinkedIn')
                                ->url()
                                ->placeholder('https://linkedin.com/'),
                            Forms\Components\TextInput::make('url_website')
                                ->label('Página web')
                                ->url()
                                ->placeholder('https://'),
                            Forms\Components\TextInput::make('url_x')
                                ->label('X')
                                ->url()
                                ->placeholder('https://x.com'),
                        ]),
                    Wizard\Step::make('Dirección')
                        ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('street')
                                ->label('Calle'),
                            Forms\Components\TextInput::make('city')
                                ->label('Ciudad'),
                            Forms\Components\TextInput::make('state')
                                ->label('Provincia'),
                            Forms\Components\TextInput::make('postcode')
                                ->label('Código postal')
                                ->numeric(),
                            Forms\Components\TextInput::make('country')
                                ->label('País'),
                        ]),
                ])
                    ->columnSpan(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Nombre')
                    ->formatStateUsing(function ($record) {
                        return $record->first_name . ' ' . $record->last_name;
                    })
                    ->searchable(['first_name', 'last_name']),
                // Tables\Columns\TextColumn::make('account_name')
                //     ->label('Empresa')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('job_title')
                    ->label('Puesto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('birthday')
                    ->label('Cumpleaños')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('source.name')
                    ->label('Fuente del Contacto')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('url_linkedin')
                    ->label('LinkedIn')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('url_website')
                    ->label('Página web')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('url_x')
                    ->label('X')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('street')
                    ->label('Calle')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city')
                    ->label('Ciudad')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state')
                    ->label('Provincia')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('postcode')
                    ->label('Código postal')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('country')
                    ->label('País')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->recordUrl(function ($record) {
                return Pages\ViewContact::getUrl([$record->id]);
            })
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infoList(Infolist $infolist): InfoList
    {
        return $infolist
            ->schema([
                Grid::make()
                    ->columns([
                        'sm' => 1,
                        'xl' => 12,
                    ])
                    ->schema([
                        Grid::make()
                            ->schema([
                                Section::make('')
                                    ->schema([
                                        ImageEntry::make('image')
                                            ->label('')
                                            ->height(60)
                                            ->circular()
                                            ->hidden(fn (Contact $record) => empty($record->image)),
                                        TextEntry::make('source.name')
                                            ->label('Fuente del Contacto')
                                            ->hidden(fn (Contact $record) => empty($record->source)),
                                        TextEntry::make('email')
                                            ->label('Email')
                                            ->hidden(fn (Contact $record) => empty($record->email)),
                                        TextEntry::make('phone')
                                            ->label('Teléfono')
                                            ->hidden(fn (Contact $record) => empty($record->phone)),
                                        TextEntry::make('country')
                                            ->label('País')
                                            ->hidden(fn (Contact $record) => empty($record->country)),
                                        TextEntry::make('description')
                                            ->label('Descripción')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(3),
                                Section::make('Social Media')
                                    ->schema([
                                        TextEntry::make('url_instagram')
                                            ->label('Instagram')
                                            ->icon('heroicon-m-link')
                                            ->copyable()
                                            ->copyMessage('¡Copiado!')
                                            ->copyMessageDuration(1500)
                                            ->limit(30)
                                            ->hidden(fn (Contact $record) => empty($record->url_instagram)),
                                        TextEntry::make('url_linkedin')
                                            ->label('LinkedIn')
                                            ->icon('heroicon-m-link')
                                            ->copyable()
                                            ->copyMessage('¡Copiado!')
                                            ->copyMessageDuration(1500)
                                            ->limit(30)
                                            ->hidden(fn (Contact $record) => empty($record->url_linkedin)),
                                        TextEntry::make('url_tiktok')
                                            ->label('TikTok')
                                            ->icon('heroicon-m-link')
                                            ->copyable()
                                            ->copyMessage('¡Copiado!')
                                            ->copyMessageDuration(1500)
                                            ->limit(30)
                                            ->hidden(fn (Contact $record) => empty($record->url_tiktok)),
                                        TextEntry::make('url_website')
                                            ->label('Página web')
                                            ->icon('heroicon-m-link')
                                            ->copyable()
                                            ->copyMessage('¡Copiado!')
                                            ->copyMessageDuration(1500)
                                            ->limit(30)
                                            ->hidden(fn (Contact $record) => empty($record->url_website)),
                                        TextEntry::make('url_youtube')
                                            ->label('YouTube')
                                            ->icon('heroicon-m-link')
                                            ->copyable()
                                            ->copyMessage('¡Copiado!')
                                            ->copyMessageDuration(1500)
                                            ->limit(30)
                                            ->hidden(fn (Contact $record) => empty($record->url_youtube)),
                                    ])
                                    ->columns(),
                            ])
                            ->columnSpan(7),
                        Grid::make()
                            ->schema([
                                Section::make('Empresa')
                                    ->schema([
                                        TextEntry::make('account.name')
                                            ->label('Nombre')
                                            ->hidden(fn (Contact $record) => empty($record->account->name)),
                                        TextEntry::make('account.account_size.name')
                                            ->label('Tamaño')
                                            ->hidden(fn (Contact $record) => empty($record->account->account_size->name)),
                                        TextEntry::make('account.industry.name')
                                            ->label('Industria')
                                            ->hidden(fn (Contact $record) => empty($record->account->industry->name)),
                                        TextEntry::make('account.account_revenue')
                                            ->label('Facturación')
                                            ->money('EUR')
                                            ->hidden(fn (Contact $record) => empty($record->account->account_revenue)),
                                        TextEntry::make('account.tools.name')
                                            ->label('Herramientas')
                                            ->formatStateUsing(function ($record) {
                                                return view('account.accountToolList', ['tools' => $record->account->tools])->render();
                                            })
                                            ->html()
                                            ->hidden(fn (Contact $record) => empty($record->tools))
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(),
                                Section::make('Dirección')
                                    ->schema([
                                        TextEntry::make('street')
                                            ->label('Calle')
                                            ->hidden(fn (Contact $record) => empty($record->street)),
                                        TextEntry::make('city')
                                            ->label('Ciudad')
                                            ->hidden(fn (Contact $record) => empty($record->city)),
                                        TextEntry::make('state')
                                            ->label('Provincia')
                                            ->hidden(fn (Contact $record) => empty($record->state)),
                                        TextEntry::make('country')
                                            ->label('País')
                                            ->hidden(fn (Contact $record) => empty($record->country)),
                                    ])
                                    ->columns()
                            ])
                            ->columnSpan(5),
                    ])
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
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
            'view' => Pages\ViewContact::route('/{record}'),
        ];
    }
}
