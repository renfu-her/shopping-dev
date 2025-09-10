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
use Illuminate\Support\Facades\Log;

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
                    ->visibility('public')
                    ->imageEditor()
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1200')
                    ->imageResizeTargetHeight('675')
                    ->imageEditorEmptyFillColor('#ffffff')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->downloadable()
                    ->openable()
                    ->required()
                    ->columnSpanFull()
                    ->getUploadedFileNameForStorageUsing(
                        fn($file): string => (string) str(Str::uuid() . '.webp')
                    )
                    ->saveUploadedFileUsing(function ($file) {
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($file);
                        $image->scale(1200, 675);
                        $filename = Str::uuid()->toString() . '.webp';

                        if (!file_exists(storage_path('app/public/banners'))) {
                            mkdir(storage_path('app/public/banners'), 0755, true);
                        }
                        $image->toWebp(90)->save(storage_path('app/public/banners/' . $filename));
                        return 'banners/' . $filename;
                    })
                    ->deleteUploadedFileUsing(function ($file) {
                        if ($file) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($file);
                        }
                    })
                    ->getUploadedFileUrlUsing(function ($file) {
                        // Handle both new WebP files and legacy sample images
                        if (str_starts_with($file, 'banners/')) {
                            return \Illuminate\Support\Facades\Storage::disk('public')->url($file);
                        } else {
                            // For legacy sample images, return the direct path
                            return $file;
                        }
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