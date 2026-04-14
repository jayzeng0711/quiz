<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Report;
use App\Services\EmailDeliveryService;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static ?string $navigationLabel = '報告管理';
    public static function getNavigationIcon(): string { return 'heroicon-o-document-text'; }
    protected static ?string $modelLabel = '報告';
    protected static ?int $navigationSort = 5;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable(),
                Tables\Columns\TextColumn::make('attempt.email')->label('Email')->searchable(),
                Tables\Columns\TextColumn::make('resultType.title')->label('結果類型'),
                Tables\Columns\TextColumn::make('status')
                    ->label('狀態')->badge()
                    ->color(fn ($state) => match ($state) {
                        'draft'     => 'secondary',
                        'generated' => 'warning',
                        'delivered' => 'success',
                        default     => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('order.order_number')->label('訂單編號'),
                Tables\Columns\TextColumn::make('generated_at')->label('生成時間')->since()->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\Action::make('view_report')
                    ->label('預覽')->icon('heroicon-o-eye')
                    ->url(fn (Report $record) => route('share', ['shareToken' => $record->access_token]))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('resend_email')
                    ->label('補寄 Email')->icon('heroicon-o-envelope')
                    ->requiresConfirmation()
                    ->action(function (Report $record) {
                        app(EmailDeliveryService::class)->sendReport($record);
                        Notification::make()->title('Email 已排入發送佇列')->success()->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListReports::route('/')];
    }

    public static function canCreate(): bool { return false; }
}
