<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResultTypeResource\Pages;
use App\Models\ResultType;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ResultTypeResource extends Resource
{
    protected static ?string $model = ResultType::class;
    protected static ?string $navigationLabel = '結果類型';
    public static function getNavigationIcon(): string { return 'heroicon-o-star'; }
    protected static ?string $modelLabel = '結果類型';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Section::make('基本資訊')->schema([
                Forms\Components\Select::make('quiz_id')
                    ->label('所屬測驗')->relationship('quiz', 'title')->required(),

                Forms\Components\TextInput::make('code')
                    ->label('類型代碼')->required()
                    ->helperText('大寫英文，例如：DRIVER、ANALYTICAL')->maxLength(50),

                Forms\Components\TextInput::make('title')->label('類型標題')->required()->maxLength(255),
                Forms\Components\TextInput::make('sort_order')->label('排序')->numeric()->default(0),
            ])->columns(2),

            Forms\Components\Textarea::make('description')
                ->label('簡短描述（顯示於結果頁）')->rows(3)->required()->columnSpanFull(),

            Forms\Components\RichEditor::make('report_content')
                ->label('完整報告內容（支援 HTML）')
                ->toolbarButtons(['bold','italic','underline','h2','h3','bulletList','orderedList','blockquote','undo','redo'])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quiz.title')->label('測驗'),
                Tables\Columns\TextColumn::make('code')->label('代碼')->badge(),
                Tables\Columns\TextColumn::make('title')->label('標題'),
                Tables\Columns\TextColumn::make('sort_order')->label('排序')->sortable(),
                Tables\Columns\TextColumn::make('description')->label('描述')->limit(50),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('quiz')->relationship('quiz', 'title')->label('篩選測驗'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListResultTypes::route('/'),
            'create' => Pages\CreateResultType::route('/create'),
            'edit'   => Pages\EditResultType::route('/{record}/edit'),
        ];
    }
}
