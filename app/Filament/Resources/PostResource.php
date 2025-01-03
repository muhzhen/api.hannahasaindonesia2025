<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Forms\Components\Grid;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                ->schema([
                    //
                    Forms\Components\FileUpload::make('thumbnail')
                    ->label('Thumbnail')
                    ->directory('thumbnails')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                    ->maxSize(2048)
                    ->helperText('Ratio Gambar (124:85) atau dalam piksel (1000px : 685px). ukuran file 1 MB. Format File JPEG, JPG, atau PNG.')
                    ->rules([
                        'mimes:jpeg,jpg,png',
                        'max:2048', // Maksimal ukuran file 1MB (1024 KB)
                    ])
                    ->imageEditor()
                    ->imageCropAspectRatio('124:85')
                    ->imageResizeTargetWidth('1000')
                    ->imageResizeTargetHeight('685.64')
                    ->required(),

                    Forms\Components\TextInput::make('reading_time')
                    ->label('Waktu Baca')
                    ->helperText('tuliskan durasi waktu baca')
                    ->required()
                    ->maxLength(255),

                    Forms\Components\DatePicker::make('tanggal')
                    ->label('Tanggal Publikasi')
                    ->helperText('masukan tanggal publikasi')
                    ->locale('id'),

                    Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255)
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', Str::slug($state));
                    }),

                    Forms\Components\MarkdownEditor::make('content')
                    ->label('Konten')
                    ->toolbarButtons([
                            'attachFiles',
                            'blockquote',
                            'bold',
                            'bulletList',
                            'codeBlock',
                            'heading',
                            'italic',
                            'link',
                            'orderedList',
                            'redo',
                            'strike',
                            'table',
                            'undo',
                        ]),

                    Forms\Components\Select::make('category_id')
                    ->label('Katagori')
                    ->options(function() {
                        return \App\Models\Category::all()->pluck('name', 'id');
                    })
                    ->required(),

                    Forms\Components\Toggle::make('is_published')
                    ->label('Status Publikasi')
                    ->required(),

                    Forms\Components\TextInput::make('slug')
                    ->helperText('tidak perlu diisi, karna sudah otomatis terisi')
                    ->unique(ignoreRecord: true)
                    ->disabled(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('title')
                ->searchable(),

                Tables\Columns\TextColumn::make('tanggal'),

                Tables\Columns\TextColumn::make('is_published')
                ->label('Status')
                ->formatStateUsing(function ($state) {
                return $state ? 'Published' : 'Draft';
            })
                ->color(function ($state) {
                     return $state ? 'success' : 'warning';
                })
                ->extraAttributes([
                    'class' => 'px-2 py-1 rounded-lg font-bold',
                ]),

                Tables\Columns\TextColumn::make('slug')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
