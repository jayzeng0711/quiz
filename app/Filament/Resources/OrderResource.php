<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Services\OrderService;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationLabel = '訂單管理';
    public static function getNavigationIcon(): string { return 'heroicon-o-credit-card'; }
    protected static ?string $modelLabel = '訂單';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\TextInput::make('order_number')->label('訂單編號')->disabled(),
            Forms\Components\TextInput::make('email')->label('Email')->disabled(),
            Forms\Components\TextInput::make('status')->label('狀態')->disabled(),
            Forms\Components\TextInput::make('amount')
                ->label('金額')
                ->formatStateUsing(fn ($state) => 'NT$' . number_format($state / 100))
                ->disabled(),
            Forms\Components\TextInput::make('payment_provider')->label('金流商')->disabled(),
            Forms\Components\TextInput::make('payment_reference')->label('交易序號')->disabled(),
            Forms\Components\DateTimePicker::make('paid_at')->label('付款時間')->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')->label('訂單編號')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('狀態')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'paid'     => 'success',
                        'pending'  => 'warning',
                        'failed', 'refunded' => 'danger',
                        default    => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->label('金額')
                    ->formatStateUsing(fn ($state) => 'NT$' . number_format($state / 100)),
                Tables\Columns\TextColumn::make('payment_provider')->label('金流商'),
                Tables\Columns\TextColumn::make('paid_at')->label('付款時間')->since()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('建立時間')->since()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('訂單狀態')
                    ->options(['pending' => '待付款', 'paid' => '已付款', 'failed' => '付款失敗', 'refunded' => '已退款']),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('refund')
                    ->label('退款')->icon('heroicon-o-arrow-uturn-left')->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Order $record) => $record->isPaid())
                    ->action(function (Order $record) {
                        app(OrderService::class)->refund($record, '後台手動退款');
                        Notification::make()->title('退款成功')->success()->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view'  => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
