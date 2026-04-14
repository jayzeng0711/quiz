<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizQuestionResource\Pages;
use App\Models\QuizQuestion;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class QuizQuestionResource extends Resource
{
    protected static ?string $model = QuizQuestion::class;
    protected static ?string $navigationLabel = '題目管理';
    public static function getNavigationIcon(): string { return 'heroicon-o-question-mark-circle'; }
    protected static ?string $modelLabel = '題目';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Section::make('題目設定')->schema([
                Forms\Components\Select::make('quiz_id')
                    ->label('所屬測驗')
                    ->relationship('quiz', 'title')
                    ->required(),

                Forms\Components\Select::make('type')
                    ->label('題目類型')
                    ->options(['single_choice' => '單選題', 'multi_choice' => '多選題'])
                    ->default('single_choice')->required(),

                Forms\Components\TextInput::make('sort_order')->label('排序')->numeric()->default(0),
                Forms\Components\Toggle::make('is_required')->label('必填')->default(true),
            ])->columns(2),

            Forms\Components\Textarea::make('body')
                ->label('題目內容')->required()->rows(3)->columnSpanFull(),

            Forms\Components\Section::make('選項設定')
                ->description('scores 格式：{"DRIVER": 3, "ANALYTICAL": 2}')
                ->schema([
                    Forms\Components\Repeater::make('options')
                        ->label('選項')
                        ->schema([
                            Forms\Components\TextInput::make('key')
                                ->label('代碼')->required()->placeholder('a/b/c/d')->maxLength(10),

                            Forms\Components\TextInput::make('label')
                                ->label('選項文字')->required()->columnSpan(2),

                            Forms\Components\KeyValue::make('scores')
                                ->label('分數對應')
                                ->keyLabel('結果類型代碼')
                                ->valueLabel('加分值')
                                ->addActionLabel('新增')
                                ->columnSpan(2),
                        ])
                        ->columns(5)
                        ->addActionLabel('新增選項')
                        ->reorderable()
                        ->defaultItems(4),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quiz.title')->label('測驗')->searchable(),
                Tables\Columns\TextColumn::make('sort_order')->label('順序')->sortable(),
                Tables\Columns\TextColumn::make('body')->label('題目')->limit(60)->searchable(),
                Tables\Columns\TextColumn::make('type')->label('類型'),
                Tables\Columns\IconColumn::make('is_required')->label('必填')->boolean(),
            ])
            ->defaultSort('quiz_id')
            ->filters([
                Tables\Filters\SelectFilter::make('quiz')
                    ->relationship('quiz', 'title')->label('篩選測驗'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListQuizQuestions::route('/'),
            'create' => Pages\CreateQuizQuestion::route('/create'),
            'edit'   => Pages\EditQuizQuestion::route('/{record}/edit'),
        ];
    }
}
