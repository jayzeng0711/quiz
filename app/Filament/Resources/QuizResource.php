<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResource\Pages;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;
    protected static ?string $navigationLabel = '測驗管理';
    public static function getNavigationIcon(): string { return 'heroicon-o-clipboard-document-list'; }
    protected static ?string $modelLabel = '測驗';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Section::make('基本資訊')->schema([
                Forms\Components\TextInput::make('title')
                    ->label('測驗名稱')->required()->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->label('網址代碼')->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('用於 URL，例如：workplace-communication-style'),

                Forms\Components\Textarea::make('description')
                    ->label('測驗描述')->rows(3),

                Forms\Components\TextInput::make('price')
                    ->label('售價（分，NT$1=100）')->numeric()->default(4900)
                    ->helperText('輸入 4900 = NT$49，0 = 免費'),

                Forms\Components\Toggle::make('is_active')
                    ->label('啟用')->default(true),
            ])->columns(2),

            Forms\Components\Section::make('額外設定（meta）')->schema([
                Forms\Components\TextInput::make('meta.estimated_minutes')
                    ->label('預估完成時間（分鐘）')->numeric()->default(5),

                Forms\Components\TagsInput::make('meta.tags')
                    ->label('標籤')->placeholder('新增標籤後按 Enter'),

                Forms\Components\TextInput::make('meta.cover_image')
                    ->label('封面圖片 URL')->url(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('名稱')->searchable(),
                Tables\Columns\TextColumn::make('slug')->label('Slug'),
                Tables\Columns\TextColumn::make('price')
                    ->label('售價')
                    ->formatStateUsing(fn ($state) => 'NT$' . number_format($state / 100)),
                Tables\Columns\IconColumn::make('is_active')->label('啟用')->boolean(),
                Tables\Columns\TextColumn::make('questions_count')->label('題目數')->counts('questions'),
                Tables\Columns\TextColumn::make('attempts_count')->label('作答次數')->counts('attempts'),
                Tables\Columns\TextColumn::make('updated_at')->label('最後更新')->since(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit'   => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }
}
