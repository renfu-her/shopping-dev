<?php

declare(strict_types=1);

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

final class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),

                FileUpload::make('image')
                    ->image()
                    ->directory('banners')
                    ->imageEditor()
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1200')
                    ->imageResizeTargetHeight('675')
                    ->imageEditorEmptyFillColor('#ffffff')
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('link')
                    ->url()
                    ->maxLength(255),

                Toggle::make('is_active')
                    ->default(true),

                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
            ]);
    }
}