# Filament v4 Complete Guide - Part 2: Table System

## Table System

### Column Types
**New Column Types in v4:**
- **Text Column**: Text display with formatting support
- **Icon Column**: Icon display
- **Image Column**: Image display
- **Color Column**: Color display
- **Select Column**: Dropdown selection
- **Toggle Column**: Toggle switch
- **Text Input Column**: Inline text editing
- **Checkbox Column**: Checkbox selection

**Example Code:**
```php
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;

TextColumn::make('name')
    ->searchable()
    ->sortable()
    ->formatStateUsing(fn (string $state): string => ucfirst($state));

ImageColumn::make('avatar')
    ->circular()
    ->size(40);

ToggleColumn::make('is_active')
    ->onColor('success')
    ->offColor('danger');
```

### Filters
**Enhanced Features in v4:**
- **Select Filters**: Dropdown selection filters
- **Ternary Filters**: Ternary filters (Yes/No/All)
- **Query Builder**: Advanced query builder
- **Custom Filters**: Complete customization of filter logic
- **Filter Layout**: Supports different filter layouts

### Actions
**Enhanced Features in v4:**
- **Modal Actions**: Supports popup-style actions
- **Action Grouping**: Organizes related actions together
- **New Action Types**:
  - Replicate (Copy)
  - Force-delete (Force Delete)
  - Restore (Restore)
  - Import (Import)
  - Export (Export)

### Layout and Features
- **Row Grouping**: Supports grouping display by columns
- **Summaries**: Statistical information at table bottom
- **Custom Data**: Supports non-Eloquent data sources
- **Empty State**: Custom empty data display

## Table Actions

### Action Types

#### Row Actions
**Actions for individual records:**
```php
use Filament\Tables\Actions;

public function table(Table $table): Table
{
    return $table
        ->actions([
            Actions\ViewAction::make(),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ]);
}
```

#### Header Actions
**Actions at the top of the table:**
```php
public function table(Table $table): Table
{
    return $table
        ->headerActions([
            Actions\CreateAction::make(),
            Actions\ImportAction::make(),
        ]);
}
```

#### Bulk Actions
**Actions for multiple records:**
```php
use Filament\Tables\Actions\BulkActionGroup;

public function table(Table $table): Table
{
    return $table
        ->bulkActions([
            BulkActionGroup::make([
                Actions\BulkAction::make('activate')
                    ->label('Activate Selected')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($records) {
                        $records->each->update(['is_active' => true]);
                    }),
                Actions\BulkAction::make('deactivate')
                    ->label('Deactivate Selected')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function ($records) {
                        $records->each->update(['is_active' => false]);
                    }),
                Actions\DeleteBulkAction::make(),
            ]),
        ]);
}
```

### Custom Actions

#### Custom Action Buttons
```php
Actions\Action::make('send_notification')
    ->label('Send Notification')
    ->icon('heroicon-o-bell')
    ->color('warning')
    ->form([
        Forms\Components\TextInput::make('subject')->required(),
        Forms\Components\Textarea::make('message')->required(),
    ])
    ->action(function (array $data, $record) {
        $record->notify(new CustomNotification($data['subject'], $data['message']));
    })
    ->successNotification(
        Notification::make()
            ->success()
            ->title('Notification sent')
            ->body('The notification has been sent successfully.')
    );
```

#### Conditional Actions
```php
Actions\EditAction::make()
    ->visible(fn ($record) => $record->status !== 'archived')
    ->disabled(fn ($record) => $record->is_locked);
```

#### Action Groups
```php
Actions\ActionGroup::make([
    Actions\ViewAction::make(),
    Actions\EditAction::make(),
    Actions\DeleteAction::make(),
])
    ->label('Actions')
    ->icon('heroicon-m-ellipsis-vertical')
    ->color('gray')
    ->dropdownPlacement('bottom-end');
```

### Action Configuration

#### Modal Configuration
```php
Actions\EditAction::make()
    ->modalHeading('Edit User')
    ->modalDescription('Update user information')
    ->modalSubmitActionLabel('Save Changes')
    ->modalCancelActionLabel('Cancel')
    ->modalWidth('lg');
```

#### Confirmation Dialog
```php
Actions\DeleteAction::make()
    ->requiresConfirmation()
    ->modalHeading('Delete User')
    ->modalDescription('Are you sure you want to delete this user? This action cannot be undone.')
    ->modalSubmitActionLabel('Yes, delete user')
    ->modalCancelActionLabel('Cancel');
```

#### Redirect Configuration
```php
Actions\CreateAction::make()
    ->redirect(route('admin.users.index'));

Actions\EditAction::make()
    ->redirect(fn ($record) => route('admin.users.view', $record));
```

## Table Layout

### Responsive Layout
```php
public function table(Table $table): Table
{
    return $table
        ->contentGrid([
            'md' => 2,
            'xl' => 3,
        ])
        ->columns([
            // ...
        ]);
}
```

### Custom Layout
```php
public function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')
                ->columnSpan([
                    'default' => 1,
                    'md' => 2,
                ]),
            Tables\Columns\TextColumn::make('email')
                ->columnSpan([
                    'default' => 1,
                    'md' => 1,
                ]),
        ]);
}
```

### Table Styles
```php
public function table(Table $table): Table
{
    return $table
        ->striped()
        ->hover()
        ->bordered()
        ->compact();
}
```

### Empty State
```php
public function table(Table $table): Table
{
    return $table
        ->emptyStateHeading('No users found')
        ->emptyStateDescription('Create your first user to get started.')
        ->emptyStateIcon('heroicon-o-users')
        ->emptyStateActions([
            Tables\Actions\Action::make('create')
                ->label('Create user')
                ->url(route('admin.users.create'))
                ->icon('heroicon-o-plus')
                ->button(),
        ]);
}
```

## Table Summaries

### Basic Summaries
```php
use Filament\Tables\Enums\FiltersLayout;

public function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('email'),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ]),
        ], layout: FiltersLayout::AboveContent)
        ->filtersFormColumns(3)
        ->filtersTriggerAction(
            fn (Tables\Actions\Action $action) => $action
                ->button()
                ->label('Filters'),
        );
}
```

### Custom Summaries
```php
public function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('email'),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime(),
        ])
        ->filters([
            Tables\Filters\Filter::make('created_from')
                ->form([
                    Forms\Components\DatePicker::make('created_from'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        );
                }),
        ]);
}
```

### Summary Layout
```php
public function table(Table $table): Table
{
    return $table
        ->filters([
            // ...
        ], layout: FiltersLayout::AboveContent) // AboveContent, AboveContentCollapsible, BelowContent, Dropdown
        ->filtersFormColumns(3)
        ->filtersTriggerAction(
            fn (Tables\Actions\Action $action) => $action
                ->button()
                ->label('Filters')
                ->icon('heroicon-o-funnel'),
        );
}
```

### Persistent Summaries
```php
public function table(Table $table): Table
{
    return $table
        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ])
                ->persist(),
        ]);
}
```

## Advanced Table Features

### Row Grouping
```php
public function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('email'),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime(),
        ])
        ->grouping([
            'groupQuery' => fn (Builder $query, string $direction): Builder => $query->orderBy('created_at', $direction),
        ])
        ->defaultGroup('created_at');
}
```

### Custom Data
```php
public function table(Table $table): Table
{
    return $table
        ->query(
            User::query()
                ->select(['id', 'name', 'email', 'created_at'])
                ->where('is_active', true)
        )
        ->columns([
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('email'),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime(),
        ]);
}
```

### Table Events
```php
public function table(Table $table): Table
{
    return $table
        ->columns([
            // ...
        ])
        ->actions([
            Tables\Actions\EditAction::make()
                ->after(function ($record) {
                    // Post-edit actions
                    activity()->log("User {$record->name} was edited");
                }),
        ]);
}
```

## Best Practices

### Performance Optimization
- Use appropriate database queries
- Avoid N+1 query problems
- Use eager loading appropriately
- Implement appropriate indexes

### User Experience
- Provide clear labels and descriptions
- Use appropriate icons and colors
- Ensure responsive design
- Provide useful empty states

### Security
- Implement appropriate permission checks
- Validate all user input
- Protect sensitive data
- Log important operations

### Code Organization
- Use appropriate namespaces
- Follow PSR standards
- Keep code clean
- Add appropriate comments

---

**Previous:** [Part 1: Overview & Architecture](FILAMENT-01-OVERVIEW.md)  
**Next:** [Part 3: Form System](FILAMENT-03-FORMS.md)
