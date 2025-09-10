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
use Illuminate\Support\Facades\Storage;

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
                    ->getUploadedFileUsing(function ($file) {
                        $imageManager = new ImageManager(new Driver());
                        $image = $imageManager->read($file->getPathname());
                        $image->scale(1200, 675);
                        $filename = Str::uuid7()->toString() . '.webp';
                        $image->toWebp(80)->save(storage_path('app/public/banners/' . $filename));
                        return 'banners/' . $filename;
                    })
                    ->saveUploadedFileUsing(function ($file) {
                        $imageManager = new ImageManager(new Driver());
                        $image = $imageManager->read($file->getPathname());
                        $image->scale(1200, 675);
                        $filename = Str::uuid7()->toString() . '.webp';
                        $image->toWebp(80)->save(storage_path('app/public/banners/' . $filename));
                        return 'banners/' . $filename;
                    })
                    ->deleteUploadedFileUsing(function ($file) {
                        if ($file) {
                            Storage::disk('public')->delete($file);
                        }
                    })
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