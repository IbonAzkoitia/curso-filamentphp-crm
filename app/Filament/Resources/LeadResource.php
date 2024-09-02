<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Filament\Resources\LeadResource\RelationManagers;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

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
                                ->label('Teléfono'),
                            Forms\Components\Textarea::make('description')
                                ->label('Descripción'),
                            Forms\Components\TextInput::make('job_title')
                                ->label('Puesto'),
                            Forms\Components\Select::make('lead_status_id')
                                ->label('Status')
                                ->default(1)
                                ->relationship(
                                    name: 'leadStatus',
                                    modifyQueryUsing: fn (Builder $query) => $query->orderBy('id')
                                )
                                ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name}"),
                            Forms\Components\Select::make('source_id')
                                ->label('Fuente del Lead')
                                ->relationship('source', 'name')
                        ]),
                    Wizard\Step::make('Social Media')
                        ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('url_linkedin')
                                ->label('LinkedIn')
                                ->url()
                                ->placeholder('https://linkedin.com/'),
                            Forms\Components\TextInput::make('url_website')
                                ->label('Página web')
                                ->url()
                                ->placeholder('https://prolinks.pro'),
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
                                ->label('Código postal'),
                            Forms\Components\TextInput::make('country')
                                ->label('País'),
                        ]),
                    Wizard\Step::make('Empresa')
                        ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('account_name')
                                ->label('Empresa'),
                            Forms\Components\TextInput::make('account_revenue')
                                ->label('Facturación')
                                ->numeric(),
                            Forms\Components\Select::make('account_size_id')
                                ->label('Tamaño de la empresa')
                                ->relationship('account_size', 'name'),
                            Forms\Components\Select::make('industry_id')
                                ->label('Industria')
                                ->relationship('industry', 'name'),
                        ])
                        
                ])
                ->columnSpan(3),
            ])
            ->columns(4);
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
                Tables\Columns\TextColumn::make('account_name')
                    ->label('Empresa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('account_size.name')
                    ->label('Tamaño')
                    ->searchable(),
                Tables\Columns\TextColumn::make('job_title')
                    ->label('Puesto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('leadStatus.name')
                    ->label('Estado')
                    ->formatStateUsing(function ($record) {
                        return view('lead.leadStatusList', ['leadStatus' => $record->leadStatus])->render();
                    })
                    ->html()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('source.name')
                    ->label('Fuente del Lead')
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
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('country')
                    ->label('Ciudad')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('account_revenue')
                    ->label('Facturación')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('account_industry')
                    ->label('Industria')
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
            ->recordUrl(function (Lead $record) {
                return Pages\ViewLead::getUrl(['record' => $record]);
            })
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make()
                    ->columns([
                        'sm' => 1,
                        'xl' => 12
                    ])
                    ->schema([
                        Grid::make()
                            ->schema([
                                Section::make()
                                    ->schema([
                                        ImageEntry::make('image')
                                            ->label('')
                                            ->height(60)
                                            ->circular()
                                            ->hidden(fn (Lead $record) => empty($record->image)),
                                        TextEntry::make('leadStatus.name')
                                            ->label('Estado')
                                            ->formatStateUsing(function ($record) {
                                                return view('lead.leadStatusList', ['leadStatus' => $record->leadStatus])->render();
                                            })
                                            ->html(),
                                        TextEntry::make('partner.name')
                                            ->label('Referido a')
                                            ->visible(fn(Lead $record) => $record->leadStatus->name === 'Referido'),
                                        TextEntry::make('source.name')
                                            ->label('Fuente del Lead')
                                            ->hidden(fn(Lead $record) => empty($record->source)),
                                        TextEntry::make('email')
                                            ->label('Email')
                                            ->hidden(fn(Lead $record) => empty($record->email)),
                                        TextEntry::make('phone')
                                            ->label('Teléfono')
                                            ->hidden(fn(Lead $record) => empty($record->phone)),
                                        TextEntry::make('country')
                                            ->label('País')
                                            ->hidden(fn(Lead $record) => empty($record->country)),
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
                                            ->hidden(fn(Lead $record) => empty($record->url_instagram)),
                                        TextEntry::make('url_linkedin')
                                            ->label('LinkedIn')
                                            ->icon('heroicon-m-link')
                                            ->copyable()
                                            ->copyMessage('¡Copiado!')
                                            ->copyMessageDuration(1500)
                                            ->limit(30)
                                            ->hidden(fn(Lead $record) => empty($record->url_linkedin)),
                                        TextEntry::make('url_tiktok')
                                            ->label('TikTok')
                                            ->icon('heroicon-m-link')
                                            ->copyable()
                                            ->copyMessage('¡Copiado!')
                                            ->copyMessageDuration(1500)
                                            ->limit(30)
                                            ->hidden(fn(Lead $record) => empty($record->url_tiktok)),
                                        TextEntry::make('url_website')
                                            ->label('Página web')
                                            ->icon('heroicon-m-link')
                                            ->copyable()
                                            ->copyMessage('¡Copiado!')
                                            ->copyMessageDuration(1500)
                                            ->limit(30)
                                            ->hidden(fn(Lead $record) => empty($record->url_website)),
                                        TextEntry::make('url_youtube')
                                            ->label('YouTube')
                                            ->icon('heroicon-m-link')
                                            ->copyable()
                                            ->copyMessage('¡Copiado!')
                                            ->copyMessageDuration(1500)
                                            ->limit(30)
                                            ->hidden(fn(Lead $record) => empty($record->url_youtube)),
                                    ])
                                    ->columns(),
                            ])
                            ->columnSpan(7),
                        Grid::make()
                            ->schema([
                                Section::make('Empresa')
                                    ->schema([
                                        TextEntry::make('account_name')
                                            ->label('Nombre')
                                            ->hidden(fn(Lead $record) => empty($record->account_name)),
                                        TextEntry::make('account_size.name')
                                            ->label('Tamaño')
                                            ->hidden(fn(Lead $record) => empty($record->account_size->name)),
                                        TextEntry::make('industry.name')
                                            ->label('Industria')
                                            ->hidden(fn(Lead $record) => empty($record->industry->name)),
                                        TextEntry::make('account_revenue')
                                            ->label('Facturación')
                                            ->money('EUR')
                                            ->hidden(fn(Lead $record) => empty($record->account_revenue)),
                                        TextEntry::make('tools.name')
                                            ->label('Herramientas')
                                            ->formatStateUsing(function ($record) {
                                                return view('account.accountToolList', ['tools' => $record->tools])->render();
                                            })
                                            ->html()
                                            ->hidden(fn(Lead $record) => empty($record->tools))
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(),
                                Section::make('Dirección')
                                    ->schema([
                                        TextEntry::make('street')
                                            ->label('Calle')
                                            ->hidden(fn(Lead $record) => empty($record->street)),
                                        TextEntry::make('city')
                                            ->label('Ciudad')
                                            ->hidden(fn(Lead $record) => empty($record->city)),
                                        TextEntry::make('state')
                                            ->label('Provincia')
                                            ->hidden(fn(Lead $record) => empty($record->state)),
                                        TextEntry::make('country')
                                            ->label('País')
                                            ->hidden(fn(Lead $record) => empty($record->country)),
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
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
            'view' => Pages\ViewLead::route('/{record}'),
        ];
    }
}
