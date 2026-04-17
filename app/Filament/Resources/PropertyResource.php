<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Property Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Forms\Components\RichEditor::make('description')
                            ->required()
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\Select::make('location_id')
                            ->relationship('location', 'area_name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Property Details')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->prefix('ETB')
                            ->inputMode('decimal'),
                        
                        Forms\Components\Select::make('price_type')
                            ->options([
                                'sale' => 'For Sale',
                                'rent' => 'For Rent',
                            ])
                            ->required(),
                        
                        Forms\Components\Select::make('property_type')
                            ->options([
                                'apartment' => 'Apartment',
                                'villa' => 'Villa',
                                'commercial' => 'Commercial',
                                'land' => 'Land',
                                'office' => 'Office',
                            ])
                            ->required(),
                        
                        Forms\Components\TextInput::make('bedrooms')
                            ->numeric()
                            ->minValue(0),
                        
                        Forms\Components\TextInput::make('bathrooms')
                            ->numeric()
                            ->minValue(0),
                        
                        Forms\Components\TextInput::make('area_sqm')
                            ->numeric()
                            ->suffix('m²')
                            ->minValue(0),
                        
                        Forms\Components\TextInput::make('year_built')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(date('Y')),
                        
                        Forms\Components\TextInput::make('address')
                            ->maxLength(255),
                    ])
                    ->columns(3),
                
                Forms\Components\Section::make('Features & Amenities')
                    ->schema([
                        Forms\Components\Toggle::make('parking')
                            ->label('Parking Available'),
                        Forms\Components\Toggle::make('furnished')
                            ->label('Furnished'),
                        Forms\Components\Toggle::make('security')
                            ->label('24/7 Security'),
                        Forms\Components\Toggle::make('elevator')
                            ->label('Elevator'),
                        Forms\Components\Toggle::make('garden')
                            ->label('Garden'),
                        Forms\Components\Toggle::make('pool')
                            ->label('Swimming Pool'),
                        Forms\Components\Toggle::make('air_conditioning')
                            ->label('Air Conditioning'),
                    ])
                    ->columns(4),
                
                Forms\Components\Section::make('Status & Visibility')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'available' => 'Available',
                                'sold' => 'Sold',
                                'rented' => 'Rented',
                                'pending' => 'Pending',
                            ])
                            ->required(),
                        
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Property'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active Listing')
                            ->default(true),
                        
                        Forms\Components\TextInput::make('video_url')
                            ->url()
                            ->label('Video Tour URL'),
                        
                        Forms\Components\TextInput::make('virtual_tour_url')
                            ->url()
                            ->label('Virtual Tour URL'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('primaryImage')
                    ->label('Image')
                    ->getStateUsing(fn (Property $record) => $record->primary_image),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('location.area_name')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('price')
                    ->money('ETB')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('price_type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                
                Tables\Columns\TextColumn::make('views')
                    ->sortable()
                    ->label('Views'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
                
                Tables\Filters\SelectFilter::make('location')
                    ->relationship('location', 'area_name'),
                
                Tables\Filters\SelectFilter::make('price_type')
                    ->options([
                        'sale' => 'For Sale',
                        'rent' => 'For Rent',
                    ]),
                
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'available' => 'Available',
                        'sold' => 'Sold',
                        'rented' => 'Rented',
                        'pending' => 'Pending',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Properties'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}