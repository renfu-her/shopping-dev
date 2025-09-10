<?php

declare(strict_types=1);

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

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
                    ->label('Banner Image')
                    ->image()
                    ->disk('public')
                    ->directory('banners')
                    ->imageEditor()
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1200')
                    ->imageResizeTargetHeight('675')
                    ->imageEditorEmptyFillColor('#ffffff')
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->required()
                    ->columnSpanFull()
                    ->getUploadedFileUsing(function ($file) {
                        // $file is a string path to the temporary file
                        $tempPath = $file;
                        
                        // Process image with Intervention Image
                        $imageManager = new ImageManager(new Driver());
                        $image = $imageManager->read($tempPath);
                        
                        // Scale image to target dimensions while maintaining aspect ratio
                        $image->scale(1200, 675);
                        
                        // Generate unique filename with original extension
                        $originalExtension = pathinfo($tempPath, PATHINFO_EXTENSION);
                        $filename = Str::uuid() . '.' . $originalExtension;
                        $processedPath = storage_path('app/public/banners/' . $filename);
                        
                        // Ensure directory exists
                        if (!file_exists(dirname($processedPath))) {
                            mkdir(dirname($processedPath), 0755, true);
                        }
                        
                        // Save processed image
                        $image->save($processedPath);
                        
                        // Return the processed file path
                        return $processedPath;
                    }),

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